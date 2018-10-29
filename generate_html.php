<?php
/**
 * LaTeX Plugin: Generate HTML document
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author Marc Català <mcatala@ioc.cat>
 */

if (!defined('DOKU_INC')) define('DOKU_INC',dirname(__FILE__).'/../../../');
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
if (!defined('DOKU_IOCEXPORTL_TEMPLATES')) define('DOKU_IOCEXPORTL_TEMPLATES',DOKU_PLUGIN.'iocexportl/templates/');
if (!defined('DOKU_IOCEXPORTL_TEMPLATES_HTML')) define('DOKU_IOCEXPORTL_TEMPLATES_HTML',DOKU_IOCEXPORTL_TEMPLATES.'html/');
if (!defined('DOKU_IOCEXPORTL_LATEX_TMP')) define('DOKU_IOCEXPORTL_LATEX_TMP',DOKU_PLUGIN.'tmp/latex/');
if (!defined('WIKI_IOC_MODEL')) define('WIKI_IOC_MODEL', DOKU_PLUGIN . "wikiiocmodel/");

require_once(DOKU_INC.'/inc/init.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');
require_once DOKU_INC.'inc/parser/xhtml.php';
require_once WIKI_IOC_MODEL.'WikiIocModel.php';

class generate_html implements WikiIocModel{

    private $def_section_href;
    private $double_cicle;
    private $exportallowed;
    private $export_ok;
    private $id;
    private $lang;
    private $log;
    private $needReturnData;
    private $returnData;
    private $max_menu;
    private $max_navmenu;
    private $media_path;
    private $menu_html;
    private $meta_dcicle;
    private $meta_params;
    private $time_start;
    private $toexport;
    private $tree_names;
    private $web_folder;

   /**
    * Default Constructor
    *
    * Initialize variables
    *
    * @param array $params Array of parameters to pass to the constructor
    */
    function __construct($params=NULL){
        if($params){
            $this->initParams($params);
        }
    }

    public function setParams($element, $value) {
        $this->params[$element] = $value;
    }

    public function initParams($params){
        $this->def_section_href = 'continguts';
        $this->exportallowed = FALSE;
        $this->export_ok = ($params['mode'] === 'zip' && !empty($params['toexport']));
        $this->id = $params['id'];
        $lang = (!isset($params['ioclanguage']))?'ca':strtolower($params['ioclanguage']);
        $lang = preg_replace('/\n/', '', $lang);
        if (!file_exists(dirname(__FILE__).'/conf/lang/'.$lang.'.conf')){
            $lang = 'ca';
        }
        $this->lang =  confToHash(dirname(__FILE__).'/conf/lang/'.$lang.'.conf');
        $_SESSION['IOCSHOW'] = $this->lang['show'];
        $_SESSION['IOCSOLUTION'] = $this->lang['solution'];
        $this->max_menu = 100;
        $this->max_navmenu = 70;
        $this->media_path = 'lib/exe/fetch.php?media=';
        $this->menu_html = '';
        $this->meta_params = array('adaptacio', 'autoria', 'ciclenom', 'coordinacio', 'copylink', 'copylogo', 'copytext', 'creditcodi', 'creditnom', 'data', 'familia', 'familypic', 'legal');
        $this->tree_names = array();
        $this->web_folder = 'WebContent';
        $this->meta_dcicle = 'dcicle';
        $this->double_cicle = FALSE;
        $this->toexport = explode(',', preg_replace('/:index(,|$)/',',',$params['toexport']));
        $this->log = isset($params['log']);
        $this->needReturnData = isset($params['needReturnData']);
        $this->returnData=NULL;
    }

    /**
     *
     * Exportation to html
     */
    public function init(){
        global $conf;

        if (!$this->export_ok) return FALSE;
        if (!$this->log && !$this->checkPerms()) return FALSE;
        $this->exportallowed = isset($conf['plugin']['iocexportl']['allowexport']);
        if (!$this->log && !$this->exportallowed && !auth_isadmin()) return FALSE;

        @set_time_limit(240);

        $this->time_start = microtime(TRUE);

        $output_filename = str_replace(':','_',$this->id);

        session_start();
        $_SESSION['export_html'] = TRUE;
        $tmp_dir = rand();
        $_SESSION['tmp_dir'] = $tmp_dir;
        $_SESSION['latex_images'] = array();
        $_SESSION['media_files'] = array();
        $_SESSION['graphviz_images'] = array();
        if (!file_exists(DOKU_IOCEXPORTL_LATEX_TMP.$tmp_dir)){
            mkdir(DOKU_IOCEXPORTL_LATEX_TMP.$tmp_dir, 0775, TRUE);
        }
        //get all pages and activitites
        $data = $this->getData();

        $zip = new ZipArchive;
        $res = $zip->open(DOKU_IOCEXPORTL_LATEX_TMP.$tmp_dir.'/'.$output_filename.'.zip', ZipArchive::CREATE);
        if ($res === TRUE) {
            list($this->menu_html, $files_name) = $this->createMenu($data[0]);
            //Get build.js and add which filenames will be used to search
            $build = io_readFile(DOKU_IOCEXPORTL_TEMPLATES_HTML.'_/js/build.js');
            preg_match('/^([^.]*\.)*([^\.]*\.[^\/]*)\/.*?$/',$data[1]['creditcodi'],$matches);
            $build = preg_replace('/"@IOCFILENAMES@"/', implode(',', $files_name), $build, 1);
            $build = preg_replace('/@IOCSEARCHING@/', $this->lang['searching'], $build, 1);
            $build = preg_replace('/@IOCPREPARINGSEARCH@/', $this->lang['preparingsearch'], $build, 1);
            $build = preg_replace('/@IOCSEARCHRESULTS@/', $this->lang['searchresults'], $build, 1);
            $build = preg_replace('/@IOCNOSEARCHRESULTS@/', $this->lang['nosearchresults'], $build, 1);
            $build = preg_replace('/@IOCSEARCHFINISHED@/', $this->lang['searchfinished'], $build, 1);
            $build = preg_replace('/@IOCSHOW@/', $this->lang['show'], $build, 1);
            $build = preg_replace('/@IOCHIDE@/', $this->lang['hide'], $build, 1);
            $build = preg_replace('/@IOCOK@/', $this->lang['ok'], $build);
            $build = preg_replace('/@IOCWRONG@/', $this->lang['wrong'], $build);
            $cookiename = '';
            if (isset($matches[2])){
                $cookiename = str_replace(".", "_", $matches[2]);
            }
            $build = preg_replace('/@IOCCOOKIENAME@/', $cookiename, $build);
            $zip->addFromString('_/js/build.js', $build);
            $this->getFiles(DOKU_IOCEXPORTL_TEMPLATES_HTML,$zip);
            //Get index source
            $text_index = io_readFile(DOKU_IOCEXPORTL_TEMPLATES_HTML.'index.html');
            $text_index = preg_replace('/@IOCHEADDOCUMENT@/', $data[1]['creditnom'], $text_index, 3);
            $text_index = preg_replace('/@IOCFAMILY@/', $data[1]['familia'], $text_index, 1);
            $text_index = preg_replace('/@IOCREFLICENSE@/', $data[1]['copylink'], $text_index, 1);
            //Get search source
            $text_search = io_readFile(DOKU_IOCEXPORTL_TEMPLATES_HTML.'search.html');
            $text_search = preg_replace('/@IOCHEADDOCUMENT@/', $data[1]['creditnom'], $text_search, 3);
            $text_search = preg_replace('/@IOCFAMILY@/', $data[1]['familia'], $text_search, 1);
            $text_search = preg_replace('/@IOCREFLICENSE@/', $data[1]['copylink'], $text_search, 1);
            $text_search = preg_replace('/@IOCLICENSE@/',$this->lang['license'], $text_search, 1);
            $text_search = preg_replace('/@IOCTOPPAGE@/',$this->lang['toppage'], $text_search, 1);
            //Get template source
            $text_template = io_readFile(DOKU_IOCEXPORTL_TEMPLATES_HTML.'template.html');
            $text_template = preg_replace('/@IOCHEADDOCUMENT@/', $data[1]['creditnom'], $text_template, 3);
            $text_template = preg_replace('/@IOCFAMILY@/', $data[1]['familia'], $text_template, 1);
            $text_template = preg_replace('/@IOCREFLICENSE@/', $data[1]['copylink'], $text_template, 1);
            $text_template = preg_replace('/@IOCCOPYTEXT@/', $data[1]['copytext'], $text_template, 1);
            $text_template = preg_replace('/@IOCLICENSE@/',$this->lang['license'], $text_template, 1);
            $text_template = preg_replace('/@IOCTOPPAGE@/',$this->lang['toppage'], $text_template, 1);
            $text_template = preg_replace('/@IOCINDEX@/',$this->lang['index'], $text_template, 1);
            $text_template = preg_replace('/@IOCSETTINGS@/',$this->lang['settings'], $text_template, 1);
            $text_template = preg_replace('/@IOCPRINT@/',$this->lang['print'], $text_template, 1);
            $text_template = preg_replace('/@IOCBOOKMARKS@/',$this->lang['bookmarks'], $text_template, 1);
            $text_template = preg_replace('/@IOCHELP@/',$this->lang['help'], $text_template, 1);
            $text_template = preg_replace('/@IOCFONTCOLOR@/',$this->lang['fontcolor'], $text_template, 1);
            $text_template = preg_replace('/@IOCWIDTH@/',$this->lang['width'], $text_template, 1);
            $text_template = preg_replace('/@IOCFONTSIZE@/',$this->lang['fontsize'], $text_template, 1);
            $text_template = preg_replace('/@IOCOPTIONS@/',$this->lang['fontsize'], $text_template, 1);
            $text_template = preg_replace('/@IOCSHOWADITIONAL@/',$this->lang['showaditionals'], $text_template, 1);
            $text_template = preg_replace('/@IOCSHOWIMAGES@/',$this->lang['showimages'], $text_template, 1);
            $text_template = preg_replace('/@IOCJUSTIFY@/',$this->lang['justify'], $text_template, 1);
            $text_template = preg_replace('/@IOCHYPHENATION@/',$this->lang['hyphenation'], $text_template, 1);
            $text_template = preg_replace('/@IOCSHORTCUTS@/',$this->lang['shortcuts'], $text_template, 1);
            $text_template = preg_replace('/@IOCGENERAL@/',$this->lang['general'], $text_template, 1);
            $text_template = preg_replace('/@IOCGOTONAVIGATION@/',$this->lang['gonavigation'], $text_template, 1);
            $text_template = preg_replace('/@IOCGOTOSETTINGS@/',$this->lang['gosettings'], $text_template, 1);
            $text_template = preg_replace('/@IOCBOOKMARK@/',$this->lang['savebookmark'], $text_template, 1);
            $text_template = preg_replace('/@IOCPRINTPAGE@/',$this->lang['printpage'], $text_template, 1);
            $text_template = preg_replace('/@IOCSHOWHIDEHELP@/',$this->lang['showhelp'], $text_template, 1);
            $text_template = preg_replace('/@IOCNAVIGATION@/',$this->lang['navigation'], $text_template, 1);
            $text_template = preg_replace('/@IOCGOMAIN@/',$this->lang['gomainindex'], $text_template, 1);
            $text_template = preg_replace('/@IOCGOTOPPAGE@/',$this->lang['gotoppage'], $text_template, 1);
            $text_template = preg_replace('/@IOCGOBOTTOMPAGE@/',$this->lang['gobottompage'], $text_template, 1);
            $text_template = preg_replace('/@IOCGOFORWARD@/',$this->lang['goforward'], $text_template, 1);
            $text_template = preg_replace('/@IOCGOBACKWARD@/',$this->lang['gobackward'], $text_template, 1);
            $text_template = preg_replace('/@IOCGOPREVIOUSPAGE@/',$this->lang['gopreviouspage'], $text_template, 1);
            $text_template = preg_replace('/@IOCGONEXTPAGE@/',$this->lang['gonextpage'], $text_template, 1);
            $text_template = preg_replace('/@IOCHELPTOC@/',$this->lang['helptoc'], $text_template, 1);
            $text_template = preg_replace('/@IOCHELPSETTINGS@/',$this->lang['helpsettings'], $text_template, 1);
            $text_template = preg_replace('/@IOCHELPPRINT@/',$this->lang['helpprint'], $text_template, 1);
            $text_template = preg_replace('/@IOCHELPBOOKMARK@/',$this->lang['helpbookmark'], $text_template, 1);
            $text_template = preg_replace('/@IOCHELPCOUNTERBOOKMARK@/',$this->lang['helpcounterbookmark'], $text_template, 1);
            $text_template = preg_replace('/@IOCBUTTONHELP@/',$this->lang['helpbutton'], $text_template, 1);
            $text_template = preg_replace('/@IOCHELPSHOWHIDEBAR@/',$this->lang['helpshowhidebar'], $text_template, 1);
            $text_template = preg_replace('/@IOCHELPSEARCH@/',$this->lang['helpsearch'], $text_template, 1);
            $text_template = preg_replace('/@IOCHELPHEADER@/',$this->lang['helpheader'], $text_template, 1);
            //Create index page
            $menu_html_index = preg_replace('/@IOCSTARTUNIT@|@IOCENDUNIT@/', '', $this->menu_html);
            $menu_html_index = preg_replace('/@IOCSTARTINTRO@|@IOCENDINTRO@/', '', $menu_html_index);
            $menu_html_index = preg_replace('/@IOCSTARTINDEX@(.*?)@IOCENDINDEX@/', '', $menu_html_index);
            $menu_html_index = preg_replace('/@IOCSTARTEXPANDER@(.*?)@IOCENDEXPANDER@/', '', $menu_html_index);
            $menu_html_index = preg_replace('/@IOCACTIVITYICONSTART@|@IOCACTIVITYICONEND@/', '', $menu_html_index);
            $menu_html_index = preg_replace('/@IOCACTIVITYNAMESTART@(.*?)@IOCACTIVITYNAMEEND@/', '', $menu_html_index);
            $menu_html_index = preg_replace('/id="\w+"/', '', $menu_html_index);
            $menu_html_index = preg_replace('/"expander"/', '"indent"', $menu_html_index);
            $html = preg_replace('/@IOCTOC@/', $menu_html_index, $text_index, 1);
            $html = preg_replace('/@IOCHEADTOC@/',$this->lang['Toc'], $html, 1);
            $html = preg_replace('/@IOCCHROME@/',$this->lang['chrome'], $html, 1);
            $html = preg_replace('/@IOCSTART@/',$this->lang['start'], $html, 1);
            $html = preg_replace('/@IOCLICENSE@/',$this->lang['license'], $html, 1);
            $html = preg_replace('/@IOCMETA@/',$this->createMeta($data[1]), $html, 1);
            $html = preg_replace('/@IOCMETABC@/',$this->createMetaBC($data[1]), $html, 1);
            $html = preg_replace('/@IOCMETABR@/',$this->createMetaBR($data[1]), $html, 1);
            $this->addMetaMedia($data[1],$zip);
            $html = preg_replace('/@IOCPATH@/', '', $html);
            $zip->addFromString('index.html', $html);
            //Create search page
            $navmenu = $this->createNavigation('');
            $html = preg_replace('/@IOCCONTENT@/', '<div id="search-results"></div>', $text_search, 1);
            $html = preg_replace('/@IOCTITLE@/', $this->lang['search'], $html, 1);
            $html = preg_replace('/@IOCTOC@/', '', $html, 1);
            $html = preg_replace('/@IOCPATH@/', '', $html);
            $html = preg_replace('/@IOCNAVMENU@/', $navmenu, $html, 1);
            $zip->addFromString('search.html', $html);
            //Remove menu index ,expander and icon/name tags
            $this->menu_html = preg_replace('/@IOCSTARTINDEX@|@IOCENDINDEX@/', '', $this->menu_html);
            $this->menu_html = preg_replace('/@IOCSTARTEXPANDER@|@IOCENDEXPANDER@/', '', $this->menu_html);
            $this->menu_html = preg_replace('/@IOCACTIVITYICONSTART@(.*?)@IOCACTIVITYICONEND@/', '', $this->menu_html);
            $this->menu_html = preg_replace('/@IOCACTIVITYNAMESTART@|@IOCACTIVITYNAMEEND@/', '', $this->menu_html);
            if (isset($data[0]['intro'])){
                if(preg_match('/@IOCSTARTINTRO@(.*?)@IOCENDINTRO@/', $this->menu_html, $matches)){
                    $menu_html_intro = $matches[1];
                    $this->menu_html = preg_replace('/@IOCSTARTINTRO@.*?@IOCENDINTRO@/', '', $this->menu_html, 1);
                }
                //var to attach all url media files
                $files = array();
                //Intro
                $_SESSION['iocintro'] = TRUE;
                foreach ($data[0]['intro'] as $i=>$page){
                   $text = io_readFile(wikiFN($page[1]));
                   $navmenu = $this->createNavigation('',array($page[0]), array(''));
                   list($header, $text) =$this->extractHeader($text);
                   $instructions = get_latex_instructions($text);
                   $html = p_latex_render('iocxhtml', $instructions, $info);
                   $html = preg_replace('/\$/', '\\\\$', $html);
                   $html = preg_replace('/@IOCCONTENT@/', $html, $text_template, 1);
                   $html = preg_replace('/@IOCMENUNAVIGATION@/', $menu_html_intro, $html, 1);
                   $html = preg_replace('/@IOCTITLE@/', $header, $html, 1);
                   $html = preg_replace('/@IOCTOC@/', '', $html, 1);
                   $html = preg_replace('/@IOCPATH@/', '', $html);
                   $html = preg_replace('/@IOCNAVMENU@/', $navmenu, $html, 1);
                   $html = $this->createrefstopages($html, $data[0]['intro'], '', $i, '', '');
                   $zip->addFromString(basename(wikiFN($page[1]),'.txt').'.html', $html);
                 }
                 $_SESSION['iocintro'] = FALSE;
                 unset($data[0]['intro']);

                 //Attach media files
                 foreach($_SESSION['media_files'] as $f){
                     resolve_mediaid(getNS($f),$f,$exists);
                     if ($exists){
                         $zip->addFile(mediaFN($f), 'media/'.basename(mediaFN($f)));
                     }
                 }
                 $_SESSION['media_files'] = array();

                 //Attach latex files
                 foreach($_SESSION['latex_images'] as $l){
                     if (file_exists($l)){
                         $zip->addFile($l, 'media/'.basename($l));
                     }
                 }
                 $_SESSION['latex_images'] = array();

                 //Attach graphviz files
                 foreach($_SESSION['graphviz_images'] as $l){
                     if (file_exists($l)){
                         $zip->addFile($l, 'media/'.basename($l));
                     }
                 }
                 $_SESSION['graphviz_images'] = array();

                 //Attach gif (png,jpg,etc) files
                 foreach($_SESSION['gif_images'] as $m){
                     if (file_exists(mediaFN($m))){
                        $zip->addFile(mediaFN($m), "gifs/". str_replace(":", "/", $m));
                     }
                 }
                 $_SESSION['gif_images'] = array();
            }

            //Content
            foreach ($data[0] as $ku => $unit){
                //Section
                $unitname = $unit['iocname'];
                unset($unit['iocname']);
                if(preg_match('/@IOCSTARTUNIT@(.*?)@IOCENDUNIT@/', $this->menu_html, $matches)){
                    $menu_html_unit = $matches[1];
                    $this->menu_html = preg_replace('/@IOCSTARTUNIT@.*?@IOCENDUNIT@/', '', $this->menu_html, 1);
                }
                $def_unit_href = $unit['def_unit_href'];
                unset($unit['def_unit_href']);
                foreach ($unit as $ks => $section){
                    if (is_array($section)){
                        //Activities
                        $_SESSION['activities'] = TRUE;
                        foreach ($section as $ka => $act){
                            $text = io_readFile(wikiFN($act));
                            if (basename(wikiFN($act),'.txt') === 'activitats'){
                                $_SESSION['activity'] = TRUE;
                            }
                            list($header, $text) = $this->extractHeader($text);
                            $navmenu = $this->createNavigation('../../../',array($unitname,$this->tree_names[$ku][$ks]['sectionname'],$this->tree_names[$ku][$ks][$ka]), array('../'.$def_unit_href.'.html',$this->def_section_href.'.html',''));
                            $instructions = get_latex_instructions($text);
                            $html = p_latex_render('iocxhtml', $instructions, $info);
                            $html = preg_replace('/\$/', '\\\\$', $html);
                            $html = preg_replace('/@IOCCONTENT@/', $html, $text_template, 1);
                            $html = preg_replace('/@IOCMENUNAVIGATION@/', $menu_html_unit, $html, 1);
                            $html = preg_replace('/@IOCTITLE@/', $header, $html, 1);
                            $html = preg_replace('/@IOCTOC@/', $this->getTOC($text), $html, 1);
                            $html = preg_replace('/@IOCPATH@/', '../../../', $html);
                            $html = preg_replace('/@IOCNAVMENU@/', $navmenu, $html, 1);
                            $html = $this->createrefstopages($html, $unit, $ku, $ks, $ka, '../../../');
                            $zip->addFromString($this->web_folder.'/'.$ku.'/'.$ks.'/'.basename(wikiFN($act),'.txt').'.html', $html);
                            if (basename(wikiFN($act),'.txt') === 'activitats'){
                                $_SESSION['activity'] = FALSE;
                            }
                        }
                        $_SESSION['activities'] = FALSE;
                    }else{
                        $_SESSION['iocintro'] = TRUE;
                        $text = io_readFile(wikiFN($section));
                        list($header, $text) = $this->extractHeader($text);
                        $navmenu = $this->createNavigation('../../',array($unitname,$this->tree_names[$ku][$ks]), array($def_unit_href.'.html',''));
                        $instructions = get_latex_instructions($text);
                        $html = p_latex_render('iocxhtml', $instructions, $info);
                        $html = preg_replace('/\$/', '\\\\$', $html);
                        $html = preg_replace('/@IOCCONTENT@/', $html, $text_template, 1);
                        $html = preg_replace('/@IOCMENUNAVIGATION@/', $menu_html_unit, $html, 1);
                        $html = preg_replace('/@IOCTITLE@/', $header, $html, 1);
                        $html = preg_replace('/@IOCTOC@/', '', $html, 1);
                        $html = preg_replace('/@IOCPATH@/', '../../', $html);
                        $html = preg_replace('/@IOCNAVMENU@/', $navmenu, $html, 1);
                        $html = $this->createrefstopages($html, $unit, $ku, '', $ks, '../../');
                        $zip->addFromString($this->web_folder.'/'.$ku.'/'.basename(wikiFN($section),'.txt').'.html', $html);
                        $_SESSION['iocintro'] = FALSE;
                    }
                }
                //Attach media files
                foreach($_SESSION['media_files'] as $f){
                    resolve_mediaid(getNS($f),$f,$exists);
                    if ($exists){
                        $zip->addFile(mediaFN($f), $this->web_folder.'/'.$ku.'/media/'.basename(mediaFN($f)));
                    }
                }
                $_SESSION['media_files'] = array();

                //Attach latex files
                foreach($_SESSION['latex_images'] as $l){
                    if (file_exists($l)){
                        $zip->addFile($l, $this->web_folder.'/'.$ku.'/media/'.basename($l));
                    }
                }
                $_SESSION['latex_images'] = array();

                //Attach graphviz files
                foreach($_SESSION['graphviz_images'] as $l){
                    if (file_exists($l)){
                        $zip->addFile($l, $this->web_folder.'/'.$ku.'/media/'.basename($l));
                    }
                }
                $_SESSION['graphviz_images'] = array();

                 //Attach gif (png, jpg, etc.) files
                 foreach($_SESSION['gif_images'] as $m){
                     if (file_exists(mediaFN($m))) {
                        $zip->addFile(mediaFN($m), "gifs/". str_replace(":", "/", $m));
                     }
                 }
                 $_SESSION['gif_images'] = array();
            }
            $zip->close();
            $result = array();
            $this->returnData(DOKU_IOCEXPORTL_LATEX_TMP.$tmp_dir, $output_filename.'.zip', $result);
        }else{
            $result = $this->lang['nozipfile'];
        }

        $_SESSION['export_html'] = FALSE;
        session_destroy();
        if(!$conf['plugin']['iocexportl']['saveWorkDir']){
            $this->removeDir(DOKU_IOCEXPORTL_LATEX_TMP.$tmp_dir);
        }
        if($this->log){
            return $result;
        }
        if($this->needReturnData){
            return $this->returnData;
        }
    }

    /**
     *
     * Returns zip file info
     * @param string $path
     * @param string $filename
     */
    private function returnData($path, $filename, &$result){
        global $conf;

        if (file_exists($path.'/'.$filename)){
            $error = '';
            $filesize = filesize($path . "/" . $filename);
            $filesize = filesize_h($filesize);

            $dest = preg_replace('/:/', '/', $this->id);
            $dest = dirname($dest);
            if (!file_exists($conf['mediadir'].'/'.$dest)){
                mkdir($conf['mediadir'].'/'.$dest, 0755, TRUE);
            }
            copy($path.'/'.$filename, $conf['mediadir'].'/'.$dest .'/'.$filename);
            $dest = preg_replace('/\//', ':', $dest);
            $time_end = microtime(TRUE);
            $time = round($time_end - $this->time_start, 2);
            setlocale(LC_TIME, 'ca_ES.utf8');
            $dateFile = strftime("%e %B %Y %T", filemtime($path.'/'.$filename));
            if($this->log){
                $result = array('time' => $dateFile, 'path' => $dest.':'.$filename, 'size' => $filesize);
            }else{
                $data = array('zip', $this->media_path.$dest.':'.$filename.'&time='.gettimeofday(TRUE), $filename, $filesize, $time, $error, $dateFile);
            }
        }else{
            $result = $this->lang['createfileerror'] . $filename;
        }
        if($this->needReturnData){
            if(!$this->log){
                $this->returnData = $data;
            }
        }else{
            if (!$this->log){
                echo json_encode($data);
            }
        }
    }

	/**
	 *
     * Remove specified dir
     * @param string $directory
     */
    private function removeDir($directory) {
        if(!file_exists($directory) || !is_dir($directory)) {
            return FALSE;
        } elseif(!is_readable($directory)) {
            return FALSE;
        } else {
            $directoryHandle = opendir($directory);

            while ($contents = readdir($directoryHandle)) {
                if($contents != '.' && $contents != '..') {
                    $path = $directory . "/" . $contents;

                    if(is_dir($path)) {
                        $this->removeDir($path);
                    } else {
                        unlink($path);
                    }
                }
            }
            closedir($directoryHandle);

            if(file_exists($directory)) {
                if(!rmdir($directory)) {
                    return FALSE;
                }
            }
            return TRUE;
        }
    }

    /**
     *
     * Check whether user has right acces level
     */
    private function checkPerms() {
        global $ID;
        global $USERINFO;
        $ID = getID();
        $user = $_SERVER['REMOTE_USER'];
        $groups = $USERINFO['grps'];
        $aclLevel = auth_aclcheck($ID,$user,$groups);
        // AUTH_ADMIN, AUTH_READ,AUTH_EDIT,AUTH_CREATE,AUTH_UPLOAD,AUTH_DELETE
        return ($aclLevel >=  AUTH_UPLOAD);
      }

    /**
     *
     * Fill data var with wiki pages using a customized structure
     * @param array $data
     * @param boolean $struct
     */
    private function getPageNames(&$data){
        global $conf;

        $file = wikiFN($this->id);
        if (@file_exists($file)) {
            $matches = array();
            $txt =  io_readFile($file);
            preg_match_all('/(?<=\={5} [T|t]oc \={5})\n+(\s{2,4}\*\s+\[\[[^\]]+\]\] *)+/is', $txt, $matches);
            $pages = (isset($matches[0][0]))?$matches[0][0]:'';
            unset($matches);
            preg_match_all('/\[\[([^|\]]+)\|?(.*?)\]\] */', $pages, $matches, PREG_SET_ORDER);
            foreach ($matches as $match){
                $sort = FALSE;
                $ns = resolve_id(getNS($this->id),$match[1]);
                if (!in_array($ns, $this->toexport)){
                    continue;
                }
                if (page_exists($ns)){
                    if(!isset($data['intro'])){
                        $data['intro'] = array();
                    }
                    $text = io_readFile($conf['datadir'].'/'.preg_replace('/:/', '/', $ns).'.txt');
                    $header = $this->getHeader($text);
                    array_push($data['intro'], array($header, $ns));
                }else{
                    $ns = preg_replace('/:/' ,'/', $ns);
                    if (file_exists($conf['datadir'].'/'.$ns.'/'.$conf['start'].'.txt')){
                        $content = io_readFile($conf['datadir'].'/'.$ns.'/'.$conf['start'].'.txt');
                        $result = array();
                        $def_unit_href='';
                        $unit_act = '';
                        if (preg_match('/^index/i', $content)){
                            $result = explode(DOKU_LF,$content);
                            $result = array_filter($result);
                            @array_shift($result);
                            $ns = str_replace('/', ':', $ns);
                            $sort = TRUE;
                        }else{
                            search($result,$conf['datadir'], 'search_allpages', null, $ns);
                        }
                        foreach ($result as $pagename){
                            if ($sort){
                                //Absolute path or relative?
                                if (!@file_exists(wikiFN($pagename))){
                                    $pagename = $ns.':'.$pagename;
                                }
                            }else{
                                $pagename = $pagename['id'];
                            }
                            if (!preg_match('/:(pdfindex|imatges|'.$conf['start'].')$/', $pagename)){
                                preg_match('/:(u\d+):/', $pagename, $unit);
                                preg_match('/:(a\d+):/', $pagename, $section);
                                if (empty($section) && empty($def_unit_href)){
                                    $def_unit_href = preg_replace('/([^:]*:)+/','',$pagename);
                                }
                                if (!empty($unit[1]) && !isset($data[$unit[1]])){
                                    $data[$unit[1]] = array();
                                    $unit_act = $unit[1];
                                }
                                if (isset($data[$unit_act]) && empty($data[$unit_act]['def_unit_href'])){
                                    $data[$unit_act]['def_unit_href'] = $def_unit_href;
                                }
                                if (!empty($section[1]) && !isset($data[$unit[1]][$section[1]])){
                                    $data[$unit[1]][$section[1]] = array();
                                }
                                //Save unit name
                                $data[$unit[1]]['iocname'] = $match[2];
                                preg_match('/([^:]*:)+([^\.]*)$/', $pagename, $name);
                                if (!empty($section[1])){
                                    //Put default section at first place
                                    if ($name[2] === $this->def_section_href){
                                        $data[$unit[1]][$section[1]] = array_merge(array($name[2] => $pagename),$data[$unit[1]][$section[1]]);
                                    }else{
                                        $data[$unit[1]][$section[1]][$name[2]] = $pagename;
                                    }
                                 }else{
                                    $data[$unit[1]][$name[2]] = $pagename;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     *
     * Get and return uri wiki pages
     */
    private function getData(){

        $data = array();
        $data[0] = array();
        $data[1] = array();
        $file = wikiFN($this->id);
        $inf = NULL;
        if (@file_exists($file)) {
            $info = io_grep($file, '/(?<=\={6} )[^\=]*/', 0, TRUE);
            $data[1]['nomcomplert'] = $info[0][0];
            $text = io_readFile($file);
            $info = array();
            preg_match_all('/(?<=\={5} [M|m]eta \={5}\n\n)\n*( {2,4}\* \*\*.*?\*\*:.*\n?)+/', $text, $info, PREG_SET_ORDER);
            if (!empty($info[0][0])){
                $text = $info[0][0];
                preg_match_all('/ {2,4}\* (\*\*(.*?)\*\*:)(.*)/m', $text, $info, PREG_SET_ORDER);
                foreach ($info as $i){
                    $key = trim($i[2]);
                    if ($key === $this->meta_dcicle){
                        $this->double_cicle = TRUE;
                        continue;
                    }
                    if (in_array($key, $this->meta_params)){
                        $data[1][$key] = trim($i[3]);
                    }else{
                        $instructions = get_latex_instructions(trim($i[1].$i[3]));
                        $latex = p_latex_render('iocxhtml', $instructions, $inf);
                        $data[1]['extra'] = $latex;
                    }
                }
            }
            //get page names
            $this->getPageNames($data[0]);
            return $data;
        }
        return FALSE;
    }

    /**
     *
     * Create side menu elements
     */
    private function setMenu($type='', $name='', $href='', $id='',$index=FALSE){

        $types = array('activitats','annexos','exercicis');
        $name = trim($name);
        if (strlen($name) > $this->max_menu){
            $name = mb_substr($name, 0, $this->max_menu) . '...';
        }
        if ($type === 'root'){
            $class = ($index)?'indexnode':'rootnode';
            $menu_html = '<li id="'.$id.'" class="'.$class.'">';
            $menu_html .= '<p><a href="'.$href.'">'.$name.'</a></p>';
            $menu_html .= '</li>';
        }elseif ($type === 'unit'){
            $matches = array();
            $unit = '';
            preg_match('/\/u(\d)\//', $href, $matches);
            if (isset($matches[1]) && is_numeric($matches[1])){
                $unit = $matches[1].'. ';
            }
            $menu_html = '<li id="'.$id.'" class="parentnode">';
            $menu_html .= '<p><a href="'.$href.'">'.$unit.$name.'</a></p>';
            $menu_html .= '<ul class="expander">';
        }elseif ($type === 'section'){
            $menu_html = '<li id="'.$id.'" class="tocsection">';
            $menu_html .= '<p id="'.$id.$this->def_section_href.'"><a href="'.$href.'">'.$name.'</a>';
            $menu_html .= '@IOCSTARTEXPANDER@<span class="buttonexp"></span>@IOCENDEXPANDER@';
            $menu_html .= '</p>';
            $menu_html .= '<ul>';
        }elseif ($type === 'intro'){
            $menu_html = '<li id="'.$id.'">';
            $menu_html .= '<a href="'.$href.'">'.$name.'</a>';
            $menu_html .= '</li>';
        }elseif ($type === 'activity'){
            $menu_html = '<li id="'.$id.'">';
            preg_match('/\/([^\.\/]*)\.html$/',$href,$type);
            if (in_array($type[1], $types)){
                if (strtolower($type[1]) === 'activitats'){
                    $img = 'activity';
                }elseif (strtolower($type[1]) === 'annexos'){
                    $img = 'appendix';
                }elseif (strtolower($type[1]) === 'exercicis'){
                    $img = 'exercise';
                }
                $menu_html .= '@IOCACTIVITYICONSTART@<a href="'.$href.'"><img src="img/'.$img.'.png" title="'.$name.'"/></a>@IOCACTIVITYICONEND@';
                $menu_html .= '@IOCACTIVITYNAMESTART@<a href="'.$href.'">'.$name.'</a>@IOCACTIVITYNAMEEND@';
            }else{
                $menu_html .= '<a href="'.$href.'">'.$name.'</a>';
            }
            $menu_html .= '</li>';
        }else{
            $menu_html = '</ul></li>';
        }
        return $menu_html;
    }

    /**
     *
     * Create side menu elements and return path to filenames
     */
    private function createMenu($elements){

        $files = array();
        $menu_html = '';
        if (isset($elements['intro'])){
            //Intro
            $menu_html .= '@IOCSTARTINTRO@';
            foreach ($elements['intro'] as $kp => $page){
                $text = io_readFile(wikiFN($page[1]));
                $href = '@IOCPATH@'.basename(wikiFN($page[1]),'.txt').'.html';
                $menu_html .= $this->setMenu('root', $page[0], $href, basename(str_replace(':','/',$page[1])));
                array_push($files, '"'.str_replace('@IOCPATH@', '', $href).'"');
            }
            //Link to index
            $menu_html .= '@IOCSTARTINDEX@';
            $href = '@IOCPATH@index.html';
            $menu_html .= $this->setMenu('root', $this->lang['gomainindex'], $href, '', TRUE);
            $menu_html .= '@IOCENDINDEX@';
            $menu_html .= '@IOCENDINTRO@';
            unset($elements['intro']);
        }
        foreach ($elements as $ku => $unit){
            $menu_html .= '@IOCSTARTUNIT@';
            $this->tree_names[$ku] = array();
            //Section
            $menu_html .= $this->setMenu('unit',$unit['iocname'], '@IOCPATH@'.$this->web_folder.'/'.$ku.'/'.$unit['def_unit_href'].'.html', $ku);
            unset($unit['iocname']);
            //First main pages
            unset($unit['def_unit_href']);
            foreach ($unit as $ks => $section){
                if (!is_array($section)){
                    $text = io_readFile(wikiFN($section));
                    $act_href = '@IOCPATH@'.$this->web_folder.'/'.$ku.'/'.basename(wikiFN($section),'.txt').'.html';
                    $act_name = $this->getHeader($text);
                    $this->tree_names[$ku][$ks]=$act_name;
                    $menu_html .= $this->setMenu('intro', $act_name, $act_href, $ku.$ks);
                    array_push($files, '"'.str_replace('@IOCPATH@', '', $act_href).'"');
                    unset($unit[$ks]);
                }
            }
            //Only sections with content
            foreach ($unit as $ks => $section){
                $this->tree_names[$ku][$ks] = array();
                //Activities
                $text = io_readFile(wikiFN($section['continguts']));
                preg_match('/\={6}([^=]+)\={6}/', $text, $matches);
                $section_name = $this->getHeader($text);
                $this->tree_names[$ku][$ks]['sectionname']=$section_name;
                //Comprovar si existeix continguts.html $def_section_href i enllaçar la secció
                $act_href = '@IOCPATH@'.$this->web_folder.'/'.$ku.'/'.$ks.'/'.$this->def_section_href.'.html';
                $menu_html .= $this->setMenu('section', $section_name, $act_href, $ku.$ks);
                foreach ($section as $ka => $act){
                    $text = io_readFile(wikiFN($act));
                    $act_href = '@IOCPATH@'.$this->web_folder.'/'.$ku.'/'.$ks.'/'.basename(wikiFN($act),'.txt').'.html';
                    if ($ka !== 'continguts'){
                        $act_name = $this->getHeader($text);
                        $this->tree_names[$ku][$ks][$ka]=$act_name;
                        $menu_html .= $this->setMenu('activity', $act_name, $act_href, $ku.$ks.$ka);
                    }else{//File continguts has a short name
                        $act_name = 'Contingut';
                        $this->tree_names[$ku][$ks]['continguts']=$act_name;
                    }
                    array_push($files, '"'.str_replace('@IOCPATH@', '', $act_href).'"');
                }
                //Close menu activities
                $menu_html .= $this->setMenu();
            }
            $menu_html .= $this->setMenu();
            //Link to index
            $menu_html .= '@IOCSTARTINDEX@';
            $href = '@IOCPATH@index.html';
            $menu_html .= $this->setMenu('root', $this->lang['gomainindex'], $href, '', TRUE);
            $menu_html .= '@IOCENDINDEX@';
            $menu_html .= '@IOCENDUNIT@';
        }
        return array($menu_html, $files);
    }


    /**
    *
    * Fill zip var with all media files stored on directory var
    * @param string $directory
    * @param string $zip
    */
    private function getFiles($directory, &$zip){
        if(!file_exists($directory) || !is_dir($directory)) {
            return FALSE;
        } elseif(!is_readable($directory)) {
            return FALSE;
        } else {
            $ignore = array('index.html','search.html','template.html','build.js');
            $directoryHandle = opendir($directory);
            while ($contents = readdir($directoryHandle)) {
                if($contents != '.' && $contents != '..') {
                    $path = $directory . "/" . $contents;
                    if(is_dir($path)) {
                        $dirname = str_replace(DOKU_IOCEXPORTL_TEMPLATES_HTML,'',$path);
                        $zip->addEmptyDir($dirname);
                        $this->getFiles($path, $zip);
                    }else{
                        if (!in_array($contents, $ignore)){
                            $dirname = str_replace(DOKU_IOCEXPORTL_TEMPLATES_HTML,'',$directory);
                            $zip->addFile($path, $dirname ."/".$contents);
                        }
                    }
                }
            }
            closedir($directoryHandle);
            return TRUE;
        }
    }

     /**
     *
     * Get Table Of Contents
     */
    private function getTOC($text){
        $matches = array();
        $headers = array();
        $toc = '<div class="toc">';
        $toc .= '<span>'.$this->lang['toc'].'</span><br /><ul>';
        preg_match_all('/\={5}([^=]+)\={5}/', $text, $matches, PREG_SET_ORDER);
        foreach ($matches as $m){
            $toc .= '<li>';
            $toc .= '<a href="#'.sectionID($m[1],$headers).'">'.trim($m[1]).'</a>';
            $toc .= '</li>';
        }
        $toc .= '</ul></div>';
        return $toc;
    }

    /**
     *
     * Create Meta data
     */
    private function createMeta($data){

        $meta .= '<h1 class="headmainindex">'.(isset($data['creditnom'])?$data['creditnom']:'').'</h1>';
        $meta .= '<div class="metainfo">';
        $meta .= '<img src="img/portada.png" alt="'.(isset($data['familia'])?$data['familia']:'').'" />';
        $meta .= '<ul>';

        $coord = (isset($data['coordinacio'])?$data['coordinacio']:'');
        if (!empty($coord)){
            $meta .= '<li><strong>'.$this->lang['editor'].'</strong></li>';
        }
        $coord = preg_split('/\s?\\\\\s?/', $coord);
        foreach ($coord as $co){
            if (!empty($co)){
                $meta .= '<li>'.$co.'</li>';
            }
        }
        $authors = (isset($data['autoria'])?$data['autoria']:'');
        if (!empty($authors)){
            $meta .= '<li><strong>'.$this->lang['author'].'</strong></li>';
        }
        $authors = preg_split('/\s?\\\\\s?/', $authors);
        foreach ($authors as $auth){
            if (!empty($auth)){
                $meta .= '<li>'.$auth.'</li>';
            }
        }
        $adapt = (isset($data['adaptacio'])?$data['adaptacio']:'');
        if (!empty($adapt)){
            $meta .= '<li><strong>'.$this->lang['adaptation'].'</strong></li>';
        }
        $adapt = preg_split('/\s?\\\\\s?/', $adapt);
        foreach ($adapt as $ad){
            if (!empty($ad)){
                $meta .= '<li>'.$ad.'</li>';
            }
        }
        $meta .= '</ul>';
        $meta .= '</div>';
        return $meta;
    }

    /**
    *
    * Create Meta data located at the bottom centered
    */
    private function createMetaBC($data){

        $meta .= '<ul>';
        $meta .= '<li>'.(isset($data['familia'])?$data['familia']:'').'</li>';
        $meta .= '<li><strong>'.(isset($data['creditcodi'])?$data['creditcodi']:'').'</strong></li>';
        if ($this->double_cicle){
            $cicles = array();
            $cc = (isset($data['ciclenom'])?$data['ciclenom']:'');
            $cicles = preg_split('/\\\\/', $cc);
            $meta .= '<li><strong>'.trim($cicles[0]).'</strong></li>';
            $meta .= '<li><strong>'.trim($cicles[2]).'</strong></li>';
        }else{
            $meta .= '<li><strong>'.(isset($data['ciclenom'])?$data['ciclenom']:'').'</strong></li>';
        }
        return $meta;
    }

    /**
    *
    * Create Meta data located at the bottom right aligned
    */
    private function createMetaBR($data){

        $meta .= '&copy; Departament d&#39;Ensenyament<br />';
        $meta .= $this->lang['firstediton'].': <strong>'.(isset($data['data'])?$data['data']:'').'</strong>';
        if (isset($data['legal'])){
            $meta .= '&nbsp;&#47;&nbsp;'.$this->lang['legaldeposit'].': <strong>'.$data['legal'].'</strong>';
        }
        return $meta;
    }

    /**
     *
     * Add media files from meta info
     * @param Array $data
     * @param ZIP $zip
     */
    private function addMetaMedia($data, &$zip){
        if (isset($data['familypic'])){
            preg_match('/\{\{([^}|?]+)[^}]*\}\}/',$data['familypic'],$matches);
            resolve_mediaid(getNS($matches[1]),$matches[1],$exists);
            if ($exists){
                $zip->addFile(mediaFN($matches[1]), 'img/portada.png');
            }
        }

        if (isset($data['copylogo'])){
            preg_match('/\{\{([^}|?]+)[^}]*\}\}/',$data['copylogo'],$matches);
            resolve_mediaid(getNS($matches[1]),$matches[1],$exists);
            if ($exists){
                $zip->addFile(mediaFN($matches[1]), 'img/license.png');
            }
        }

        if(isset($data['familia'])){
            $urlfamily = DOKU_IOCEXPORTL_TEMPLATES;
            if (preg_match('/administraci/i', $data['familia'])){
                $urlfamily .= 'gad';
            }elseif (preg_match('/electricitat/i', $data['familia'])){
                $urlfamily .= 'iea';
            }elseif (preg_match('/socioculturals/i', $data['familia'])){
                $urlfamily .= 'edi';
            }elseif (preg_match('/sanitat/i', $data['familia'])){
                $urlfamily .= 'cai';
            }else{
                $urlfamily .= 'asix';
            }
            $urlfamily .= '_family_icon.png';
            $zip->addFile($urlfamily, 'img/family_icon.png');
        }
    }

     /**
     *
     * Extract main header from text
     */
    private function extractHeader($text){
        $check = array();
        if(preg_match('/^\{\{page>([^}]*)\}\}/', $text, $matches)){
            if (!empty($matches[1])){
                $text = io_readFile(wikiFN($matches[1]));
            }
        }
        if (preg_match('/\={6}([^=]+)\={6}/', $text, $matches)){
            $text = preg_replace('/\={6}[^=]+\={6}/', '', $text);
            $id = sectionID($matches[1], $check);
            $header = '<a id="'.$id.'">'.$matches[1].'</a>';
            return array($header, $text);
        }
        return array('',$text);
    }

    /**
    *
    * Get main header from text
    */
    private function getHeader($text){
        preg_match('/\={6}([^=]+)\={6}/', $text, $matches);
        if (empty($matches[1])){
            preg_match('/^\{\{page>([^}]*)\}\}/', $text, $matches);
            if (!empty($matches[1])){
                $text = io_readFile(wikiFN($matches[1]));
                preg_match('/\={6}([^=]+)\={6}/', $text, $matches);
            }
        }
        $pagename = (!empty($matches[1]))?trim($matches[1]):'HEADER LEVEL 1 NOT FOUND';
        return $pagename;
    }

     /**
     *
     * Create menu navigation
     */
    private function createNavigation($index_path, $options=NULL,$refs=NULL){

        $navigation = '<ul class="webnav"><li><a href="'.$index_path.'index.html" title="'.$this->lang['gomainindex'].'">'.$this->lang['start'].'</a></li>';
        if (!is_null($options)){
            foreach ($options as $k => $op){
                if ($op != 'Contingut'){
                    if ((strlen($op) > $this->max_navmenu) && $k < (count($options)-1)){
                        $op = mb_substr($op, 0, $this->max_navmenu) . '...';
                    }
                    $navigation .= '<li>';
                    if (!empty($refs[$k]) && (isset($options[$k+1]) && $options[$k+1] != 'Contingut')){
                        $navigation .= '<a href="'.$refs[$k].'">';
                    }
                    $navigation .= $op;
                    if (!empty($refs[$k]) && (isset($options[$k+1]) && $options[$k+1] != 'Contingut')){
                        $navigation .= '</a>';
                    }
                    $navigation .= '</li>';
                }
            }
        }
        $navigation .= '</ul>';
        return $navigation;
    }

    /**
     *
     * Create previous and next reference for each page
     * @param string $html
     * @param array $data
     * @param string $unit
     * @param string $section
     * @param string $activity
     * @param string $href
     */
    private function createrefstopages($html, $data, $unit, $section, $activity, $href){

        $textprev = $this->lang['gopreviouspage'].'<br />';
        $textnext = $this->lang['gonextpage'].'<br />';
        //Intro
        if (empty($unit)){
            $prev = $section-1;
            $next = $section+1;
            $content = '';
            if ($prev >=0){
                $phref = $href.basename(str_replace(':', '/', $data[$prev][1])).'.html';
                $content = '<div id="prevpage">'.$textprev.'<a href="'.$phref.'">'.$data[$prev][0].'</a></div>';
            }
            $html = preg_replace('/@IOCPREVPAGE@/',$content, $html);
            $content = '';
            if ($next <= (count($data)-1)){
                $href = $href.basename(str_replace(':', '/', $data[$next][1])).'.html';
                $content = '<div id="nextpage">'.$textnext.'<a href="'.$href.'">'.$data[$next][0].'</a></div>';
            }else{
                $content = '<div id="nextpage">'.$textnext.'<a href="'.$href.'index.html">'.$this->lang['mainindex'].'</a></div>';
            }
            $html = preg_replace('/@IOCNEXTPAGE@/',$content, $html);
        }else{
            if (empty($section)){//NO SECTION
                $cont = 0;
                reset($data);
                while($cont < count($data) && key($data) !== $activity){
                    next($data);
                    $cont++;
                }
                $prev = $cont-1;
                $next = $cont+1;
                if ($prev >= 0){
                    list($prev_key,$prev_item) = $this->goAssocArrayNumeric($data,$prev);
                    if (is_array($data[$prev_key])){//First intro element
                        $prev_key = FALSE;
                    }
                }else{
                    $prev_key = FALSE;
                }
                if ($next < count($data)){
                    list($next_key,$next_item) = $this->goAssocArrayNumeric($data,$next);
                }else{
                    $next_key = FALSE;
                }
                if ($prev_key){
                    $phref = $href.'WebContent/'.$unit.'/'.basename(str_replace(':', '/', $prev_item)).'.html';
                    $content = '<div id="prevpage">'.$textprev.'<a href="'.$phref.'">'.$this->tree_names[$unit][$prev_key].'</a></div>';
                }
                $html = preg_replace('/@IOCPREVPAGE@/',$content, $html);
                if ($next_key && !is_array($data[$next_key])){
                    $phref = $href.'WebContent/'.$unit.'/'.basename(str_replace(':', '/', $next_item)).'.html';
                    $content = '<div id="nextpage">'.$textnext.'<a href="'.$phref.'">'.$this->tree_names[$unit][$next_key].'</a></div>';
                }else{
                    reset($data);
                    $sect = key($data);
                    $cont = 0;
                    while($cont < count($data) && !is_array($data[$sect])){
                        next($data);
                        $sect = key($data);
                        $cont++;
                    }
                    if (is_array($data[$sect])){
                        $phref = $href.'WebContent/'.$unit.'/'.$sect.'/'.$this->def_section_href.'.html';
                        $content = '<div id="nextpage">'.$textnext.'<a href="'.$phref.'">'.$this->tree_names[$unit][$sect]['sectionname'].'</a></div>';
                    }else{
                        $content = '<div id="nextpage">'.$textnext.'<a href="'.$href.'index.html">'.$this->lang['mainindex'].'</a></div>';
                    }
                }
                $html = preg_replace('/@IOCNEXTPAGE@/',$content, $html);
            }else{//INSIDE SECTION
                preg_match('/a(\d+)/', $section, $num_section);
                $num_section = $num_section[1];
                reset($data[$section]);
     			$cont = 0;
                while($cont < count($data[$section]) && key($data[$section]) !== $activity){
                    next($data[$section]);
                    $cont++;
                }
                $prev = $cont-1;
                $next = $cont+1;
                if ($prev >= 0){
                    list($prev_key,$prev_item) = $this->goAssocArrayNumeric($data[$section],$prev);
                }else{
                    $prev_key = FALSE;
                }
                if ($next < count($data[$section])){
                    list($next_key,$next_item) = $this->goAssocArrayNumeric($data[$section],$next);
                }else{
                    $next_key = FALSE;
                }
                if ($prev_key){
                    $phref = $href.'WebContent/'.$unit.'/'.$section.'/'.basename(str_replace(':', '/', $prev_item)).'.html';
                    if (basename(str_replace(':', '/', $prev_item)) === $this->def_section_href){
                        $name = $this->tree_names[$unit][$section]['sectionname'];
                    }else{
                        $name = $this->tree_names[$unit][$section][$prev_key];
                    }
                    $content = '<div id="prevpage">'.$textprev.'<a href="'.$phref.'">'.$name.'</a></div>';
                }else{
                    if (isset($data['a'.intval($num_section-1)])){
                        end($data['a'.intval($num_section-1)]);
                        $phref = $href.'WebContent/'.$unit.'/'.'a'.intval($num_section-1).'/'.basename(str_replace(':', '/', key($data['a'.intval($num_section-1)]))).'.html';
                        $content = '<div id="prevpage">'.$textprev.'<a href="'.$phref.'">'.end($this->tree_names[$unit]['a'.intval($num_section-1)]).'</a></div>';
                    }else{
                        end($data);
                        $sect = key($data);
                        $cont = 0;
                        while($cont < count($data) && is_array($data[$sect])){
                            prev($data);
                            $sect = key($data);
                            $cont++;
                        }
                        if (!is_array($data[$sect])){//Look whether intro exists when we're at first section
                            $phref = $href.'WebContent/'.$unit.'/'.basename(str_replace(':', '/', $sect)).'.html';
                            $content = '<div id="prevpage">'.$textprev.'<a href="'.$phref.'">'.$this->tree_names[$unit][$sect].'</a></div>';
                        }else{
                            $content = '';
                        }
                    }
                }
                $html = preg_replace('/@IOCPREVPAGE@/',$content, $html);
                if ($next_key){
                    $phref = $href.'WebContent/'.$unit.'/'.$section.'/'.basename(str_replace(':', '/', $next_item)).'.html';
                    $content = '<div id="nextpage">'.$textnext.'<a href="'.$phref.'">'.$this->tree_names[$unit][$section][$next_key].'</a></div>';
                }else{
                    if (isset($data['a'.intval($num_section+1)])){
                        reset($data['a'.intval($num_section+1)]);
                        $phref = $href.'WebContent/'.$unit.'/'.'a'.intval($num_section+1).'/'.basename(str_replace(':', '/', key($data['a'.intval($num_section+1)]))).'.html';
                        $content = '<div id="nextpage">'.$textnext.'<a href="'.$phref.'">'.reset($this->tree_names[$unit]['a'.intval($num_section+1)]).'</a></div>';
                    }else{
                        $content = '<div id="nextpage">'.$textnext.'<a href="'.$href.'index.html">'.$this->lang['mainindex'].'</a></div>';
                    }
                }
                $html = preg_replace('/@IOCNEXTPAGE@/',$content, $html);
            }
        }
        return $html;
    }


    /**
     *
     * Returns a certain value from an associative array
     * @param array $arrAssoc
     * @param int $key
     */
    private function goAssocArrayNumeric($arrAssoc, $key=-1)
    {
        $i = -1;
        foreach ($arrAssoc as $k => $v)
        {
            $i++;
            if ($i == $key)
            {
                return array($k,$v);
            }
        }
        return FALSE;
    }

    public function isDenied() {
        return FALSE;
    }

}

if(!isset($_GET["call"])){
    //Initialize params
    $params = array();
    $params['id'] = getID();
    $params['mode'] = $_POST['mode'];
    if ($params['id'] === $_POST['id']){
        $params['toexport'] = $_POST['toexport'];
        $params['ioclanguage'] = $_POST['ioclanguage'];
        $generate = new generate_html($params);
        $generate->init();
    }
}