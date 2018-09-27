<?php

class WiocclParser
{

    protected $rawValue;

    public function __construct($value)
    {
        $this->rawValue = $value;
    }

    protected $types = [
        'field' => [
//            'open' =>  '{##',
//            'close' => '{##',
//            'check' => '/^{##.*?##}$/',
            'class' => 'WiocclField',
            'stackable' => false,
        ],
        'if' => [
            /*            'open' => '<WIOCCL:IF .*?>',*/
//            'close' => '</WIOCCL:IF>',
//            'check' => '/^<WIOCCL:IF .*?</WIOCCL:IF>$/',
            'class' => 'WiocclIf'
        ],
    ];

    protected $tokenPatterns = [
        '{##' => [
            'state' => 'open_field',
            'type' => 'field'
        ],
        '##}' => [
            'state' => 'close_field',
            'type' => 'field'
        ],
        '<WIOCCL:IF .*?>' => [
            'state' => 'open_if',
            'type' => 'if'
        ],
        '</WIOCCL:IF>' => [
            'state' => 'close_if',
            'type' => 'if'
        ],
    ];

// TODO: automatitzar la creació a partir del token patterns? <-- no seria posible en el cas del open del if
// TODO: Determinar si es necessari coneixer el tipus o només cal l'state
    protected $tokenKey = [
        '<WIOCCL:IF' => ['state' => 'open_if', 'type' => 'if'],
        '</WIOCCL:IF>' => ['state' => 'close_if', 'type' => 'if'],
        '{##' => ['state' => 'open_field', 'type' => 'field'],
        '##}' => ['state' => 'close_field', 'type' => 'field']
    ];


    protected function getState($token)
    {

        foreach ($this->tokenKey as $key => $value) {

            if (strpos($token, $key) === 0) {
                // It starts with the token
                return $value['state'];
            }
        }

        return 'none';
    }

    public function getValue()
    {
        return $this->parse($this->rawValue);
    }

    protected function parse($value)
    {

        // Això no es correcte, falla en el cas d'una línia que comenci i acabi amb els mateixos tipus d'elements
//    foreach ($this->tokenPatterns as $type => $token) {
//        $matched = preg_match($token['check'], $value, $matches);
//        if ($matched === 1) {
//            return new $token['class'];
//        }
//    }

        // Si arriba aquí es que no es un element

        // si no es cap dels anteriors, s'ha de descomponsar. TODO: s'ha de normalitzar com es feia amb els arguments de condició
        $tokens = $this->tokenize($value); // això ha de retornar els tokens


        // això retornarà un únic valor amb els tokens concatenats
        return $this->parseTokens($tokens);
    }

    protected function tokenize($text)
    {

        // Creem la regexp que permet dividir el $text
        $pattern = '/(';

        foreach($this->tokenPatterns as $pattern=>$data) {
            $pattern .= $pattern . '|';
        }

        $pattern = substr($pattern, 0, strlen($pattern) -1) . ')/g';

        preg_match ( $pattern , $text,$matches);


        // A $matches s'han de trobar totes les coincidencies de la expressió amb la posició de manera que podem extra polar el contingut "pla" que no forma part dels tokens

        $tokens = [];


        for ($i=1; $i<count($matches); $i++) {

        }





    }


    protected function parseTokens($tokens)
    {

    }

}