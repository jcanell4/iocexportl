<?php
/**
 * Table Syntax Plugin
 * @author     Josep Cañellas <jcanell4@ioc.cat>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocbtable extends DokuWiki_Syntax_Plugin {
    const SKIP = 0;
    const PROCESS = 1;

    var $tableStruct = null;
    var $currentCell = null;
    var $currentRow = null;
    
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Josep Cañellas',
            'email'  => 'jcanell4@ioc.cat',
            'date'   => '2018-05-20',
            'name'   => 'IOC latex Plugin',
            'desc'   => 'Plugin to parse multiline tables',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'container';
    }

    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'block';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 59;
    }

    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'
    function getAllowedTypes() {
        return array('formatting', 'substition', 'disabled', 'protected', 'container');
    }
//    function getAllowedTypes() {
//        global $PARSER_MODES;
//        
//        return array_merge (
//                $PARSER_MODES['formatting'],
//                $PARSER_MODES['substition'],
//                $PARSER_MODES['disabled'],
//                $PARSER_MODES['protected']
//            );
//    }
    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        //inici de taula
        $this->Lexer->addEntryPattern('[\t ]*\n[\t ]*\[\^',$mode,'plugin_iocexportl_iocbtable');
        $this->Lexer->addEntryPattern('[\t ]*\n[\t ]*\[\|',$mode,'plugin_iocexportl_iocbtable');
    }

    function postConnect() {
        //aliniació
        $this->Lexer->addPattern('[\t ]*:::[\t ]*(?=[\|\^])','plugin_iocexportl_iocbtable');
        $this->Lexer->addPattern('[\t ]+','plugin_iocexportl_iocbtable');

        //final de taula
        $this->Lexer->addExitPattern('\^{2,}\][\t ]*\n','plugin_iocexportl_iocbtable');
        $this->Lexer->addExitPattern('\|{2,}\][\t ]*\n','plugin_iocexportl_iocbtable');

        $this->Lexer->addExitPattern('\^\][\t ]*\n','plugin_iocexportl_iocbtable');
        $this->Lexer->addExitPattern('\|\][\t ]*\n','plugin_iocexportl_iocbtable');

        //final
        $this->Lexer->addPattern('\^{2,}[\t ]*\n','plugin_iocexportl_iocbtable');
        $this->Lexer->addPattern('\|{2,}[\t ]*\n','plugin_iocexportl_iocbtable');

        $this->Lexer->addPattern('\^[\t ]*\n','plugin_iocexportl_iocbtable');
        $this->Lexer->addPattern('\|[\t ]*\n','plugin_iocexportl_iocbtable');


        //inicial i intermedis
        $this->Lexer->addPattern('\^{2,}','plugin_iocexportl_iocbtable');
        $this->Lexer->addPattern('\|{2,}','plugin_iocexportl_iocbtable');

        $this->Lexer->addPattern('\^','plugin_iocexportl_iocbtable');
        $this->Lexer->addPattern('\|','plugin_iocexportl_iocbtable');
    }


    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        $inst;
        $data = array("command" => self::SKIP);
        
        $calls = array();
        while(!empty($handler->calls) && !$this->isCallMine(end($handler->calls))){
            array_unshift($calls, array_pop($handler->calls));
        }
        foreach ($calls as $call){
            $content = new ContentCell(ContentCell::CALL_CONTENT, $call);
            $this->currentCell->addContent($content);                        
        }
        
        switch ( $state ) {
            case DOKU_LEXER_ENTER:
                if ( trim($match) == '[^' ) {
                    $this->currentCell = new CellStructure(CellStructure::T_HEADER);
                }else{
                    $this->currentCell = new CellStructure(CellStructure::T_CELL);
                }
                $this->currentRow = new RowStructure();
                $this->tableStruct = new TableStructure();
                break;

            case DOKU_LEXER_EXIT:        
                if(preg_match('/\^{2,}\][\t ]*\n/', $match) || preg_match('/\|{2,}\][\t ]*\n/', $match)){
                    $nlimit = strlen(trim($match));
                    for($i=2; $i<$nlimit; $i++){                    
                        $content = new ContentCell(ContentCell::COLSPAN_CONTENT);
                        $this->currentCell->addContent($content);
                    }
                }
                $this->currentRow->addColumn($this->currentCell);
                $this->currentCell = NULL;                    
                $this->tableStruct->addRow($this->currentRow);
                $this->currentRow = NULL;

                $data = array(
                    "command" => self::PROCESS, 
                    "table" => $this->tableStruct
                );
            break;

            case DOKU_LEXER_UNMATCHED:
                if ( trim($match) != '' ) {
                    $content = new ContentCell(ContentCell::CDATA_CONTENT, $match);
                    $this->currentCell->addContent($content);
                }
            break;

            case DOKU_LEXER_MATCHED:
                if ( $match == ' ' ){
                    $content = new ContentCell(ContentCell::CDATA_CONTENT, $match);
                    $this->currentCell->addContent($content);
                } else if ( preg_match('/\|{2,}[\t ]*\n/',$match) || preg_match('/\^{2,}[\t ]*\n/',$match)) { 
                    $nlimit = strlen(trim($match));
                    for($i=1; $i<$nlimit; $i++){                    
                        $content = new ContentCell(ContentCell::COLSPAN_CONTENT);
                        $this->currentCell->addContent($content);
                    }
                    $this->currentRow->addColumn($this->currentCell);
                    $this->currentCell = NULL;                    
                    $this->tableStruct->addRow($this->currentRow);
                    $this->currentRow = NULL;                    
                } else if ( preg_match('/\|{2,}/',$match) || preg_match('/\^{2,}/',$match) ) {  
                    if($this->currentRow==NULL){
                        $this->currentRow = new RowStructure();
                    }                    
                    for($i=1; $i<strlen($match); $i++){                    
                        $content = new ContentCell(ContentCell::COLSPAN_CONTENT);
                        $this->currentCell->addContent($content);
                    }
                } else if ( preg_match('/[\t ]*:::[\t ]*/',$match) ) {
                    if(empty($this->currentCell->content)){
                        $ncol = count($this->currentRow->cells);
                        $nrow = count($this->tableStruct->rows)-1;
                        while($nrow>=0 && $this->tableStruct->rows[$nrow]->cells[$ncol]->type== CellStructure::NON_CELL){
                            $nrow--;
                        }
                        if($nrow>=0){
                            $content = new ContentCell(ContentCell::ROWSPAN_CONTENT);
                            $this->tableStruct->rows[$nrow]->cells[$ncol]->addContent($content);
                        }
                        $content = new ContentCell(ContentCell::NON_CONTENT);                        
                        $this->currentCell->addContent($content);
                    }else{
                        $content = new ContentCell(ContentCell::CDATA_CONTENT, ' '.trim($match).' ');
                        $this->currentCell->addContent($content);
                    }
                } else if ( preg_match('/\t+/',$match) ) {
                    $content = new ContentCell(ContentCell::ALLIGN_CONTENT, $match);
                    $this->currentCell->addContent($content);
                } else if ( preg_match('/ {2,}/',$match) ) {
                    $content = new ContentCell(ContentCell::ALLIGN_CONTENT, $match);
                    $this->currentCell->addContent($content);
                } else if ( $match == "|" ) {
                    if($this->currentRow==NULL){
                        $this->currentRow = new RowStructure();
                        $this->currentCell=new CellStructure(CellStructure::T_CELL);
                    }else{
                        $this->currentRow->addColumn($this->currentCell);
                        $this->currentCell=new CellStructure(CellStructure::T_CELL);
                    }
                } else if ( $match == "^" ) {
                    if($this->currentRow==NULL){
                        $this->currentRow = new RowStructure();
                        $this->currentCell=new CellStructure(CellStructure::T_HEADER);
                    }else{
                        $this->currentRow->addColumn($this->currentCell);
                        $this->currentCell=new CellStructure(CellStructure::T_HEADER);
                    }
                } else if (preg_match ('/\^[\t ]*\n/', $match) || preg_match ('/\|[\t ]*\n/', $match)){
                    $this->currentRow->addColumn($this->currentCell);
                    $this->currentCell = NULL;                    
                    $this->tableStruct->addRow($this->currentRow);
                    $this->currentRow = NULL;
                }
            break;
        }
        return array($state, $data);
    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {        
        switch ($mode){
            case 'ioccounter':
            case 'iocexportl':
            case 'xhtml':
            case 'iocxhtml':
                list ($state, $toProcess) = $data;
                if($toProcess['command'] == self::PROCESS) {
                   $renderer->doc .= $toProcess['table']->render($mode, $renderer); 
                }
                break;
                $ret = TRUE;
            default :
                $ret = FALSE;
        }
        return $ret;
    }
    
    function _getDivClass($type=NULL){
        if($this->type === 'table'){
            $divclass = '<div class="ioctable';
        }else{
            $divclass = '<div class="iocaccounting';
        }
        if(isset($type)){
            $divclass .= ' '. $type . '">';
        }else{
            $divclass .= '">';
        }
        return $divclass;        
    }
    
    function isCallMine($call){
        return $call[0]==='plugin' && $call[1][0] ==='iocexportl_iocbtable';
    }
}

class TableStructure{
    var $rows = array();

    public function addRow($r){
        $this->rows[] =  $r;                
    }
    
    function render($mode, &$renderer){
        //$doc="";
        if ($mode === 'ioccounter'){
            foreach ($this->rows as $row){
                //$doc .= $row->render($mode, $renderer);
                $row->render($mode, $renderer);
            }
        }elseif ($mode === 'iocexportl'){
        }elseif ($mode === 'xhtml'){
            $renderer->doc .= "<div class='table'>\n<table class='inline'>";
            foreach ($this->rows as $row){
                //$doc .= $row->render($mode, $renderer);
                $row->render($mode, $renderer);
            }
            $renderer->doc .= "</table>\n</div>";            
        }elseif ($mode === 'iocxhtml'){
        }
        //return $doc;
    }
}

class RowStructure{
    private static $n_renderRows = 0;
    var $cells = array();

    public function addColumn($c){
        $this->cells[] =  $c;                
    }
    
    function render($mode, &$renderer){
        //$doc="";
        if ($mode === 'ioccounter'){
            foreach ($this->cells as $cell){
//                $doc .= $cell->render($mode, $renderer);
                $cell->render($mode, $renderer);
            }
        }elseif ($mode === 'iocexportl'){
        }elseif ($mode === 'xhtml'){
            $class = 'row'. self::$n_renderRows++;
            $renderer->doc .= "<tr class='$class'>";
            foreach ($this->cells as $cell){
//                $doc .= $cell->render($mode, $renderer);
                $cell->render($mode, $renderer);
            }
            $renderer->doc .= "</tr>";            
        }elseif ($mode === 'iocxhtml'){
        }
        //return $doc;
    }
}

class CellStructure{
    const T_HEADER = 7;
    const T_CELL = 8;
    const NON_CELL = 9;

    private static  $n_renderCols = 0;
    var $type;
    var $colSpan = 1;
    var $rowSpan = 1;
    var $content = array();
    
    function CellStructure($type) {
        $this->type = $type;
    }
    
    function addContent($content){
        if($content->type == ContentCell::ROWSPAN_CONTENT){
            $this->rowSpan++;
        }else if($content->type == ContentCell::COLSPAN_CONTENT){
            $this->colSpan++;
        }else if($content->type == ContentCell::NON_CONTENT){
            $this->type= CellStructure::NON_CELL;
        }else{
            $this->content []= $content;                            
        }
    }
    
    function render($mode, &$renderer){
        //$doc="";
        if($this->type=== CellStructure::NON_CELL){
            return;
        }
        
        if ($mode === 'ioccounter'){
            foreach ($this->content as $value){
                $renderer->doc .= $value;
            }
        }elseif ($mode === 'iocexportl'){
        }elseif ($mode === 'xhtml'){
            $rowspan = $this->rowSpan>1?"rowspan='".$this->rowSpan."'":"";
            $colspan = $this->colSpan>1?"colspan='".$this->colSpan."'":"";
            $class = 'col'.self::$n_renderCols++;
            $align = "";
            if($this->content[0]->type == ContentCell::ALLIGN_CONTENT){
                unset($this->content[0]);
                $align = ' rightalign';
            }
            if(end($this->content)->type == ContentCell::ALLIGN_CONTENT){
                array_pop($this->content);
                $align = $align == ' rightalign'?' centeralign':' leftalign';
            }

            $class .= $align;
            if($this->type== CellStructure::T_HEADER){
                $renderer->doc .= "<th class='$class' $colspan $rowspan>";
            }else{
                $renderer->doc .= "<td  class='$class' $colspan $rowspan>";
            }
            foreach ($this->content as $content){
                if($content->type == ContentCell::CDATA_CONTENT){
                    $renderer->doc .= $content->data;
                }elseif($content->type == ContentCell::CALL_CONTENT){
                    if(method_exists($renderer, $content->data[0])){
                      call_user_func_array(array(&$renderer, $content->data[0]), $content->data[1] ? $content->data[1] : array());
                    }
                }
            }
            if($this->type== CellStructure::T_HEADER){
                $renderer->doc .= "</th>";
            }else{
                $renderer->doc .= "</td>";
            }
        }elseif ($mode === 'iocxhtml'){
        }
        //return $doc;
    }
}

class ContentCell{
    const CDATA_CONTENT = 3;
    const ROWSPAN_CONTENT = 4;
    const COLSPAN_CONTENT = 5;
    const ALLIGN_CONTENT= 6;
    const CALL_CONTENT= 7;
    const NON_CONTENT= 8;

    var $type;
    var $data;

    public function ContentCell($type, $data=NULL) {
        $this->type = $type;
        $this->data = $data;
    }    
}
