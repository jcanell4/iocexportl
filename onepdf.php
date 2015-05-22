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

//Initialize params
$params = array();
$params['id'] = getID();
$params['mode'] = $_POST['mode'];
if ($params['id'] === $_POST['id']){
    $params['ioclanguage'] = $_POST['ioclanguage'];
    $params['user'] = $_SERVER['REMOTE_USER'];
    $generate = new generate_latex($params);
    $generate->init();
}


class generate_latex implements WikiIocModel{

    private $end_characters;
    private $exportallowed;
    private $export_ok;
    private $id;
    private $currentNs;
    private $log;
    private $needReturnData;
    private $returnData;    
    private $media_path;
    private $mode;
    private $tmp_dir;
    private $permissionToExport;
    private $user;
    private $groups;
    private $time_start;


    /**
    * Default Constructor
    *
    * Initialize variables
    *
    * @param array $params Array of parameters to pass to the constructor
    */
    function __construct($params){
        if($params){
            $this->initParams($params);
        }
    }
    
    public function initParams($params){                
        global $USERINFO;

        //Due listings problems whith header it's necessary to replace extended characters
        $this->end_characters = array("\'{a}", "\'{e}", "\'{i}", "\'{o}", "\'{u}", "\`{a}", "\`{e}", "\`{o}", '\"{i}', '\"{u}', '\~{n}', '\c{c}', "\'{A}", "\'{E}", "\'{I}", "\'{O}", "\'{U}", "\`{A}", "\`{E}", "\`{O}", '\"{I}', '\"{U}', '\~{N}', '\c{C}','\break ');
        $this->exportallowed = FALSE;        
        $this->export_ok = ($params['mode'] === 'pdf' || $params['mode'] === 'zip' );
        $this->id = $params['id'];
        $this->currentNs = getNS($this->id);
        $this->log = isset($params['log']);
        $this->media_path = 'lib/exe/fetch.php?media=';
        $this->mode = $params['mode'];
        $this->tmp_dir = '';
        $this->permissionToExport=FALSE;
        $this->user = $params['user'];
        $this->groups = $USERINFO['grps'];
        $this->needReturnData = isset($params['needReturnData']);        
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
        
        
        
        if (file_exists(DOKU_IOCEXPORTL_TEMPLATES.'onePdfHeader.ltx')
                && file_exists(DOKU_IOCEXPORTL_TEMPLATES.'onePdfFrontPage.ltx')
                && file_exists(DOKU_IOCEXPORTL_TEMPLATES.'onePdfFooter.ltx')){
            //read header
            $latex = io_readFile(DOKU_IOCEXPORTL_TEMPLATES.'onePdfHeader.ltx');
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
            //get all pdfs
            $data = $this->getData();

            //FrontPage
            $latexPattern = io_readFile(DOKU_IOCEXPORTL_TEMPLATES . 'onePdfFrontPage.ltx');
            
            foreach ($data as $i){
                $latex .= $latexPattern;
                $latex = preg_replace('/@PDF_NAME@/', trim($i), $latex);
            }
            
            //Footer
            $latex .= io_readFile(DOKU_IOCEXPORTL_TEMPLATES.'onePdfFooter.ltx');
        }
        if ($this->mode === 'zip'){
            $this->createZip($output_filename,DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir,$latex);
        }else{
            $result = array();
            $this->createLatex($output_filename, DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir, $latex, $result);
        }
        $this->removeDir(DOKU_IOCEXPORTL_LATEX_TMP.$this->tmp_dir);
        if($this->log){
            return $result;
        }
        if($this->needReturnData){
            return $this->returnData;
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
            if($this->log){
                if($type === 'log'){
                    $num_pages = 'E';
                }
                setlocale(LC_TIME, 'ca_ES.utf8');
                $result = array('time' => strftime("%e %B %Y %T", filemtime($path.'/'.$filename)), 'path' => $dest.':'.$filename_dest, 'pages' => $num_pages, 'size' => $filesize);
            }else{
                if ($type === 'pdf'){
                    $data = array($type, $this->media_path.$dest.':'.$filename_dest.'&time='.gettimeofday(TRUE), $filename_dest, $filesize, $num_pages, $time);
                }else{
                    $data = array($type, $this->media_path.$dest.':'.$filename_dest.'&time='.gettimeofday(TRUE), $filename_dest, $filesize, $time, $error);
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
    private function getLogError($path, $filename, &$return=NULL){

        $output = array();

        if($this->log || auth_isadmin()){
            $this->returnData($path, $filename.'.log', 'log', $return);
        }else{
            @exec('tail -n 20 '.$path.'/'.$filename.'.log;', $output);
            io_saveFile($path.'/'.filename.'.log', implode(DOKU_LF, $output));
            $this->returnData($path, $filename.'.log', 'log');
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
     * Get and return uri wiki pages
     */
    private function getData(){
        //recull tots el pdfs que formen el mòdul

        $data = array();
        $file = wikiFN($this->id);
        $inf = NULL;
        //{{:fp:dam:m03:fp_dam_m03_pdfindex.pdf|Presentació}}
        if (@file_exists($file)) {
            $info = io_grep($file, '/(?<=\{\{)[^\}]+(?=\|.*\}\})|(?<=\{\{)[^\}]+(?=\}\})/', 0, TRUE);
            foreach ($info as $i){
                $data[] = mediaFN(resolve_id($this->currentNs, $i[0]));
            }
            return $data;
        }
        return FALSE;
    }

    public function isDenied() {
        return FALSE;
    }

}
