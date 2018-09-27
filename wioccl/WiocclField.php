<?php
require_once "WiocclParser.php";

class WiocclField extends WiocclParser {

    protected function getContent ($token) {

        // es un array? el value tindrà el format xxx['yyy'] llavors el valor serà $this->arrays[xxx][yyy]

        if (preg_match ('/(.*?)\[\'(.*?)\'\]/', $token['value'], $matches)===1) {
            // es un array
            $varName = $matches[1];
            $key = $matches[2];
            return $this->arrays[$varName][$key]; // ALERTA: El valor emmagatzemmat pot ser un field? o sempre serà un valor?
        } else {
            return isset($this->dataSource[$token['value']])?$this->dataSource[$token['value']] : '[ERROR: undefined field]';
        }

    }

    protected function parseTokens($tokens, &$tokenIndex)
    {

        $result = '';


        while ($tokenIndex<count($tokens)) {

            $parsedValue = $this->parseToken($tokens, $tokenIndex);

            if ($parsedValue == null) { // tancament del field
                break;

            } else {
                $result .= $parsedValue;
            }

            ++$tokenIndex;
        }

        return $result;
    }
}