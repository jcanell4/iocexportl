<?php

require_once "WiocclField.php";
require_once "WiocclIf.php";
require_once "WiocclForEach.php";
require_once "WiocclBlock.php";
require_once "WiocclFunction.php";

class WiocclParser
{

    protected $rawValue;


    // TODO: El datasource es passarà al constructor del parser desde la wiki
    protected $dataSource = [];

    protected $arrays = [];

    /* TODO: els noms dels WIOOCCL s'extrauran automàticament */
    protected $tokenPatterns = [
        '{##' => [
            'state' => 'open_field',
        ],
        '##}' => [
            'state' => 'close_field',
        ],
        '{#_.*?_#}' => [
            'state' => 'open_function',
        ],
//        '_#}' => [
//            'state' => 'close_function',
//        ],

        '<WIOCCL:IF .*?>' => [
            'state' => 'open_if',
        ],
        '</WIOCCL:IF>' => [
            'state' => 'close_if',
        ],
        '<WIOCCL:FOREACH .*?>' => [
            'state' => 'open_foreach',
        ],
        '</WIOCCL:FOREACH>' => [
            'state' => 'close_foreach',
        ],
        '<WIOCCL:BLOCK>' => [
            'state' => 'open_block',
//            'type' => 'block',
        ],
        '</WIOCCL:BLOCK>' => [
            'state' => 'close_block',
//            'type' => 'block'
        ]


    ];

    // ALERTA: eliminar el nom de la las
    // TODO: automatitzar la creació a partir del token patterns? <-- no seria posible en el cas del open del if
    // TODO: Determinar si es necessari coneixer el tipus o només cal l'state
    // Automatitzar la generació de noms de les classes a partir del wioccl:**
    protected $tokenKey = [
        '<WIOCCL:BLOCK' => ['state' => 'open_block', 'type' => 'block', 'class' => 'WiocclBlock', 'action' => 'open'],
        '</WIOCCL:BLOCK>' => ['state' => 'close_block', 'type' => 'block', 'action' => 'close'],
        '<WIOCCL:FOREACH' => ['state' => 'open_foreach', 'type' => 'foreach', 'class' => 'WiocclForEach', 'action' => 'open'],
        '</WIOCCL:FOREACH>' => ['state' => 'close_foreach', 'type' => 'foreach', 'action' => 'close'],
        '<WIOCCL:IF' => ['state' => 'open_if', 'type' => 'if', 'class' => 'WiocclIf', 'action' => 'open'],
        '</WIOCCL:IF>' => ['state' => 'close_if', 'type' => 'if', 'action' => 'close'],
        '{##' => ['state' => 'open_field', 'type' => 'field', 'class' => 'WiocclField', 'action' => 'open'],
        '##}' => ['state' => 'close_field', 'type' => 'field', 'action' => 'close'],
        '{#_' => ['state' => 'open_function', 'type' => 'field', 'class' => 'WiocclFunction', 'action' => 'open'],
        '_#}' => ['state' => 'close_function', 'type' => 'field', 'action' => 'close']
    ];


    // TODO: Afegir dataSource al constructor, deixem els arrays separats perque el seu us es intern, al datasource es ficaran com a JSON
    public function __construct($value = null, $arrays = [], $dataSource = [])
    {
        $this->rawValue = $value;
        $this->arrays += $arrays;
        $this->dataSource = $dataSource; // TODO: Reactivar quan es comprovi que funciona
    }


    public function getValue()
    {
        return $this->parse($this->rawValue);
    }

    public function getTokensValue($tokens, &$tokenIndex)
    {
        return $this->parseTokens($tokens, $tokenIndex);
    }

    protected function parse($value)
    {
        $tokens = $this->tokenize($value); // això ha de retornar els tokens
        return $this->parseTokens($tokens); // això retorna un únic valor amb els valor dels tokens concatenats
    }

    protected function getContent($token)
    {
        return $token['value'];
    }

    protected function tokenize($rawText)
    {

        // Creem la regexp que permet dividir el $text
        $pattern = '(';

        foreach ($this->tokenPatterns as $statePattern => $data) {
            $pattern .= $statePattern . '|';
        }

        $pattern = substr($pattern, 0, strlen($pattern) - 1) . ')';

        preg_match_all($pattern, $rawText, $matches, PREG_OFFSET_CAPTURE);

        // A $matches s'han de trobar totes les coincidencies de la expressió amb la posició de manera que podem extra polar el contingut "pla" que no forma part dels tokens

        $tokens = [];

        $pos = 0;

        for ($i = 0; $i < count($matches[0]); $i++) {
            $match = $matches[0][$i];

            $len = strlen($match[0]);

            // la posició inicial es igual a la posició final del token anterior? <-- s'ha trobat content
            if ($pos !== $match[1]) {
                $text = substr($rawText, $pos, $match[1] - $pos);
                $tokens[] = ['state' => 'content', 'value' => $text];
            }

            $tokens[] = $this->generateToken($match[0]);

            $pos = $match[1] + $len;
        }

        if ($pos < strlen($rawText)) {
            $tokens[] = ['state' => 'content', 'value' => substr($rawText, $pos, strlen($rawText) - $pos)];
        }


        return $tokens;

    }

    protected function generateToken($tokenInfo)
    {
        $token = ['state' => 'none', 'class' => null, 'value' => $tokenInfo];


        foreach ($this->tokenKey as $key => $value) {

            if (strpos($tokenInfo, $key) === 0) {
                // It starts with the token
                $token['state'] = $value['state'];
                $token['class'] = isset($value['class']) ? $value['class'] : null;
                $token['action'] = $value['action'];
            }
        }

        return $token;
    }

    protected function parseTokens($tokens, &$tokenIndex = 0)
    {

        $result = '';

        while ($tokenIndex < count($tokens)) {
            $newChunk = $this->parseToken($tokens, $tokenIndex);
            $result .= $newChunk !== null ? $newChunk : '';
            ++$tokenIndex;
        }

        return $result;
    }

    // l'index del token analitzat s'actualitza globalment per referència
    protected function parseToken($tokens, &$tokenIndex)
    {

        $currentToken = $tokens[$tokenIndex];
        $result = '';

        if ($currentToken['state'] == 'content') {
            $action = 'content';
        } else {
            $action = $currentToken['action'];
        }


        switch ($action) {
            case 'content':
                $result .= $this->getContent($currentToken);
                break;

            case 'open':
                $item = $this->getClassForToken($currentToken);
                $result .= $item->getTokensValue($tokens, ++$tokenIndex);
                break;

            case 'close':
                return null;
                break;
        }

        return $result;
    }

    protected function getClassForToken($token)
    {
        // TODO: pasar el datasource i els arrays al constructor
        return new $token['class']($token['value'], $this->arrays, $this->dataSource);
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
