<?php
ob_start();//Desactivar la salida para evitar la aparicion de un whitespace no deseado en el connect de MDB2

//ini_set('include_path','.:/home/donatovallin/php');

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);	
ini_set('log_errors', 1); 
ini_set('error_log', './error_log.txt');

set_include_path('.:/usr/share/php/');

require_once 'MDB2.php';

if(!function_exists('nicerror')){
	function nicerror($msg){	
	   $plantilla = file_get_contents(APP_PATH.'/template/mensaje_error_fatal.html');
	   $cad = str_replace("<#MENSAJE#>",$msg,$plantilla);
	   return($cad);
	}
}//end dierror

//verificar a que motor se hace la conexion, oracle o mysql
$config['database']='mysql';
$config['autenticacion']='db';  //Posibles valores: ldap, db, soap
$config['doc_folder'] = "../documentos/";

if($config['database']=='mysql'){
   $dsn = array ( 
    'phptype'  => 'mysql', //Posibles valores: oci8, pgsql, mssql
    'hostspec' => 'localhost',
    'database' => 'sievas',
    'username' => 'sievas_admin',
    'password' => 'Danger89312011$',
	'charset'  => 'utf8'
    );
}

if($config['database']=='oracle')
{
   $dsn = array ( 
    'phptype'  => 'oci8',
    'hostspec' => '/',
    'username' => '',
    'password' => '',
    );
}

if($config['database']=='postgresql')
{
   $dsn = 'pgsql://admin:*****@localhost/eval';
}
$db = MDB2::factory($dsn);
ob_end_clean();  //Borrar el buffer de salida para asegurar que no hay nada antes del header
if (PEAR::isError($db)) {
    die(nicerror('No se puede crear conexion a la base de datos. Verifique la configuración o comuníquese con su administrador de TI. El mensaje detallado es:<br />'.$db->GetMessage()));
}
else
{
    $db->setFetchMode(MDB2_FETCHMODE_ASSOC);
	$db->loadModule('Function');
	$db->loadModule('Extended');
	//print(print_error("Conexion Exitosa a DB"));
}

BD::$db = $db;

class BD{
    static $db;    
}

?>
