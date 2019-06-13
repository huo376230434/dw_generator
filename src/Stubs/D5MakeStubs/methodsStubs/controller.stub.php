<?php

namespace App\DummyNamespacePrefix\Controllers;



use App\Admin\Controllers\AdminBase\AdminController;
use App\Admin\Extensions\BaseExtends\AdminUtil;
use App\Admin\Extensions\AdminException;
use App\Admin\Extensions\Form;
use App\Model\OperateFlow;
use DB;
use App\DummyNamespacePrefix\Extensions\Layout\Content;
use App\DummyNamespacePrefix\Facades\Tenancy;

class DummyControllername extends DummyModuleNameController
{
    public $map_route=true;



}
