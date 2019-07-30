
{{--带表单的搜索checkbox--}}
@if (request('is_first_request',false))
    {!! $form ?? null !!}
    {!! CusAdmin::script() !!}
    <div class="{{$name}}-modal_checkbox_group_wrap">
        @endif

        @include('admin.base_extends.pieces.modal.modal_checkbox_group', [])

        @if (request('is_first_request',false))
    </div>
@endif





{{--radio--}}

@include('admin.base_extends.pieces.modal.modal_radio',array_merge(get_defined_vars(),[]))



