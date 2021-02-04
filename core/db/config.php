<?php
// Definicion de parametros globales de SIEVA

$config = array(
  'ruta'          => 'http://192.168.0.100/nosirve/', 	  					//Ruta URL donde reside el sitio
  'database'      => 'mysql',   									      	//Motor de database: oracle o mysql
  'timeout'		  =>  6000,													//Tiempo en segundos para expirar sesion
//'doc_url'		  => '/sievalab/documentos/',								//Ruta a los documentos almacenados por web
//'doc_path'	  => '/u02/app/banner/SIGAAPDN/www/sievalab/documentos/', 	//Ruta a los documentos filesystem
  'mail_server'	  => 'localhost', 											//Host para envio de email con phpmailer
  'mail_user'     => 'usuario',	  									        //Username servidor de correo
  'mail_pass'	  => '*******',											    //ContraseÃ±a servidor de correo
  'modo_sistema'  => 'developer',                          		            //Modo de trabajo, developer = produccion  
  'autenticacion' => 'db'
); ?>