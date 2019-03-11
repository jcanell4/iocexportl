<?php
/**
 * Latex Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_ioclatex extends DokuWiki_Syntax_Plugin {

    var $type;
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-01-28',
            'name'   => 'IOC latex Plugin',
            'desc'   => 'Plugin to parse latex tags',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'protected';
    }

    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'normal';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 510;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
       $this->Lexer->addSpecialPattern('(?:\$[^\$].*?\$|\${2}.+?\${2}|<latex>.*?\</latex>)', $mode, 'plugin_iocexportl_ioclatex');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        return $match;
    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {
        global $symbols;
        if ($mode == 'wikiiocmodel_psdom'){
            list ($state, $text) = $data;
            //TODO
            return TRUE;
        }elseif ($mode === 'ioccounter'){
            list ($state, $text) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $renderer->doc .= $text;
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;
        }elseif ($mode === 'iocexportl'){
            if(preg_match('/<latex>(.*?)<\/latex>/', $data, $matches)){
                $text = str_ireplace($symbols, ' (Invalid character) ', $matches[1]);
                $text = preg_replace('/(\$)/', '\\\\$1', $text);
                $renderer->doc .= filter_tex_sanitize_formula($text);
            }elseif(preg_match('/\${2}\n?([^\$]+)\n?\${2}/', $data, $matches)){//Math block mode
                $text = str_ireplace($symbols, ' (Invalid character) ', $matches[1]);
                $text = preg_replace('/(\$)/', '\\\\$1', $text);
                $renderer->doc .= '\begin{center}\begin{math}'.filter_tex_sanitize_formula($text).'\end{math}\end{center}';
            }elseif(preg_match('/\$\n?([^\$]+)\n?\$/', $data, $matches)){//Math inline mode
                $text = str_ireplace($symbols, ' (Invalid character) ', $matches[1]);
                $text = preg_replace('/(\$)/', '\\\\$1', $text);
                $renderer->doc .= '$'.filter_tex_sanitize_formula($text).'$';
            }
            return TRUE;
        }elseif ($mode === 'iocxhtml'){
            if(!$this->reservedWords($data)){
                $lpath = '../';
                if($_SESSION['iocintro']){
                    $lpath = '';
                }
                $block = preg_match('/^\${2}/', $data);
                $class = ($block)?'blocklatex':'inlinelatex';
                $render = new Doku_Renderer_xhtml();
                $xhtml = $render->render($data);
                //Inside quiz and xhtml wiki required
                if ($_SESSION['xhtml_latex_quiz']) {
                    $renderer->doc .= $xhtml;
                }else{
                    if (preg_match('/<img src="(.*?\?media=(.*?))"/', $xhtml, $match)) {
                        $path = mediaFN($match[2]);
                    } else {
                        $path = DOKU_INC . "lib/plugins/latex/images/renderfail.png";
                    }
                    if (!isset($_SESSION['latex_images'])){
                        $_SESSION['latex_images'] = array();
                    }
                    array_push($_SESSION['latex_images'],$path);
                    $renderer->doc .= '<span class="'.$class.'"><img src="'.$lpath.'media/'.basename($match[1]).'" /></span>';
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     *
     * Function returns whether latex command is a reserved word
     * @param string $text
     * @return boolean
     */
    function reservedWords($text){
        $words = array('\\\\newpage','\\\\enlargethispage');
        return preg_match('/'.implode('|', $words).'/', $text);
    }
}
