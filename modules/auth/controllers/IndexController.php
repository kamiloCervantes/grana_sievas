<?php
class indexController extends ControllerBase
{    
    public function index()//Equivale a login
    {
        $config =  Config::singleton();
        require_once $config->get('modulesFolder').'auth/views/login.php';        
    }       
}