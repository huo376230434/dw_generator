


@component('admin.base_extends.components.bt3modals.bt3modal',['id' => $handle_type,'modal_lg' =>false])

    @slot('body')
        <div class="form">

            <div id="{{$handle_type}}-wrap" class="m-2">

            </div>

            @include('admin.custom.bt3modals.'.$handle_type.'.'.$handle_type.'_piece', ['api_url' => '','name' => $handle_type,'params' => $params])

            <input type="hidden" name="_method" value="post">
            <input id="{{$handle_type}}-id" type="hidden" name="id" value="">
            {{csrf_field()}}
        </div>
    @endslot

    @slot('footer')
        @include('admin.base_extends.components.bt3modals.bt3modal_footer',array_merge(get_defined_vars(),[]))
    @endslot

@endcomponent

