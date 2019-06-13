<!-- Main Footer -->
<footer class="main-footer" style="margin-left: 0;">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        @if(config('admin.show_environment'))
            <strong>Env</strong>&nbsp;&nbsp; {!! env('APP_ENV') !!}
        @endif

        &nbsp;&nbsp;&nbsp;&nbsp;

            @if(config('admin.show_version'))
                <strong>版本号：</strong>&nbsp;&nbsp; 0.1
            @endif

    </div>
    <!-- Default to the left -->
    技术支持 <strong>： 点五科技</strong>
</footer>
