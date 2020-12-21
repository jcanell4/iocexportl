<?php
/**
 * Iocquiz tag Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocquiz extends DokuWiki_Syntax_Plugin {

    var $class;

    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-03-21',
            'name'   => 'IOC quiz Plugin',
            'desc'   => 'Plugin to parse quiz tags',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){
        return 'container';
    }

    function getPType(){
        return 'block';
    } //stack, block, normal

    function getSort(){
        return 514;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<quiz.*?>(?=.*?</quiz>)',$mode,'plugin_iocexportl_iocquiz');
    }
    function postConnect() {
        $this->Lexer->addExitPattern('</quiz>','plugin_iocexportl_iocquiz');
    }

    /**
     * Handle the match
     */

    function handle($match, $state, $pos, Doku_Handler $handler){
        $opt = array();
        switch ($state) {
            case DOKU_LEXER_ENTER :
                $class = trim(mb_substr($match,5,-1));
                return array($state, $class);

            case DOKU_LEXER_UNMATCHED :
                return array($state, $match);

            default:
                return array($state);
        }
    }

   /**
    * output
    */
    function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode === 'ioccounter'){
            list($state, $text) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :
                  break;
              case DOKU_LEXER_UNMATCHED :
                  $instructions = get_latex_instructions($text);
                  $renderer->doc .= p_latex_render($mode, $instructions, $info);
                  break;
              case DOKU_LEXER_EXIT :
                  break;
            }
            return TRUE;
        }
        elseif ($mode === 'iocexportl'){
            list($state, $text) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :
                  $this->class = $text;
                  unset($_SESSION['quizsol']);
                  break;
              case DOKU_LEXER_UNMATCHED :
                  //convert unnumered lists to numbered
                  $_SESSION['quizmode'] = $this->class;
                  if ($this->class !== 'complete' && $this->class !== 'relations'){
                     $text = $this->getsolutions($text);
                  }
                  if ($this->class === 'relations'){
                      $text = preg_replace('/(\n)(\n  \*)/', '$1'.DOKU_LF.'@IOCRELATIONS@'.DOKU_LF.'$2', $text, 1);
                  }
                  $instructions = get_latex_instructions($text);
                  $renderer->doc .= p_latex_render($mode, $instructions, $info);
                  $_SESSION['quizmode'] = FALSE;
                  break;
              case DOKU_LEXER_EXIT :
                  if ($this->class === 'relations'){
                      $this->printoptions($renderer);
                  }
                  $this->printsolutions($renderer);
                  $this->class='';
                  unset($_SESSION['quizsol']);
                  break;
            }
            return TRUE;
        }
        elseif (strpos("xhtml/iocxhtml/wikiiocmodel_ptxhtml", $mode) !== FALSE){
            list($state, $text) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :
                  $this->class = $text;
                  break;
              case DOKU_LEXER_UNMATCHED :
                  //convert unnumered lists to tables
                  $_SESSION['quizmode'] = $this->class;
                  if ($this->class !== 'complete' && $this->class !== 'relations'){
                     $text = $this->getsolutions($text);
                  }
                  $this->printquiz($text, $mode, $renderer);
                  $_SESSION['quizmode'] = FALSE;
                  break;
              case DOKU_LEXER_EXIT :
                  $this->class='';
                  unset($_SESSION['quizsol']);
                  break;
            }
            return TRUE;
        }
        return FALSE;
    }

    function getsolutions($text){
        $matches = array();
        $_SESSION['quizsol'] = array();
        if ($this->class === 'choice'){
            preg_match_all('/  \*.*?\n/', $text, $matches);
            $count = 1;
            foreach ($matches[0] as $match){
                if (preg_match('/\(ok\)/i',$match)){
                    array_push($_SESSION['quizsol'], $count);
                }
                $count += 1;
            }
            $text = preg_replace('/(  \*.*?)\(ok\)/i', '$1', $text);
        }elseif ($this->class === 'vf'){
            preg_match_all('/  \*.*?\((V|F)\)/', $text, $matches);
            foreach ($matches[1] as $match){
                array_push($_SESSION['quizsol'], $match);
            }
            $text = preg_replace('/(  \*.*?)\((V|F)\)/', '$1', $text);
        }
        return $text;
    }

    function printsolutions($renderer){
        if (!empty($_SESSION['quizsol'])){
            $renderer->doc .= '\rotatebox[origin=c]{180}{'.DOKU_LF;
            $renderer->doc .= '\parbox{\textwidth}{ \small';
            $renderer->doc .= '\textbf{Solució: }';
            if ($this->class !== 'choice'){
                $renderer->doc .= '\begin{inparaenum}'.DOKU_LF;
            }
            $count = count($_SESSION['quizsol']);
            $separator = ($this->class === 'choice')?',':';';
            foreach ($_SESSION['quizsol'] as $key => $sol){
              if ($this->class !== 'choice'){
                  $renderer->listitem_open(1);
              }
              $renderer->doc .= '\textit{'.$sol.'}';//.'\hspace{2mm}';
              if ($key < $count-1){
                  $renderer->doc .= $separator.'\hspace{1mm}';
              }

            }
            if ($this->class !== 'choice'){
                $renderer->doc .= '\end{inparaenum}'.DOKU_LF;
            }
            $renderer->doc .= '}}'.DOKU_LF;
            unset ($_SESSION['quizsol']);
       }
    }

    function printoptions($renderer){
      if (!empty($_SESSION['quizsol'])){
          $sol = array();
          $aux = array();
          foreach ($_SESSION['quizsol'] as $s){
              array_push($sol,$s);
              array_push($aux,$s);
          }
          $_SESSION['quizsol'] = array();
          //Sort solutions
          sort($sol);
          foreach ($aux as $s){
              $pos = array_search($s, $sol, TRUE);
              array_push($_SESSION['quizsol'],chr(ord('a')+$pos));
          }
          $text = '\optrelations{'.DOKU_LF;
          $count = count($sol);
          foreach ($sol as $key => $s){
              $text .= '\mbox{';
              $text .= '\item ';
              if (strlen($s) > 75){
                  $text .= '\parbox[t]{.85\linewidth}{\begin{spacing}{.6}\quizoptions ';
              }
              $text .= $s;
              if (strlen($s) > 75){
                  $text .= '\end{spacing}\hspace{10mm}}';
              }
              $text .= '}';
              if ($key < $count-1){
              	$text .= '\hspace{5mm}';
              }
          }
          $text .= '}'.DOKU_LF;
          $renderer->doc = preg_replace('/@IOCRELATIONS@/',$text, $renderer->doc, 1);
      }
    }

    function printquiz($text, $mode, $renderer, $export = FALSE){
        //Get and print statement
        preg_match('/^(?<!  \*)(.*?\n)+(?=\n+  \*)/', $text, $matches);
        $text = str_replace($matches[0], '', $text);
        $instructions = get_latex_instructions($matches[0]);
        $renderer->doc .=  p_latex_render('iocxhtml', $instructions, $info);
        //Get options
        preg_match_all('/  \*(.*?)\n/', $text, $matches);
        $renderer->doc .= '<div id="id_'.$this->class.'_'.md5($text).'" class="quiz">';
        $renderer->doc .= '<form action="">';
        $renderer->doc .= '<table>';
        $renderer->doc .= '<tr>';
        $renderer->doc .= '<th>Núm</th><th>Pregunta</th>';
        if ($this->class == 'vf'){
            $renderer->doc .= '<th>V</th><th>F</th>';
        }elseif($this->class == 'choice'){
            $renderer->doc .= '<th></th>';
        }elseif($this->class == 'relations'){
            $renderer->doc .= '<th>Resposta</th>';
        }
        $cont = 1;
        $renderer->doc .= '</tr>';
        foreach ($matches[1] as $k => $m){
            if (($this->class === 'complete' || $this->class === 'relations') &&
                preg_match('/^\s*-/',$m)){
                $instructions = get_latex_instructions($m);
                p_latex_render('iocxhtml', $instructions, $info);
                continue;
            }
            $renderer->doc .= '<tr>';
            $renderer->doc .= '<td>'.($k+1).'</td>';
            if ($mode === 'xhtml') {
		$_SESSION['xhtml_latex_quiz'] = TRUE;
            }
            $instructions = get_latex_instructions($m);
            $renderer->doc .=  '<td>'.p_latex_render('iocxhtml', $instructions, $info).'</td>';
            if ($mode === 'xhtml') {
		$_SESSION['xhtml_latex_quiz'] = FALSE;
            }
            if ($this->class === 'complete' || $this->class === 'relations'){
                preg_match_all('/@IOCDROPDOWN@/', $renderer->doc, $match);
                foreach ($match[0] as $elem){
                    $renderer->doc = preg_replace('/@IOCDROPDOWN@/', '@IOCDROPDOWN'.$cont.'@', $renderer->doc, 1);
                    $cont += 1;
                }
            }
            if ($this->class == 'vf'){
                $renderer->doc .= '<td><input type="radio" value="V" name="sol_'.$cont.'"></input></td>';
                $renderer->doc .= '<td><input type="radio" value="F" name="sol_'.$cont.'"></input></td>';
                $cont += 1;
            }elseif($this->class == 'choice'){
                $renderer->doc .= '<td><input type="checkbox" value="V" name="sol_'.$cont.'"></input></td>';
                $cont += 1;
            }elseif($this->class == 'relations'){
                $renderer->doc .= '<td>@IOCDROPDOWN'.$cont.'@</td>';
                $cont += 1;
            }
            $renderer->doc .= '</tr>';
        }
        if ($this->class === 'complete' || $this->class === 'relations'){
            $options = '';
            $num = 1;
            $sol = array();
            $aux = array();
            $used = array();
            if (!empty($_SESSION['quizsol'])){
                foreach ($_SESSION['quizsol'] as $s){
                  array_push($sol,$s);
                  array_push($aux,$s);
                }
            }
            //Remove duplicated values
            $aux = array_unique($aux);
            //Sort solutions
            sort($sol);
            //Default option
            $options .= '<option value="sol_0">@IOCDOTS@</option>'.DOKU_LF;
            $max_length = 1;
            $max_option_length = 80;
            foreach ($sol as $opt){
                $pos = array_search($opt, $aux, TRUE);
                if (is_numeric($pos)){
                    if (is_numeric(array_search($pos, $used, TRUE))){
                        continue;
                    }else{
                        $title = '';
                        array_push($used, $pos);
                        $opt = str_replace(array('<p>','</p>'), '', $opt);
                        if (strlen($opt) > $max_option_length){
                            $title = ' title="'.$opt.'"';
                            $opt = mb_substr($opt, 0, $max_option_length) . '...';
                        }
                        if (strlen($opt) > $max_length){
                            $max_length = strlen($opt);
                        }
                        $options .= '<option value="sol_'.($pos+1).'"'.$title.'>'.$opt.'</option>'.DOKU_LF;
                    }
                }
            }
            $max_length = max($max_length, 20);
            $options = preg_replace('/@IOCDOTS@/', str_repeat(".", $max_length), $options, 1);
            $cont = 1;
            if (!empty($_SESSION['quizsol'])){
                foreach ($_SESSION['quizsol'] as $s){
                    $pos = array_search($s, $aux, TRUE);
                    $renderer->doc = preg_replace('/@IOCDROPDOWN'.$cont.'@/', '<select name="sol_'.($pos+1).'">'.$options.'</select>', $renderer->doc, 1);
                    $cont += 1;
                }
            }
        }
        $renderer->doc .= '</table>';
        $renderer->doc .= '<input type="hidden" name="qtype" value="'.$this->class.'"></input>';
        if ($this->class !== 'complete'){
            $renderer->doc .= '<input type="hidden" name="qnum" value="'.($cont-1).'"></input>';
            $renderer->doc .= '<input type="hidden" name="qsol" value="'.implode(',', $_SESSION['quizsol']).'"></input>';
        }
        $checkquiz2 = ($this->class === 'complete' || $this->class === 'relations' );
        $value = (!empty($_SESSION['IOCSOLUTION']))?$_SESSION['IOCSOLUTION']:'Soluci&oacute;';
        $renderer->doc .= '<input class="btn_solution'.(($checkquiz2)?'2':'').'" type="button" value="'.$value.'">';
        $renderer->doc .= '</form>';
        $renderer->doc .= '<div class="quiz_result"></div>';
        $renderer->doc .= '</div>';
    }
}
