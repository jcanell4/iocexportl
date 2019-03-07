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

class syntax_plugin_iocexportl_iocprotectedcontent extends DokuWiki_Syntax_Plugin {

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Josep Cañellas',
            'email'  => 'jcanell4@ioc.cat',
            'date'   => '2018-06-15',
            'name'   => 'IOC to protect content in the ACE editor',
            'desc'   => 'Plugin to parse identifiable keys and replace them for content',
            'url'    => 'http://ioc.gencat.cat/',        );
    }

    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'
    function getType(){ return 'container'; }
    function getPType(){ return 'stack'; }
    
    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'
    function getAllowedTypes() {
        return array('container','substition','protected','disabled','formatting','paragraphs');
    }
    
    function getSort(){
        return 40;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern(":###", $mode, 'plugin_iocexportl_iocprotectedcontent');
    }
    
    function postConnect() {
        $this->Lexer->addExitPattern('###:', 'plugin_iocexportl_iocprotectedcontent');         
    }
    
    /**
     * Handle the match
     */

    function handle($match, $state, $pos, &$handler){
        return array($state, $match);
    }

   /**
    * output
    */
    function render($mode, &$renderer, $data) {
        global $symbols;
        if ($mode === 'ioccounter'){
            $this->renderCounter($mode, $renderer, $data);
            return TRUE;
        }elseif ($mode === 'xhtml'){
            $this->renderWiki($mode, $renderer, $data);
            return TRUE;
        }elseif ($mode === 'iocxhtml'){
            $this->renderHtmlExport($mode, $renderer, $data);
            return TRUE;
        }elseif ($mode === 'iocexportl'){
            $this->renderPdfExport($mode, $renderer, $data);
            return TRUE;
        }
        return FALSE;
    }
    
    function renderPdfExport($mode, &$renderer, $data) {
        
    }
    
    function renderHtmlExport($mode, &$renderer, $data) {
        
    }
    
    function renderCounter($mode, &$renderer, $data) {
        
    }
    
    function renderWiki($mode, &$renderer, $data) {
        global $symbols;
        list ($state, $text) = $data;
        switch ($state) {
            case DOKU_LEXER_ENTER :
                $renderer->doc .= "<div class='iocprotectedcontent'>\n";
                break;
            case DOKU_LEXER_UNMATCHED :
                $renderer->doc .= $text;
                break;
            case DOKU_LEXER_EXIT :
                $renderer->doc .= "</div>\n";
                break;
        }
    }
}