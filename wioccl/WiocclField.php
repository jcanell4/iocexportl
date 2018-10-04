<?php
require_once "WiocclParser.php";

class WiocclField extends WiocclParser {

    protected function getContent ($token) {

        // es un array? el value tindrà el format xxx['yyy'] llavors el valor serà $this->arrays[xxx][yyy]

        if (preg_match ('/(.*?)\[(.*?)\]/', $token['value'], $matches)===1) {
            // es un array
            $varName = $matches[1];
            $key = $matches[2];
            return $this->arrays[$varName][$key];
        } else {
            $fieldName = $token['value'];

            // Primer comprovem als arrays i si no es troba comprovem el datasource
            if (isset($this->arrays[$fieldName])) {
                return json_encode($this->arrays[$fieldName]);
            } else if (isset($this->dataSource[$fieldName])) {
                return $this->dataSource[$fieldName];
            }



        }

        return '[ERROR: undefined field]';

    }

    protected function parseTokens($tokens, &$tokenIndex)
    {

        $result = '';


        while ($tokenIndex<count($tokens)) {

            $parsedValue = $this->parseToken($tokens, $tokenIndex);

            if ($parsedValue === null) { // tancament del field
                break;

            } else {
                $result .= $parsedValue;
            }

            ++$tokenIndex;
        }

        return $result;
    }
}