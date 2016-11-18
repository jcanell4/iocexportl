<?php
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
if(!defined('DOKU_COMMAND')) define('DOKU_COMMAND', DOKU_PLUGIN . "ajaxcommand/");
if (!defined('DOKU_IOCEXPORTL')) define('DOKU_IOCEXPORTL',DOKU_INC.'lib/plugins/iocexportl/');
require_once(DOKU_COMMAND . 'AjaxCmdResponseGenerator.php');
require_once(DOKU_COMMAND . 'JsonGenerator.php');
require_once(DOKU_COMMAND . 'abstract_command_class.php');
require_once(DOKU_IOCEXPORTL . 'generate_latex.php');


/**
 * Class cancel_command
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
class export_pdf_command extends abstract_command_class {

    /**
     * Constructor per defecte que estableix el tipus id.
     */
    public function __construct() {
        parent::__construct(new generate_latex());
        $this->types['id'] = abstract_command_class::T_STRING;
        $this->types['mode'] = abstract_command_class::T_STRING;
        $this->types['ioclanguage'] = abstract_command_class::T_STRING;
        $this->types['toexport'] = abstract_command_class::T_STRING;
    }

    public function setParameters($params) {
        parent::setParameters($params);
        $params["needReturnData"] = true;
        $params["form_by_columns"] = true;
        $this->modelWrapper->initParams($params);
    }

    /**
     * Cancela la edició.
     *
     * @return string[] array associatiu amb la resposta formatada (id, ns, tittle i content)
     */
    protected function process() {
        $contentData = $this->modelWrapper->init();
        return $contentData;

    }

    /**
     * Afegeix una resposta de tipus HTML_TYPE al generador de respostes passat com argument.
     *
     * @param mixed                    $response // TODO[Xavi] No es fa servir per a res?
     * @param AjaxCmdResponseGenerator $ret      objecte al que s'afegirà la resposta
     *
     * @return void
     */
    protected function getDefaultResponse($response, &$ret) {
        if($response){
            $response[action_plugin_iocexportl::DATA_PAGEID] = $this->params['id']; 
            $response[action_plugin_iocexportl::DATA_IOCLANGUAGE] = $this->params['ioclanguage'];
            $response[action_plugin_iocexportl::DATA_IS_ZIP_RADIO_CHECKED] = $this->params['mode']==="zip";        
            $meta = action_plugin_iocexportl::getform_latex_from_data($response);    
            $pageId = str_replace( ":", "_", $this->params['id'] );
            $ret->addExtraMetadata($pageId, 
                   array(
                       "id" => $pageId."_iocexportl",
                       "content" => $meta)
                   );  
        }else{
            $ret->addError(1000, "EXPORTACIÓ NO REALITZADA");  //[TODO] codi i internacionaLITZACIÓ
        }         
    }
}
