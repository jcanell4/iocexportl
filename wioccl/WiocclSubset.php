<?php
require_once "WiocclParser.php";

class WiocclSubset extends WiocclParser {

    protected $varName;
    protected $fullArray =[];
    protected $itemName;

    public function __construct($value = null, $arrays = [], $dataSource)
    {
        parent::__construct($value, $arrays, $dataSource);

        // varName correspón a la propietat var i es el nom de l'array
        // ALERTA! els arrays es llegeixen com un camp, la conversió d'array al seu valor es tracta al field

        $this->varName = $this->extractVarName($value);
        $this->fullArray = $this->extractArray($value);
        $this->itemName = $this->extractArrayItemName($value);

        // TODO: efegir el condition


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
        if (preg_match('/subsetvar="(.*?)"/', $value, $matches)) {
            return $matches[1];
        } else {
            throw new Exception("Var name is missing");
        }

    }

    protected function extractArrayItemName($value) {
        if (preg_match('/arrayitem="(.*?)"/', $value, $matches)) {
            return $matches[1];
        } else {
            throw new Exception("Var name is missing");
        }

    }

}