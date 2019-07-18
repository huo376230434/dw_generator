<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}  {{$custom_extra_class ?? '' }}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{!! $label !!}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="wrap">

            <input  class="form-control" />

        </div>

        @include('admin::form.help-block')

    </div>
</div>
