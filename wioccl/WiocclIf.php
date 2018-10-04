<?php
require_once "WiocclParser.php";

class WiocclIf extends WiocclParser
{

    protected $condition = false;

    public function __construct($value = null, $arrays = [], $dataSource)
    {
        parent::__construct($value, $arrays, $dataSource);

        $this->condition = $this->evaluateCondition($value);

    }

    protected function parseTokens($tokens, &$tokenIndex)
    {

        $result = '';

        while ($tokenIndex < count($tokens)) {
            $parsedValue = $this->parseToken($tokens, $tokenIndex);

            if ($parsedValue === null) { // tancament del if
                break;

            } else {
                $result .= $parsedValue;
            }

            ++$tokenIndex;
        }


        return ($this->condition ? $result : '');
    }


    protected function evaluateCondition($value)
    {

        if (!$value) {
            return false;
        }

        // ALERTA! la condició es troba entre cometes: condition="
        if (preg_match('/condition="(.*?([><=!]=?))(.*?)">/', $value, $matches) === 0) {
            // ALERTA: Actualment el token amb > arriba tallat perquè l'identifica com a tancament del token d'apertura
            return false;
//            throw new Exception("Incorrect condition structure");
        };

        $arg1 = $this->normalizeArg(str_replace(['==', '>', '<', '!=', '>=', '<=', '='], '', $matches[1]));
        $arg2 = $matches[3];

        $operator = $matches[2];

        $arg1 = $this->normalizeArg((new WiocclParser($arg1, $this->arrays, $this->dataSource))->getValue());
        $arg2 = $this->normalizeArg((new WiocclParser($arg2, $this->arrays, $this->dataSource))->getValue());

        return $this->resolveCondition($arg1, $arg2, $operator);
    }


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

        }

//        throw new Exception ("Condition " . $operator . " not supported.");
        return false;

    }
}