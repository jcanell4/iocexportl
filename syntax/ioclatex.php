<?php
/**
 * Latex Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 */
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_ioclatex extends DokuWiki_Syntax_Plugin {

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

    function getType() { return 'protected'; }
    function getPType(){ return 'normal'; }
    function getSort() { return 210; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
       $this->Lexer->addSpecialPattern('(?:\$[^\$].*?\$|\${2}.+?\${2}|<latex>.*?\</latex>)', $mode, 'plugin_iocexportl_ioclatex');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return $match;
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        global $symbols;
        if ($mode == 'wikiiocmodel_psdom'){
            if (!$this->reservedWords($data)) {
                $block = preg_match('/^\${2}/', $data);
                $class = ($block) ? "blocklatex" : "inlinelatex";

                $xhtml = p_render("xhtml", get_ioc_instructions($data, array("plugin_iocexportl_ioclatex")), $info);
                if ($_SESSION['xhtml_latex_quiz']) {
                    //afegir un noda amb el valor retornat a $xhtml;
                }else{
                    if (preg_match('/<img src="(.*?\?media=(.*?))"/', $xhtml, $match)) {
                        $path = mediaFN($match[2]);
                    }elseif (preg_match('/<img src="(.*?)"/', $xhtml, $match)) {
                        $path = realpath(DOKU_INC . ".." . $match[1]);
                    } else {
                        $path = DOKU_INC . "lib/plugins/latex/images/renderfail.png";
                    }
                    if (preg_match('/<img src="(.*?title="(.*?))"/', $xhtml, $match)) {
                        $title = $match[2];
                    }
                    $node = new LatexMathNodeDoc($path, $title, $class);
                    $renderer->getCurrentNode()->addContent($node);
                }
            }
            return TRUE;
        }
        elseif ($mode === 'ioccounter'){
             $renderer->doc .= $data;
            return TRUE;
        }
        elseif ($mode === 'xhtml'){
            $renderer->doc .= p_render($mode, get_ioc_instructions($data, array("plugin_iocexportl_ioclatex")), $info);
        }
        elseif ($mode === 'iocexportl'){
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
        }
        elseif ($mode === 'iocxhtml' || $mode === 'wikiiocmodel_ptxhtml') {
            if (!$this->reservedWords($data)) {
                $lpath = ($_SESSION['iocintro']) ? "" : "../";
                $block = preg_match('/^\${2}/', $data);
                $class = ($block) ? "blocklatex" : "inlinelatex";

                $xhtml = p_render("xhtml", get_ioc_instructions($data, array("plugin_iocexportl_ioclatex")), $info);
                //Inside quiz and xhtml wiki required
                if ($_SESSION['xhtml_latex_quiz']) {
                    $renderer->doc .= $xhtml;
                }else{
                    if (preg_match('/<img src="(.*?\?media=(.*?))"/', $xhtml, $match)) {
                        $path = mediaFN($match[2]);
                    } else {
                        if (preg_match('/<img (.*?src="(.*?))"/', $xhtml, $match)) {
                            $path = realpath(DOKU_INC . ".." . $match[2]);
                        }else {
                            $path = DOKU_INC . "lib/plugins/latex/images/renderfail.png";
                        }
                    }
                    if (!isset($_SESSION['latex_images'])){
                        $_SESSION['latex_images'] = array();
                    }
                    array_push($_SESSION['latex_images'], $path);
                    if ($mode === 'iocxhtml') {
                        $renderer->doc .= '<span class="'.$class.'"><img src="'.$lpath.'media/'.basename($match[1]).'" /></span>';
                    }else{
                        $renderer->doc .= '<span class="'.$class.'"><img src="img/'.basename($path).'" /></span>';
                    }
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
