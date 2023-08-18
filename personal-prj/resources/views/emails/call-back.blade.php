@if (isset($flash_message))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $flash_message }}</strong>
    </div>
@endif
