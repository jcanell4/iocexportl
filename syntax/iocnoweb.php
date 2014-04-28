<?php
/**
 * Plugin iocnoweb : add a noprint class to a content
 *
 * Syntax: <noweb>content</noweb>
 *
 * @author     Eduard Diaz <edudiaz@scopia.es>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');



class syntax_plugin_iocexportl_iocnoweb extends DokuWiki_Syntax_Plugin {

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-02-24',
            'name'   => 'IOC noweb tags Plugin',
            'desc'   => 'Plugin to parse noweb tags',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){
        return 'container';
    }

    function getPType(){
        return 'block';
    }

    function getSort(){
        return 513;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<noweb>(?=.*?</noweb>)',$mode,'plugin_iocexportl_iocnoweb');
    }
    function postConnect() {
        $this->Lexer->addExitPattern('</noweb>','plugin_iocexportl_iocnoweb');
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
    function render($mode, &$renderer, $indata) {
        if ($mode !== 'iocexportl' && $mode !== 'ioccounter') return FALSE;
        return TRUE;
    }

}
