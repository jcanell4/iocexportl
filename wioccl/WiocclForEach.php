<?php
require_once "WiocclParser.php";

class WiocclForEach extends WiocclParser {

    protected $varName;
    protected $fullArray =[];

    public function __construct($value = null, $arrays = [])
    {
        parent::__construct($value, $arrays);

        // varName correspón a la propietat var i es el nom de l'array
        // ALERTA! els arrays es llegeixen com un camp, la conversió d'array al seu valor es tracta al field

        $this->varName = $this->extractVarName($value);
        $this->fullArray = $this->extractArray($value);

    }

    protected function parseTokens($tokens, &$tokenIndex)
    {

        $result = '';
        $startTokenIndex = $tokenIndex;
        $lastBlockIndex = null;


        for ($arrayIndex = 0; $arrayIndex<count($this->fullArray); $arrayIndex++) {

            $tokenIndex = $startTokenIndex;
            $this->arrays[$this->varName] = $this->fullArray[$arrayIndex];

            // Els format dels arrays al fullArray es similar a (sense els index 0, 1, etc):
//            '[
//                '0' => ['tipus' => 'lalala', 'eina' => 'ggg', 'opcionalitat' => 'cap'],
//                '1' => ['tipus' => 'oooo', 'eina' => 'elelel', 'opcionalitat' => 'no']
//            ]

            // El format que es passa a arrays es:
            //      ['tipus' => 'lalala', 'eina' => 'ggg', 'opcionalitat' => 'cap']


            while ($tokenIndex < count($tokens)) {

                $parsedValue = $this->parseToken($tokens, $tokenIndex);

                if ($parsedValue === null) { // tancament del foreach
                    break;

                } else {
                    $result .= $parsedValue;
                }

                ++$tokenIndex;
            }

        }



        return $result;
    }

    protected function extractVarName($value) {
        if (preg_match('/var="(.*?)"/', $value, $matches)) {
            return $matches[1];
        } else {
            throw new Exception("Var name is missing");
        }

    }

    protected function extractArray($value) {
        $jsonString = '';
        // ALERTA: El $value pot ser un json directament o una variable, s'ha de fer un parse del $value
        if (preg_match('/array="(.*?)"/', $value, $matches)) {
            $jsonString = (new WiocclParser($matches[1], $this->arrays))->getValue();
        } else {
            throw new Exception("Var name is missing");
        }


        return json_decode($jsonString, true);
    }
}