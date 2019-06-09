<?php
/**
 * Created by PhpStorm.
 * User: huo
 * Date: 18-8-6
 * Time: 上午10:16
 */
namespace DummyControllerNamespace\ControllerTrait;



use App\Admin\Extensions\AdminBase\Widgets\OperateWithMsg;
use App\Admin\Extensions\AdminBase\Widgets\DereplicateBackBtn;
use App\Admin\Extensions\AdminException;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use App\Admin\Extensions\AdminBase\Widgets\DoWithConfirm;
use App\Admin\Extensions\AdminBase\Widgets\Batch\BatchDoWithConfirm;
use App\Admin\Extensions\AdminBase\Widgets\Batch\BatchOperateWithMsg;
use App\Admin\Extensions\AdminBase\AdminUtil;

//DummyNamespaces

trait DummyControllerClassTrait {


    public function defaultGrid(Grid $grid, $_this)
    {
        //grid_handle_item_hook

    }


    public function defaultGridActions(Grid\Displayers\Actions $actions, $_this)
    {
        $row = $actions->row;

        //grid_handle_action_hook
    }


    public function defaultGridTools(Grid\Tools $tools ,$_this)
    {
        //grid_handle_tool_hook
    }


    public function defaultGridBatchs(Grid\Tools\BatchActions $batch,$_this )
    {
        //grid_handle_batch_hook

    }


    public function defaultGridFilters(Grid\Filter $filter ,$_this)
    {
        //grid_handle_filter_hook
    }


    public function defaultForm(Form $form ,$_this)
    {

        //form_handle_button_hook

    }

}
