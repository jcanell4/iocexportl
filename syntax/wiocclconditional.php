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
//        $this->Lexer->addSpecialPattern('\x60|\$\\\grave{\\\\:}\$', $mode, 'plugin_iocexportl_iocfield');
        //$this->Lexer->addSpecialPattern('\{\#\#(?=.*)\#\#\}', $mode, 'plugin_iocexportl_iocfield');

//        $this->Lexer->addSpecialPattern('{##.*?##}', $mode, 'plugin_iocexportl_iocfield');
        $this->Lexer->addSpecialPattern('<WIOCCL:IF .*?</WIOCCL:IF>', $mode, 'plugin_iocexportl_wiocclconditional');
    }

    /**
     * Handle the match
     */

    function handle($match, $state, $pos, &$handler)
    {
        // $state es un nombre, en aquest cas no ens interessa
        // $match es la coincidencia, per exemple: {##tipusModulBloc##}
        // auquests valors arriban com a index 0 = $state y 1 = $match al $data del render

        return array($state, $match, true);
    }

    function render($mode, &$renderer, $data) {

        $field = $this->getRenderString($data[1]);

        $dataSource = $this->getDataSource();

        if (strlen($field) === 0) {

        } else if ($dataSource == null || isset($data[2]) && $data[2]===false) {
            $renderer->doc .=  '<b style="color:grey">' . $data[1] . '</b>';
        } else {
            $renderer->doc .=  '<b style="color:blue">' . $field . '</b>';
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

    /**
     * ALERTA[XAVI] Duplicat
     */
    function getDataSource()
    {
        global $plugin_controller;

        if (!$this->dataSource) {
            try {
                $this->dataSource = $plugin_controller->getCurrentProjectDataSource();
            } catch (Exception $e) {
                $this->dataSource = null;
            }
        }
        return $this->dataSource;
    }

    /**
     * ALERTA[XAVI] Duplicat
     */
    function getRenderString($data)
    {

        // pas 1: reemplaçar els camps pels seus valors
        $line = $this->replaceFieldsWithData($data, $this->getDataSource());

        //   $line = $data;

        // Determinar el valor a posar si es cert

        preg_match('/(?:<WIOCCL:IF .*?>)(.*?)(?:<\/WIOCCL:IF>)/', $line, $matches);

        $trueResult = $matches[1];

        preg_match('/condition=(.*?([><=]=?))(.*?)>/', $line, $matches);

        $arg1 = $this->normalizeArg(str_replace(['==', '>', '<', '=', '>=', '<=', '!='], '', $matches[1]));
        $arg2 = $this->normalizeArg($matches[3]);
        $operator = $matches[2];

        if ($this->resolveCondition($arg1, $arg2, $operator)) {
            return $trueResult;
        }

        return '';

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

    // TODO: Crear una superclasse o trait pels wioccl que contingui les funcions comunes
    protected function replaceFieldsWithData($line, $dataSource)
    {

        return preg_replace_callback(
            '/{##.*?##}/',
            function ($matches) use ($dataSource) {
                $field = substr($matches[0], 3, strlen($matches[0]) - 6);
                return $dataSource[$field];
            },
            $line);

    }
}
