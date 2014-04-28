<?php
/**
 * LaTeX Plugin: Calculate characters
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author Marc Català <mcatala@ioc.cat>
 */

if (!defined('DOKU_INC')) define('DOKU_INC',dirname(__FILE__).'/../../../');
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once(DOKU_INC.'/inc/init.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/ContentCounterClass.php');

if (!checkPerms(getID())) return FALSE;
 session_start();
 countCharacters(getID());
 session_destroy();

    /**
    *
    * Count characters for the path indicated
    * @param string $path
    */
    function countCharacters($id){
        global $conf;
        $startPageName = $conf['start'];
        $ns = getNS($id);
        $pageName = noNS($id);
        if($startPageName===$pageName){
            $result=  countCharactersOfIndex($ns, $pageName);
        }else{
            $result= countCharactersOfDocument($ns, $pageName);
        }
        echo $result->toJsonEncode();
    }

    function countCharactersOfIndex($ns, $pageName){
        global $conf;
        $contentPage=$conf["plugin"]["iocexportl"]["ContentPageName"]; /*TO DO: set content name via properties*/
//        $contentPage="continguts"; /*TO DO: set content name via properties*/
        $result = new ContentCounterClass();
        $pathFile = wikiFN($ns.":".$pageName);
        if (file_exists($pathFile)){
            $content = io_readFile($pathFile);
             if (preg_match('/^index/i', $content)){
                $contentArray = explode(DOKU_LF,$content);
                $contentArray = array_filter($contentArray);
                @array_shift($contentArray);
                foreach ($contentArray as $p){
                    if(!preg_match("/^".$contentPage."$|:"
                                        .$contentPage."$/i", $p)){
                        $pathFile = wikiFN($ns.":".$p);
                        _countCharactersOfDocument($pathFile, $result);
                    }
                }
             }
        }else{
            $result = null; /*TO DO canviar per un objecte error */
        }
        return $result;
    }

    function countCharactersOfDocument($ns, $pageName){
        $result = new ContentCounterClass();
        $pathFile = wikiFN($ns.":".$pageName);
        if (file_exists($pathFile)){
            _countCharactersOfDocument($pathFile, $result);
        }else{
            $result = null; /*TO DO canviar per un objecte error */
        }
        return $result;
    }
     
     
    function _countCharactersOfDocument($pathFile, &$result){
        $text = io_readFile($pathFile);
        $text = preg_replace('/<noprint>\n?<noweb>\n?(<verd>.*?<\/verd>)\n?<\/noweb>\n?<\/noprint>/', '$1',$text);
        $instructions = get_latex_instructions($text);
        $clean_text = p_latex_render('ioccounter', $instructions, $info);
        if (preg_match('/::IOCVERDINICI::/', $clean_text)){
            $matches = array();
            preg_match_all('/(?<=::IOCVERDINICI::)(.*?)(?=::IOCVERDFINAL::)/', $clean_text, $matches, PREG_SET_ORDER);
            $newContent = '';
            foreach ($matches as $m){
                $newContent .= $m[1];
            }
            $result->incNewContent(mb_strlen($newContent));
            $reusedContent = preg_replace('/::IOCVERDINICI::.*?::IOCVERDFINAL::/', '', $clean_text);
            $result->incReusedContent(mb_strlen($reusedContent));
        }else if (preg_match('/::IOCNEWCONTENTINICI::/', $clean_text)){
            $matches = array();
            preg_match_all('/(?<=::IOCNEWCONTENTINICI::)(.*?)(?=::IOCNEWCONTENTFINAL::)/', $clean_text, $matches, PREG_SET_ORDER);
            $newContent = '';
            foreach ($matches as $m){
                $newContent .= $m[1];
            }
            $result->incNewContent(mb_strlen($newContent));
            $reusedContent = preg_replace('/::IOCNEWCONTENTINICI::.*?::IOCNEWCONTENTFINAL::/', ' ', $clean_text);
            $result->incReusedContent(mb_strlen($reusedContent));
        }else{
            $result->incTotal(mb_strlen($clean_text));
        }
    }

    /**
     *
     * Check whether user has right acces level
     */
    function checkPerms($id) {
        global $USERINFO;

        $user = $_SERVER['REMOTE_USER'];
        $groups = $USERINFO['grps'];
        $aclLevel = auth_aclcheck($id,$user,$groups);
        // AUTH_ADMIN, AUTH_READ,AUTH_EDIT,AUTH_CREATE,AUTH_UPLOAD,AUTH_DELETE
        return ($aclLevel >=  AUTH_UPLOAD);
      }
