<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26
 * Time: 9:27
 */

namespace App\Admin\Extensions\Custom\Widgets\Bt3Modals;
use App\Admin\Extensions\BaseExtends\Widgets\BaseBtpModalTrait;
use App\Admin\Extensions\BaseExtends\Widgets\Bt3Modals\BaseBtp3Modal;
use Illuminate\Contracts\Support\Renderable;

class DummyClass extends BaseBtp3Modal implements Renderable {
    use BaseBtpModalTrait;

    /**
     * @var string
     */

    protected $handle_type = "DummySnakeName";

    protected $view = 'admin.custom.bt3modals.DummySnakeName.DummySnakeName_trigger';

    protected $extjs_path = "views/admin/custom/bt3modals/DummySnakeName/DummySnakeName_extra.js";
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
