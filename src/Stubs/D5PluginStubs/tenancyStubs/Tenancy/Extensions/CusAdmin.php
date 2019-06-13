<?php
namespace App\Tenancy\Extensions;

use Closure;

/**
 * Created by IntelliJ IDEA.
 * User: huo
 * Date: 2019/2/11
 * Time: 下午2:31
 */
class CusAdmin extends \Encore\Admin\Admin {

    /**
     * @param $model
     * @param Closure $callable
     */
    public function grid($model, Closure $callable)
    {
        return new Grid($this->getModel($model), $callable);
    }


}
