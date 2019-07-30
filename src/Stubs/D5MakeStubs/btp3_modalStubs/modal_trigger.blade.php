

@include('admin.custom.pieces.modal_trigger_extra_js',[])

@if($is_btn)

    <a href="javascript:void(0);" class="btn btn-sm btn-{{$color_type}} grid-row-{{$handle_type}}"  data-color_type="{{$color_type}}" data-title="{{$msg}}"    data-id="{{$id}}"  data-url="{{$url}}">

        {{$title}}
    </a>
    <input type="hidden"name="{{$handle_type.$id}}_ids"   value="{{$default_ids ?? ''}}">
    {{--<div id="{{$handle_type.$id}}_show_wrap" class="">--}}
        {{--{!! $default_names ?? "" !!}--}}

    {{--</div>--}}
@else

    <a href="javascript:void(0);"   class="grid-row-{{$handle_type}} "   data-color_type="{{$color_type}}" data-title="{{$title}}"  data-id="{{$id}}"  data-url="{{$url}}">

        {{$title}}  </a>

@endif


