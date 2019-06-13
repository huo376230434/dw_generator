<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26
 * Time: 9:27
 */

namespace App\Admin\Extensions\Custom\Widgets;
use App\Admin\Extensions\BaseExtends\Widgets\BaseBtpModal;
use App\Admin\Extensions\BaseExtends\Widgets\BaseBtpModalTrait;
use App\Admin\Extensions\CusAdmin;
use Illuminate\Contracts\Support\Renderable;
use Encore\Admin\Widgets\Widget;
class DummyClass extends BaseBtpModal implements Renderable {
    use BaseBtpModalTrait;

    /**
     * @var string
     */

    protected $handle_type = "DummySnakeName";

    protected $view = 'admin.custom.modals.DummySnakeName.DummySnakeName_trigger';

    protected $extjs_path = "views/admin/custom/modals/DummySnakeName/DummySnakeName_extra.js";
    public function customData()
    {
        return [

        ];
    }

    public function modalData()
    {
        $default = [['name' => '张三', 'id' => 1], ['name' => '李四', 'id' => 2], ['name' => '王五', 'id' => 3]];
        $default = collect($default)->map((function ($value,$key){

                    return json_decode(json_encode($value));

        }));
        return [
            'params' => $default

        ];
    }


}
