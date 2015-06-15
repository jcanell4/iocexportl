<?php
/**
 * Action Plugin:   iocexportl.
 * @license    GPL (http://www.gnu.org/licenses/gpl.html)
 * @author     Marc Català 		<mcatala@ioc.cat>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
if (!defined('DOKU_IOCEXPORTL_LIB')) define('DOKU_IOCEXPORTL_LIB',DOKU_PLUGIN.'iocexportl/lib/');
if (!defined('DOKU_IOCEXPORTL_COMMANDS')) define('DOKU_IOCEXPORTL_COMMANDS',DOKU_PLUGIN.'iocexportl/commands/');

require_once(DOKU_PLUGIN.'action.php');

class action_plugin_iocexportl extends DokuWiki_Action_Plugin{
    const DATA_TYPE = 0;
    const DATA_MEDIAPATH = 1;
    const DATA_FILENAME = 2;
    const DATA_SIZE = 3;
    const DATA_TIME = 4;
    const DATA_ERROR = 5;
    const DATA_DATE = 6;
    const DATA_INPUT_BUTTON = 7;
    const DATA_PAGEID = 8;
    const DATA_IOCLANGUAGE = 9;
    const DATA_FORM_URL = 10;
    const DATA_URL_FILE_CLASS = 11;
    const DATA_HAS_PDF_RADIO = 12;
    const DATA_HAS_ZIP_RADIO = 13;
    const DATA_HAS_ZIP_HIDDEN = 14;
    const DATA_IS_ZIP_RADIO_CHECKED = 15;

    var $exportallowed = FALSE;
    var $id = '';
    var $language = 'CA';

    function register(&$controller) {
        global $ACT;
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER', $this, 'handle_dokuwiki_started');
        if ($ACT === 'show' || (is_array($ACT) && $ACT['preview'])){
            $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'handle_tpl_metaheader_output');
            $controller->register_hook('TPL_ACT_RENDER', 'BEFORE', $this, 'getLanguage', array());
            $controller->register_hook('TPL_ACT_RENDER', 'AFTER', $this, 'showform', array());
        }
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'ioctoolbar_buttons', array ());
        $controller->register_hook('ADD_TPL_CONTROLS', "AFTER", $this, "addWikiIocButtons", array());
        $controller->register_hook('ADD_TPL_CONTROL_SCRIPTS', "AFTER", $this, "addControlScripts", array());
        $controller->register_hook('WIOC_PROCESS_RESPONSE_page', "AFTER", $this, "setExtraState", array());
        $controller->register_hook('CALLING_EXTRA_COMMANDS', "AFTER", $this, "addCommands", array());
    }
    
    public function handle_dokuwiki_started(Doku_Event &$event, $param) {
        global $JSINFO;

        $JSINFO['plugin_iocexportl'] = array(
            'toccontents' => $this->getConf('toccontents'),
        );
    }

    public function handle_tpl_metaheader_output(Doku_Event &$event, $param) {
        global $conf;

        $this->exportallowed = (isset($conf['plugin']['iocexportl']['allowexport']) && $conf['plugin']['iocexportl']['allowexport']);

        $this->link_script($event, DOKU_BASE.'lib/plugins/iocexportl/lib/mediaScript.js');

        if (!$this->has_jquery()) {
            $this->link_script($event, 'http://code.jquery.com/jquery.min.js');
            $this->include_script($event, 'jQuery.noConflict();');
        }
        
        if ($this->isExportPage() && $this->checkPerms() && $this->showcounts()){
            $this->link_script($event, DOKU_BASE.'lib/plugins/iocexportl/lib/counter.js');
        }
        if ($this->isExportPage() && ($this->exportallowed || auth_isadmin())){
            $this->link_script($event, DOKU_BASE.'lib/plugins/iocexportl/lib/chooser.js');
        }
        if (!$this->isExportPage()){
            $this->link_script($event, DOKU_BASE.'lib/plugins/iocexportl/lib/numbering.js');
            $this->link_script($event, DOKU_BASE.'lib/plugins/iocexportl/lib/quiz.js');
            $this->link_script($event, DOKU_BASE.'lib/plugins/iocexportl/lib/jquery.imagesloaded.js');
            $this->link_script($event, DOKU_BASE.'lib/plugins/iocexportl/lib/render.js');
        }

    }
    
    function setExtraState(&$event, $param){
        $this->getLanguage();
        $ret=TRUE;
        $formType = $this->getFormType("show");
        if ($formType==1){
            $strFromType = "exportPdf";
            $strForm = $this->getform_latex(FALSE);
        }elseif ($formType==2){
            $strFromType = "exportHtml";
            $strForm = $this->getform_html(FALSE);
        }elseif ($formType==3){
            $strFromType = "exportOnePdf";
            $strForm = $this->getform_onepdf(FALSE);
        }else{
            $ret = FALSE;
        }
        if($ret){
            $event->data["ajaxCmdResponseGenerator"]->addExtraContentStateResponse(
                $event->data["responseData"]["id"],
                "exportableType",
                $strFromType
                );
            $event->data["ajaxCmdResponseGenerator"]->addExtraMetadata(
                    $event->data["responseData"]["id"],
                    $event->data["responseData"]["id"]."_iocexportl",
                    "Propietats exportació",  //TODO [JOSEP] internacionalització
                    $strForm
                    );
            $event->data["ajaxCmdResponseGenerator"]->addProcessDomFromFunction(
                    $event->data["responseData"]["id"],
                    TRUE,
                    "ioc/dokuwiki/runChooser"
                    );
            $event->data["ajaxCmdResponseGenerator"]->addProcessDomFromFunction(
                    $event->data["responseData"]["id"],
                    TRUE,
                    "ioc/dokuwiki/runCounter"
                    );
            
        }
        return $ret;
    }


    function showform(&$event){
        $ret=TRUE;
        $formType = $this->getFormType($event->data);
        if ($formType==1){
            echo $this->getform_latex();
        }elseif ($formType==2){
            echo $this->getform_html();
        }elseif ($formType==3){
            echo $this->getform_onepdf();
        }else{
            $ret = FALSE;
        }
        return $ret;
    }
    
    function getFormType($data){
        global $conf;
        $ret = 0;

	$this->id = getID();
        $this->exportallowed = (isset($conf['plugin']['iocexportl']['allowexport']) && $conf['plugin']['iocexportl']['allowexport']);
        if (!$this->isExportPage()) return $ret;
        if ($data != 'show') return $ret;
        if (!$this->checkPerms()) return $ret;
        //Always admin can export
        if ($this->exportallowed || auth_isadmin()){
	        if (preg_match('/^(?!talk).*?:pdfindex$/', $this->id)){
                    $ret = 1;
	        }elseif (preg_match('/^(?!talk).*?:htmlindex$/', $this->id)){
                    $ret = 2;
	        }elseif (preg_match('/^(?!talk).*?:material_paper$/', $this->id)){
                    $ret = 3;
	        }
        }
        return $ret;        
    }

    public function has_jquery() {
        $version = getVersionData();
        $date = str_replace('-', '', $version['date']);
        return (int) $date > 20110525;
    }

    private function link_script($event, $url) {
        array_push($event->data['script'], array(
            'type' => 'text/javascript',
            'charset' => 'utf-8',
            'src' => $url,
        ));
    }

    private function include_script($event, $code) {
        $event->data['script'][] = array(
            'type' => 'text/javascript',
            'charset' => 'utf-8',
            '_data' => $code,
        );
    }

    function showcounts(){
        global $conf;
        $this->id = getID();
        if (!$this->isExportPage()){
            return FALSE;
        }
        $file = wikiFN($this->id);
        $bool = io_grep($file, '/~~NOCOUNT~~/', 1);
        $counter = (isset($conf['plugin']['iocexportl']['counter']) && $conf['plugin']['iocexportl']['counter']);
        return !$bool && $counter && preg_match('/^(?!talk).*?:(pdfindex|htmlindex)$/', $this->id, $matches);
    }

    function checkPerms() {
        global $ID;
        global $USERINFO;
        $ID    = getID();
        $user = $_SERVER['REMOTE_USER'];
        $groups = $USERINFO['grps'];
        $aclLevel = auth_aclcheck($ID,$user,$groups);
        // AUTH_ADMIN, AUTH_READ,AUTH_EDIT,AUTH_CREATE,AUTH_UPLOAD,AUTH_DELETE
        return ($aclLevel >=  AUTH_UPLOAD);
      }

    function isExportPage(){
        $this->id = getID();
        return preg_match('/^(?!talk).*?:(htmlindex|pdfindex|material_paper)$/', $this->id);
    }

    function getLanguage(){
        $this->id = getID();
        if (!$this->isExportPage()){
            return FALSE;
        }
        $file = wikiFN($this->id);
        $lang = io_grep($file, '/^~~(?:ca|de|en|es|fr|it)~~$/i', 1);
        if (isset($lang[0])){
            $lang = strtoupper($lang[0]);
            $this->language = preg_replace('/~/', '', $lang);
        }
    }
    
    function getform_onepdf($inputButton=TRUE){
        global $conf;
        $data = array();
        $data[self::DATA_TYPE]="zip";
        //$this->id = getID();

        $url = '';
        $path_filename = str_replace(':','/',$this->id);
        $filename = str_replace(':','_',basename($this->id)).'.pdf';
        $path_filename = $conf['mediadir'].'/'.dirname($path_filename).'/'.$filename;

        if (file_exists($path_filename)){
            $data[self::DATA_FILENAME]=$filename;
            $data[self::DATA_MEDIAPATH] = 'lib/exe/fetch.php?media='.str_replace('/', ':',dirname(str_replace(':','/',$this->id))).':'.$filename;            
            $data[self::DATA_DATE] = strftime("%e %B %Y %T", filemtime($path_filename));
        }
        $data[self::DATA_INPUT_BUTTON]=$inputButton;
        $data[self::DATA_PAGEID] = $this->id;
        $data[self::DATA_IOCLANGUAGE] = $this->language;
        $data[self::DATA_IS_ZIP_RADIO_CHECKED]=FALSE;
        $ret = $this->getform_onepdf_from_data($data);
        return $ret;        
    }

    function getform_onepdf_from_data($data){
       $data[self::DATA_FORM_URL] = "onepdf.php";
       $data[self::DATA_URL_FILE_CLASS] = "mf_pdf";
       $data[self::DATA_HAS_PDF_RADIO] = TRUE;
       $data[self::DATA_HAS_ZIP_RADIO] = auth_isadmin();
       $data[self::DATA_HAS_ZIP_HIDDEN] = FALSE;
       return self::getform_from_data($data);
    }

    function getform_latex($inputButton=TRUE){
       global $conf;
        $data = array();
        $data[self::DATA_TYPE]="zip";
        //$this->id = getID();

        $url = '';
        $path_filename = str_replace(':','/',$this->id);
        $filename = str_replace(':','_',basename($this->id)).'.pdf';
        $path_filename = $conf['mediadir'].'/'.dirname($path_filename).'/'.$filename;

        if (file_exists($path_filename)){
            $data[self::DATA_FILENAME]=$filename;
            $data[self::DATA_MEDIAPATH] = 'lib/exe/fetch.php?media='.str_replace('/', ':',dirname(str_replace(':','/',$this->id))).':'.$filename;            
            $data[self::DATA_DATE] = strftime("%e %B %Y %T", filemtime($path_filename));
        }
        $data[self::DATA_INPUT_BUTTON]=$inputButton;
        $data[self::DATA_PAGEID] = $this->id;
        $data[self::DATA_IOCLANGUAGE] = $this->language;
        $data[self::DATA_IS_ZIP_RADIO_CHECKED]=FALSE;
        $ret = $this->getform_latex_from_data($data);
        return $ret;        
    }

    function getform_latex_from_data($data){
       $data[self::DATA_FORM_URL] = "generate_latex.php";
       $data[self::DATA_URL_FILE_CLASS] = "mf_pdf";
       $data[self::DATA_HAS_PDF_RADIO] = TRUE;
       $data[self::DATA_HAS_ZIP_RADIO] = auth_isadmin();
       $data[self::DATA_HAS_ZIP_HIDDEN] = FALSE;
       return self::getform_from_data($data);
    }
    
    function getform_html($inputButton=TRUE){
        global $conf;
        $data = array();
        $data[self::DATA_TYPE]="zip";
        //$this->id = getID();
        $path_filename = str_replace(':','/',$this->id);
        $filename = str_replace(':','_',basename($this->id)).'.zip';
        $path_filename = $conf['mediadir'].'/'.dirname($path_filename).'/'.$filename;
        if (file_exists($path_filename)){
            $data[self::DATA_FILENAME]=$filename;
            $data[self::DATA_MEDIAPATH] = 'lib/exe/fetch.php?media='.str_replace('/', ':',dirname(str_replace(':','/',$this->id))).':'.$filename;
            $data[self::DATA_DATE] = strftime("%e %B %Y %T", filemtime($path_filename));
        }
        $data[self::DATA_INPUT_BUTTON]=$inputButton;
        $data[self::DATA_PAGEID] = $this->id;
        $data[self::DATA_IOCLANGUAGE] = $this->language;
        $data[self::DATA_IS_ZIP_RADIO_CHECKED]=$inputButton;
        $ret = $this->getform_html_from_data($data);
        return $ret;
        
    }
    
    function getform_html_from_data($data){
       $data[self::DATA_FORM_URL] = "generate_html.php";
       $data[self::DATA_URL_FILE_CLASS] = "mf_zip";
       $data[self::DATA_HAS_PDF_RADIO] = FALSE;
       $data[self::DATA_HAS_ZIP_RADIO] = $data[self::DATA_INPUT_BUTTON];
       $data[self::DATA_HAS_ZIP_HIDDEN] = !$data[self::DATA_INPUT_BUTTON];
       return self::getform_from_data($data);
    }
    
    function getform_from_data($data){
        $formId = str_replace(":", "_", $data[self::DATA_PAGEID]); //Id del node que conté la pàgina
        if (isset($data[self::DATA_FILENAME])){
            $filename = $data[self::DATA_FILENAME];
            $media_path = $data[self::DATA_MEDIAPATH];
            if(isset($data[self::DATA_DATE])){
                $dateFile = $data[self::DATA_DATE];
            }
            setlocale(LC_TIME, 'ca_ES.utf8');
            $url = '<a class="media mediafile '.$data[self::DATA_URL_FILE_CLASS].'" href="'.$media_path.'">'.$filename.'</a> <strong>'.$dateFile.'</strong>';
            if(isset($data[self::DATA_SIZE])){
                $url .=' <strong>|</strong> Mida: '.$data[self::DATA_SIZE].' <strong>|</strong> Temps emprat: '.$data[self::DATA_TIME].' segons';
            }
        }

        $pdfRadioCheckedAtt = $data[self::DATA_IS_ZIP_RADIO_CHECKED]?"":"checked=\"checked\"";
        $zipRadioCheckedAtt = $data[self::DATA_IS_ZIP_RADIO_CHECKED]?"checked=\"checked\"":"";

        $ret  = "<br /><br />";
        $ret .= "<div class=\"iocexport\">\n";
         $inputButton = isset($data[self::DATA_INPUT_BUTTON])?$data[self::DATA_INPUT_BUTTON]:FALSE;
        if($inputButton){
            $ret .= "<strong>Exportació IOC: </strong>";
        }
        $ret .= " <form action=\"lib/plugins/iocexportl/{$data[self::DATA_FORM_URL]}\" id=\"export__form_$formId\" method=\"post\" >\n";
        if($data[self::DATA_HAS_ZIP_RADIO]){
            $ret .= "  <input type=\"radio\" name=\"mode\" value=\"zip\" $zipRadioCheckedAtt/> Zip";
        }else if($data[self::DATA_HAS_ZIP_HIDDEN]){
             $ret .= "  <input type=\"hidden\" name=\"mode\" value=\"zip\"/>";
        }
        if($data[self::DATA_HAS_PDF_RADIO]){
            $ret .= "  <input type=\"radio\" name=\"mode\" value=\"pdf\" $pdfRadioCheckedAtt /> PDF";
        }
        $ret .= "  <input type=\"hidden\" name=\"pageid\" value=\"".$data[self::DATA_PAGEID]."\" />";
        $ret .= "  <input type=\"hidden\" name=\"ioclanguage\" value=\"".$data[self::DATA_IOCLANGUAGE]."\" />";
        if($inputButton){
            $ret .= "  <input type=\"submit\" name=\"submit\" id=\"id_submit\" value=\"Exporta\" class=\"button\" />\n";
        }
        $ret .= " </form>\n";
        $ret .= "<span id=\"exportacio\">".$url."</span>";
        $ret .= "</div>";
        if($inputButton){
          //$ret .= "<script type=\"text/javascript\" src =\"lib/plugins/iocexportl/lib/form.js\"></script>";
            $ret .= $this->getFormScript($data[self::DATA_PAGEID]);
        }
        return $ret;
    }

    private function getFormScript($id){
        $id = str_replace(":", "_", $id); //Id del node que conté la pàgina
        $script = "<script type=\"text/javascript\">\n";
        $script .= file_get_contents(DOKU_IOCEXPORTL_LIB."forms.js");
        $script = str_replace("export__form", "export__form_$id", $script);
        $script .= "</script>";
        return $script;
    }

    /**
     * Inserts the toolbar button
     */
    function ioctoolbar_buttons(& $event, $param) {
        $event->data[] = array (
            'type'   => 'picker',
            'title'  => $this->getLang('toolbar_btn'),
            'icon'   => '../../plugins/iocexportl/img/ico_toolbar.png',
            'class'  => 'ioctoolbar',
            'list'   => array(
                           array(
                                'type'   => 'format',
                                'title'  => $this->getLang('newcontent_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_newcontent.png',
                                'open'   => '<newcontent>\n',
                                'close'  => '\n</newcontent>',
                                ),
                           array(
                                'type'   => 'format',
                                'title'  => $this->getLang('figure_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_figure.png',
                                'key'    => 'z',
                                'open'   => '::figure:\n  :title:\n  :footer:\n',
                                'close'  => '\n:::\n',
                                ),
                           array(
                                'type'   => 'format',
                                'title'  => $this->getLang('figlink_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_figlink.png',
                                'open'   => ':figure:',
                                'close'  => ':',
                                ),
                           array(
                                'type'   => 'format',
                                'title'  => $this->getLang('table_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_table.png',
                                'key'    => 't',
                                'open'   => '::table:\n  :title:\n  :footer:\n',
                                'close'  => '\n:::\n',
                                ),
                            array(
                                'type'   => 'format',
                                'title'  => $this->getLang('tablink_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_tablink.png',
                                'open'   => ':table:',
                                'close'  => ':',
                                ),
                            array(
                                'type'   => 'format',
                                'title'  => $this->getLang('text_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_text.png',
                                'open'   => '::text:\n  :title:\n',
                                'close'  => '\n:::\n',
                                ),
                            array(
                                'type'   => 'format',
                                'title'  => $this->getLang('textlarge_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_textlarge.png',
                                'open'   => '::text:\n  :title:\n  :large:\n',
                                'close'  => '\n:::\n',
                                ),
                            array(
                                'type'   => 'format',
                                'title'  => $this->getLang('example_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_example.png',
                                'open'   => '::example:\n  :title:\n',
                                'close'  => '\n:::\n',
                                ),
                            array(
                                'type'   => 'format',
                                'title'  => $this->getLang('note_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_note.png',
                                'open'   => '::note:\n',
                                'close'  => '\n:::\n',
                                ),
                            array(
                                'type'   => 'format',
                                'title'  => $this->getLang('reference_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_reference.png',
                                'open'   => '::reference:\n',
                                'close'  => '\n:::\n',
                                ),
                            array(
                                'type'   => 'format',
                                'title'  => $this->getLang('important_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_important.png',
                                'open'   => '::important:\n',
                                'close'  => '\n:::\n',
                                ),
                            array(
                                'type'   => 'format',
                                'title'  => $this->getLang('quote_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_quote.png',
                                'open'   => '::quote:\n',
                                'close'  => '\n:::\n',
                                ),
                            array(
                                'type'   => 'format',
                                'title'  => $this->getLang('accounting_btn'),
                                'icon'   => '../../plugins/iocexportl/img/ico_table.png',
                                'open'   => '::accounting:\n  :title:\n  :footer:\n',
                                'close'  => '\n:::\n',
                                ),
                        ),
            'block'  => TRUE,
        );
    }
    
    function addCommands(Doku_Event &$event, $param) {
        $event->data["export_html"] = array(
            "callFile" => DOKU_IOCEXPORTL_COMMANDS."export_html_command.php"
        );
        $event->data["export_pdf"] = array(
            "callFile" =>  DOKU_IOCEXPORTL_COMMANDS."export_pdf_command.php"
        );
        $event->data["export_onepdf"] = array(
            "callFile" =>  DOKU_IOCEXPORTL_COMMANDS."export_onepdf_command.php"
        );
    }
    
    function addControlScripts(Doku_Event &$event, $param) {
        $event->data->addControlScript(DOKU_IOCEXPORTL_LIB."ExportButtonGetQuery.js");
        $event->data->addControlScript(DOKU_IOCEXPORTL_LIB."UpdateViewHandler.js");
    }
    
    function addWikiIocButtons(Doku_Event &$event, $param) {
        $control1 = array(
                    'DOM' => array (
                      'id' => 'exportPdf',
                      'label' => 'Exportar', //TODO [Josep] etiqueta en diferents idiomes
                      'class' => 'iocDisplayBlock',
                    ),
                    'DJO' => array (
                        'query' => '\'do=edit\'',
                        'autoSize' => true,
                        'visible' => false,
                        'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=export_pdf\'',
                        'disableOnSend' => true,
                    ),
                );
        $event->data->addWikiIocButton($control1);
        
        $control2 = array(
                    'DOM' => array (
                      'id' => 'exportHtml',
                      'label' => 'Exportar', //TODO [Josep] etiqueta en diferents idiomes
                      'class' => 'iocDisplayBlock',
                    ),
                    'DJO' => array (
                        'query' => '\'do=edit\'',
                        'autoSize' => true,
                        'visible' => false,
                        'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=export_html\'',
                        'disableOnSend' => true,
                    ),
                );
        $event->data->addWikiIocButton($control2);
        
        $control3 = array(
                    'DOM' => array (
                      'id' => 'exportOnePdf',
                      'label' => 'PDF únic', //TODO [Josep] etiqueta en diferents idiomes
                      'class' => 'iocDisplayBlock',
                    ),
                    'DJO' => array (
                        'query' => '\'do=edit\'',
                        'autoSize' => true,
                        'visible' => false,
                        'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=export_onepdf\'',
                        'disableOnSend' => true,
                    ),
                );
        $event->data->addWikiIocButton($control3);
   }
}
