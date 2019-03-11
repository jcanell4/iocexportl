<?php
/**
 * Syntax Plugin hiddenContainerRef
 * @culpable Rafael
 */
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
if (!defined('DOKU_PLUGIN_TEMPLATES')) define('DOKU_PLUGIN_TEMPLATES', DOKU_PLUGIN.'iocexportl/templates/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_hiddencontainerref extends DokuWiki_Syntax_Plugin {
    var $refCounter = 0;

    function getInfo(){
        return array(
            'name'   => 'IOC hidden container reference',
            'desc'   => 'Plugin to reference hidden containers',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }
    function getType(){
        return 'substition';
    }
    function getSort(){
        return 318; //{{uri}} dokuwiki has 320 priority
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\{\{hiddenContainerRef>.*?[^}]+\}\}', $mode, 'plugin_iocexportl_hiddencontainerref');
    }

    /**
     * Tratamiento de la estructura $match
     */
    function handle($match, $state, $pos, &$handler){
        $this->refCounter++;

        $command = substr($match,2,-2);     //remove {{ }}

        list( , $title) = explode('>', $command);
        $title = trim($title);

        return array($state, $this->refCounter, $title, $pos);
    }

   /**
    * output
    */
    function render($mode, &$renderer, $data) {
        if ($mode == 'wikiiocmodel_psdom'){
            list($state, $num, $title, $pos) = $data;
            $renderer->getCurrentNode()->addContent(new TextNodeDoc(TextNodeDoc::PLAIN_TEXT_TYPE, $title));
            return TRUE;
        }else if ($mode === 'iocexportl'){
            //[TODO]
            return TRUE;
        }elseif ($mode === 'ioccounter'){
            //[TODO]
            return TRUE;
        }elseif ($mode === 'xhtml' || $mode === 'iocxhtml'){
            list($state, $num, $title, $pos) = $data;
            $renderer->doc .= "<a href='#";
            $renderer->doc .= $title;
            $renderer->doc .= "' title='Cliqueu per obrir el detall de $title' data-container-id-referred='$title'><sup>$num</sup></a>"; //[JOSEP] Alerta Canviar per internacionalització
            return TRUE;
        }
        return FALSE;
    }
}
