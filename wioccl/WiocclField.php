<?php
require_once "WiocclParser.php";

class WiocclField extends WiocclParser {

    protected function getContent ($token) {
        $ret = '[ERROR: undefined field]';
        // es un array? el value tindrà el format xxx['yyy'] llavors el valor serà $this->arrays[xxx][yyy]

        if (preg_match ('/(.*?)\[(.*?)\]/', $token['value'], $matches)===1) {
            // es un array
            $varName = $matches[1];
            $key = $matches[2];
            $ret =$this->arrays[$varName][$key];
        } else {
            $fieldName = $token['value'];

            // Primer comprovem als arrays i si no es troba comprovem el datasource
            if (isset($this->arrays[$fieldName])) {
//                $ret =json_encode($this->arrays[$fieldName]);
                $ret =$this->arrays[$fieldName];
            } else if (isset($this->dataSource[$fieldName])) {
                $ret =$this->dataSource[$fieldName];
            }
        }

        if(!is_string($ret)){
            $ret = json_encode($ret);
        }
        return $ret;

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