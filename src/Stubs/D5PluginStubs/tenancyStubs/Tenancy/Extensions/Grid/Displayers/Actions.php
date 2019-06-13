<?php
namespace App\Tenancy\Extensions\Grid\Displayers;

use App\Tenancy\Extensions\TenancyAdmin;

/**
 * Created by IntelliJ IDEA.
 * User: huo
 * Date: 2019/2/11
 * Time: 下午4:34
 */
class Actions extends \Encore\Admin\Grid\Displayers\Actions{


    /**
     * Render view action.
     *
     * @return string
     */
    protected function renderView()
    {
        return <<<EOT
<a href="{$this->getResource()}/{$this->getKey()}">
     <button class="btn btn-info btn-sm">详情</button>  

</a>
EOT;
    }



    /**
     * Render edit action.
     *
     * @return string
     */
    protected function renderEdit()
    {
        return <<<EOT
<a href="{$this->getResource()}/{$this->getKey()}/edit">
  <button class="btn btn-primary btn-sm">编辑</button>  
</a>
EOT;
    }



    /**
     * Render delete action.
     *
     * @return string
     */
    protected function renderDelete()
    {
        $deleteConfirm = trans('admin.delete_confirm');
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        $script = <<<SCRIPT

$('.{$this->grid->getGridRowName()}-delete').unbind('click').click(function() {

    var id = $(this).data('id');

    swal({
        title: "$deleteConfirm",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "$confirm",
        showLoaderOnConfirm: true,
        cancelButtonText: "$cancel",
        preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    method: 'post',
                    url: '{$this->getResource()}/' + id,
                    data: {
                        _method:'delete',
                        _token:LA.token,
                    },
                    success: function (data) {
                        $.pjax.reload('#pjax-container');

                        resolve(data);
                    }
                });
            });
        }
    }).then(function(result) {
        var data = result.value;
        if (typeof data === 'object') {
            if (data.status) {
                swal(data.message, '', 'success');
            } else {
                swal(data.message, '', 'error');
            }
        }
    });
});

SCRIPT;

        TenancyAdmin::script($script);

        return <<<EOT
<a href="javascript:void(0);" data-id="{$this->getKey()}" class="{$this->grid->getGridRowName()}-delete">
    <!--<i class="fa fa-trash"></i>-->
    <button class="btn btn-danger btn-sm">删除</button> 
</a>
EOT;
    }



}
