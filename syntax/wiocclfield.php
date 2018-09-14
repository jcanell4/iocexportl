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

class syntax_plugin_iocexportl_wiocclfield extends DokuWiki_Syntax_Plugin {

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
//    function getAllowedTypes() {
//        return array('formatting', 'protected');
//    }
    function getSort(){
        return 40;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
//        $this->Lexer->addSpecialPattern('\x60|\$\\\grave{\\\\:}\$', $mode, 'plugin_iocexportl_iocfield');
        //$this->Lexer->addSpecialPattern('\{\#\#(?=.*)\#\#\}', $mode, 'plugin_iocexportl_iocfield');
        $this->Lexer->addSpecialPattern('{##.*?##}', $mode, 'plugin_iocexportl_wiocclfield');
//        $this->Lexer->addSpecialPattern('{##durada##}', $mode, 'plugin_iocexportl_iocfield');
    }
    
    /**
     * Handle the match
     */

    function handle($match, $state, $pos, &$handler){
        // $state es un nombre, en aquest cas no ens interessa
        // $match es la coincidencia, per exemple: {##tipusModulBloc##}
        // auquests valors arriban com a index 0 = $state y 1 = $match al $data del render

        return array($state, $match, true);
    }

   /**
    * output
    */
    function render($mode, &$renderer, $data) {

        $field = substr($data[1], 3, strlen($data[1])-6);


        $dataSource = $this->getDataSource();

        if ($dataSource == null || !$data[2]) {
            $renderer->doc .=  '<b style="color:grey">' . $data[1] . '</b>';
        } else {
            $renderer->doc .=  '<b style="color:red">' . $dataSource[$field] . '</b>';
        }



        return true;

//        global $symbols;
//        if ($mode === 'ioccounter'){
//            $renderer->doc .=  '`';
//            return TRUE;
//        }elseif ($mode === 'xhtml'){
//            $renderer->doc .= '`';
//            return TRUE;
//        }elseif ($mode === 'iocxhtml'){
//            $renderer->doc .= '`';
//            return TRUE;
//        }elseif ($mode === 'iocexportl'){
//            $renderer->doc .= filter_tex_sanitize_formula("$\grave{\:}$");
//            return TRUE;
//        }
//        return FALSE;
    }

    function setDataSource($dataSource) {
        $this->dataSource = $dataSource;
    }

    function getDataSource()
    {
        global $plugin_controller;

        if ($this->dataSource) {
            return $this->dataSource;
        } else {

            try {
                return  $plugin_controller->getCurrentProjectDataSource();
            } catch (Exception $e) {
                return null;
            }
        }
    }
}
