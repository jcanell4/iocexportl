<?php
require_once "WiocclParser.php";

class WiocclForEach extends WiocclParser {

    protected $varName;
    protected $fullArray =[];
    protected $filterArgs;

    const ARG1 = 0;
    const ARG2 = 2;
    const OPERATOR = 1;

    public function __construct($value = null, $arrays = [], $dataSource)
    {
        parent::__construct($value, $arrays, $dataSource);

        // varName correspón a la propietat var i es el nom de l'array
        // ALERTA! els arrays es llegeixen com un camp, la conversió d'array al seu valor es tracta al field

        $this->varName = $this->extractVarName($value);
        $this->fullArray = $this->extractArray($value);
        $this->filterArgs = $this->extractFilterArgs($value);

    }

    protected function parseTokens($tokens, &$tokenIndex)
    {

        $result = '';
        $startTokenIndex = $tokenIndex;
        $lastBlockIndex = null;
        $lastTokenIndex = 0;


        for ($arrayIndex = 0; $arrayIndex<count($this->fullArray); $arrayIndex++) {

            $tokenIndex = $startTokenIndex;
            $row = $this->fullArray[$arrayIndex];
            $this->arrays[$this->varName] = $row;


            // TODO: Extreure a una funció a part per poder reutilizar al foreach, if i subset

            $arg1 = $this->normalizeArg((new WiocclParser($this->filterArgs[self::ARG1], $this->arrays, $this->dataSource))->getValue());
            $arg2 = $this->normalizeArg((new WiocclParser($this->filterArgs[self::ARG2], $this->arrays, $this->dataSource))->getValue());









            // Els format dels arrays al fullArray es similar a (sense els index 0, 1, etc):
//            '[
//                '0' => ['tipus' => 'lalala', 'eina' => 'ggg', 'opcionalitat' => 'cap'],
//                '1' => ['tipus' => 'oooo', 'eina' => 'elelel', 'opcionalitat' => 'no']
//            ]

            // El format que es passa a arrays es:
            //      ['tipus' => 'lalala', 'eina' => 'ggg', 'opcionalitat' => 'cap']


            // ALERTA: No es pot fer
            $process = $this->filterArgs==NULL || $this->resolveCondition($arg1, $arg2, $this->filterArgs[self::OPERATOR]);

            if (!$process && $lastTokenIndex >0) {
                // Ja s'ha processat previament el token de tancament i no s'acompleix la condició, no cal continuar processant
                continue;
            }

            while ($tokenIndex < count($tokens)) {

                $parsedValue =  $this->parseToken($tokens, $tokenIndex);

                if ($parsedValue === null) { // tancament del foreach
                    break;

                } else if ($process) {
                    $result .= $parsedValue;
                }

                ++$tokenIndex;
            }

            $lastTokenIndex = $tokenIndex;

        }

        $tokenIndex = $lastTokenIndex;


        return $result;
    }


    //** ALERTA! Duplicat al  foreach*/
    protected function extractFilterArgs($value) {
        if (preg_match('/filter="(.*?([><=]=?))(.*?)">/', $value, $matches) === 1) {
            // ALERTA: Actualment el token amb > arriba tallat perquè l'identifica com a tancament del token d'apertura

            $arg1 = $this->normalizeArg(str_replace(['==', '>', '<', '=', '>=', '<=', '!='], '', $matches[1]));
            $arg2 = $matches[3];

            $operator = $matches[2];



            return [$arg1, $operator, $arg2];
//            throw new Exception("Incorrect condition structure");
        };




        return null;
    }


    // Duplict al foreach, subset i el if
    protected function resolveCondition($arg1, $arg2, $operator)
    {

        switch ($operator) {

            case '==':
                return $arg1 == $arg2;
            case '<=':
                return $arg1 <= $arg2;
            case '<':
                return $arg1 < $arg2;
            case '>=':
                return $arg1 >= $arg2;
            case '>':
                return $arg1 > $arg2;
            case '!=':
                return $arg1 != $arg2;

            default:
                return $arg1 && $arg2;
        }

    }
}