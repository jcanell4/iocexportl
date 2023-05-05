<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of export_command
 *
 * @author josep
 */
abstract class export_command extends abstract_writer_command_class{

    public function __construct($modelAdapter) {
        parent::__construct($modelAdapter);
        $this->setPermissionFor(array('admin','manager','manualsfp'));
        // ALERTA! [Xavi] Cal disparar l'esdeveniment per que s'afegeixin les dades a JSINFO per l'editor
        // Actualment aquest esdeveniment es dispara a AbstractWikiAction#triggerStartEvents() però
        // aquests commands no fan servir actions.
        //
        // TODO: S'ha de moure la crida al trigger event a la classe AjaxCommand però no sabem si la implementació
        // de l'AbstractWikiAction és necessaria o no, perquè es fiquen els valors del paràmetre per referencia.
        //
        // També es troba un trigger al DoluModelAdapter, però sembla que no s'utilitza.

        $tmp= array(); //NO DATA
        trigger_event( 'WIOC_AJAX_COMMAND_STARTED', $tmp);
    }

    public function getAuthorizationType() {
        return "save";
    }    
}
