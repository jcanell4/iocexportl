<?php
require_once "WiocclParser.php";

class WiocclForEach extends WiocclParser
{

    protected $varName;
    protected $fullArray = [];

    protected $validator;

    public function __construct($value = null, $arrays = [], $dataSource)
    {
        parent::__construct($value, $arrays, $dataSource);

        $this->varName = $this->extractVarName($value);
        $this->fullArray = $this->extractArray($value);
        $this->validator = new _WiocclCondition($value, $arrays, $dataSource);
    }

    protected function parseTokens($tokens, &$tokenIndex = 0)
    {

        $result = '';
        $startTokenIndex = $tokenIndex;
        $lastBlockIndex = null;
        $lastTokenIndex = 0;

        for ($arrayIndex = 0; $arrayIndex < count($this->fullArray); $arrayIndex++) {

            $tokenIndex = $startTokenIndex;
            $row = $this->fullArray[$arrayIndex];
            $this->arrays[$this->varName] = $row;

            // Els format dels arrays al fullArray es similar a (sense els index 0, 1, etc):
//            '[
//                '0' => ['tipus' => 'lalala', 'eina' => 'ggg', 'opcionalitat' => 'cap'],
//                '1' => ['tipus' => 'oooo', 'eina' => 'elelel', 'opcionalitat' => 'no']
//            ]

            // El format que es passa a arrays es:
            //      ['tipus' => 'lalala', 'eina' => 'ggg', 'opcionalitat' => 'cap']

            $process = $this->validator->validate();

            if (!$process && $lastTokenIndex > 0) {
                // Ja s'ha processat previament el token de tancament i no s'acompleix la condició, no cal continuar processant
                continue;
            }

            while ($tokenIndex < count($tokens)) {

                $parsedValue = $this->parseToken($tokens, $tokenIndex);

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
}