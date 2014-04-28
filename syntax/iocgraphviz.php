<?php
/**
 * Graphviz Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');


if (!class_exists('syntax_plugin_graphviz')) return;

class syntax_plugin_iocexportl_iocgraphviz extends syntax_plugin_graphviz {

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<graphviz.*?>\n.*?\n</graphviz>',$mode,'plugin_iocexportl_iocgraphviz');
    }

    /**
     * Create output
     */
    function render($format, &$R, $data) {
        if(parent::render($format, $R, $data)){
            return true;
        }
		if($format == 'iocxhtml'){
            $lpath = '../';
            if($_SESSION['iocintro']){
                $lpath = '';
            }
            $img  = parent::_imgfile($data);
            if (!isset($_SESSION['graphviz_images'])){
                $_SESSION['graphviz_images'] = array();
            }
            array_push($_SESSION['graphviz_images'], $img);
            $R->doc .= '<div class="iocfigure">';
            $R->doc .= $R->_media($img);
            $R->doc .= '</div>';
            return true;
        }elseif($format == 'iocexportl'){
            $src  = $this->_imgfile($data);
            $width = ($data['width'])?$data['width']:NULL;
            $height = ($data['height'])?$data['height']:NULL;
            $R->_latexAddImage($src, $width, $height);
            return true;
        }
        return false;
    }

    /**
     * Return path to the rendered image on our local system
     */
    function _imgfile($data){
        $cache  = parent::_cachename($data,'png');

        // create the file if needed
        if(!file_exists($cache)){
            $in = parent::_cachename($data,'txt');
            $ok = $this->_run($data,$in,$cache);
            if(!$ok) return false;
            clearstatcache();
        }

        // resized version
        if($data['width']){
            $cache = media_resize_image($cache,'png',$data['width'],$data['height']);
        }

        // something went wrong, we're missing the file
        if(!file_exists($cache)) return false;

        return $cache;
    }

    /**
     * Run the graphviz program
     */
    function _run($data,$in,$out) {
        global $conf;

        if(!file_exists($in)){
            if($conf['debug']){
                dbglog($in,'no such graphviz input file');
            }
            return false;
        }

        $cmd  = '/usr/bin/dot';
        $cmd .= ' -Tpng';
        $cmd .= ' -Gdpi=300';
        $cmd .= ' -K'.$data['layout'];
        $cmd .= ' -o'.escapeshellarg($out); //output
        $cmd .= ' '.escapeshellarg($in); //input

        exec($cmd, $output, $error);

        if ($error != 0){
            if($conf['debug']){
                dbglog(join("\n",$output),'graphviz command failed: '.$cmd);
            }
            return false;
        }
        return true;
    }

}
