<?php
namespace App\Tenancy\Extensions;
use App\Tenancy\Extensions\Grid\Displayers\Actions;
use App\Tenancy\Extensions\Grid\Tools;
use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Created by IntelliJ IDEA.
 * User: huo
 * Date: 2019/2/8
 * Time: 上午10:06
 */
class Grid extends \Encore\Admin\Grid{
    public function __construct(Eloquent $model, Closure $builder = null)
    {
        parent::__construct($model, $builder);
    }


    public function setupTools()
    {
        $this->tools = new Tools($this);
    }



    /**
     * Add `actions` column for grid.
     *
     * @return void
     */
    protected function appendActionsColumn()
    {
        if (!$this->option('show_actions')) {
            return;
        }

        $this->addColumn('__actions__', trans('admin.action'))
            ->displayUsing(Actions::class, [$this->actionsCallback]);
    }


}
