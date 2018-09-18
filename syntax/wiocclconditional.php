<?php
/**
 * lang Syntax Plugin
 *
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(DOKU_PLUGIN . 'syntax.php');
require_once(DOKU_PLUGIN . 'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_wiocclconditional extends DokuWiki_Syntax_Plugin
{

    /**
     * ALERTA[Xavi] Duplicat
     */
    protected $dataSource = null;

    protected $conditionResult = false;

    /**
     * Get an associative array with plugin info.
     */
    function getInfo()
    {
        return array(
            'author' => 'Josep Cañellas',
            'email' => 'jcanell4@ioc.cat',
            'date' => '2015-10-30',
            'name' => 'IOC grave Plugin',
            'desc' => 'Plugin to parse grave accents syntax in pdf and html',
            'url' => 'http://ioc.gencat.cat/',
        );
    }

    function getType()
    {
        return 'substition'; // TODO[Xavi] convertir en container
    }

    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'
//    function getAllowedTypes() {
//        return array('formatting', 'protected');
//    }
    function getSort()
    {
//        return 50;
        return 50;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode)
    {
//        $this->Lexer->addSpecialPattern('<WIOCCL:IF .*?</WIOCCL:IF>', $mode, 'plugin_iocexportl_wiocclconditional');

        $this->Lexer->addEntryPattern('<WIOCCL:IF .*?>',$mode,'plugin_iocexportl_wiocclconditional');
    }

    function postConnect() {

//        //aliniació
//        $this->Lexer->addPattern('[\t ]*:::[\t ]*(?=[\|\^])','plugin_iocexportl_iocbtable');
//        $this->Lexer->addPattern('[\t ]+','plugin_iocexportl_iocbtable');
//
//        //final de taula
//        $this->Lexer->addExitPattern('\^{2,}\][\t ]*\n','plugin_iocexportl_iocbtable');
//        $this->Lexer->addExitPattern('\|{2,}\][\t ]*\n','plugin_iocexportl_iocbtable');

        $this->Lexer->addExitPattern('</WIOCCL:IF>','plugin_iocexportl_wiocclconditional');
//
//        $this->Lexer->addExitPattern('\^\][\t ]*\n','plugin_iocexportl_iocbtable');
//        $this->Lexer->addExitPattern('\|\][\t ]*\n','plugin_iocexportl_iocbtable');
//
//        //final
//        $this->Lexer->addPattern('\^{2,}[\t ]*\n','plugin_iocexportl_iocbtable');
//        $this->Lexer->addPattern('\|{2,}[\t ]*\n','plugin_iocexportl_iocbtable');
//
//        $this->Lexer->addPattern('\^[\t ]*\n','plugin_iocexportl_iocbtable');
//        $this->Lexer->addPattern('\|[\t ]*\n','plugin_iocexportl_iocbtable');
//
//
//        //inicial i intermedis
//        $this->Lexer->addPattern('\^{2,}','plugin_iocexportl_iocbtable');
//        $this->Lexer->addPattern('\|{2,}','plugin_iocexportl_iocbtable');
//
//        $this->Lexer->addPattern('\^','plugin_iocexportl_iocbtable');
//        $this->Lexer->addPattern('\|','plugin_iocexportl_iocbtable');
    }





    /**
     * Handle the match
     */

    function handle($match, $state, $pos, &$handler)
    {
        // $state es un nombre, en aquest cas no ens interessa
        // $match es la coincidencia, per exemple: {##tipusModulBloc##}
        // auquests valors arriban com a index 0 = $state y 1 = $match al $data del render
        // true es el valor que passem per indicar si ha de ser raw (false) o interpretar la expresió/camp (true)

        switch ( $state ) {
            case DOKU_LEXER_ENTER: // Aquesta es l'entrada i s'ha d'extreure el conditional
                // aquí ja es té suficient informació per determinar si el unmatched s'ha d'afegir o no

                $this->conditionResult = $this->evaluateCondition($match);
                break;

            case DOKU_LEXER_EXIT: // Aquesta es la sortida, aquí s'ha d'indicar d'alguna forma que si la condició s'ha resolt com a true o com a false>?

                if (!$this->conditionResult) {
                    $match = '';
                }

                break;

            case DOKU_LEXER_UNMATCHED: // Això es el contingut intern entre les etiquetes que s'ha de reparsejar
                // aquí tenim accés al que afegim al enter, així que podem reemplaçar el $match per ''
                // ALERTA: aquí s'ha de modificar el $match per ser

//                $match = $this->parse($match, $mode);<-- no hi ha mode aquí

                break;

            case DOKU_LEXER_MATCHED:
                break;
        }


        return array($state, $match, true, $this->conditionResult);
    }



    function render($mode, &$renderer, $data, $show) {
        // ALERTA[Xavi] Falta controlar el $mode com al wiooclfield!

        if ($data[0] !== DOKU_LEXER_UNMATCHED) { // només s'afegeix el unmatched
            return true;
        }



        if (!$show) {
            $text = 0;
        }

        $text = $this->parse($data[1], $mode);

//        $field = $this->getRenderString($data[1]);

//        $dataSource = $this->getDataSource();

        if (strlen($text) === 0) {

        } else if (isset($data[2]) && $data[2]===false) {
            $renderer->doc .=  '<b style="color:grey">' . $data[1] . '</b>';
        } else {
            $renderer->doc .=  '<b style="color:blue">' . $text . '</b>';
        }



        return true;

//        global $symbols;
//        if ($mode === 'ioccounter'){
//            $renderer->doc .=  '`';
//            return TRUE;
//        }elseif ($mode === 'xhtml'){
//            $renderer->doc .= '`';
//            return TRUE;
//        }elseif ($mode === 'iocxhtml'){
//            $renderer->doc .= '`';
//            return TRUE;
//        }elseif ($mode === 'iocexportl'){
//            $renderer->doc .= filter_tex_sanitize_formula("$\grave{\:}$");
//            return TRUE;
//        }
//        return FALSE;
    }

    /**
     * ALERTA[XAVI] Duplicat
     */
    function setDataSource($dataSource) {
        $this->dataSource = $dataSource;
    }
//
//    /**
//     * ALERTA[XAVI] Duplicat
//     */
//    function getDataSource()
//    {
//        global $plugin_controller;
//
//        if (!$this->dataSource) {
//            try {
//                $this->dataSource = $plugin_controller->getCurrentProjectDataSource();
//            } catch (Exception $e) {
//                $this->dataSource = null;
//            }
//        }
//        return $this->dataSource;
//    }

//    /**
//     * ALERTA[XAVI] Duplicat
//     */
//    function getRenderString($data)
//    {
//
//        // pas 1: reemplaçar els camps pels seus valors
//        $line = $this->replaceFieldsWithData($data, $this->getDataSource());
//
//        //   $line = $data;
//
//        // Determinar el valor a posar si es cert
//
/*        preg_match('/(?:<WIOCCL:IF .*?>)(.*?)(?:<\/WIOCCL:IF>)/', $line, $matches);*/
//
//        $trueResult = $matches[1];
//
//        preg_match('/condition=(.*?([><=]=?))(.*?)>/', $line, $matches);
//
//        $arg1 = $this->normalizeArg(str_replace(['==', '>', '<', '=', '>=', '<=', '!='], '', $matches[1]));
//        $arg2 = $this->normalizeArg($matches[3]);
//        $operator = $matches[2];
//
//        if ($this->resolveCondition($arg1, $arg2, $operator)) {
//            return $trueResult;
//        }
//
//        return '';
//
//    }

    protected function evaluateCondition($text) {




        // ALERTA! la condició es troba entre cometes: condition="
        preg_match('/condition="(.*?([><=]=?))(.*?)">/', $text, $matches);

        $arg1 = $this->normalizeArg(str_replace(['==', '>', '<', '=', '>=', '<=', '!='], '', $matches[1]));
        $arg2 = $this->normalizeArg($matches[3]);
        $operator = $matches[2];

        $arg1 = $this->normalizeArg($this->parse($arg1, 'xhtml'));
        $arg2 = $this->normalizeArg($this->parse($arg2, 'xhtml'));

        return $this->resolveCondition($arg1, $arg2, $operator);
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

        }

//        throw new Exception ("Condition " . $operator . " not supported.");
        return false;

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
        } else {
            return $arg;
        }

    }

//    // TODO: Crear una superclasse o trait pels wioccl que contingui les funcions comunes
//    protected function replaceFieldsWithData($line, $dataSource)
//    {
//
//        return preg_replace_callback(
//            '/{##.*?##}/',
//            function ($matches) use ($dataSource) {
//                $field = substr($matches[0], 3, strlen($matches[0]) - 6);
//                return $dataSource[$field];
//            },
//            $line);
//
//    }

    function parse($text, $mode) {
        if (is_numeric($text) || $text === true || $text == false) {
            return $text;
        }

        $instructions = p_get_instructions($text);
        $parsedText = p_render($mode, $instructions, $info);


        $parsedText = substr($parsedText, 4, strlen($parsedText)-9);

        preg_replace( "/^\n*/m", "", $parsedText );

        return $parsedText;

        // Remove

    }
}
