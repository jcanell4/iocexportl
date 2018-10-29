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
        return array('formatting', 'substition', 'disabled', 'protected', 'container', 'paragraphs');
    }

    function getSort(){
        return 10;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<mark title=?.*?>', $mode, 'plugin_iocexportl_wioccl');
        $this->Lexer->addEntryPattern('<mark>', $mode, 'plugin_iocexportl_wioccl');
    }
    
    function postConnect() {
        $this->Lexer->addExitPattern('</mark>', 'plugin_iocexportl_wioccl');
    }


    /**
     * Handle the match
     */

    function handle($match, $state, $pos, &$handler){
        // $state es un nombre, en aquest cas no ens interessa
        // $match es la coincidencia, per exemple: {##tipusModulBloc##}
        // auquests valors arriban com a index 0 = $state y 1 = $match al $data del render

        return array($state, $match);
    }

   /**
    * output
    * ALERTA[XAVI] Duplicat
    */
    function render($mode, &$renderer, $data) {
        list ($state, $text) = $data;
        if ($mode === 'xhtml') {
            $renderer->doc .= $text;
        }else if ($mode === 'iocxhtml'|| $mode === 'none') {
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($text);
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
        }else {
            return FALSE;
        }
        return true;
    }
}
