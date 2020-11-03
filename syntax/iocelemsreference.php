<?php
/**
 * Table and figure Syntax Plugin
 * @author     Josep Cañellas <jcanell4@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if(!defined('DOKU_INC')) die(); // must be run within Dokuwiki
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_iocexportl_iocelemsreference extends DokuWiki_Syntax_Plugin {


    function getInfo(){
        return array(
            'author' => 'Josep Cañellas',
            'email'  => 'jcanell4@ioc.cat',
            'date'   => '2020-10-20',
            'name'   => 'IOC elems repference Plugin',
            'desc'   => 'Plugin to parse text, note and reference references',
            'url'    => 'http://ioc.gencat.cat/',
         );
    }

    function getType(){ return 'substition'; }
    function getPType(){ return 'normal'; }
    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'

    function getSort(){
        return 513;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern(':(?:text|note|reference):[^:]+:', $mode, 'plugin_iocexportl_iocelemsreference');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        $matches = array();
        if (preg_match('/:(text|note|reference):(.*?):/', $match, $matches)){
            $type = trim($matches[1]);
            $id = trim($matches[2]);
        }
        return array($match, $type, $id); //$match;
    }

   /**
    * output
    */
    function render($mode, Doku_Renderer $renderer, $data) {
        list($match, $type, $id) = $data;
        if ($mode === 'ioccounter'){
            $renderer->doc .= "$type$id";
            return TRUE;
        }
        elseif($mode === 'iocexportl'){
            return TRUE;
        }
        elseif($mode === 'xhtml'){
            if($type=="note"){
                $renderer->doc.="<span class='note_reference' data-ref='$id'>**</span>";
            }
            return TRUE;
        }elseif ($mode === 'wikiiocmodel_psdom'|| $mode === 'iocxhtml' || $mode === 'wikiiocmodel_ptxhtml'){
            $renderer->bIocElemsRefQueue []= $id;
            return TRUE;
        }
        return FALSE;
    }
}
