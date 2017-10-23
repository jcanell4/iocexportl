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

class syntax_plugin_iocexportl_iocgif extends DokuWiki_Syntax_Plugin {

    static $hrefiocgif = DOKU_BASE."lib/exe/detail.php?id=@ID@&media=@MEDIA@";
    static $srciocgif = "lib/exe/fetch.php?w=@W@&tok=@TOK@&media=@MEDIA@";

    function getInfo(){
        return array(
            'name' => 'IOC gif Plugin',
            'desc' => 'Plugin to parse animated gif files',
            'sintax' => '{{iocgif>ns_path:file.gif?width|title}}',
            'url'  => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){
        return 'paragraphs'; //¿Qué tipo de sintaxis? (container,substition,formatting,protected,paragraphs)
    }

    function getPType(){
        return 'block';  //¿Qué hacer con los párrafos? stack, block, normal
    }

    function getSort(){
        return 520; //{{uri}} dokuwiki has 320 priority
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        //$this->Lexer->addEntryPattern('\{\{iocgif>.*?:[^}]+\}\}', $mode, 'plugin_iocexportl_iocgif');
        $this->Lexer->addEntryPattern('##iocgifK.*?:[^#]+##', $mode, 'plugin_iocexportl_iocgif');
    }

    /**
     * Tratamiento de la estructura $match
     */
    function handle($match, $state, $pos, &$handler){
        global $ID;
        // Ejemplo de $match: {{iocgif>ruta_ns:archivo.gif?200|título}}
        $match = substr($match, 2, -2);     //remove {{ }}
        list($command, $title) = explode('|', $match);
        $title = trim($title);
        $command = trim($command);
        list($type, $param) = explode('>', $command);
        list($fullpath, $width ) = explode('?', $param);

        //separa la ruta ns del nombre del fichero gif
        $arr = explode(':', $fullpath);
        $gif = array_pop($arr);
        $ns = implode(":", $arr);

        return array($type, $title, $width, $ns, $ID, $gif);
    }

   /**
    * output
    */
    function render($mode, &$renderer, $data) {
        if ($mode === "ioccounter"){
            list($type, $title) = $data;
            $renderer->doc .= $title;

        }elseif ($mode === "iocexportl"){
            list($type, $title, $width, $ns, $id, $gif) = $data;
            if ($type === "iocgif"){
                $_SESSION['qrcode'] = TRUE;
                $href = preg_replace('/@ID@/', $id, self::$hrefiocgif);
                $href = preg_replace('/@MEDIA@/', "$ns:$gif", $href);
                qrcode_media_url($renderer, $href, $title, $type);
            }
            return TRUE;

        }elseif ($mode === "xhtml"){
            list($type, $title, $width, $ns, $id, $gif) = $data;
            if ($type === "iocgif"){
                $href = preg_replace('/@ID@/', $id, self::$hrefiocgif);
                $href = preg_replace('/@MEDIA@/', "$ns:$gif", $href);
                $src = preg_replace('/@W@/', $width, self::$srciocgif);
                $src = preg_replace('/@TOK@/', media_get_token("$ns:$gif", $width), $src);
                $src = preg_replace('/@MEDIA@/', "$ns:$gif", $src);
            }
            $renderer->doc .= '<div class="iocgif">';
            $renderer->doc .= '<a class="media" href="'.$href.'" title="'.$title.'">';
            $renderer->doc .= '<img class="media" src="'.$src.'" width="'.$width.'px" alt="'.$title.'" />';
            $renderer->doc .= '</a>';
            $renderer->doc .= '</div>';
            return TRUE;

        }elseif ($mode === "iocxhtml"){
            list($type, $title, $width, $ns, $id, $gif) = $data;
            if ($type === "iocgif"){
                $href = preg_replace('/@ID@/', $id, self::$hrefiocgif);
                $href = preg_replace('/@MEDIA@/', "$ns:$gif", $href);
                $src = preg_replace('/@W@/', $width, self::$srciocgif);
                $src = preg_replace('/@TOK@/', media_get_token("$ns:$gif", $width), $src);
                $src = preg_replace('/@MEDIA@/', "$ns:$gif", $src);
            }
            $renderer->doc .= '<div class="iocgif">';
            $renderer->doc .= '<a class="media" href="'.$href.'" title="'.$title.'">';
            $renderer->doc .= '<img class="media" src="'.$src.'" width="'.$width.'px" alt="'.$title.'" title="'.$title.'">';
            $renderer->doc .= '</a>';
            $renderer->doc .= '</div>';
            return TRUE;
        }
        return FALSE;
    }
}
