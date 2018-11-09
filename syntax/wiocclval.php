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

class syntax_plugin_iocexportl_wiocclval extends DokuWiki_Syntax_Plugin {

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
//    function getAllowedTypes() {
//        return array('substition');
//    }

    function getSort(){
        return 40;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('{@@', $mode, 'plugin_iocexportl_wiocclval');
//        $this->Lexer->addEntryPattern('{#[#@]', $mode, 'plugin_iocexportl_wiocclval');
    }
    
    function postConnect() {
        //aliniació
//        $this->Lexer->addExitPattern('@@}', 'plugin_iocexportl_wiocclfield');
        $this->Lexer->addExitPattern('[#@]#}', 'plugin_iocexportl_wiocclval');
    }
    /**
     * Handle the match
     */

    function handle($match, $state, $pos, &$handler){
        //$data = array("command" => self::SKIP);
        
        switch ( $state ) {
            case DOKU_LEXER_ENTER:
                $data = array("state" => "ENTER", "text" =>$match);
                break;

            case DOKU_LEXER_EXIT:        
                $data = array("state" => "EXIT", "text" =>$match);
                break;

            case DOKU_LEXER_UNMATCHED:
                $data = array("state" => "UNMATCHED", "text" =>$match);
                break;

            case DOKU_LEXER_MATCHED:
                $data = array("state" => "MATCHED", "text" =>$match);
                break;
        }
        return array($state, $data);        

//        return array($state, $match, true);
    }

   /**
    * output
    * ALERTA[XAVI] Duplicat
    */
    function render($mode, &$renderer, $data) {


        if ($mode === 'xhtml') {
//            $htmlText="<mark title='@TITLE@'>@VALUE@</mark>";
        }else if ($mode === 'iocxhtml') {
//            $htmlText="@VALUE@";
        }else {
            return FALSE;
        }
        
         list ($state, $data) = $data;
        $renderer->doc .= "<mark>".$data["state"]."(((".$data["text"].")))-</mark>";

//        $dataSource = $this->getDataSource();
//
//        if ($dataSource == null || !$data[2]) {
//
//
//
//            $renderer->doc .= str_replace("@TITLE@", "", str_replace("@VALUE@", $data[1], $htmlText));
//
//            return true;
//        } else {
//            $renderedString = $this->getRenderString($data[1], $mode);
//            if (strlen($renderedString)>0) {
//
//
//                $text = $this->getRenderString($data[1], $mode);
////                $renderer->doc .= $text;
//                $renderer->doc .= str_replace("@TITLE@", $data[1], str_replace("@VALUE@", $text, $htmlText));
//            }
//        }


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
