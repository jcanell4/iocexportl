<?php
/**
 * LaTeX Plugin: Export content to LaTeX
 */
if (!defined('DOKU_INC')) die();
define('WIKI_IOC_PROJECTS', DOKU_INC."lib/plugins/wikiiocmodel/projects/");
require_once(WIKI_IOC_PROJECTS."documentation/renderer/basic_latex.php");

class renderer_plugin_doclatex extends BasicLatex {

    static $convert = FALSE;    //convert images to $imgext
    static $imgext = '.pdf';    //Format to convert images
    static $img_max_table = 99; //Image max width inside tables
    static $hr_width = 354;
    static $p_width = 360;      //415.12572;
    var $code = FALSE;
    var $col_colspan;
    var $col_num = 1;
    var $endimg = FALSE;
    var $formatting = '';
    var $id = '';
    var $max_cols = 0;
    var $monospace = FALSE;
    var $table = FALSE;
    var $tableheader = FALSE;
    var $tableheader_count = 0; //Only one header per table
    var $tableheader_end = FALSE;
    var $tmp_dir = 0;           //Value of temp dir


    /**
     * Returns the format produced by this renderer.
     */
    function getFormat(){
        return "iocexportl";
    }

    function reset(){
        $this->doc = '';
    }

    /**
     * Initialize the rendering
     */
    function document_start() {
        global $USERINFO;
        global $conf;

	$this->id = getID();
        //Check whether user can export
	$exportallowed = (isset($conf['plugin']['iocexportl']['allowexport']) && $conf['plugin']['iocexportl']['allowexport']);
        if (!$exportallowed && !auth_isadmin()) die;

        if (!isset($_SESSION['tmp_dir'])){
            $this->tmp_dir = rand();
        }else{
            $this->tmp_dir = $_SESSION['tmp_dir'];
        }
        if (!file_exists(DOKU_PLUGIN_LATEX_TMP.$this->tmp_dir)){
            mkdir(DOKU_PLUGIN_LATEX_TMP.$this->tmp_dir, 0775, TRUE);
            mkdir(DOKU_PLUGIN_LATEX_TMP.$this->tmp_dir.'/media', 0775, TRUE);
        }
        if ($_SESSION['fpd']){
            $filename = 'backgroundfpd';
        } else {
            if ($_SESSION['u0']){
                $filename = 'backgroundu0';
            }else{
                $filename = 'background';
            }
        }
        if ($_SESSION['double_cicle']){
            $filename .= 'dc';
        }
        if(!file_exists(DOKU_PLUGIN_LATEX_TMP.$this->tmp_dir.'/media/'.$filename.'.pdf')){
            copy(DOKU_PLUGIN.'iocexportl/templates/'.$filename.'.pdf', DOKU_PLUGIN_LATEX_TMP.$this->tmp_dir.'/media/'.$filename.'.pdf');
        }
        //Global variables
        $this->_initialize_globals();
    }

    /**
     * Closes the document
     */
    function document_end(){
        $this->doc = preg_replace('/@IOCKEYSTART@/','\{', $this->doc);
        $this->doc = preg_replace('/@IOCKEYEND@/','\}', $this->doc);
        $this->doc = preg_replace('/@IOCBACKSLASH@/',"\\\\", $this->doc);
        $this->doc = preg_replace('/(textbf{)(\s*)(.*?)(\s*)(})/',"$1$3$5", $this->doc);
        $this->doc = preg_replace('/(raggedright)(\s{2,*})/',"$1 ", $this->doc);
    }

    /**
     * Initialization session variables
     */
    function _initialize_globals(){
        if (!isset($_SESSION['accounting']))        $_SESSION['accounting'] = FALSE;
        if (!isset($_SESSION['activities_header'])) $_SESSION['activities_header'] = FALSE;
        if (!isset($_SESSION['activities']))        $_SESSION['activities'] = FALSE;
        if (!isset($_SESSION['chapter']))           $_SESSION['chapter'] = 1;
        if (!isset($_SESSION['createbook']))        $_SESSION['createbook'] = FALSE;
        if (!isset($_SESSION['double_cicle']))      $_SESSION['double_cicle'] = FALSE;
        if (!isset($_SESSION['draft']))             $_SESSION['draft'] = FALSE;
        if (!isset($_SESSION['figfooter']))         $_SESSION['figfooter'] = '';
        if (!isset($_SESSION['figlabel']))          $_SESSION['figlabel'] = '';
        if (!isset($_SESSION['figlarge']))          $_SESSION['figlarge'] = FALSE;
        if (!isset($_SESSION['figtitle']))          $_SESSION['figtitle'] = '';
        if (!isset($_SESSION['figure']))            $_SESSION['figure'] = FALSE;
        if (!isset($_SESSION['fpd']))               $_SESSION['fpd'] = FALSE;
        if (!isset($_SESSION['iocelem']))           $_SESSION['iocelem'] = FALSE;
        if (!isset($_SESSION['imgB']))              $_SESSION['imgB'] = FALSE;
        if (!isset($_SESSION['introbook']))         $_SESSION['introbook'] = TRUE;
        if (!isset($_SESSION['onemoreparsing']))    $_SESSION['onemoreparsing'] = FALSE;
        if (!isset($_SESSION['qrcode']))            $_SESSION['qrcode'] = FALSE;
        if (!isset($_SESSION['quizmode']))          $_SESSION['quizmode'] = FALSE;
        if (!isset($_SESSION['table_id']))          $_SESSION['table_id'] = '';
        if (!isset($_SESSION['table_footer']))      $_SESSION['table_footer'] = '';
        if (!isset($_SESSION['table_large']))       $_SESSION['table_large'] = FALSE;
        if (!isset($_SESSION['table_small']))       $_SESSION['table_small'] = FALSE;
        if (!isset($_SESSION['table_title']))       $_SESSION['table_title'] = '';
        if (!isset($_SESSION['table_widths']))      $_SESSION['table_widths'] = '';
        if (!isset($_SESSION['u0']))                $_SESSION['u0'] = FALSE;
        if (!isset($_SESSION['video_url']))         $_SESSION['video_url'] = FALSE;
        if (!isset($_SESSION['xhtml_latex_quiz']))  $_SESSION['xhtml_latex_quiz'] = FALSE;
    }

    function _format_text($text){
        $text = $this->_ttEntities(trim($text));//Remove extended symbols
        if ($_SESSION['iocelem']){
            $text = preg_replace('/\n/',"^^J$1", $text);
        }
        $this->doc .= $text . DOKU_LF;
    }

    function label_document() { //For links
        if (isset($this->info['current_file_id'])) {
            $cleanid = $this->info['current_file_id'];
        }
        else {
            $cleanid = noNS(cleanID($this->info['current_id'], TRUE));
        }
        $this->doc .= "\label{" . md5($cleanid) . "}";
        if (isset($this->info['current_file_id'])){
            $this->doc .= "%%Start: $cleanid => " . $this->info['current_file_id'].DOKU_LF;
        } else {
            $this->doc .= "%%Start: $cleanid  => " . wikiFN($cleanid).DOKU_LF;
        }
    }

    function _latexEntities($string) {
        return $this->_xmlEntities($string);
    }

    function smiley($smiley) {
        $img = DOKU_INC . 'lib/images/smileys/'. $this->smileys[$smiley];
        $img_aux = $this->_image_convert($img, DOKU_PLUGIN_LATEX_TMP.$this->tmp_dir.'/media');
        $this->doc .= '\includegraphics[height=1em, width=1em]{media/'.basename($img_aux).'}';
    }

    function _image_convert($img, $dest, $width = NULL, $height = NULL){
        $imgdest = tempnam($dest, 'ltx');
        $resize = '';
        if ($width && $height){
            $resize = "-resize $width"."x"."$height";
        }
        @exec("convert $img $resize $imgdest".self::$imgext);
        return $imgdest.self::$imgext;
    }

    function _latexAddImage($src, $width = NULL, $height = NULL, $align = NULL, $title = NULL, $linking = NULL, $external = FALSE){
        $max_width_elem = '.9\linewidth';
        if ($_SESSION['figure']){
            $title = $_SESSION['figtitle'];
            $title = preg_replace('/<verd>|<\/verd>/', '', $title);
        }
        $figure = FALSE;
        $footer = '';
        $icon = FALSE;
        $imgb = FALSE;
        if (!empty($_SESSION['figfooter'])){
            $footer = $_SESSION['figfooter'];
        }
        // make sure width and height are available
        if (!$width && !$height) {
            if (file_exists($src)) {
                $info  = getimagesize($src);
                $width  = $info[0];
            }
        }else{
            if (file_exists($src)) {
                $info  = getimagesize($src);
                $ratio = $info[0]/$info[1];
                if(!$width){
                    $width = round($height * $ratio, 0);
                }
            }
        }
        if (!$_SESSION['u0']){
            $align = 'centering';
        }else{//Unit 0
            $align = 'flushleft';
        }
        if (!$this->table && !$_SESSION['figure'] && !$_SESSION['video_url'] && $_SESSION['iocelem'] !== 'textl'){
            if ($width < 133){
                $max_width = '[width='.$width.'px]';
                $icon = ($width < 49 && $height < 49);
            }else{
                $max_width = '[width=35mm]';
            }
            $img_width = FALSE;
        }elseif (!$this->table && $width > self::$p_width && !$_SESSION['iocelem']){
            $max_width = '[width=\textwidth]';
            $img_width = FALSE;
        }elseif ($_SESSION['iocelem']){
             //Check wheter image fits on iocelem
             if ($width >= (.9 * self::$p_width)){
                $max_width = '[width='.$max_width_elem.']';
                $img_width = $max_width_elem;
             }else{
                 $max_width = '[width='.$width.'px]';
                 $img_width = $width;
                 $max_width_elem = FALSE;
             }
        }else{
            $max_width = '[width='.$width.'px]';
            $img_width = $width;
        }

        $imgb = (!$icon && !$this->table && !$_SESSION['figure'] && !$_SESSION['iocelem'] && !$_SESSION['video_url'] && !$_SESSION['u0']);
        $figure = (!$this->table && $_SESSION['figure'] && !$_SESSION['video_url'] && !$_SESSION['u0']);

        if (self::$convert || $_SESSION['draft'] || $external){
            $img_aux = $this->_image_convert($src, DOKU_PLUGIN_LATEX_TMP.$this->tmp_dir.'/media');
        }else{
            $img_aux = tempnam(DOKU_PLUGIN_LATEX_TMP . $this->tmp_dir . '/media', 'ltx');
            $ext = pathinfo($src,PATHINFO_EXTENSION);
            if (file_exists($src)){
                copy($src, "$img_aux.$ext");
            }
        }
        if (file_exists($img_aux)){
            if ($imgb){
                $offset = '';
                //Extract offset
                if ($title){
                    preg_match('/(.*?)(\/([^\/]*$))/', $title, $data);
                    if (!empty($data)){
                        if(!empty($data[3]) &&  is_numeric($data[3])){
                            $offset = '['.trim($data[3]).'mm]';
                            $footer = $data[1];
                        }else{
                            $footer = $title;
                        }
                    }else{
                        $footer = $title;
                    }
                }
                $this->doc .= '\imgB'.$offset.'{';
            }elseif ($figure){
                if ($_SESSION['figlarge']){
                    $this->doc .= '\checkoddpage\ifthenelse{\boolean{oddpage}}{\hspace*{0mm}}{\hspace*{-\marginparwidth}\hspace*{-10mm}}'.DOKU_LF;
                    if ($img_width) {
                        $this->doc .= '\begin{center}';
                    }
                    $this->doc .= '\begin{minipage}[c]{\textwidth+\marginparwidth+\marginparsep}'. DOKU_LF;
                }
                $this->doc .= '\begin{figure}[H]'.DOKU_LF;
            }
            if (!is_null($linking) && $linking !== 'details'){
                $this->doc .= '\href{'.$linking.'}{';
            }
            if ($_SESSION['figure']){
                $this->doc .= '\\' . $align . DOKU_LF;
            }
            $hspace = 0;//Align text and image
            //Create title with label

            $title_width = ($img_width)?$img_width.'px':'\textwidth';
            if ($_SESSION['figure']){
                if ($_SESSION['iocelem'] && $max_width_elem){
                    $title_width = $max_width_elem;
                }
                $this->doc .= '\parbox[t]{'.$title_width.'}{\caption{'.trim($this->_xmlEntities($title));
				if (!empty($_SESSION['figlabel'])){
	                $this->doc .= '\label{'.$_SESSION['figlabel'].'}';
				}
				$this->doc .= '}}\\\\\vspace{2mm}'.DOKU_LF;
            }
            //Inside table, images will be centered vertically
            if ($this->table && $width > self::$img_max_table){
                $this->doc .= '\resizebox{\linewidth}{!}{';
            }
            //Image is smaller than page size
            if ($_SESSION['figure'] && $img_width){
                $this->doc .= '\begin{center}'.DOKU_LF;
            }
            $this->doc .= '\includegraphics'.$max_width.'{media/'.basename($img_aux).'}';
            if ($_SESSION['figure'] && $img_width){
                $this->doc .= '\end{center}'.DOKU_LF;
            }
            if ($this->table && $width > self::$img_max_table){
                $this->doc .= '}';

            }
			//Close href
            if (!is_null($linking) && $linking !== 'details'){
                $this->doc .= '}';
                if (!$_SESSION['video_url']){
                    $this->doc .= DOKU_LF;
                }
            }
            if (!$_SESSION['video_url'] && !empty($footer)){
                $this->doc .= DOKU_LF;
            }
            //Check whether footer exists
            if ($footer) {
                if ($_SESSION['figure']){
                    if ($img_width && !$_SESSION['iocelem']){
                        $hspace = ($img_width + $hspace).'pt';
                    }elseif($_SESSION['iocelem']){
                        $hspace = ($max_width_elem)?$max_width_elem:$img_width.'px';
                    }else{
                       $hspace = '\textwidth';
                    }
					$vspace = '\vspace{-2mm}';
					$align = '\raggedleft';
                }elseif($_SESSION['iocelem']){
                        //textboxsize .05
                        $hspace = '.9\linewidth';
                        $vspace = '\vspace{-6mm}';
                        $align = '\raggedleft';
                }else{
                    $hspace = '\marginparwidth';
					$vspace = '\vspace{-4mm}';
					$align = '\iocalignment';
                }
                $this->doc .=  '\raisebox{\height}{\parbox[t]{'.$hspace.'}{'.$align.'\footerspacingline\textsf{\tiny'.$vspace.trim($this->_xmlEntities($footer)).'}}}';
            }
            if ($figure){
                $this->doc .= '\end{figure}';
                if ($_SESSION['figlarge']){
                    $this->doc .= '\end{minipage}'. DOKU_LF;
                    if ($img_width) {
                        $this->doc .= '\end{center}'. DOKU_LF;
                    }
                }
            }elseif ($imgb){
                if (!empty($footer)){
                    $this->doc .= DOKU_LF;
                }
                $this->doc .= '}' . DOKU_LF;
            }
            if ($_SESSION['iocelem'] && !$_SESSION['figure']){
                $this->doc .= '\vspace{1ex}' . DOKU_LF;
            }
            $this->endimg = TRUE;
        }else{
            $this->doc .= '\textcolor{red}{\textbf{File '. $this->_xmlEntities(basename($src)).' does not exist.}}';
        }
    }

    function render_TOC() {
         return '';
    }

    function toc_additem($id, $text, $level) {}

    function cdata($text) {
        if ($this->monospace){
            $text = preg_replace('/\n/', '\\newline ', $text);
        }
        $this->doc .= $this->_xmlEntities($text);
    }

    function p_open() {}

    function p_close(){
        if (!$this->endimg){
            $this->doc .= DOKU_LF;
        }else{
            $this->endimg = FALSE;
        }
        $this->doc .= DOKU_LF;
    }

    function header($text, $level, $pos){
        global $conf;

        if ($_SESSION['activities']){
            $level += 1;
        }
        $levels = array(
    		    1 => '\chapter',
    		    2 => '\section',
    		    3 => '\subsection',
    		    4 => '\subsubsection',
    		    5 => '\paragraph',
    		    );

        if ( isset($levels[$level]) ) {
          $token = $levels[$level];
        } else {
          $token = $levels[1];
        }
        $text = $this->_xmlEntities(trim($text));
        $chapternumber = '';
        if ($_SESSION['u0']){
            $chapternumber = '*';
            $this->doc .= '\headingnonumbers';
        }elseif ($_SESSION['introbook'] && $_SESSION['createbook'] && $level === 1 && $_SESSION['chapter'] < 3){
            $chapternumber = '*';
            $_SESSION['chapter'] += 1;
            $this->doc .= '\cleardoublepage\phantomsection\addcontentsline{toc}{chapter}{' . $text . '}'.DOKU_LF;
        }elseif($level === 1){ //Change chapter style
            $this->doc .= '\headingnumbers';
            $_SESSION['activities_header'] = TRUE;
        }
        if ($_SESSION['activities'] && $_SESSION['activities_header'] === TRUE){
            $this->doc .= '\newpage'.DOKU_LF;
            $_SESSION['activities_header'] = FALSE;
        }
        if ($_SESSION['activities'] && $level !== 2){
            $this->doc .= '\headingnonumbers\phantomsection';
            $chapternumber = '*';
        }elseif($_SESSION['activities']){
            $this->doc .= '\headingnumbers';
        }
		//CAL ELIMINAR VARIABLE breakline al canviar el header de nivell 5!!!!!!!
        $breakline = ($level === 5)?"\hspace*{\\fill}\\\\":"";
        $this->doc .= '\hyphenpenalty=100000'.DOKU_LF;
        $this->doc .= "$token$chapternumber{" . $text . "}". $breakline .DOKU_LF;
        $this->doc .= '\hyphenpenalty=1000'.DOKU_LF;
    }

    function hr() {
        $this->doc .= '\newpage'.DOKU_LF;
    }

    function linebreak() {
        if ($this->table && !empty($this->formatting)){
            $this->doc .= '}';
        }
        if ($this->table){
            $this->doc .= '\break ';
        }else{
            $this->doc .= DOKU_LF.DOKU_LF;
        }
        $this->doc .= $this->formatting;
    }

    function strong_open() {
        if ($this->table){
            $this->formatting = '\textbf{';
        }
        $this->doc .= '\textbf{';
    }

    function strong_close() {
        $this->doc .= '}';
        $this->formatting = '';
    }

    function emphasis_open() {
        if ($this->table){
            $this->formatting = '\textit{';
        }
        $this->doc .= '\textit{';
    }

    function emphasis_close() {
        $this->doc .= '}';
        $this->formatting = '';
    }

    function underline_open() {
        if ($this->table){
            $this->formatting = '\underline{';
        }
        $this->doc .= '\underline{';
    }

    function underline_close() {
        $this->doc .= '}';
        $this->formatting = '';
    }

    function monospace_open() {
        $this->monospace = TRUE;
        $this->doc .= '\texttt{';
    }

    function monospace_close() {
        $this->doc .= '}';
        $this->monospace = FALSE;
    }

    function subscript_open() {
        $this->doc .= '\textsubscript{';
    }

    function subscript_close() {
        $this->doc .= '}';
    }

    function superscript_open() {
        $this->doc .= '\textsuperscript{';
    }

    function superscript_close() {
        $this->doc .= '}';
    }

    function deleted_open() {
        $this->doc .= '\sout{';
    }

    function deleted_close() {
        $this->doc .= '}';
    }

    /*
     * Tables
     */
    function table_open($maxcols = NULL, $numrows = NULL){
        global $conf;

        $this->table = TRUE;
        $this->tableheader = TRUE;
        $this->max_cols = $maxcols;
        $this->col_num = 1;
        $this->table_align = array();
        $this->doc .= '\fonttable'.DOKU_LF;
        $border = ($_SESSION['accounting'])?'|':'';
        $large = '';
        $csetup = '';
        $col_width = '-1,';
        $tablecaption = '\tablecaption';
        $table_type = 'longtabu';
        if ($_SESSION['table_large']){
            $large = ' to 170mm';
            $csetup = '\tablelargecaption';

        }elseif($_SESSION['table_small']){
            $this->doc .= '\addtocounter{table}{-1}\caption{'.$_SESSION['table_title'].
            			  '\label{'.$_SESSION['table_id'].'}}'.DOKU_LF;
            $large = ' spread 0pt';
            $tablecaption = '\tablesmallcaption{'.$maxcols.'}';
            $col_width = '';
            $table_type = 'tabu';
        }elseif($_SESSION['iocelem']){
            $large = ' to \tableiocelemsize';
            $tablecaption = '\tableiocelemcaption';
        }
        $this->doc .= '\begin{'.$table_type.'}'.$large.'{';
        for($i=0; $i < $maxcols; $i++) {
            $table_widths = $_SESSION['accounting'] &&
                is_array($_SESSION['table_widths']) &&
                array_key_exists($i, $_SESSION['table_widths']);
            if ($table_widths) {
                $value = floatval($_SESSION['table_widths'][$i]);
                if ($value <= 1) {
                    $col_width = '-1,';
                } else {
                    $col_width = $value . ',';
                }
                if ($i === 0) {
                    $this->doc .= $border;
                }
            } elseif($_SESSION['accounting'] && $i===0) {//default behaviour
                $col_width = '3,';
                $this->doc .= $border;
            } elseif($_SESSION['accounting']) {
                $col_width = '-1,';
            }
            $this->doc .= 'X['.$col_width.'l] '.$border;
        }
        $this->doc .= '}';
        if (!$_SESSION['table_small']){
            if (!$_SESSION['table_large']){
                $vspace = '\vspace{-2.5ex}';
            } else {
                $separation = (isset($conf['plugin']['iocexportl']['largetablecaptmargin'])?'-2.9ex':'-2.5ex');
                $vspace = '\vspace{'.$separation.'}';
            }
            if (strlen($_SESSION['table_title']) > 86){
                $vspace = '';
            }
            $this->doc .= $csetup.$tablecaption.'\caption{'.$_SESSION['table_title']. $vspace.
            			  '\label{'.$_SESSION['table_id'].'}}'.
            			  '\\\\'.DOKU_LF;
        }
        $this->doc .= '\hline'.DOKU_LF;
    }

    function table_close(){
        $this->table = FALSE;
        if (!$_SESSION['accounting']){
            $this->doc .= '\noalign{\vspace{1mm}}'.DOKU_LF;
            $this->doc .= '\hline'.DOKU_LF;
        }
        if (($_SESSION['iocelem'] || $_SESSION['accounting']) && $_SESSION['table_footer']){
            $this->doc .='\multicolumn{'.$this->max_cols.'}{l@{\hspace{0mm}}}{\hspace{-2mm}'.$_SESSION['table_footer'].'}'.DOKU_LF;
        }
        $this->tableheader_count = 0;
        preg_match('/(?<=@IOCHEADERSTART@)([^@]*)(?=@IOCHEADEREND@)/',$this->doc, $matches);
        $this->doc = preg_replace('/@IOCHEADERSTART@|@IOCHEADEREND@/','', $this->doc);
        $this->doc = preg_replace('/@IOCHEADERBIS@/',isset($matches[1])?$matches[1]:'', $this->doc, 1);
        $this->doc .= '\tabuphantomline';
        if ($_SESSION['table_small']){
            $this->doc .= '\end{tabu}'.DOKU_LF;
        }else{
            $this->doc .= '\end{longtabu}'.DOKU_LF;
        }
        if (!$_SESSION['iocelem']){
            $this->doc .= '\normalfont\normalsize'.DOKU_LF;
        }else{
            $this->doc .= '\defaultspacingpar\ioctextfont'.DOKU_LF;
        }
    }

    function tablerow_open(){
        if($_SESSION['accounting'] && $this->tableheader && $this->tableheader_count === 0){
            $this->doc .='\rowcolor{coloraccounting}';
        }

        $this->col_num = 1;
    }

    function tablerow_close(){
        if ($this->tableheader_end){
            $this->tableheader_count += 1;
            $this->tableheader = TRUE;
        }
        if ($this->tableheader_end && $this->tableheader_count === 1
            && !$_SESSION['table_small'] && !$_SESSION['iocelem'] && !$_SESSION['accounting']){
            $this->doc .= '@IOCHEADEREND@';
            $this->doc .= '\\\\ \hline \noalign{\vspace{1mm}} \endfirsthead'.DOKU_LF;
            $this->doc .= '\tablecaptioncontinue\caption[]{(\ioclangcontinue)\vspace{-3mm}} \\\\' . DOKU_LF;
            $this->doc .= '\hline' . DOKU_LF;
            $this->doc .= '@IOCHEADERBIS@ \\\\ \hline' . DOKU_LF;
            $this->doc .= '\endhead' . DOKU_LF;
            if (!$_SESSION['table_small']){
                $headrule = '\tableheadrule';
            }else{
                $headrule = '\tablesmallheadrule';
            }
            $this->doc .= '\noalign{\vspace{-2mm}}\multicolumn{'.$this->max_cols.'}{c}{'.$headrule.'}' . DOKU_LF;
            $this->doc .= '\endfoot' . DOKU_LF;
            $this->doc .= (!empty($_SESSION['table_footer']))?'\multicolumn{'.$this->max_cols.'}{r@{\hspace{0mm}}}{\tablefooter{'.$_SESSION['table_footer'].'}}'.DOKU_LF:''.DOKU_LF;
            $this->doc .= '\endlastfoot' . DOKU_LF;
        }elseif ($this->tableheader_end && $this->tableheader_count === 1
            && !$_SESSION['table_small'] && $_SESSION['iocelem'] && $_SESSION['accounting']){
            $this->doc .= '\\\\ \hline \endfirsthead\endhead'.DOKU_LF;
        }else{
            $this->doc .= '\\\\'.DOKU_LF;
            if ($this->tableheader_end){
                $this->doc .= '\hline'.DOKU_LF;
            }elseif($_SESSION['accounting']){
                $this->doc .= '\hline'.DOKU_LF;
            }
        }
        $this->tableheader_end = FALSE;
    }

    function tableheader_open($colspan = 1, $align = NULL, $rowspan = 1){
        $position = 'p{\the\tabucolX * '.$colspan.'}';
        if($this->tableheader){
              $this->doc .= '@IOCHEADERSTART@';
              $this->tableheader = FALSE;
        }
        $this->col_colspan = $colspan;
        if ($colspan > 1){
            $this->doc .= '\multicolumn{'.$colspan.'}{'.$position.'}{';
        }else{
            $this->doc .= '\raggedright ';
        }
        if ($this->tableheader_count > 0 && !$_SESSION['table_small']){
            $this->doc .= '\raisebox{-\height}{';
        }
        if ($align){
            if ($align === 'left'){
                $align = '\raggedright';
            }elseif($align === 'right'){
                $align = '\raggedleft';
            }else{
                $align = '\centering';
            }
        }else{
            $align = '\raggedright';
        }
        if (!$_SESSION['table_small']){
                    $this->doc .= '\parbox[t]{\linewidth}{'.$align;
        }
        $this->formatting = '\textbf{';
        $this->doc .= $this->formatting;
    }

    function tableheader_close(){
        $this->formatting = '';
        $this->doc .= '}';//close format
        if (!$_SESSION['table_small']){
            $this->doc .= '}';//close parbox
        }
        if ($this->tableheader_count > 0 && !$_SESSION['table_small']){
            $this->doc .= '}';//close raisebox
        }
        $col_num_aux = ($this->col_colspan > 1)?$this->col_num + ($this->col_colspan-1):$this->col_num;
        if ($this->col_colspan > 1){
            $this->doc .= '}';
        }
        if ($col_num_aux < $this->max_cols){
           $this->doc .= '& ';
        }
       $this->col_num += $this->col_colspan;
       $this->tableheader_end = TRUE;
    }

    function tablecell_open($colspan = 1, $align = NULL, $rowspan = 1){
        if ($_SESSION['accounting'] && $colspan === $this->max_cols){
            $this->doc .= '\rowcolor{coloraccounting}';
            for($i=1;$i<$colspan;$i++){
                $this->doc .= ' &';
            }
            $this->col_colspan = $colspan;
        }else{
            $position = 'p{\the\tabucolX * '.$colspan.'}';
            $this->tableheader = FALSE;
            if ($colspan > 1){
                $this->doc .= '\multicolumn{'.$colspan.'}{'.$position.'}{';
            }
            $this->col_colspan = $colspan;
            if (!$_SESSION['table_small']){
                $this->doc .= '\raisebox{-\height}{';
            }
            if ($align){
                if ($align === 'left'){
                    $align = '\raggedright';
                }elseif($align === 'right'){
                    $align = '\raggedleft';
                }else{
                    $align = '\centering';
                }
            }else{
                $align = '\raggedright';
            }
            if (!$_SESSION['table_small']){
                $this->doc .= '\parbox[t]{\linewidth}{'.$align.' ';
            }
        }
    }

    function tablecell_close(){
        if ($_SESSION['accounting'] && $this->col_colspan >= 3){
            $this->col_num += $this->col_colspan;
        }else{
            $col_num_aux = ($this->col_colspan > 1)?$this->col_num + $this->col_colspan:$this->col_num;
            if (!$_SESSION['table_small']){
                $this->doc .= '}';//close parbox
                $this->doc .= '}';//close raisebox
            }
            if ($this->col_colspan > 1) {
                $col_num_aux--;
                $this->doc .= '} ';//close multicolumn
            }
            if ($col_num_aux < $this->max_cols){
                $this->doc .= ' & ';
            }
            $this->col_num += $this->col_colspan;
        }
    }

    function footnote_open() {
        $this->doc .= '\footnote{';
    }

    function footnote_close() {
        $this->doc .= '}'.DOKU_LF;
    }

    function listu_open() {
        //Quiz questions are numered
        if ($_SESSION['quizmode']){
            $this->listo_open();
        }else{
            $this->doc .= '\nobreak\begin{itemize}'.DOKU_LF;
            //Inside iocelems lists are aligned to left
            if ($_SESSION['iocelem'] && $_SESSION['iocelem'] !== 'textl'){
                $this->doc .= '\raggedright'.DOKU_LF;
            }
        }
    }

    function listu_close() {
        if ($_SESSION['quizmode']){
            $this->listo_close();
        }else{
            $this->doc .= '\end{itemize}'.DOKU_LF;
            //Return to normal align
            if ($_SESSION['iocelem'] && $_SESSION['iocelem'] !== 'textl'){
                $this->doc .= '\iocalignment'.DOKU_LF;
            }
        }
    }

    function listo_open() {
        $this->doc .= '\nobreak\begin{enumerate}'.DOKU_LF;
        //Inside iocelems lists are aligned to left
        if ($_SESSION['iocelem'] && $_SESSION['iocelem'] !== 'textl'){
            $this->doc .= '\raggedright'.DOKU_LF;
        }
    }

    function listo_close() {
        $this->doc .= '\end{enumerate}'.DOKU_LF;
        //Return to normal align
        if ($_SESSION['iocelem'] && $_SESSION['iocelem'] !== 'textl'){
            $this->doc .= '\iocalignment'.DOKU_LF;
        }
    }

    function listitem_open($level) {
        $this->doc .= '\item ';
    }

    function listitem_close() {
        $this->doc .= DOKU_LF;
    }

    function listcontent_open() {
    }

    function listcontent_close() {
    }

    function unformatted($text) {
        $this->doc .= $this->_xmlEntities($text);
    }

    function acronym($acronym) {
        $this->doc .= $this->_xmlEntities($acronym);
    }

    function entity($entity) {
        $this->doc .= $this->_xmlEntities($entity);
    }

    function multiplyentity($x, $y) {
        $this->doc .= $x.'x'.$y;
    }

    function singlequoteopening() {
        $this->doc .= "`";
    }

    function singlequoteclosing() {
        $this->doc .= "'";
    }

    function apostrophe() {
        $this->doc .= "'";
    }

    function doublequoteopening() {
        $this->doc .= "``";
    }

    function doublequoteclosing() {
        $this->doc .= "''";
    }

    function php($text, $wrapper='dummy') {
        $this->monospace_open();
        $this->doc .= $this->_xmlEntities($text);
        $this->monospace_close();
    }

    function phpblock($text) {
        $this->file($text);
    }

    function html($text, $wrapper='dummy') {
        $this->monospace_open();
        $this->doc .= $this->_xmlEntities($text);
        $this->monospace_close();
    }

    function htmlblock($text) {
        $this->file($text);
    }

    function preformatted($text) {
        $this->doc .= '\codeinline{';
        $text = clean_reserved_symbols($text);
        $this->_format_text($text);
        $this->doc .= '}';
    }

    function file($text) {
        $this->preformatted($text);
    }

    function quote_open() {
        $this->doc .= "\textbar";
    }

    function quote_close() {
    }

    function code($text, $language=null, $filename=null) {
        $large = preg_split('/\//', $language, 2);
        $language = preg_replace('/\/.*$/', '', $language);
        if (preg_match('/html|css|dtd|rss/i', $language)){
            $language = 'HTML';
        }
        if(!$_SESSION['iocelem']){
            if (isset($large[1]) && $large[1] === 'l'){
                $this->doc .= '\checkoddpage\ifthenelse{\boolean{oddpage}}{\hspace*{4mm}}{\hspace*{-\marginparwidth}\hspace*{-6mm}}'.DOKU_LF;
                $this->doc .= '\begin{minipage}[c]{\textwidth+\marginparwidth+4mm}'. DOKU_LF;
            }
            $this->doc .= '\vspace{1ex}'.DOKU_LF;
            if ( !$language ) {
                $this->doc .= '\begin{csource}{language=}'.DOKU_LF;
            } else {
                $this->doc .= '\begin{csource}{language='.$language.'}'.DOKU_LF;
            }
            $this->doc .=  $this->_format_text($text);
            $this->doc .= '\end{csource}'.DOKU_LF;
            if (isset($large[1]) && $large[1] === 'l'){
                $this->doc .= '\end{minipage}'.DOKU_LF.DOKU_LF;
            }
        }else{
            $this->doc .= '\vspace{1ex}'. DOKU_LF;
            $this->doc .= '\begin{adjustwidth}{12mm}{9mm}'. DOKU_LF;
            if ( !$language ) {
                $this->doc .= '\begin{csource}{language=}^^J';
            } else {
                $this->doc .= '\begin{csource}{language='.$language.'}^^J';
            }
            $text = preg_replace('/\\\\/', '\\\\\\\\', $text);
            $text = preg_replace('/ /', '\\\\ ', $text);
            $text = preg_replace('/([%{}])/', '\\\\$1', $text);
            $this->doc .=  $this->_format_text($text) . '^^J';
            $this->doc .= '\end{csource}'.DOKU_LF;
            $this->doc .= '\end{adjustwidth}'.DOKU_LF;
            $this->doc .= '\vspace{-2ex}'. DOKU_LF;
        }
    }

    function internalmedia ($src, $title=null, $align=null, $width=null,
                            $height=null, $cache=null, $linking=null) {
        global $conf;
        resolve_mediaid(getNS($this->id),$src, $exists);
        list($ext,$mime) = mimetype($src);
        $type = substr($mime,0,5);
        if($type === 'image'){
            $file = mediaFN($src);
            $this->_latexAddImage($file, $width, $height, $align, $title, $linking);
        }elseif($type === 'appli' && !$_SESSION['u0']){
            if (preg_match('/\.pdf$/', $src)){
                $_SESSION['qrcode'] = TRUE;
                $src = $this->_xmlEntities(DOKU_URL.'lib/exe/fetch.php?media='.$src);
                qrcode_media_url($this, $src, $title, 'pdf');
            }
        }else{
            if (!$_SESSION['u0']){
                $this->code('FIXME internalmedia ('.$type.'): '.$src);
            }
        }
    }

    function externalmedia ($src, $title=NULL, $align=NULL, $width=NULL,
                            $height=NULL, $cache=NULL, $linking=NULL) {
        global $conf;
        list($ext,$mime) = mimetype($src);
        if (substr($mime,0,5) == 'image'){
            $tmp_name = tempnam(DOKU_PLUGIN_LATEX_TMP.$this->tmp_dir.'/media', 'ext');
            $client = new DokuHTTPClient;
            $img = $client->get($src);
            if (!$img) {
                $this->externallink($src, $title);
            } else {
                $tmp_img = fopen($tmp_name, "w") or die("Can't create temp file $tmp_img");
                fwrite($tmp_img, $img);
                fclose($tmp_img);
		//Add and convert image to pdf
                $this->_latexAddImage($tmp_name, $width, $height, $align, $title, $linking, TRUE);
            }
        }else{
            $this->externallink($src, $title);
        }
    }

    function camelcaselink($link) {
        $this->internallink($link, $link);
    }

    /**
     * Render an internal Wiki Link
     */
    function internallink($id, $name = NULL) {
        // default name is based on $id as given
        $default = $this->_simpleTitle($id);
        // now first resolve and clean up the $id
        resolve_pageid(getNS($this->id),$id,$exists);
        $name = $this->_getLinkTitle($name, $default, $isImage, $id);
        list($page, $section) = preg_split('/#/', $id, 2);
        if (!empty($section)){
            $cleanid = noNS(cleanID($section, TRUE));
        }else{
            $cleanid = noNS(cleanID($id, TRUE));
        }
        $md5 = md5($cleanid);

        $this->doc .= '\hyperref[';
        $this->doc .= $md5;
        $this->doc .= ']{';
        $this->doc .= $name;
        $this->doc .= '}';
    }

    /**
     * Add external link
     */
    function externallink($url, $title = NULL) {
        //$url = $this->_xmlEntities($url);
        //Escape # only inside iocelem
        if ($_SESSION['iocelem']){
            $url = preg_replace('/(#|%)/','\\\\$1', $url);
        }
        if (!$title){
            $this->doc .= '\url{'.$url.'}';
        }else{
            $title = $this->_getLinkTitle($title, $url, $isImage);
            if (is_string($title)){
                $this->doc .= '\href{'.$url.'}{'.$title.'}';
            }else{//image
                if (preg_match('/http|https|ftp/', $title['src'])){
                    $this->externalmedia($title['src'],null,$title['align'],$title['width'],null,null,$url);
                }else{
                    $this->internalmedia($title['src'],null,$title['align'],$title['width'],null,null,$url);
                }
            }
        }
   }

    /**
     * Just print local links
     *
     * @fixme add image handling
     */
    function locallink($hash, $name = NULL){
        $name = $this->_getLinkTitle($name, $hash, $isImage);
        $this->doc .= $name;
    }

    /**
     * InterWiki links
     */
    function interwikilink($match, $name = NULL, $wikiName, $wikiUri) {}

    /**
     * Just print WindowsShare links
     *
     * @fixme add image handling
     */
    function windowssharelink($url, $name = NULL) {
        $this->unformatted('[['.$link.'|'.$title.']]');
    }

    /**
     * Just print email links
     *
     * @fixme add image handling
     */
    function emaillink($address, $name = NULL) {
        $this->doc .= '\href{mailto:'.$this->_xmlEntities($address).'}{'.$this->_xmlEntities($address).'}';
    }

    /**
     * Construct a title and handle images in titles
     *
     * @author Harry Fuecks <hfuecks@gmail.com>
     */
    function _getLinkTitle($title, $default, & $isImage, $id=null) {
        global $conf;

        $isImage = FALSE;
        if ( is_null($title) ) {
            if ($conf['useheading'] && $id) {
                $heading = p_get_first_heading($id);
                if ($heading) {
                      return $this->_xmlEntities($heading);
                }
            }
            return $this->_xmlEntities($default);
        } else if ( is_string($title) ) {
            return $this->_xmlEntities($title);
        } else if ( is_array($title) ) {
            $isImage = TRUE;
            if (isset($title['caption'])) {
                $title['title'] = $title['caption'];
            } else {
                $title['title'] = $default;
            }
            return $title;
        }
    }

    function _xmlEntities($value) {
        static $find = array('{', '}', '\\', '_', '^', '<', '>', '#', '%', '$', '&', '~', '"', '−');
        static $replace = array('@IOCKEYSTART@', '@IOCKEYEND@', '\textbackslash ', '@IOCBACKSLASH@_', '@IOCBACKSLASH@^{}',
				'@IOCBACKSLASH@textless{}','@IOCBACKSLASH@textgreater{}','@IOCBACKSLASH@#','@IOCBACKSLASH@%',
                                '@IOCBACKSLASH@$', '@IOCBACKSLASH@&', '@IOCBACKSLASH@~{}', '@IOCBACKSLASH@textquotedbl{}', '-');

        if ($this->monospace){
            $value = str_ireplace($find, $replace, $value);
            return preg_replace('/\n/', '\\newline ', $value);
        }else{
            return str_ireplace($find, $replace, $value);
        }
    }

    function _ttEntities($value) {
        global $symbols;
        return str_ireplace($symbols, ' (Invalid character) ', $value);
    }

    function _latexElements($value){
        //LaTeX mode
        $replace = FALSE;
        while(preg_match('/<latex>(.*?)<\/latex>/', $value, $matches)){
            $text = str_ireplace($symbols, ' (Invalid character) ', $matches[1]);
			$text = preg_replace('/(\$)/', '\\\\$1', $text);
			$value = preg_replace('/<latex>(.*?)<\/latex>/', filter_tex_sanitize_formula($text), $value, 1);
			$replace = TRUE;
        }
        //Math block mode
        while(preg_match('/\${2}\n?([^\$]+)\n?\${2}/', $value, $matches)){
            $text = str_ireplace($symbols, ' (Invalid character) ', $matches[1]);
			$text = preg_replace('/(\$)/', '\\\\$1', $text);
            $value = preg_replace('/\${2}\n?([^\$]+)\n?\${2}/', '\begin{center}\begin{math}'.filter_tex_sanitize_formula($text).'\end{math}\end{center}', $value, 1);
            $replace = TRUE;
        }
        //Math inline mode
        if(preg_match_all('/\$\n?([^\$]+)\n?\$/', $value, $matches, PREG_SET_ORDER)){
            foreach($matches as $m){
                $text = str_ireplace($symbols, ' (Invalid character) ', $m[1]);
    			$text = preg_replace('/(\$)/', '\\\\$1', $text);
                $value = str_replace($m[0], '$ '.filter_tex_sanitize_formula($text).' $', $value);
                $replace = TRUE;
            }
        }
        return array($value, $replace);
    }

    function rss ($url,$params){
        global $lang;
        global $conf;

        require_once(DOKU_INC.'inc/FeedParser.php');
        $feed = new FeedParser();
        $feed->feed_url($url);

        //disable warning while fetching
        if (!defined('DOKU_E_LEVEL')) { $elvl = error_reporting(E_ERROR); }
        $rc = $feed->init();
        if (!defined('DOKU_E_LEVEL')) { error_reporting($elvl); }

        //decide on start and end
        if($params['reverse']){
            $mod = -1;
            $start = $feed->get_item_quantity()-1;
            $end   = $start - ($params['max']);
            $end   = ($end < -1) ? -1 : $end;
        }else{
            $mod   = 1;
            $start = 0;
            $end   = $feed->get_item_quantity();
            $end   = ($end > $params['max']) ? $params['max'] : $end;;
        }

        $this->listu_open();
        if($rc){
            for ($x = $start; $x != $end; $x += $mod) {
                $item = $feed->get_item($x);
                $this->listitem_open(0);
                $this->listcontent_open();
                $this->externallink($item->get_permalink(),
                                    $item->get_title());
                if($params['author']){
                    $author = $item->get_author(0);
                    if($author){
                        $name = $author->get_name();
                        if(!$name) $name = $author->get_email();
                        if($name) $this->cdata(' '.$lang['by'].' '.$name);
                    }
                }
                if($params['date']){
                    $this->cdata(' ('.$item->get_date($conf['dformat']).')');
                }
                if($params['details']){
                    $this->cdata(strip_tags($item->get_description()));
                }
                $this->listcontent_close();
                $this->listitem_close();
            }
        }else{
            $this->listitem_open(0);
            $this->listcontent_open();
            $this->emphasis_open();
            $this->cdata($lang['rssfailed']);
            $this->emphasis_close();
            $this->externallink($url);
            $this->listcontent_close();
            $this->listitem_close();
        }
        $this->listu_close();
    }
}
