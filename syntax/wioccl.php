<?php
/**
 * lang Syntax Plugin
 *
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

require_once(DOKU_PLUGIN.'iocexportl/wioccl/WiocclParser.php');


class syntax_plugin_iocexportl_wioccl extends DokuWiki_Syntax_Plugin {

    /**
     * ALERTA[Xavi] Duplicat
     */
    protected $dataSource = null;

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Josep Cañellas',
            'email'  => 'jcanell4@ioc.cat',
            'date'   => '2015-10-30',
            'name'   => 'IOC grave Plugin',
            'desc'   => 'Plugin to parse grave accents syntax in pdf and html',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){ return 'substition'; }
    function getPType(){ return 'normal'; }

    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'
    function getAllowedTypes() {
        return array('formatting', 'protected');
    }

    function getSort(){
        return 40;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
//        $this->Lexer->addSpecialPattern('', $mode, 'plugin_iocexportl_wioccl');
//        $this->Lexer->addSpecialPattern('{##.*?##}|<WIOCCL:.*</WIOCCL:.*>', $mode, 'plugin_iocexportl_wioccl');
//        $this->Lexer->addSpecialPattern('<WIOCCL:.*</WIOCCL:.*>', $mode, 'plugin_iocexportl_wioccl');
    }

    /**
     * Handle the match
     */

    function handle($match, $state, $pos, &$handler){
        // $state es un nombre, en aquest cas no ens interessa
        // $match es la coincidencia, per exemple: {##tipusModulBloc##}
        // auquests valors arriban com a index 0 = $state y 1 = $match al $data del render

        return array($state, $match, true);
    }

   /**
    * output
    * ALERTA[XAVI] Duplicat
    */
    function render($mode, &$renderer, $data) {
        return false;

        // TODO: passar la cadena del $data[1] pel wiocclparser y el resultat enviarlo al $this->parse(





        if ($mode === 'xhtml') {
            // ALERTA: Això no funciona quan el camp es troba dins d'un bloc wioccl ja que la conversió del camp es realitza directament i el resultat es reparsejat (i no inclou cap diferenciació pels camps, tot es text pla)
            $htmlText="<mark title='@TITLE@'>@VALUE@</mark>";
        }else if ($mode === 'iocxhtml'|| $mode === 'none') {
            $htmlText="@VALUE@";
        }else {
            return FALSE;
        }

        $dataSource = $this->getDataSource();

        if ($dataSource == null || !$data[2]) {

            $renderer->doc .= str_replace("@TITLE@", "", str_replace("@VALUE@", $data[1], $htmlText));


        } else {
            $parser = new WiocclParser($data[1],[], $dataSource);
            $renderedString = $this->parse($parser->getValue(), $mode);

            if (strlen($renderedString)>0) {


                $text = $renderedString;
//                $renderer->doc .= $text;
                // ALERTA: no es pot passar codi xml ni similar a htm com a contingut de la etiqueta, es trenca tot el format
                $renderer->doc .= str_replace("@TITLE@", ''/*$data[1]*/, str_replace("@VALUE@", $text, $htmlText));
            }
        }

        return true;
    }


    function getRenderString($data, $mode) {
        $dataSource = $this->getDataSource();
        $rawText = $dataSource[substr($data, 3, strlen($data)-6)];
        return $this->parse($rawText, $mode);
    }

    function parse($text, $mode) {
        $instructions = p_get_instructions($text);
        $parsedText = p_render($mode, $instructions, $info);
        return substr($parsedText, 4, strlen($parsedText)-9);
    }

    /**
     * ALERTA[XAVI] Duplicat
     */
    function setDataSource($dataSource) {
        $this->dataSource = $dataSource;
    }

    /**
     * * ALERTA[XAVI] Duplicat
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
}
