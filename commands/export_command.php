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
abstract class export_command extends abstract_command_class {
    public function getAuthorizationType() {
        return "save";
    }
    
    public function isEmptyText(){
        return FALSE;
    }
}
