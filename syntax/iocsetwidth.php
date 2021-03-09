<?php
/**
 * Plugin iocgif: gestión de gifs animados
 * @culpable Rafael
 * @Sintax: {{iocgif>ruta_ns:archivo.gif?ancho_del_gif_en_px|título}}
*/
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
if (!defined('DOKU_PLUGIN_TEMPLATES')) define('DOKU_PLUGIN_TEMPLATES', DOKU_PLUGIN.'iocexportl/templates/');

require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocsetwidth extends DokuWiki_Syntax_Plugin {

    var $extraWidth = false;

    function getInfo(){
        return array(
            'name' => 'IOC Set Widht Plugin',
            'desc' => 'Plugin to mark the start and the end of an area extra width',
            'sintax' => '~~EXTRA WIDTH~~|~~NORMAL WIDTH~~',
            'url'  => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){
        return 'substition'; //¿Qué tipo de sintaxis? (container,substition,formatting,protected,paragraphs)
    }

    function getPType(){
        return 'block';  //¿Qué hacer con los párrafos? stack, block, normal
    }

    function getSort(){
        return 318; //{{uri}} dokuwiki has 320 priority
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~EXTRA WIDTH~~', $mode, 'plugin_iocexportl_iocsetwidth');
        $this->Lexer->addSpecialPattern('~~NORMAL WIDTH~~', $mode, 'plugin_iocexportl_iocsetwidth');
    }

    /**
     * Tratamiento de la estructura $match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return array($match, $state, $pos);
    }

   /**
    * output
    */
    function render($mode, Doku_Renderer $renderer, $data) {
        list($typeWidth, $state, $pos) = $data;
        $output = "";

        if (!$this->extraWidth && $typeWidth=="~~EXTRA WIDTH~~"){
            $renderer->tmpData[$typeWidth]=TRUE;
            $this->extraWidth = true;
            $output = LeafNodeDoc::EXTRA_WIDTH_TYPE;
        }

        if ($this->extraWidth && $typeWidth=="~~NORMAL WIDTH~~"){
            unset($renderer->tmpData["~~EXTRA WIDTH~~"]);
            $this->extraWidth = false;
            $output = LeafNodeDoc::NORMAL_WIDTH_TYPE;
        }

        if ($mode === 'wikiiocmodel_psdom'){
            if ($output === LeafNodeDoc::EXTRA_WIDTH_TYPE) {
                $renderer->getCurrentNode()->addContent(new LeafNodeDoc($output));
            }else if($output === LeafNodeDoc::NORMAL_WIDTH_TYPE){
                $renderer->getCurrentNode()->addContent(new LeafNodeDoc($output));
            }
            return TRUE;
        }elseif ($mode === "ioccounter"){
            return TRUE;
        }elseif ($mode === "iocexportl"){
            return TRUE;

        }elseif ($mode === "iocxhtml" || $mode === 'wikiiocmodel_ptxhtml'|| $mode === "xhtml") {
            if($output== LeafNodeDoc::EXTRA_WIDTH_TYPE){
//                $renderer->doc .= '<div class="extrawidth">';
            }else if($output== LeafNodeDoc::NORMAL_WIDTH_TYPE){
//                $renderer->doc .= '</div>';
            }
            return TRUE;
        }
        return FALSE;
    }
}
