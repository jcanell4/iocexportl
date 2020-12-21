<?php
/**
 * lang Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
if(!defined('DOKU_INC')) die(); //must be run within Dokuwiki
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocdoublehyphen extends DokuWiki_Syntax_Plugin {

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Josep Cañellas',
            'email'  => 'jcanell4@ioc.cat',
            'date'   => '2015-10-30',
            'name'   => 'IOC double hyphen Plugin',
            'desc'   => 'Plugin to parse double hyphen syntax in pdf and html',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType() {
        return 'substition';
    }
    function getPType() {
        return 'normal';
    }
    function getSort(){
        return 40;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('%%--%%', $mode, 'plugin_iocexportl_iocdoublehyphen');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return array($state, $match);
    }

   /**
    * output
    */
    function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode === 'wikiiocmodel_psdom'){
            $renderer->getCurrentNode()->addContent(new LeafNodeDoc(LeafNodeDoc::DOUBLEHYPHEN_TYPE));
            return TRUE;
        }elseif ($mode === 'ioccounter'){
            $renderer->doc .=  '--';
            return TRUE;
        }elseif ($mode === 'xhtml'){
            $renderer->doc .= '--';
            return TRUE;
        }elseif ($mode === 'iocxhtml' || $mode === 'wikiiocmodel_ptxhtml'){
            $renderer->doc .= '&mdash;';
            return TRUE;
        }elseif ($mode === 'iocexportl'){
            $renderer->doc .= '$\\texttt{-{}-}$';
            return TRUE;
        }
        return FALSE;
    }
}
