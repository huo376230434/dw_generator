<?php
namespace App\Tenancy\Extensions;

/**
 * Created by IntelliJ IDEA.
 * User: huo
 * Date: 2019/2/11
 * Time: 下午2:31
 */
class Admin extends \Encore\Admin\Facades\Admin {


    protected static function getFacadeAccessor()
    {
        return CusAdmin::class;
    }

}
