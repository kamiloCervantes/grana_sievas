<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Translations
 *
 * @author Usuario
 */



class Translator {
    private $lang = array();
    
    public function __construct(){

    }
    
    public function test(){

    }
    
    private function findString($str,$lang) {
        if (array_key_exists($str, $this->lang[$lang])) {
            return $this->lang[$lang][$str];
        }
        return $str;
    }
    
    private function splitStrings($str) {
        return explode('=',trim($str));
    }
    
    public function __($str,$lang) {
        if (!array_key_exists($lang, $this->lang)) {           
            if (file_exists(CORE_PATH.'/i18n/'.$lang.'.txt')) {
                $strings = array_map(array($this,'splitStrings'),file(CORE_PATH.'/i18n/'.$lang.'.txt'));
                foreach ($strings as $k => $v) {
                    $this->lang[$lang][$v[0]] = $v[1];
                }
                return $this->findString($str, $lang);
            }
            else {
                return $str;
            }
        }
        else {
            return $this->findString($str, $lang);
        }
    }
}