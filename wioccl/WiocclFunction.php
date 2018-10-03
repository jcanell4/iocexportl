<?php
require_once "WiocclParser.php";

class WiocclFunction extends WiocclParser
{

    protected $functionName = '';
    protected $arguments = [];

    public function __construct($value = null, $arrays = [], $dataSource)
    {
        parent::__construct($value, $arrays, $dataSource);

        $this->init($value);
    }

    protected function init($value)
    {
        if (preg_match('/{#_(.*?)\((.*?)\)_#}/', $value, $matches) === 0) {
            throw new Exception("Incorrect function structure");
        };

        $this->functionName = $matches[1];
        $this->arguments = $this->extractArgs($matches[2]);
    }


    protected function extractArgs($string)
    {
//        $args = explode(',', $string);
//        $extractedArgs = [];
//
//        foreach ($args as $arg) {
//            $extractedArgs[] = $this->normalizeArg((new WiocclParser($arg, $this->arrays, $this->dataSource))->getValue());
//        }
        $string = (new WiocclParser($string, $this->arrays, $this->dataSource))->getValue();
        $string = "[" . $string . "]";

        $jsonArgs = json_decode($string, true);

        return $jsonArgs;
    }

    protected function parseTokens($tokens, &$tokenIndex = 0)
    {
        if (method_exists($this, $this->functionName)) {
            $result = call_user_func_array(array($this, $this->functionName), $this->arguments);
        } else {
            $result = '[Error: Unknown function ' . $this->functionName . ']';
        }

        --$tokenIndex; // s'ha de tornar enrere perquè la funció es troba al token anterior

        return $result;
    }

    protected function DATE($date)
    {
        return date('d-m-Y', strtotime($date));
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


    protected function FIRST($array, $store)
    {
        // $store pot tenir tres formes:
        // FIRST: retorna tota la fila com a json
        // FIRST[camp]: retorna el valor del camp com a string
        // {"a":{##camX##}, "b":LAST[xx], "c":10, "d":"hola", "f":true})#}: retorna la mateixa plantilla amb els valors reemplaçats com a json.

//        $jsonString = json_decode($store, true);
//
//        if ($jsonString !== null) {
//            $replaced = preg_replace_callback('/FIRST\[(.*?)\]/', function ($matches) use ($array) {
//                return $array[0][$matches[1]];
//            }, $store);
//
//            return $replaced;
//
//        } else if ($store === 'FIRST') {
//            return json_encode($array[0]);
//        } else if (preg_match('/FIRST\[(.*?)\]/', $store, $matches)) {
//            return $array[0][$matches[1]];
//        }

        return $this->formatItem($array[0], 'FIRST', $store);
    }

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


    protected function LAST($array, $store)
    {
        return $this->formatItem($array[count($array)-1], 'LAST', $store);

        // $store pot tenir tres formes:
        // FIRST: retorna tota la fila com a json
        // FIRST[camp]: retorna el valor del camp com a string
        // {"a":{##camX##}, "b":LAST[xx], "c":10, "d":"hola", "f":true})#}: retorna la mateixa plantilla amb els valors reemplaçats com a json.

//        $jsonString = json_decode($store, true);
//
//        if ($jsonString !== null) {
//            $replaced = preg_replace_callback('/FIRST\[(.*?)\]/', function ($matches) use ($array) {
//                return $array[0][$matches[1]];
//            }, $store);
//
//            return $replaced;
//
//        } else if ($store === 'FIRST') {
//            return json_encode($array[0]);
//        } else if (preg_match('/FIRST\[(.*?)\]/', $store, $matches)) {
//            return $array[0][$matches[1]];
//        }
    }


//
//
//        // TODO: el valor a desar i retornar es l´ultim corresponent al camp
//        $key = key($store);
//        $field = reset($store);
//        $this->arrays[$key] = $array[count($array) - 1][$field];
//
//        return $this->arrays[$key];
//    }

}