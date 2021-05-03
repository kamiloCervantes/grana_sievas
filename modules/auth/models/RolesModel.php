<?php
$config = Config::singleton();
require_once $config->get('modulesFolder').'default/models/GeneralModel.php';
class RolesModel extends GeneralModel
{
    public function table_name() {
        return 'roles';
    }
}