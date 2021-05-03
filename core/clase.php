<?php
class Mdb2loader
{
    public static function connectdb()
    {
        require_once "MDB2.php";
        $dsn = 'mysql://root:shenlong1@192.168.0.100/academico';
        $mdb2 = MDB2::factory($dsn);
        if (PEAR::isError($mdb2)) {
            die($mdb2->getMessage());
        }
        return $mdb2;
    }
}
?>