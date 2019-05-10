<?php
/**
 * Latex Syntax Plugin
 * @author     Josep Cañellas <jcanell4@ioc.cat>
 */
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC."lib/plugins/");
require_once(DOKU_PLUGIN."syntax.php");
require_once(DOKU_PLUGIN."iocexportl/lib/renderlib.php");

class syntax_plugin_iocexportl_hiddencontainer extends DokuWiki_Syntax_Plugin {
    var $isHeaderOpened=false;
    var $headerCalls=array();
    var $node = array(0,0,0,0,0);
    var $sectionCounter=1;
    var $headers = array();
    var $containerId="";

    function getInfo(){
        return array(
            'author' => 'Josep Cañellas',
            'email'  => 'jcanell4@ioc.cat',
            'date'   => '2018-05-24',
            'name'   => 'IOC Container',
            'desc'   => 'Plugin to classify the content in diferent containers',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    // Tipus de sintaxi
    function getType(){
        return 'container';
    }

    // Tipus de paràgraf
    function getPType(){
        return 'block';
    }

    // On s'ordena
    function getSort(){
        return 513;
    }

    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'
    function getAllowedTypes() {
        return array('formatting', 'substition', 'disabled', 'protected', 'paragraphs', 'container');
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<hiddenContainer.*?>(?=.*?<\/hiddenContainer>)', $mode, 'plugin_iocexportl_hiddencontainer');
    }

    function postConnect() {
        $this->Lexer->addPattern('[ \t]*={2,}[^\n]+={2,}[ \t]*(?=\n)', 'plugin_iocexportl_hiddencontainer');
        $this->Lexer->addExitPattern('<\/hiddenContainer>', 'plugin_iocexportl_hiddencontainer');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        switch ($state) {
            case DOKU_LEXER_ENTER :
                $matches = array();
                preg_match("/<hiddenContainer(.*)?>/", $match, $matches);
                $content = trim(isset($matches[1])?$matches[1]:"");
                $this->containerId = $content;
                break;
            case DOKU_LEXER_MATCHED:
                $content = $this->handlerHeader($match, $state, $pos);
                break;
            case DOKU_LEXER_UNMATCHED :
                $content = $match;
                break;
            case DOKU_LEXER_EXIT :
                $content="";
                $this->containerId = $content;
                break;
        }
        return array($state, $content, $this->containerId);
    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {
        if ($mode === 'wikiiocmodel_psdom'){
            list($state, $text, $type) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER:
                    $node = new SpecialBlockNodeDoc(SpecialBlockNodeDoc::HIDDENCONTAINER_TYPE);
                    $renderer->getCurrentNode()->addContent($node);
                    $renderer->setCurrentNode($node);
                    break;
                case DOKU_LEXER_UNMATCHED:
                    $instructions = get_latex_instructions($text);
                    //delete document_start and document_end instructions
                    array_shift($instructions);
                    array_pop($instructions);
                    //delete p_open and p_close instructions
                    array_shift($instructions);
                    array_pop($instructions);
                    foreach ( $instructions as $instruction ) {
                        call_user_func_array(array(&$renderer, $instruction[0]),$instruction[1]);
                    }
                    break;
                case DOKU_LEXER_EXIT:
                    $renderer->setCurrentNode($renderer->getCurrentNode()->getOwner());
                    break;
            }
            return TRUE;

        }else if ($mode == 'iocexportl'){
            list ($state, $content) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_MATCHED:
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($content);
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;

        }else if($mode == 'ioccounter'){
            list ($state, $content) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_MATCHED:
                case DOKU_LEXER_UNMATCHED :
                    $renderer->doc .= $content;
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;

        }else if (strpos("xhtml/iocxhtml/wikiiocmodel_ptxhtml", $mode)!==FALSE ) {
            list ($state, $content, $containerId) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $type = explode(":", $content)[0];
                    $renderer->doc .= "<a id='$content' class='imploded hiddenContainer' data-container-type='$type' href='#'>$content</a>";
                    $renderer->doc .= "<div class='imploded' data-container-id='$containerId'>";
                    break;
                case DOKU_LEXER_MATCHED:
                    $renderer->doc .= "</div>";
                    foreach ($content as $call){
                        switch ($call[0]){
                            case "section_open":
                                $this->section_open($mode, $renderer, $call[1][0], $containerId);
                                break;
                            case "section_close":
                                $this->section_close($mode, $renderer);
                            default :
                                $this->renderHeader($mode, $renderer, $call[1][0], $call[1][1], $call[1][2], $containerId);
                        }
                    }
                    $renderer->doc .= "<div class='imploded' data-container-id='$containerId'>";
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $renderer->doc .= $content;
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->doc .= "</div>";
                    break;
            }
            return TRUE;
        }
        return FALSE;
    }

    function handlerHeader($match, $state, $pos) {
        // get level and title
        $title = trim($match);
        $level = 7 - strspn($title,'=');
        if($level < 1) $level = 1;
        $title = trim($title,'=');
        $title = trim($title);

        $hederCalls = array();

        $headerCalls[]=array('section_open',array($level),$pos);
        $headerCalls[]=array('header',array($title,$level,$pos), $pos);
        $headerCalls[]=array('section_close',array(),$pos);
        return $headerCalls;
    }


    function renderHeader($mode, &$renderer, $text, $level, $pos, $containerId) {
        if(!$text) return; //skip empty headlines

        $hid = sectionID($text, $this->headers);

        // write the header
        $className = 'sectionedit' . $this->sectionCounter++;
        $renderer->doc .= DOKU_LF.'<h'.$level;
        if ($level <= WikiGlobalConfig::getConf('maxseclevel')) {
            $renderer->doc .= ' class="imploded ' . $className . '"';
        }
        $renderer->doc .= ' id="' . $hid . '" data-container-id="' . $containerId . '">';
        $renderer->doc .=  htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $renderer->doc .= "</h$level>".DOKU_LF;
    }

    function section_open($mode, &$renderer, $level, $containerId) {
        $renderer->doc .= '<div class="level' . $level . ' imploded" data-container-id="'. $containerId .'">' . DOKU_LF;
    }

    function section_close($mode, &$renderer) {
        $renderer->doc .= DOKU_LF.'</div>'.DOKU_LF;
    }

}
