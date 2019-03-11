<?php
/**
 * Nocount Syntax Plugin
 *
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_iocexportl_iocnocount extends DokuWiki_Syntax_Plugin {

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-06-01',
            'name'   => 'IOC nocount Plugin',
            'desc'   => 'Plugin to parse nocount syntax',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){ return 'baseonly'; }
    function getPType(){ return 'block'; }
    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'
    function getAllowedTypes() {
        return array('baseonly');
    }
    function getSort(){
        return 513;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~NOCOUNT~~', $mode, 'plugin_iocexportl_iocnocount');
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
        if ($mode !== 'iocexportl' && $mode !== 'ioccounter' && $mode !== 'wikiiocmodel_psdom')
            return FALSE;
        return TRUE;
    }
}
