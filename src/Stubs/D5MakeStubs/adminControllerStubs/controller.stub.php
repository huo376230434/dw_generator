<?php

namespace DummyControllerNamespace;
use App\Admin\Extensions\AdminBase\AdminUtil;
use App\Admin\Extensions\AdminException;

use App\Admin\Controllers\AdminBase\AdminController;
use App\Model\OperateFlow;
use DummyControllerNamespace\ControllerTrait\DummyControllerClassTrait;
use DummyControllerNamespace\ControllerTrait\DummyControllerClassExtraTrait;
use DummyModelNamespace;
use Encore\Admin\Grid\Tools\BatchActions;
use Encore\Admin\Show;

use App\Admin\Extensions\AdminBase\CsvExporter\CommonExporter;
use App\Admin\Extensions\CusAdmin;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\AdminBase\Widgets\ExcelBtn;
use App\Admin\Extensions\AdminBase\Widgets\DereplicateBackBtn;
use Illuminate\Support\Facades\DB;
use App\Admin\Extensions\AdminBase\Widgets\DoWithConfirm;
use App\Admin\Extensions\AdminBase\Widgets\Batch\BatchDoWithConfirm;
use App\Admin\Extensions\AdminBase\Widgets\Batch\BatchOperateWithMsg;
use App\Admin\Extensions\Form;
use App\Admin\Extensions\Grid;
use App\Admin\Extensions\Grid\Displayers\Actions;
use App\Admin\Extensions\Grid\Filter;
use App\Admin\Extensions\Grid\Tools;
use App\Admin\Extensions\Layout\Content;

//DummyNamespaces

class DummyControllerClass extends AdminController
{
    use ModelForm, DummyControllerClassTrait,DummyControllerClassExtraTrait;

    protected $edit_id;

    protected $default_filter = [];


    protected $forbidden_actions = [
        //forbidden_action_hook
    ];

    public function __construct()
    {
        $this->middleware("forbidden")->only($this->forbidden_actions);
    }


//    默认的filter
    protected function initDefaultFilter()
    {
        $this->default_filter = [
//            'is_sale' => 0,
//            'admin_user_id' => Admin::user()->id,
            "_sort"=>[
                'column' => "updated_at",
                'type' => 'desc'
            ]
        ];
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        //确认是否有默认filter
        if ($url = $this->checkFilter()) {
            return redirect($url);
        };
        return CusAdmin::content(function (Content $content) {
            $content->header('title_header');
            $content->description('列表');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return CusAdmin::content(function (Content $content) use ($id) {
            $content->header('title_header');
            $content->description('编辑');
            $this->edit_id = $id;
            $this->edit_obj = DummyNameModel::find($id);

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return CusAdmin::content(function (Content $content) {
            $content->header('title_header');
            $content->description('创建');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return CusAdmin::grid(DummyNameModel::class, function (Grid $grid) {
            $_this = $this;
            $grid->id('ID')->sortable();
            $grid->paginate(10);
//            $grid->expandFilter();
            $grid->actions(function ( Actions $actions)use($_this) {
                $_this->defaultGridActions($actions, $_this);
            });

            $grid->tools(function (Grid\Tools $tools) use ($_this) {
                $_this->defaultGridTools($tools, $_this);
                $tools->batch(function (BatchActions $batch) use ($_this) {
                    $_this->defaultGridBatchs($batch, $_this);
                });
            });

            $grid->filter(function(Grid\Filter $filter) use ($_this){
                $_this->defaultGridFilters($filter, $_this);
            });

            $this->defaultGrid($grid, $_this);
            $this->export($grid);

            $grid->created_at("创建时间")->sortable();
            $grid->updated_at("修改时间")->sortable();
        });
    }


    protected function export(Grid $grid)
    {
//            $grid->disableExport();
//            是否excel导出功能，如果有，则取消注释并完善代码
        $grid->exporter(new CommonExporter($grid,"table",
            //todo 补充excel 数据
            [
                //字段列表及中文名
                'id' => "ID",
                "name" => "名称",
                "created_at" => "创建时间",
                "updated_at" => "更新时间"
//                    'card_resource.price' => "金额",
//                    "card_resource.is_sale" => "是否卖出",
//                    'created_at' => "创建时间",
            ],
            [
                //有需要分转元的
            ],
            [
                //需要后台补tab键的字段，主要是csv模式有用
//                'created_at'
            ],
            [
                //自定义回调处理
//                    'is_sale' => function($is_sale){
//                        return $is_sale==1 ? "已售出" : "未售";
//                    }
            ]

        ));
    }



    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return CusAdmin::form(DummyNameModel::class, function (Form $form) {
            $_this = $this;
            $form->display('id', 'ID');

            AdminUtil::DefaultFormOptimize($form);

            $this->defaultForm($form ,$_this);

            $form->saved(function(Form $form){
                if ($form->action_mode == "update") {
                    $msg =  " 在 title_header 模块 修改了 : $form->dummy_row_show_field "." 的信息";
                } else{
                    $msg = "在 title_header 模块  添加了 : $form->dummy_row_show_field";
                }
                OperateFlow::log($msg);
            });

        });
    }




    public function destroy($id=null)
    {
        try {
            if ($id === null) {
                throw new AdminException("请至少选择一项!");
            }
            $ids = explode(",",$id);
            $msg = ' 在 title_header 模块删除了  ';
            foreach ($ids as $v) {
                $obj = DummyNameModel::find($v);
                $msg .= $obj->dummy_row_show_field . ",";
            }
            $msg = trim($msg,",");
            if ($this->form()->destroy($id)) {
                OperateFlow::log($msg);
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin.delete_succeeded'),
                ]);
            } else {
                throw new AdminException( trans('admin.delete_failed'));
            }
        } catch (AdminException $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function show($id, Content $content)
    {
        return $content->header('title_header')
            ->description('详情')
            ->body(CusAdmin::show(DummyNameModel::findOrFail($id), function (Show $show) {
                //show_hook
            }));


    }
}
