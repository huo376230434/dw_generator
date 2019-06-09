


@component('admin.base_extends.components.modal',['id' => $handle_type,'modal_lg' =>false])


@slot('body')
<div class="form">

    <div id="{{$handle_type}}-wrap" class="m-2">

    </div>

    @include('admin.custom.modals.'.$handle_type.'.'.$handle_type.'_piece', ['api_url' => '','name' => $handle_type,'params' => $params])

    <input type="hidden" name="_method" value="post">
    <input id="{{$handle_type}}-id" type="hidden" name="id" value="">
    {{csrf_field()}}
</div>
@endslot

@slot('footer')
<button type="submit" id="{{$handle_type}}-submit" class="btn btn-primary submit">提交</button>
<button   data-dismiss="modal" aria-label="Close" class="btn btn-default pull-left">取消</button>
@endslot


@endcomponent

