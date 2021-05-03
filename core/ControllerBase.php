<?php



abstract class ControllerBase
{
    protected $db;

    function __construct()
    {

    }

    public function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) ==
            'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }
    
    public function disparar_error($status) {
        header($_SERVER['SERVER_PROTOCOL'] . '500 Internal Server Error', true, 500);
        $msg['error']=true;
        $msg['status'] = $status;
        echo json_encode($msg);
        exit();
    }
    protected  function is_date($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}