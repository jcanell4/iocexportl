<?php
require_once "WiocclParser.php";

class WiocclFunction extends WiocclParser
{

    protected $functionName = '';
    protected $arguments = [];


    protected function init($value)
    {
        if (preg_match('/(.*?)\((.*?)\)/s', $value, $matches) === 0) {
            throw new Exception("Incorrect function structure");
        };

        $this->functionName = $matches[1];
        $this->arguments = $this->extractArgs($matches[2]);
    }


    protected function extractArgs($string)
    {
        $string = preg_replace("/'/", '"', $string);
        $string = (new WiocclParser($string, $this->arrays, $this->dataSource))->getValue();
        $string = "[" . $string . "]";

        $jsonArgs = json_decode($string, true);

        return $jsonArgs;
    }
    
    protected function parseTokens($tokens, &$tokenIndex)
    {
        $result = '';
        $textFunction = '';
        while ($tokenIndex<count($tokens)) {

            $parsedValue = $this->parseToken($tokens, $tokenIndex);

            if ($parsedValue === null) { // tancament del field
                $this->init($textFunction);
                $result = call_user_func_array(array($this, $this->functionName), $this->arguments);
                break;

            } else {
                $textFunction .= $parsedValue;
            }

            ++$tokenIndex;
        }

        return $result;
    }

    protected function DATE($date, $sep="-")
    {
        return date("d".$sep."m".$sep."Y", strtotime(str_replace('/', '-', $date)));
    }

    // ALERTA: El paràmetre de la funció no ha d'anar entre cometes, ja es tracta d'un JSON vàlid
    protected function ARRAY_LENGTH($array)
    {
        return count($array);
    }

    protected function COUNTDISTINCT($array, $fields)
    {
        $unique = [];


        foreach ($array as $item) {
            $aux = '';
            foreach ($fields as $field) {
                $aux .= $item[$field];
            }
            if (!in_array($aux, $unique)) {
                $unique[] = $aux;
            }
        }

        return count($unique);
    }


    protected function FIRST($array, $template)
    {
        return $this->formatItem($array[0], 'FIRST', $template);
    }

    protected function LAST($array, $template)
    {
        return $this->formatItem($array[count($array)-1], 'LAST', $template);
    }

    // $template pot tenir tres formes:
    // FIRST: retorna tota la fila com a json
    // FIRST[camp]: retorna el valor del camp com a string
    // {"a":{##camX##}, "b":LAST[xx], "c":10, "d":"hola", "f":true})#}: retorna la mateixa plantilla amb els valors reemplaçats com a json.
    protected function formatItem($row, $ownKey, $template)
    {
        $jsonString = json_decode($template, true);

        if ($jsonString !== null) {
            $replaced = preg_replace_callback('/'.$ownKey.'\[(.*?)\]/', function ($matches) use ($row) {
                return $row[$matches[1]];
            }, $template);

            return $replaced;

        } else if ($template === $ownKey) {
            return json_encode($row);
        } else if (preg_match('/'.$ownKey.'\[(.*?)\]/', $template, $matches)) {
            return $row[$matches[1]];
        }
    }


}