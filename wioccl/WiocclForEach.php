<?php
require_once "WiocclParser.php";

class WiocclForEach extends WiocclParser {

    protected $varName;
    protected $counterName;
    protected $fullArray =[];
//    protected $filterArgs;
    protected $logicOp;

    const ARG1 = 0;
    const ARG2 = 2;
    const OPERATOR = 1;
    const ARRAY_INDEX_ATTR = "counter";
    const FILTER_ATTR = "filter";

    public function __construct($value = null, $arrays = [], $dataSource)
    {
        parent::__construct($value, $arrays, $dataSource);

        // varName correspón a la propietat var i es el nom de l'array
        // ALERTA! els arrays es llegeixen com un camp, la conversió d'array al seu valor es tracta al field

        $this->varName = $this->extractVarName($value);
        $this->counterName = $this->extractVarName($value, self::ARRAY_INDEX_ATTR, false);
        $this->fullArray = $this->extractArray($value);
//        $this->filterArgs = $this->extractFilterArgs($value);
        $this->logicOp = _LogicParser::getOperator($this->extractVarName($value, self::FILTER_ATTR, false));

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
            if(!empty($this->counterName)){
                $this->arrays[$this->counterName] = $arrayIndex;
            }
            
//
//
//            // TODO: Extreure a una funció a part per poder reutilizar al foreach, if i subset
//
//            $arg1 = $this->normalizeArg((new WiocclParser($this->filterArgs[self::ARG1], $this->arrays, $this->dataSource))->getValue());
//            $arg2 = $this->normalizeArg((new WiocclParser($this->filterArgs[self::ARG2], $this->arrays, $this->dataSource))->getValue());
//
//
//
//
//
//
//
//
//
//            // Els format dels arrays al fullArray es similar a (sense els index 0, 1, etc):
////            '[
////                '0' => ['tipus' => 'lalala', 'eina' => 'ggg', 'opcionalitat' => 'cap'],
////                '1' => ['tipus' => 'oooo', 'eina' => 'elelel', 'opcionalitat' => 'no']
////            ]
//
//            // El format que es passa a arrays es:
//            //      ['tipus' => 'lalala', 'eina' => 'ggg', 'opcionalitat' => 'cap']
//
//
//            // ALERTA: No es pot fer
//            $process = $this->filterArgs==NULL || $this->resolveCondition($arg1, $arg2, $this->filterArgs[self::OPERATOR]);

            $this->logicOp->parseData($this->arrays, $this->datasource);
            $process = $this->logicOp->getValue();
            
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

class _LogicParser{
//    protected $text;
//    function __construct($text) {
//        $this->text = $text;
//    }        
    
    public static function getOperator($text){
        $ret=NULL;
//        if(preg_match('/\(.*\)/', $text, $matches) === 1){
//            
//        }
        $aOrOp = explode("||", $text, 2);
        if(count($aOrOp)>1){//OR
            $ret = new _OrOperation(_LogicParser::getOperator($aOrOp[0]), _LogicParser::getOperator($aOrOp[1]));
        }else{//AND
            $aAndOp = explode("&&", $text, 2);
            if(count($aAndOp)>1){
                $ret = new _AndOperation(_LogicParser::getOperator($aAndOp[0]), _LogicParser::getOperator($aAndOp[1]));
            }else if(preg_match('/[=!]=/', $text) === 1){//CONDITION == o !=
                $ret = new _ConditionOperation($text);
            }else if(preg_match('/[><]=?/', $text) === 1){//CONDITION <, >, <=, <=
                $ret = new _ConditionOperation($text);
            }else if(preg_match('/!/', $text) === 1){// NotOperation
                $ret = new _NotOperation(_LogicParser::getOperator($text));
            }else{//LITERAL
                $ret = new _Literal($text);
            }
        }
        return $ret;
    }
}

abstract class _LogicOperation{
    abstract function getValue();
    
    abstract function parseData($arrays, $datasource);
}

abstract class _BinaryOperation extends _LogicOperation{    
    private $operator1;
    private $operator2;

    function __construct($op1, $op2=NULL) {
        $this->operator1 = $op1;
        $this->operator2 = $op2;
    }

    public function getOperator1(){
        return $this->operator1;
    }
    
    public function getOperator2(){
        return $this->operator2;
    }

    public function setOperator1($operator1){
        $this->operator1 = $operator1;
    }
    
    public function setOperator2($operator2){
        $this->operator2 = $operator2;
    }
    
    public function parseData($arrays, $datasource){
        $this->operator1->parseData($arrays, $datasource);
        if($this->operator2!==NULL){
            $this->operator2->parseData($arrays, $datasource);
        }
    }
}

class _Literal extends _LogicOperation{
    private $literal;
    private $value;
    
    function __construct($text) {
        $this->literal = $this->normalizeArg($text);
    }

    public function getValue() {
        return $this->value?$this->normalizeArg($this->value):true;
    }
    
    public function parseData($arrays, $datasource) {
        $this->value = (new WiocclParser($this->literal, $arrays, $datasource))->getValue();
    }

    protected function normalizeArg($arg)
    {
        if (strtolower($arg) == 'true') {
            return true;
        } else if (strtolower($arg) == 'false') {
            return false;
        } else if (is_int($arg)) {
            return intval($arg);
        } else if (is_numeric($arg)) {
            return floatval($arg);
        } else if (preg_match("/^'(.*?)'$/", $arg, $matches) === 1) {
            return $this->normalizeArg($matches[1]);
        } else {
            return $arg;
        }

    }
}

class _NotOperation extends _BinaryOperation{
    function __construct($operator1) {
        parent::__construct($operator1);
    }

    public function getValue() {
        return !$this->getOperator1()->getValue();
    }
}

class _AndOperation extends _BinaryOperation{
    
    function __construct($operator1, $operator2) {
        parent::__construct($operator1, $operator2);
    }

    public function getValue() {
        return $this->getOperator1()->getValue() && $this->getOperator2()->getValue();
    }
}

class _OrOperation extends _BinaryOperation{
    
    function __construct($operator1, $operator2) {
        parent::__construct($operator1, $operator2);
    }

    public function getValue() {
        return $this->getOperator1()->getValue() || $this->getOperator2()->getValue();
    }
}

class _ConditionOperation extends _LogicOperation{
    private $operation;
    private $arg1;
    private $arg2;
    private $value1;
    private $value2;
            
    function __construct($expression) {
        $ac = $this->extractFilterArgs($expression);
        $this->arg1 = $ac[0];
        $this->arg2 = $ac[2];
        $this->operation = $ac[1];
    }

    public function parseData($arrays, $datasource) {
        $this->value1 = (new WiocclParser($this->arg1, $arrays, $datasource))->getValue();
        $this->value2 = (new WiocclParser($this->arg2, $arrays, $datasource))->getValue();
    }

    public function getValue() {
        return $this->resolveCondition($this->value1, $this->value2, $this->operation);
    }

    protected function extractFilterArgs($value) {
        if (preg_match('/(.*?)([><=]=?)(.*)/', $value, $matches) === 1) {
            // ALERTA: Actualment el token amb > arriba tallat perquè l'identifica com a tancament del token d'apertura

            $arg1 = $matches[1];
            $arg2 = $matches[3];
            $operator = $matches[2];



            return [$arg1, $operator, $arg2];
//            throw new Exception("Incorrect condition structure");
        };
        return null;
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

            default:
                return $arg1 && $arg2;
        }

    }
}