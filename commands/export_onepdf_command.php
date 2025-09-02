<?php
if(!defined('DOKU_INC')) die();
if (!defined('DOKU_IOCEXPORTL')) define('DOKU_IOCEXPORTL',DOKU_INC."lib/plugins/iocexportl/");
require_once(DOKU_IOCEXPORTL . "onepdf.php");
require_once(DOKU_IOCEXPORTL . 'commands/export_command.php');

/**
 * Class export_onepdf_command
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
class export_onepdf_command extends export_command {

    public function __construct() {
        parent::__construct(new onepdf());
        $this->types[AjaxKeys::KEY_ID] = self::T_STRING;
        $this->types['mode'] = self::T_STRING;
        $this->types['ioclanguage'] = self::T_STRING;
        $this->types['toexport'] = self::T_STRING;
//        $this->setPermissionFor(array('admin','manager'));
    }

    public function setParameters($params) {
        parent::setParameters($params);
        $params["needReturnData"] = true;
        $params["form_by_columns"] = true;
        $this->modelAdapter->initParams($params);
    }

    /**
     * @return string[] array associatiu amb la resposta formatada (id, ns, tittle i content)
     */
    protected function process() {
        $contentData = $this->modelAdapter->init();
        return $contentData;
    }

    /**
     * Afegeix una resposta de tipus HTML_TYPE al generador de respostes passat com argument.
     * @param mixed                    $response // TODO[Xavi] No es fa servir per a res?
     * @param AjaxCmdResponseGenerator $ret      objecte al que s'afegirà la resposta
     * @return void
     */
    protected function getDefaultResponse($response, &$ret) {
        if($response){
            $response[action_plugin_iocexportl::DATA_PAGEID] = $this->params[AjaxKeys::KEY_ID];
            $response[action_plugin_iocexportl::DATA_IOCLANGUAGE] = $this->params['ioclanguage'];
            $response[action_plugin_iocexportl::DATA_IS_ZIP_RADIO_CHECKED] = $this->params['mode']==="zip";
            $meta = action_plugin_iocexportl::getform_onepdf_from_data($response);
            $pageId = str_replace( ":", "_", $this->params[AjaxKeys::KEY_ID] );
            $ret->addExtraMetadata($pageId,
                   array(
                       AjaxKeys::KEY_ID => $pageId."_iocexportl",
                       "content" => $meta)
                   );
        }else{
            $ret->addError(1000, "EXPORTACIÓ NO REALITZADA");  //[TODO] codi i internacionaLITZACIÓ
        }
    }
}
