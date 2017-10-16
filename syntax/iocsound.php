<?php
/**
 * Plugin iocsound: manage sound content
 * @culpable Rafael
 */
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
if (!defined('DOKU_PLUGIN_TEMPLATES')) define('DOKU_PLUGIN_TEMPLATES', DOKU_PLUGIN.'iocexportl/templates/');

require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocsound extends DokuWiki_Syntax_Plugin {

    static $soundcloud = 'https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/@ID@?secret_token=@TOKEN@&color=%230066cc&inverse=false&auto_play=false&show_user=true';
    //<iframe width="100%" height="20" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/341144902?secret_token=s-PNOVW&color=%230066cc&inverse=false&auto_play=false&show_user=true"></iframe>

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'name'   => 'IOC sound Plugin',
            'desc'   => 'Plugin to parse media files',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){
        return 'substition';
    }

    function getPType(){
        return 'block';  //stack, block, normal
    }

    function getSort(){
        return 318; //{{uri}} dokuwiki has 320 priority
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\{\{\s?(?:soundcloud|soundcloud).*?>.*?:[^}]+\}\}', $mode, 'plugin_iocexportl_iocsound');
    }

    /**
     * Tratamiento de la estructura $match
     */
    function handle($match, $state, $pos, &$handler){
        // Ejemplo de $match: {{soundcloud>341144902:s-PNOVW|sonido de nivel 3}}

        $command = substr($match,2,-2);     //remove {{ }}

        list($command, $title) = explode('|', $command);
        $title = trim($title);

        $command = trim($command);
        list($type, $param) = explode('>', $command);

        list($id, $token) = explode(':', $param);

        return array($type, $title, $id, $token);
    }

   /**
    * output
    */
    function render($mode, &$renderer, $data) {
        if ($mode === 'iocexportl'){
            list($type, $title, $id, $token) = $data;
            if ($type==='soundcloud' || $type==='soundcloud'){
                $_SESSION['qrcode'] = TRUE;
                if ($type === 'soundcloud'){
                    $site = self::$soundcloud;
                }else{
                    $site = self::$soundcloud;
                }
                $url = preg_replace('/@ID@/', $id, $site);
                $url = preg_replace('/@TOKEN@/', $token, $url);
                qrcode_media_url($renderer, $url, $title, $type);
            }
            return TRUE;
        }elseif ($mode === 'ioccounter'){
            list($site, $url, $title) = $data;
            $renderer->doc .= $title;
        }elseif ($mode === 'xhtml' || $mode === 'iocxhtml'){
            list($type, $title, $id, $token) = $data;
            if ($type==='soundcloud' || $type==='soundcloud'){
                if ($type === 'soundcloud'){
                    $site = self::$soundcloud;
                }else{
                    $site = self::$soundcloud;
                }
                $url = preg_replace('/@ID@/', $id, $site);
                $url = preg_replace('/@TOKEN@/', $token, $url);
            }
            $renderer->doc .= '<div class="mediasound">';
            $renderer->doc .= $title;
            if ($type === 'soundcloud'){
                 $renderer->doc .= '<iframe width="100%" height="20" scrolling="no" frameborder="no" src="'.$url.'"></iframe>';
            }else{
                 $renderer->doc .= '<iframe width="100%" height="20" scrolling="no" frameborder="no" src="'.$url.'"></iframe>';
            }
            $renderer->doc .= '</div>';
        }
        return FALSE;
    }
}
