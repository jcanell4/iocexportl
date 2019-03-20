<?php
/**
 * Syntax iocgrave: Plugin to parse grave accents syntax in pdf and html
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
if(!defined('DOKU_INC')) die();  //must be run within Dokuwiki
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocgrave extends DokuWiki_Syntax_Plugin {

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

    function getType() { return 'substition'; }
    function getPType() { return 'normal'; }    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'
    function getSort(){ return 40; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\x60|\$\\\grave{\\\\:}\$', $mode, 'plugin_iocexportl_iocgrave');
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
        if ($mode === 'wikiiocmodel_psdom'){
            $renderer->getCurrentNode()->addContent(new LeafNodeDoc(LeafNodeDoc::GRAVE_TYPE));
            return TRUE;
        }elseif (strpos("ioccounter/xhtml/iocxhtml/wikiiocmodel_ptxhtml", $mode) !== FALSE){
            $renderer->doc .=  '`';
            return TRUE;
        }elseif ($mode === 'iocexportl'){
            $renderer->doc .= filter_tex_sanitize_formula("$\grave{\:}$");
            return TRUE;
        }
        return FALSE;
    }
}
