<?php
require_once "WiocclParser.php";

class WiocclFor extends WiocclParser {
    
    private $step = 1;
    private $from;
    private $to;
    private $counterName;

    public function __construct($value = null, $arrays = [], $dataSource)
    {
        parent::__construct($value, $arrays, $dataSource);

        $this->counterName = $this->extractVarName($value, "counter");
        $this->from = $this->extractNumber($value, "from");
        $this->to = $this->extractNumber($value, "to");

    }

    protected function parseTokens($tokens, &$tokenIndex)
    {

        $result = '';
        $startTokenIndex = $tokenIndex;
        $lastBlockIndex = null;
        $lastTokenIndex = 0;

        if($this->from > $this->to){
            $this->arrays[$this->counterName] = -1;
            $this->parseTokensOfItem($tokens, $tokenIndex);
        }else{
            for ($arrayIndex = $this->from; $arrayIndex<=$this->to; $arrayIndex+= $this->step) {

                $tokenIndex = $startTokenIndex;
                $this->arrays[$this->counterName] = $arrayIndex;

                $result .= $this->parseTokensOfItem($tokens, $tokenIndex);

                $lastTokenIndex = $tokenIndex;

            }
            
            $tokenIndex = $lastTokenIndex;
        }


        return $result;
    }
    
    protected function parseTokensOfItem($tokens, &$tokenIndex){
        $result = '';
        while ($tokenIndex < count($tokens)) {

            $parsedValue =  $this->parseToken($tokens, $tokenIndex);

            if ($parsedValue === null) { // tancament del foreach
                break;

            }
            $result .= $parsedValue;

            ++$tokenIndex;
        }
        return $result;
    }

}