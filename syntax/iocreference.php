<?php
/**
 * Table and figure Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if(!defined('DOKU_INC')) die(); // must be run within Dokuwiki
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_iocexportl_iocreference extends DokuWiki_Syntax_Plugin {


    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-06-20',
            'name'   => 'IOC repference Plugin',
            'desc'   => 'Plugin to parse table and figures references',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){ return 'substition'; }
    function getPType(){ return 'normal'; }
    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'

    function getSort(){
        return 513;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern(':(?:figure|table):[^:]+:', $mode, 'plugin_iocexportl_iocreference');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return $match;
    }

   /**
    * output
    */
    function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode === 'wikiiocmodel_psdom'){
            if(preg_match('/:figure:(.*?):/',$data,$matches)){
                $renderer->getCurrentNode()->addContent(new ReferenceNodeDoc(trim($matches[1]), ReferenceNodeDoc::REF_FIGURE_TYPE));
            }elseif(preg_match('/:table:(.*?):/',$data,$matches)){
                $renderer->getCurrentNode()->addContent(new ReferenceNodeDoc(trim($matches[1]), ReferenceNodeDoc::REF_TABLE_TYPE));
            }
        }
        elseif ($mode === 'ioccounter'){
            preg_match('/:(figure|table):([^:]+):/',$data, $matches);
            $renderer->doc .= $matches[2];
            return TRUE;
        }
        elseif($mode === 'iocexportl'){
            if(preg_match('/:figure:(.*?):/',$data,$matches)){
                $renderer->doc .= '\MakeLowercase{\figurename}  \ref{'.trim($matches[1]).'}';
            }elseif(preg_match('/:table:(.*?):/',$data,$matches)){
                $renderer->doc .= '\MakeLowercase{\tablename}  \ref{'.trim($matches[1]).'}';
            }
            return TRUE;
        }
        elseif($mode === 'xhtml'){
            $match = preg_replace('/(:(?:figure|table):)([^:]+)(:)/','<a href="#$2">$2</a>',$data, 1);
            $renderer->doc .= $match;
            return TRUE;
        }
        elseif($mode === 'iocxhtml'){
            $match = preg_replace('/(:figure:)([^:]+)(:)/','<span class="figref"><a href="#$2"><span>figura</span></a></span>',$data, 1);
            $match = preg_replace('/(:table:)([^:]+)(:)/','<span class="tabref"><a href="#$2"><span>taula</span></a></span>',$match, 1);
            $renderer->doc .= $match;
            return TRUE;
        }
        elseif($mode === 'wikiiocmodel_ptxhtml'){
            $match = preg_replace('/(:figure:)([^:]+)(:)/','<span class="figref"><a href="#$2"><span>figura<span class=refId> $2</span></span></a></span>',$data, 1);
            $match = preg_replace('/(:table:)([^:]+)(:)/','<span class="tabref"><a href="#$2"><span>taula<span class=refId> $2</span></span></a></span>',$match, 1);
            $renderer->doc .= $match;
            return TRUE;
        }
        return FALSE;
    }
}
