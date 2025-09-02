<?php
/**
 * Plugin iocmedia : manage media content
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
if (!defined('DOKU_PLUGIN_TEMPLATES')) define('DOKU_PLUGIN_TEMPLATES', DOKU_PLUGIN . 'iocexportl/templates/');
require_once(DOKU_PLUGIN . 'syntax.php');
require_once(DOKU_PLUGIN . 'iocexportl/lib/renderlib.php');



class syntax_plugin_iocexportl_iocmedia extends DokuWiki_Syntax_Plugin {

    static $altamarFromUrl = 'vídeo[altamar: @VIDEO@]';
    static $altamarFromId = 'vídeo[altamar: @VIDEO@]';
    static $altamarFromReq = 'vídeo[altamar: @VIDEO@]';
    static $altamarVideos = 'vídeo[altamar: @VIDEO@]';

    /**
     * Get an associative array with plugin info.
     */
    function getInfo() {
        return array(
            'author' => 'Marc Català',
            'email' => 'mcatala@ioc.cat',
            'date' => '2011-01-27',
            'name' => 'IOC media Plugin',
            'desc' => 'Plugin to parse media files',
            'url' => 'http://ioc.gencat.cat/',
        );
    }

    function getType() {
        return 'substition';
    }

    function getPType() {
        return 'block';
    } //stack, block, normal

    function getSort() {
        return 318; //{{uri}} dokuwiki has 320 priority
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\{\{\s?(?:vimeo|youtube|dailymotion|altamarVideos).*?>[^}]+\}\}', $mode, 'plugin_iocexportl_iocmedia');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler) {
        // remove {{ }}
        $command = substr($match, 2, -2);

        // title
        list($command, $title) = explode('|', $command);
        $title = trim($title);
        $command = trim($command);

        // get site and video
        list($site, $url) = explode('>', $command);

        // what size?
        $width = '';
        $height = '';

        list($url, $param) = explode('?', $url, 2);
        if (preg_match('/(\d+)x(\d+)/i', $param, $m)) {
            // custom
            $width = $m[1];
            $height = $m[2];
        } else {

            foreach (SharedConstants::ONLINE_VIDEO_CONFIG['sizes'] as $key => $value) {

                if (strpos($param, $key) !== false) {
                    list($width, $height) = explode('x', $value, 2);
                    break;
                }
            }

        }



        return array($site, $url, $title, $width, $height);
    }

    /**
     * output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode == 'wikiiocmodel_psdom') {
            $url = $this->generaURL($data);
            list(, , $title) = $data;
            $text = "$url | $title";
            $renderer->getCurrentNode()->addContent(new TextNodeDoc(TextNodeDoc::PLAIN_TEXT_TYPE, $text));
            return TRUE;
        } elseif ($mode === 'iocexportl') {
            $this->generaQRCode($renderer, $data);
            return TRUE;
        } elseif ($mode === 'ioccounter') {
            list($site, $url, $title) = $data;
            $renderer->doc .= $title;
        } elseif (strpos("xhtml/iocxhtml/wikiiocmodel_ptxhtml", $mode) !== FALSE) {
            list($site, $params, $title, $width, $height) = $data;


            if (array_key_exists($site, SharedConstants::ONLINE_VIDEO_CONFIG['origins'])) {
                $type = SharedConstants::ONLINE_VIDEO_CONFIG['origins'][$site]['url_template'];
                list($url, $full) = explode(":", $params);
                $url = preg_replace('/\$\{id\}/', $url, $type);
            }

            $renderer->doc .= '<div class="mediavideo">';


            if ($site === 'altamarVideos') {
                $tpl = io_readFile(DOKU_PLUGIN_TEMPLATES . $site . '.tpl');
                $tpl = preg_replace("/@HEIGHT@/", $height, $tpl);
                $tpl = preg_replace("/@WIDTH@/", $width, $tpl);
                $tpl = preg_replace("/@ID_VIDEO@/", strval($params), $tpl);
                $renderer->doc .= $tpl;
            } else {
                $renderer->doc .= '<iframe height="' . $height . 'px" width="' . $width . 'px" src="' . $url . '"></iframe>';
                $tagMessage = WikiIocLangManager::getLang("showInFullScreen", "iocexportl");
                
                $renderer->doc .= "<p><a target=_blank href='$url'>$tagMessage</a></p>";
            }
            $renderer->doc .= '</div>';
        }
        return FALSE;
    }

    private function generaURL($data) {
        list($site, $params) = $data;

        list($url) = explode(":", $params);

        if ($site !== 'altamarVideos' && array_key_exists($site, SharedConstants::ONLINE_VIDEO_CONFIG['origins'])) {
            $type = SharedConstants::ONLINE_VIDEO_CONFIG['origins'][$site]['url_template'];
            $url = preg_replace('/\$\{id\}/', $url, $type);
        } else if ($site === 'altamarVideos') {
            $type = self::$altamarVideos;
            $url = preg_replace('/@VIDEO@/', $url, $type);
        }

        return $url;
    }

    private function generaQRCode(&$renderer, $data) {
        list($site, $params, $title) = $data;
        $url = $this->generaURL($data);
        if ($url) {
            $_SESSION['qrcode'] = TRUE;
            qrcode_media_url($renderer, $url, $title, $site);
        }
    }

}
