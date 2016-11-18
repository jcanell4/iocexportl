<?php
/**
 * LaTeX Plugin: Generate Latex document
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author Marc Català <mcatala@ioc.cat>
 */

if (!defined('DOKU_INC')) define('DOKU_INC',dirname(__FILE__).'/../../../');
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
if (!defined('DOKU_IOCEXPORTL_TEMPLATES')) define('DOKU_IOCEXPORTL_TEMPLATES',DOKU_PLUGIN.'iocexportl/templates/');
if (!defined('DOKU_IOCEXPORTL_LATEX_TMP')) define('DOKU_IOCEXPORTL_LATEX_TMP',DOKU_PLUGIN.'tmp/latex/');
if(!defined('DOKU_MODEL')) define('DOKU_MODEL', DOKU_PLUGIN . "wikiiocmodel/");

require_once(DOKU_INC.'/inc/init.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');
require_once DOKU_MODEL.'WikiIocModel.php';
require_once DOKU_PLUGIN.'ownInit/WikiGlobalConfig.php';

//Initialize params
$params = array();
$params['id'] = getID();
$params['mode'] = $_POST['mode'];
if ($params['id'] === $_POST['id']){
    $params['toexport'] = $_POST['toexport'];
    $params['ioclanguage'] = $_POST['ioclanguage'];
    $params['user'] = $_SERVER['REMOTE_USER'];
    $generate = new generate_latex($params);
    $generate->init();
}


class generate_latex implements WikiIocModel{

    private $end_characters;
    private $exportallowed;
    private $img_src;
    private $img_pref;
    private $ini_characters;
    private $ioclang;
    private $ioclanguages;
    private $ioclangcontinue;
    private $log;
    private $needReturnData;
    private $formByColumns;
    private $returnData;
    private $media_path;
    private $meta_dcicle;
    private $meta_option;
    private $meta_params;
    private $nointro;
    private $time_start;
    private $toexport;
    private $tmp_dir;
    private $unitzero;
    private $permissionToExport;
    private $user;
    private $groups;
    private $fpd;
    private $bCoverPage;
    private $coverImage;
    private $coverBackground;


/**
    * Default Constructor
    *
    * Initialize variables
    *
    * @param array $params Array of parameters to pass to the constructor
    */
    function __construct($params=NULL){
        if($params!==NULL){
            $this->initParams($params);
        }
    }
    
    public function setParams($element, $value) {
        $this->params[$element] = $value;
    }
    
    public function initParams($params){        
        global $USERINFO;

        //Due listings problems whith header it's necessary to replace extended characters
        $this->end_characters = array("\'{a}", "\'{e}", "\'{i}", "\'{o}", "\'{u}", "\`{a}", "\`{e}", "\`{o}", '\"{i}', '\"{u}', '\~{n}', '\c{c}', "\'{A}", "\'{E}", "\'{I}", "\'{O}", "\'{U}", "\`{A}", "\`{E}", "\`{O}", '\"{I}', '\"{U}', '\~{N}', '\c{C}','\break ');
        $this->exportallowed = FALSE;
        $this->export_ok = ($params['mode'] === 'pdf' || $params['mode'] === 'zip' );
        $this->id = $params['id'];
        $this->img_pref = 'familyicon_';
        $this->img_src = array('administracio.png', 'electronica.png', 'infantil.png', 'informatica.png', 'seguretat.png', 'transversal.png');
        //Due listings problems whith header it's necessary to replace extended characters
        $this->ini_characters = array('á', 'é', 'í', 'ó', 'ú', 'à', 'è', 'ò', 'ï', 'ü', 'ñ', 'ç','Á', 'É', 'Í', 'Ó', 'Ú', 'À', 'È', 'Ò', 'Ï', 'Ü', 'Ñ', 'Ç','\\\\');
        $this->ioclang = (empty($params['ioclanguage']))?'CA':strtoupper($params['ioclanguage']);
        $this->ioclanguages = array('CA' => 'catalan', 'DE' => 'german', 'EN' => 'english','ES' => 'catalan','FR' => 'frenchb','IT' => 'italian');
        $this->ioclangcontinue = array('CA' => 'continuació', 'DE' => 'fortsetzung', 'EN' => 'continued','ES' => 'continuación','FR' => 'suite','IT' => 'continua');
        $this->log = isset($params['log']);
        $this->media_path = 'lib/exe/fetch.php?media=';
        $this->meta_params = array('autoria', 'ciclenom', 'creditcodi', 'creditnom', 'familia', 'coordinacio', 'coverimage', 'backcovertext');
        $this->hasLatexInstructions = array('autoria' => FALSE, 'ciclenom' => FALSE, 'creditcodi' => FALSE, 'creditnom' => FALSE, 'familia' => FALSE, 'coordinacio' => FALSE, 'coverimage' => FALSE, 'backcovertext' => TRUE);
        $this->meta_option = 'opcions';
        $this->mode = $params['mode'];
        $this->tmp_dir = '';
        $this->toexport = explode(',', preg_replace('/:index(,|$)/',',',$params['toexport']));
        $this->unitzero = FALSE;
        $this->permissionToExport=FALSE;
        $this->user = $params['user'];
        $this->groups = $USERINFO['grps'];
        $this->fpd = FALSE;
        $this->needReturnData = isset($params['needReturnData']);        
        $this->formByColumns = isset($params['form_by_columns']);        
        $this->returnData=NULL;
    }

    /**
     *
     * Exportation to pdf or zip
     */
    public function init(){
        global $conf;

        if (!$this->export_ok) return FALSE;
        if (!$this->log && !$this->checkPerms()) return FALSE;
        $this->permissionToExport = $this->hasUserPermissionToExport(
                    $conf['plugin']['iocexportl']['UsersWithPdfSelf-generationAllowed']
                );
        $this->exportallowed = isset($conf['plugin']['iocexportl']['allowexport']);
        if (!$this->log && !$this->exportallowed 
                                && !$this->permissionToExport) return FALSE;
        if (!$this->log && !$this->permissionToExport 
                                && $params['mode'] === 'zip') return FALSE;

        $this->time_start = microtime(TRUE);

        $output_filename = str_replace(':','_',$this->id);
        if (file_exists(DOKU_IOCEXPORTL_TEMPLATES.'header.ltx')){
            //read header
            $latex = io_readFile(DOKU_IOCEXPORTL_TEMPLATES.'header.ltx');
            session_start();
            $this->tmp_dir = rand();
            $_SESSION['tmp_dir'] = $this->tmp_dir;
            if (!file_exists(DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir)){
                mkdir(DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir, 0775, TRUE);
                mkdir(DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir.'/media', 0775, TRUE);
            }
            if (!$this->log && !$this->permissionToExport){
                $latex .= '\draft{Provisional}' . DOKU_LF;
                $_SESSION['draft'] = TRUE;
            }
            if (!file_exists(DOKU_IOCEXPORTL_TEMPLATES.'frontpage.ltx')){
                session_destroy();
                return FALSE;
            }
            //get all pages and activitites
            $data = $this->getData();

            //FrontPage
            $this->renderFrontpage($latex, $data);
            $latex .= '\frontpageparskip'.DOKU_LF;
            $_SESSION['createbook'] = TRUE;
            //Sets default language
            $this->ioclang = preg_replace('/\n/', '', $this->ioclang);
            $language = $this->ioclanguages[$this->ioclang];
            $latex = preg_replace('/@IOCLANGUAGE@/', $language, $latex, 1);
            $latex = preg_replace('/@IOCLANGCONTINUE@/', $this->ioclangcontinue[$this->ioclang], $latex, 1);
            //Render a non unit zero
            if (!$this->unitzero){
                $_SESSION['chapter'] = 1;
                //Intro
                foreach ($data[0]['intro'] as $page){
                    $text = io_readFile(wikiFN($page));
                    $instructions = get_latex_instructions($text);
                    $latex .= p_latex_render('iocexportl', $instructions, $info);
                }
                //Content
                foreach ($data[0]['pageid'] as $page){
                    //Check whether this page has to be exported
                    if (!in_array($page, $this->toexport)){
                        continue;
                    }
                    $text = io_readFile(wikiFN($page));
                    $instructions = get_latex_instructions($text);
                    $latex .= p_latex_render('iocexportl', $instructions, $info);
                    //render activities
                    if (array_key_exists($page, $data[0]['activities'])){
                        $_SESSION['activities'] = TRUE;
                        foreach ($data[0]['activities'][$page] as $act){
                            //Check whether this page has to be exported
                            if (!in_array($act, $this->toexport)){
                                continue;
                            }
                            $text = io_readFile(wikiFN($act));
                            $instructions = get_latex_instructions($text);
                            $latex .= p_latex_render('iocexportl', $instructions, $info);
                        }
                        $_SESSION['activities'] = FALSE;
                    }
                }
            }else{//Render unit zero
                $_SESSION['u0'] = TRUE;
                $text = io_readFile(wikiFN($this->id));
                $text = preg_replace('/(\={6} ?.*? ?\={6}\n{2,}\={5} [Mm]eta \={5}\n{2,}( {2,4}\* \*\*[^\*]+\*\*:.*\n?)+)/', '', $text);
                preg_match('/(?<=\={5} [Cc]redits \={5})\n+(.*?\n?)+(?=\={5} [Cc]opyright \={5})/', $text, $matches);
                if (isset($matches[0])){
                    $latex .= '\creditspacingline\creditspacingpar\scriptsize' . DOKU_LF;
                    $matches[0] = preg_replace('/^\n+/', '', $matches[0]);
                    $matches[0] = preg_replace('/\n{2,3}/', DOKU_LF.'@IOCBR@'.DOKU_LF, $matches[0]);
                    $instructions = get_latex_instructions($matches[0]);
                    $latex .= p_latex_render('iocexportl', $instructions, $info);
                    $latex = preg_replace('/@IOCBR@/', '\par\vspace{2ex} ', $latex);
                    $text = preg_replace('/(\={5} [Cc]redits \={5}\n{2,}(.*?\n?)+)(?=\={5} [Cc]opyright \={5})/', '', $text);
                    preg_match('%(?<=\={5} [Cc]opyright \={5})\n+(.*?\n?)+(?=//|\={6})%', $text, $matches);
                    if (isset($matches[0])){
                        $matches[0] = preg_replace('/\n{2,3}/', DOKU_LF.'@IOCBR@'.DOKU_LF, $matches[0]);
                        $latex .= '\vfill'.DOKU_LF;
                        $instructions = get_latex_instructions($matches[0]);
                        $latex .= p_latex_render('iocexportl', $instructions, $info);
                        $latex = preg_replace('/@IOCBR@/', '\par\vspace{2ex} ', $latex);
                        $text = preg_replace('%\={5} [Cc]opyright \={5}\n+(.*?\n?)+(?=//|\={6})%', '', $text);
                        preg_match('/(.*?\n?)+(?=\={6} [Ii]ntro)/', $text, $matches);
                        if (isset($matches[0])){
                            $latex .= '\creditspacingline\creditspacingpar\tiny\par\vspace{2ex}\vspace{2ex}'.DOKU_LF.DOKU_LF;
                            $matches[0] = preg_replace('/(http.*)/', DOKU_LF.DOKU_LF.'$1', $matches[0]);
                            $matches[0] = preg_replace('/\n{2,3}/', DOKU_LF.'@IOCBR@'.DOKU_LF, $matches[0]);
                            $instructions = get_latex_instructions($matches[0]);
                            $latex .= p_latex_render('iocexportl', $instructions, $info);
                            $latex = preg_replace('/@IOCBR@/', '\par\vspace{2ex} ', $latex);
                            $text = preg_replace('/([^=]+)(?=\={6} [Ii]ntro)/', '', $text);
                        }
                    }
                }
                $latex .= '\restoregeometry' . DOKU_LF;
                $latex .= '\defaultspacingpar\defaultspacingline' . DOKU_LF;
                $latex .= '\normalfont\normalsize' . DOKU_LF;
                $instructions = get_latex_instructions($text);
                $latex .= p_latex_render('iocexportl', $instructions, $info);
            }
            //replace IOCQRCODE
            $qrcode = '';
            if ($_SESSION['qrcode']){
                $qrcode = '\usepackage{pst-barcode,auto-pst-pdf}';
            }
            $latex = preg_replace('/@IOCQRCODE@/', $qrcode, $latex, 1);
            session_destroy();
            //Footer
            if (file_exists(DOKU_IOCEXPORTL_TEMPLATES.'footer.ltx')){
                $latex .= io_readFile(DOKU_IOCEXPORTL_TEMPLATES.'footer.ltx');
            }
        }
        $result = array();
        if ($this->mode === 'zip'){
            $this->createZip($output_filename,DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir,$latex, $result);
        }else{
            $this->createLatex($output_filename, DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir, $latex, $result);
        }
        if(!$conf['plugin']['iocexportl']['saveWorkDir']){
            $this->removeDir(DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir);
        }
        if($this->log){
            return $result;
        }
        if($this->needReturnData){
            return $this->returnData;
        }            
    }
    
    private function renderCoverPage(&$latex, $frontCover, $bacground='', $extraData=NULL){
        $latex .= io_readFile(DOKU_IOCEXPORTL_TEMPLATES . $frontCover);
        $latex = preg_replace('/@IOC_BACKGROUND_FILENAME@/', "media/".$bacground, $latex);
        $latex = preg_replace('/@IOC_COVER_IMAGE@/', $this->coverImage, $latex);
        if(isset($extraData)){
            foreach ($extraData as $key => $value) {
                $latex = preg_replace($key, $value, $latex);
            }            
        }
        if($bacground){
            $this->copyToTmp(DOKU_IOCEXPORTL_TEMPLATES . $bacground.".pdf", "media/".$bacground.".pdf");
        }
    }

    /**
     *
     * Render frontpage
     * @param string $latex
     * @param array $data
     */
    private function renderFrontpage(&$latex, $data){

         if ($this->fpd) {
            $filename = 'backgroundfpd';
            if ($this->unitzero) {
                if($this->bCoverPage){
                    $this->renderCoverPage($latex, 
                                    'frontCoverFpd.ltx', 
                                    'backgroundcfpd',
                                    array("/@BACK_COVER_TEXT@/" 
                                            => trim($data[1]["backcovertext"])));
                }else{
                    $this->renderCoverPage($latex, 'frontNoCover.ltx');
                }
                $latex .= io_readFile(DOKU_IOCEXPORTL_TEMPLATES . 'frontpagefpd_u0.ltx');
                $latex = preg_replace('/@IOC_EXPORT_FAMILIA@/', trim($data[1]['familia']), $latex);
                $coordinacio = explode(',', $data[1]['coordinacio']);
                $latex = preg_replace('/@IOC_EXPORT_COORDINACIO@/', implode('\\\\\\\\', $coordinacio), $latex);
                $latex = preg_replace('/@IOC_EXPORT_CREDIT@/', clean_reserved_symbols($data[1]['creditcodi']), $latex);
            } else {
                $latex .= io_readFile(DOKU_IOCEXPORTL_TEMPLATES . 'frontpagefpd.ltx');
                $data[1]['nomcomplert'] = preg_replace('/\'/','{\textquotesingle}', $data[1]['nomcomplert']);
                $data[1]['nomcomplert'] = str_replace($this->ini_characters, $this->end_characters, $data[1]['nomcomplert']);
                $latex = preg_replace('/@IOC_EXPORT_NOMCOMPLERT@/', trim($data[1]['nomcomplert']), $latex);
            }
            $latex = preg_replace('/@IOC_BACKGROUND_FILENAME@/', $filename, $latex);
            $latex = preg_replace('/@IOC_EXPORT_CICLENOM@/', trim($data[1]['ciclenom']), $latex);
            $autoria = explode(',', $data[1]['autoria']);
            $latex = preg_replace('/@IOC_EXPORT_AUTOR@/', implode('\\\\\\\\', $autoria), $latex);
            $header_creditnom = str_replace($this->ini_characters, $this->end_characters, $data[1]['creditnom']);
            $latex = preg_replace('/@IOC_EXPORT_CREDIT_F@/', strtoupper($header_creditnom), $latex);
            $latex = preg_replace('/@IOC_EXPORT_CREDIT@/', $header_creditnom, $latex);
            $header_nomcomplert = str_replace($this->ini_characters, $this->end_characters, $data[1]['nomcomplert']);
            $latex = preg_replace('/@IOC_EXPORT_NOMCOMPLERT_H@/', trim(wordwrap($header_nomcomplert,77,'\break ')), $latex);
        } else if ($this->unitzero){
            if($this->bCoverPage){
                $this->renderCoverPage($latex, 'frontCoverFp.ltx', 'backgroundcfp');
            }else{
                $this->renderCoverPage($latex, 'frontNoCover.ltx');                
            }
            $filename = 'backgroundu0';
            $latex .= io_readFile(DOKU_IOCEXPORTL_TEMPLATES.'frontpage_u0.ltx');
            if ($_SESSION['double_cicle']){
                $filename .= 'dc';
                $latex = preg_replace('/@IOC_HEIGHT_CICLENOM@/', '20', $latex, 1);
            }else{
                $latex = preg_replace('/@IOC_HEIGHT_CICLENOM@/', '10', $latex, 1);
            }
            $latex = preg_replace('/@IOC_BACKGROUND_FILENAME@/', $filename, $latex);
            $latex = preg_replace('/@IOC_EXPORT_FAMILIA@/', $data[1]['familia'], $latex);
            if (preg_match('/administraci/i', $data[1]['familia'])){
                $family = 0;
            }elseif (preg_match('/electricitat/i', $data[1]['familia'])){
                $family = 1;
            }elseif (preg_match('/socioculturals/i', $data[1]['familia'])){
                $family = 2;
            }elseif (preg_match('/comunicacions/i', $data[1]['familia'])){
                $family = 3;
            }elseif (preg_match('/seguretat/i', $data[1]['familia'])){
                $family = 4;
            }else{
                $family = 5;
            }
            copy(DOKU_PLUGIN.'iocexportl/templates/'.$this->img_pref.$this->img_src[$family], DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir.'/media/'.$this->img_pref.$this->img_src[$family]);
            $latex = preg_replace('/@IOC_EXPORT_IMGFAMILIA@/', 'media/'.$this->img_pref.$this->img_src[$family], $latex);
            //Two titles
            if(preg_match('/\\\\/',$data[1]['nomcomplert'])){
                $twotitles = preg_replace('/\\\\\\\\/', '\\twotitles', $data[1]['nomcomplert']);
                $twotitles = preg_replace('/\((\w+)\)/', '\\frontpagefamily{$1}', $twotitles);
                $latex = preg_replace('/@IOC_EXPORT_NOMCOMPLERT@/', '\\fonttwofamilies'.DOKU_LF. trim($twotitles), $latex);
            }else{
                $latex = preg_replace('/@IOC_EXPORT_NOMCOMPLERT@/', trim($data[1]['nomcomplert']), $latex);
            }
            if (isset($twotitles)){
                $data[1]['nomcomplert'] = preg_replace('/\\\\\\\\/', '\\break', $data[1]['nomcomplert']);
            }
            $data[1]['nomcomplert'] = preg_replace('/\'/','{\textquotesingle}', $data[1]['nomcomplert']);
            $data[1]['nomcomplert'] = str_replace($this->ini_characters, $this->end_characters, $data[1]['nomcomplert']);
            $latex = preg_replace('/@IOC_EXPORT_NOMCOMPLERT_H@/', trim(wordwrap($data[1]['nomcomplert'],77,'\break ')), $latex);
            $latex = preg_replace('/@IOC_EXPORT_CREDIT@/', $data[1]['creditcodi'], $latex);
            $header_ciclenom = str_replace($this->ini_characters, $this->end_characters, $data[1]['ciclenom']);
            $latex = preg_replace('/@IOC_EXPORT_CICLENOM@/', $header_ciclenom, $latex);

        } else {
            $filename = 'background';
            $latex .= io_readFile(DOKU_IOCEXPORTL_TEMPLATES.'frontpage.ltx');
            if ($_SESSION['double_cicle']){
                $filename .= 'dc';
                $latex = preg_replace('/@IOC_HEIGHT_CICLENOM@/', '20', $latex, 1);
            }else{
                $latex = preg_replace('/@IOC_HEIGHT_CICLENOM@/', '10', $latex, 1);
            }
            $latex = preg_replace('/@IOC_BACKGROUND_FILENAME@/', $filename, $latex);
            $data[1]['nomcomplert'] = preg_replace('/\'/','{\textquotesingle}', $data[1]['nomcomplert']);
            $data[1]['nomcomplert'] = str_replace($this->ini_characters, $this->end_characters, $data[1]['nomcomplert']);
            $latex = preg_replace('/@IOC_EXPORT_NOMCOMPLERT@/', trim($data[1]['nomcomplert']), $latex);
            $header_nomcomplert = str_replace($this->ini_characters, $this->end_characters, $data[1]['nomcomplert']);
            $latex = preg_replace('/@IOC_EXPORT_NOMCOMPLERT_H@/', trim(wordwrap($header_nomcomplert,77,'\break ')), $latex);
            $latex = preg_replace('/@IOC_EXPORT_AUTOR@/', $data[1]['autoria'], $latex, 1);
            if (!isset($data[1]['extra'])){
                $data[1]['extra'] = '';
            }
            $latex = preg_replace('/@IOC_EXPORT_EXTRA@/', $data[1]['extra'], $latex, 1);
            $header_creditnom = str_replace($this->ini_characters, $this->end_characters, $data[1]['creditnom']);
            $latex = preg_replace('/@IOC_EXPORT_CREDIT@/', $header_creditnom, $latex);
        }
    }

    /**
     *
     * Compile latex document to create a pdf file
     * @param string $filename
     * @param string $path
     * @param string $text
     */
    private function createLatex($filename, $path, &$text, &$result){
        //Replace media relative URI's for absolute URI's
        $text = preg_replace('/\{media\//', '{'.$path.'/media/', $text);
        io_saveFile($path.'/'.$filename.'.tex', $text);
        $shell_escape = '';
        if ($_SESSION['qrcode']){
            $shell_escape = '-shell-escape';
        }
        @exec('cd '.$path.' && pdflatex -draftmode '.$shell_escape.' -halt-on-error ' .$filename.'.tex' , $sortida, $return);
        if ($return === 0){
            //One more to calculate correctly size tables
            @exec('cd '.$path.' && pdflatex -draftmode '.$shell_escape.' -halt-on-error ' .$filename.'.tex' , $sortida, $return);
            if ($_SESSION['onemoreparsing']){
                @exec('cd '.$path.' && pdflatex -draftmode '.$shell_escape.' -halt-on-error ' .$filename.'.tex' , $sortida, $return);
            }
            @exec('cd '.$path.' && pdflatex '.$shell_escape.' -halt-on-error ' .$filename.'.tex' , $sortida, $return);
        }
        if ($return !== 0){
            $this->getLogError($path, $filename, $result);
        }else{
            $this->returnData($path, $filename.'.pdf', 'pdf', $result);
        }
    }

    /**
     *
     * Returns pdf/zip file info
     * @param string $path
     * @param string $filename
     * @param string $type
     */
    private function returnData($path, $filename, $type, &$result=NULL){
        global $conf;

        if (file_exists($path.'/'.$filename)){
            $error = '';
            //Return pdf number pages
            if ($type === 'pdf'){
                $num_pages = @exec("pdfinfo " . $path . "/" . $filename . " | awk '/Pages/ {print $2}'");
            }
            $filesize = filesize($path . "/" . $filename);
            $filesize = filesize_h($filesize);
            $dest = preg_replace('/:/', '/', $this->id);
            $dest = dirname($dest);
            if (!file_exists($conf['mediadir'].'/'.$dest)){
                mkdir($conf['mediadir'].'/'.$dest, 0755, TRUE);
            }
            $filename_dest = ($this->log || $this->permissionToExport)?$filename:basename($filename, '.'.$type).'_draft.'.$type;
            //Replace log extension to txt, and show where error is
            if ($type === 'log'){
                $filename_dest = preg_replace('/\.log$/', '.txt', $filename_dest, 1);
                $error = io_grep($path.'/'.$filename, '/^!/', 1);
                $line = io_grep($path.'/'.$filename, '/^l.\d+/', 1);
                preg_match('/\d+/', $line[0], $matches);
                $error = preg_replace('/!/', '('.$matches[0].') ', $error);
            }
            copy($path.'/'.$filename, $conf['mediadir'].'/'.$dest .'/'.$filename_dest);
            $dest = preg_replace('/\//', ':', $dest);
            $time_end = microtime(TRUE);
            $time = round($time_end - $this->time_start, 2);
            setlocale(LC_TIME, 'ca_ES.utf8');
            $dateFile = strftime("%e %B %Y %T", filemtime($path.'/'.$filename));
            if($this->log){
                if($type === 'log'){
                    $num_pages = 'E';
                }
                $result = array('time' => $dateFile, 'path' => $dest.':'.$filename_dest, 'pages' => $num_pages, 'size' => $filesize);
            }else{
                if ($type === 'pdf'){
                    $data = array($type, $this->media_path.$dest.':'.$filename_dest.'&time='.gettimeofday(TRUE), $filename_dest, $filesize, $num_pages, $time, $dateFile, $this->formByColumns);
                }else{
                    $data = array($type, $this->media_path.$dest.':'.$filename_dest.'&time='.gettimeofday(TRUE), $filename_dest, $filesize, $time, $error, $dateFile, $this->formByColumns);
                }
            }
        }else{
            $result = 'Error en la creació del arixu: ' . $filename;
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
     * Create a zip file with tex file and all media files
     * @param string $filename
     * @param string $path
     * @param string $text
     */
    private function createZip($filename, $path, &$text){

        $zip = new ZipArchive;
        $res = $zip->open($path.'/'.$filename.'.zip', ZipArchive::CREATE);
        if ($res === TRUE) {
            $zip->addFromString($filename.'.tex', $text);
            $zip->addEmptyDir('media');
            $files = array();
            $this->getFiles($path.'/media', $files);
            foreach($files as $f){
                $zip->addFile($f, 'media/'.basename($f));
            }
            $zip->close();
            $this->returnData($path, $filename.'.zip', 'zip');
        }else{
            $this->getLogError($filename);
        }
    }

    /**
     *
     * Returns log file on latex compilation
     * @param string $path
     * @param string $filename
     */
    private function getLogError($path, $filename, &$return=array()){
        $output = array();

        if($this->log || auth_isadmin()){
            $this->returnData($path, $filename.'.log', 'log', $return);
        }else{
            @exec('tail -n 20 '.$path.'/'.$filename.'.log;', $output);
            io_saveFile($path.'/'.filename.'.log', implode(DOKU_LF, $output));
            $this->returnData($path, $filename.'.log', 'log', $return);
        }
    }

    /**
     *
     * Fill files var with all media files stored on directory var
     * @param string $directory
     * @param string $files
     */
    private function getFiles($directory, &$files){

        if(!file_exists($directory) || !is_dir($directory)) {
                return FALSE;
        } elseif(!is_readable($directory)) {
            return FALSE;
        } else {
            $directoryHandle = opendir($directory);
            while ($contents = readdir($directoryHandle)) {
                if($contents != '.' && $contents != '..') {
                    //Extensions allowed
                    if (preg_match('/.*?\.pdf|.*?\.png|.*?\.jpg/', $contents)){
                        $path = $directory . "/" . $contents;
                        if(!is_dir($path)) {
                            array_push($files, $path);
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
   private  function checkPerms() {
        global $ID;
        global $USERINFO;
        $ID = getID();
        $user = $_SERVER['REMOTE_USER'];
        $groups = $USERINFO['grps'];
        $aclLevel = auth_aclcheck($ID,$user,$groups);
        // AUTH_ADMIN, AUTH_READ,AUTH_EDIT,AUTH_CREATE,AUTH_UPLOAD,AUTH_DELETE
        return ($aclLevel >=  AUTH_UPLOAD);
      }
      
    private function hasUserPermissionToExport($userWIthPermission){
        $return = auth_isadmin();
        if(!$return){
            $aclLevel = auth_aclcheck($this->id,$this->user,$this->groups);             
            $pattern = '/'.$this->user.'(?:\b)/';
            $selfGenerationAllowed = (preg_match($pattern,$userWIthPermission)===1);
            $return = (($aclLevel >= AUTH_UPLOAD)&&($selfGenerationAllowed));
        }
        return $return;
    }   

    /**
     *
     * Fill data var with wiki pages using a customized structure
     * @param array $data
     * @param boolean $struct
     */
    private function getPageNames(&$data, $struct = FALSE){
        global $conf;

        $data['intro'] = array();
        $data['pageid'] = array();
        if (!$struct){
            $exists = FALSE;
            $file = wikiFN($this->id);
            if (@file_exists($file)) {
                $matches = array();
                $txt =  io_readFile($file);
                preg_match_all('/(?<=\={5} [T|t]oc \={5})\n+(\s{2,4}\*\s+\[\[[^\]]+\]\] *\n?)+/i', $txt, $matches);
                $pages = implode('\n', $matches[0]);
                //get exercises and activities
                $pages = $this->getActivities($data, $pages);
                preg_match_all('/\[\[([^|]+).*?\]\] */', $pages, $matches);
                $counter = 0;
                foreach ($matches[1] as $page){
                    resolve_pageid(getNS($this->id),$page,$exists);
                    if ($exists){
                        if ($counter < 2){
                            array_push($data['intro'], $page);
                        }else{
                            array_push($data['pageid'], $page);
                        }
                    }
                    $counter += 1;
                }
            }
        }else{
            $result = array();
            preg_match('/(\w+:)+pdf:\w+\b/', $this->id, $result);
            $ns = preg_replace('/:/' ,'/', $result[0]);
            search($result,$conf['datadir'],'search_index', null, $ns);
            foreach ($result as $pagename){
                if (is_array($pagename) && !preg_match('/:imatges/', $pagename['id'])
                    && !preg_match('/:pdfindex/', $pagename['id'])){
                    if (preg_match('/:introduccio/', $pagename['id'])){
                        $data['intro'][0] = $pagename['id'];
                    }elseif (preg_match('/:objectius/', $pagename['id'])){
                        $data['intro'][1] = $pagename['id'];
                    }else{
                        array_push($data, $pagename['id']);
                    }
                }
            }
        }
    }

    /**
     *
     * Fill data var with activities and return pages without it
     * @param array $data
     * @param string $pages
     */
    private function getActivities(&$data, $pages){

        $matches = array();
        $data['activities'] = array();
        //return all pages with activities
        preg_match_all('/\s{2}\*\s+\[\[.*?\]\]\n(\s{4}\*\s+\[\[.*?\]\] *\n?)+/', $pages, $matches);
        foreach ($matches[0] as $match){
            //return page namespace
            preg_match('/\s{2}\*\s+\[\[([^|]+).*?\]\]/', $match, $ret);
            if (!isset($ret[1])){
                continue;
            }else{
                $masterid = $ret[1];
                resolve_pageid(getNS($this->id),$masterid,$exists);
                //return all activities for active page
                preg_match_all('/\s{4}\*\s+\[\[([^|]+).*?\]\]/', $match, $ret);
                foreach ($ret[1] as $r){
                    if (!isset($data['activities'][$masterid])){
                        $data['activities'][$masterid] = array();
                    }
                    array_push($data['activities'][$masterid], $r);
                }
            }
        }
        //remove activities and exercises
        $pages = preg_replace('/    \*\s+\[\[.*?\]\]\n?/', '', $pages);
        return $pages;
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
            $info = io_grep($file, '/(?<=\={6} )[^=]*/', 0, TRUE);
            $data[1]['nomcomplert'] = $info[0][0];
            $text = io_readFile($file);
            $info = array();
            preg_match_all('/(?<=\={5} [M|m]eta \={5}\n\n)\n*( {2,4}\* \*\*.*?\*\*:.*\n?)+/', $text, $info, PREG_SET_ORDER);
            if (!empty($info[0][0])){
                $text = $info[0][0];
                preg_match_all('/ {2,4}\* (\*\*(.*?)\*\*:)(.*)/m', $text, $info, PREG_SET_ORDER);
                foreach ($info as $i){
                    $key = trim($i[2]);
                    if (preg_match('/'.$key.'/i', $this->meta_option)){
                        $options = explode(',', $i[3]);
                        foreach ($options as $option) {
                            switch (trim($option)) {
                                //Double cicle name
                                case 'dcicle':
                                    $_SESSION['double_cicle'] = TRUE;
                                    break;
                                //Avoid removing numeration on first two chapters
                                case 'nointro':
                                    $_SESSION['introbook'] = FALSE;
                                    break;
                                case 'fpd':
                                    $_SESSION['fpd'] = TRUE;
                                    $this->fpd = TRUE;
                                    break;
                            }
                        }
                        continue;
                    }
                    if (in_array($key, $this->meta_params)){
                        if($this->hasLatexInstructions[$key]){
                            $instructions = get_latex_instructions(trim($i[3]));
                            $content = p_latex_render('iocexportl', $instructions, $inf);
                        }else{
                            $content = trim($i[3]);
                        }
                        $data[1][$key] = $content;
                    }else{
                        $instructions = get_latex_instructions(trim($i[1].$i[3]));
                        $latex = p_latex_render('iocexportl', $instructions, $inf);
                        $data[1]['extra'] = $latex;
                    }
                }
            }
            //get page names
            if (key_exists('familia', $data[1])){
                $this->unitzero = TRUE;
                if(key_exists('coverimage', $data[1])){
                    $this->bCoverPage=TRUE;
                    $image = $this->getPathImage($data[1]['coverimage']);
                    $this->coverImage = 'media'.strrchr($image, '/');
                    $this->copyToTmp($image, $this->coverImage);
                }
            }else{
                $this->getPageNames($data[0]);
            }
            return $data;
        }
        return FALSE;
    }
    
    private function getPathImage($wikiLink){
        preg_match('/\{\{([^}|?]+)[^}]*\}\}/',$wikiLink, $matches);
        resolve_mediaid(getNS($matches[1]),$matches[1],$exists);
        return mediaFN($matches[1]);
    }
    
    private function copyToTmp($image, $targetName){ 
        $dest = DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir."/".$targetName;
        copy($image, $dest);
    }

    public function isDenied() {
        return FALSE;
    }

    /**
     * Wrapper around msg() but outputs only when debug is enabled
     *
     * @param string $message
     * @param int    $err
     * @param int    $line
     * @param string $file
     * @return void
     */
    protected function _debug($message, $err, $line, $file, $level=1) {
        if (!defined('DOKU_TMP_LOG')) define('DOKU_TMP_LOG',DOKU_INC.'lib/plugins/tmp/debug.log');
        if($this->getConf('debug')<$level) return;
        msg($message, $err, $line, $file);
        $tag = $err===0?"Info: ":"Error($err): ";
        file_put_contents(DOKU_TMP_LOG, "$tag\"$message\" ($file:$line)\n", FILE_APPEND);
    }
    
    protected function getConf($key){
        return WikiGlobalConfig::getConf($key, "iocexportl");
    }
}
