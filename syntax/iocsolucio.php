<?php
/**
 * Iocsol tag Syntax Plugin
 *
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');


class syntax_plugin_iocexportl_iocsolucio extends DokuWiki_Syntax_Plugin {

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-09-20',
            'name'   => 'IOC sol Plugin',
            'desc'   => 'Plugin to parse iocstl solucio tags',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){
        return 'container';
    }

    function getPType(){
        return 'normal';
    } //stack, block, normal

    function getSort(){
        return 513;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<iocstl solucio>(?=.*?</iocstl>)',$mode,'plugin_iocexportl_iocsolucio');
    }
    function postConnect() {
        $this->Lexer->addExitPattern('</iocstl>','plugin_iocexportl_iocsolucio');
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
        if($mode === 'ioccounter'){
            list($state, $text) = $data;
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
            return TRUE;
        }elseif($mode === 'iocexportl'){
            list($state, $text) = $data;
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
            return TRUE;
        }elseif($mode === 'iocxhtml'){
            list($state, $text) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :
                  break;
              case DOKU_LEXER_UNMATCHED :
                  $_SESSION['iocelem'] = TRUE;
                  $value = (!empty($_SESSION['IOCSHOW']))?$_SESSION['IOCSHOW']:'Mostra';
                  $renderer->doc .= '<form action="">';
                  $renderer->doc .= '<div class="solution ioccontent">';
                  $instructions = get_latex_instructions($text);
                  $renderer->doc .= p_latex_render('iocxhtml', $instructions, $info);
                  $renderer->doc .= '</div>';
                  $renderer->doc .= '<input class="btn_solution3" type="button" value="'.$value.'"></input>';
                  $renderer->doc .= '</form>';
                  $_SESSION['iocelem'] = FALSE;
                  break;
              case DOKU_LEXER_EXIT :
                  break;
            }
            return TRUE;
        }elseif($mode === 'xhtml'){
            list($state, $text) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :
                  break;
              case DOKU_LEXER_UNMATCHED :
                  $_SESSION['iocelem'] = TRUE;
                  $value = (!empty($_SESSION['IOCSHOW']))?$_SESSION['IOCSHOW']:'Mostra';
                  $renderer->doc .= '<form action="">';
                  $renderer->doc .= '<div class="solution ioccontent">';
                  $instructions = p_get_instructions($text);
                  $renderer->doc .= p_render($mode, $instructions, $info);
                  $renderer->doc .= '</div>';
                  $renderer->doc .= '<input class="btn_solution3" type="button" value="'.$value.'"></input>';
                  $renderer->doc .= '</form>';
                  $_SESSION['iocelem'] = FALSE;
                  break;
              case DOKU_LEXER_EXIT :
                  break;
            }
            return TRUE;
        }
        return FALSE;
    }
}
