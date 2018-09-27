<?php

class WiocclValue {

    protected $text;
    protected $value;


    public function __construct($text) {
        $this->$text = $text;
    }

    public function getValue() {
        return $this->text;
    }
}