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
        $string = "[". $string."]";
        
        $jsonArgs = json_decode($string, true);

        return $jsonArgs;
    }

    protected function parseTokens($tokens, &$tokenIndex = 0)
    {
        $this->arguments[] = "test";

        if (method_exists($this, $this->functionName)) {
            $result = call_user_func_array(array($this, $this->functionName), $this->arguments);
        } else {
            $result = '[Error: Unknown function]';
        }

        --$tokenIndex; // s'ha de tornar enrere perquè la funció es troba al token anterior

        return $result;
    }

    protected function DATE($date)
    {
        return date('d-m-Y', strtotime($date));
    }


}