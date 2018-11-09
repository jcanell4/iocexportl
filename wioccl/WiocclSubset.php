<?php
require_once "WiocclParser.php";

class WiocclSubset extends WiocclParser {

    protected $varName;
    protected $fullArray =[];
    protected $itemName;
    protected $filterArgs;

    const ARG1 = 0;
    const ARG2 = 2;
    const OPERATOR = 1;

    public function __construct($value = null, $arrays = [], $dataSource, $generateSubset = true)
    {
        parent::__construct($value, $arrays, $dataSource);

        // varName correspón a la propietat var i es el nom de l'array
        // ALERTA! els arrays es llegeixen com un camp, la conversió d'array al seu valor es tracta al field

        $this->varName = $this->extractVarName($value);
        $this->fullArray = $this->extractArray($value);
        $this->itemName = $this->extractArrayItemName($value);
        $this->filterArgs = $this->extractFilterArgs($value);


        if ($this->filterArgs!== null && $generateSubset) {
            // TODO: efegir el conditional
            // Crear el subsset
            $subset = $this->generateSubset();


            $this->arrays[$this->varName] = $subset;
        }

    }


    protected function extractVarName($value) {
        if (preg_match('/subsetvar="(.*?)"/', $value, $matches)) {
            return $matches[1];
        } else {
            throw new Exception("subsetvar name is missing");
        }
    }

    protected function extractArrayItemName($value) {
        if (preg_match('/arrayitem="(.*?)"/', $value, $matches)) {
            return $matches[1];
        } else {
            throw new Exception("arrayitem name is missing");
        }

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

    protected function generateSubset() {
        $subset = [];


        foreach ($this->fullArray as $row) {

            // TODO: Extreure a una funció a part per poder reutilizar al foreach, if i subset
            $this->arrays[$this->itemName] = $row;

            $arg1 = $this->normalizeArg((new WiocclParser($this->filterArgs[self::ARG1], $this->arrays, $this->dataSource))->getValue());
            $arg2 = $this->normalizeArg((new WiocclParser($this->filterArgs[self::ARG2], $this->arrays, $this->dataSource))->getValue());

            if ($this->resolveCondition($arg1, $arg2, $this->filterArgs[self::OPERATOR])) {
                $subset[] = $row;
            }
        }

        unset($this->arrays[$this->itemName]);

        return $subset;
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