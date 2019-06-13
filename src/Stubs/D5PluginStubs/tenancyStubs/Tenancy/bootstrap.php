<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use Encore\Admin\Grid\Exporter;
use Encore\Admin\Grid\Column;

Encore\Admin\Form::forget(['map', 'editor']);
app('view')->prependNamespace('admin', resource_path('views/admin'));

//添加自定义样式
Admin::css("/css/admin_shim.css");

Admin::js("/js/third/echarts.common.min.js");

Encore\Admin\Form::extend('largefile', \Encore\LargeFileUpload\LargeFileField::class);

Encore\Admin\Form::extend('media', \Encore\FileBrowser\FileBrowserField::class);

//自定义csvzExcel导出类
Exporter::extend('test-exporter', TestExporter::class);

//Admin::js("/js/echarts.common.min.js");
//Admin::js("/js/echarts.simple.min.js");

//添加组件
Column::extend('popover', function ($value,$max_word=25) {

    if (mb_strwidth($value, 'UTF-8') > $max_word) {
        return  '<span style="text-decoration:underline" href="javascript:void(0);" data-toggle="popover" data-placement="auto top" data-trigger="hover"  title="" data-content="'.$value.'">'.str_limit($value,$max_word).'</span><script>	$("[data-toggle=\'popover\']").popover();</script>';
    }else{
        return $value;

    }

});
