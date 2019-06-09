//初始化勾子 //比如icheck select 等的绑定可以在这里
function initHook(modal_prefix) {


}

//modal显示前勾子
function beferShowHook(id,modal_prefix,$this) {


    var api_url = "DummyApiUrl";
    if (!api_url) {
        return false;
    }
    $("#"+modal_prefix+"-wrap").html("");

    //先请求接口拿回roles数据
    $.get(api_url,{id:id},function (response) {
        responseHandle(response)

    })
    function responseHandle(response) {
        console.log(response);
        var html = response.data;

        $("#"+modal_prefix+"-wrap").html(html)


    }

}


//表单提交前的勾子，用于参数的增减
function beferSubmit(post_data,modal_prefix,id) {

    // console.log(post_data);
    // if (post_data[modal_prefix]) {
    //
    //     var html = "";
    //     $('.'+modal_prefix+'_checkbox:checked').each(function(){
    //
    //         var obj_arr =   window.dw_obj_container[''+modal_prefix+ id+'_arr']
    //         var value_id = $(this).val()
    //
    //         console.log(obj_arr);
    //         //判断是否已加过
    //         var has_chose = _.some(obj_arr,function (item, key) {
    //             return item.id==value_id
    //         })
    //
    //         if (has_chose) {
    //             return true;
    //         }
    //         obj_arr.push({
    //             id: value_id,
    //             name:$(this).data('title')
    //         });
    //
    //     })
    // }
    // window.dw_obj_container[''+modal_prefix+ id+'_arr_refresh_function']()
    //
    //
    // return 'quit';

    return true;
}

//表单提交成功的勾子，
function  submitSucceed($data,modal_prefix) {

}
