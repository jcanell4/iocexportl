<?php
/**
 * copycode tag Syntax Plugin: Add HTML instructions to copy bloc code to clipboard
 *
 * @culpable rafa
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_iocexportl_ioccopycode extends DokuWiki_Syntax_Plugin {

    function getInfo(){
        return array(
            'author' => 'Rafael',
            'email'  => 'rclaver@ioc.cat',
            'date'   => '2024-07-19',
            'name'   => 'IOC copycode tags Plugin',
            'desc'   => 'Plugin to parse copycode tags: Add HTML instructions to copy bloc code to clipboard',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'container';
    }

    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'block';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 513;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<copycode>(?=.*?</copycode>)', $mode, 'plugin_iocexportl_ioccopycode');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('</copycode>', 'plugin_iocexportl_ioccopycode');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return array($state, $match);
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        list($state, $text) = $data;
        switch ($mode) {
            case 'xhtml':
            case 'iocxhtml':
            case 'wikiiocmodel_ptxhtml':
                switch ($state) {
                    case DOKU_LEXER_ENTER :
                        $r = rand();
                        $renderer->doc .= "<div id=\"cpco_$r\">";
                        $renderer->doc .= "<div class=\"iocbtncopycode\" onClick=\"copyToClipboard('cpco_".$r."');\"></div>";
                        break;
                    case DOKU_LEXER_UNMATCHED :
                        $instructions = p_get_instructions($text);
                        if ($mode === 'xhtml') {
                            $renderer->doc .= p_render($mode, $instructions, $info);
                        }else {
                            $renderer->doc .= p_latex_render($mode, $instructions, $info);
                        }
                        break;
                    case DOKU_LEXER_EXIT :
                        $renderer->doc .= '</div>';
                        break;
                }
                $ret = TRUE;
                break;
            case 'iocexportl':
            case 'ioccounter':
            case 'wikiiocmodel_psdom':
                $ret = TRUE;
                break;
            default:
                $ret = FALSE;
        }
        return $ret;
    }
}
