





var json_obj = {
    beferShowHook: function (id,modal_prefix,$this) {

        var api_url = "DummyApiUrl";
        if (!api_url) {
            return false;
        }
        $("#"+modal_prefix+"-wrap").html("");

        //先请求接口拿回roles数据
        $.get(api_url,{id:id,is_first_request : 1},function (response) {
            responseHandle(response)
        })
        function responseHandle(response) {
            console.log(response);
            var html = response.data;
            $("#"+modal_prefix+"-wrap").html(html)
        }
    },
    afterShowHook: function () {
    },
    beferSubmit: function (post_data,modal_prefix,id) {
    },
    submitParamsWrap: function (post_data,modal_prefix,id) {
        return post_data;
    },
    submitSucceed: function ($data,modal_prefix) {
    },
    successRefreshTime: function () {
    },
    forceRefresh: function () {
    }
}







