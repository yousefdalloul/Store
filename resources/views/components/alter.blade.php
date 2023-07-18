    @if(session()->has($type))
    <div class="alert alert-success">
        {{session($type)}}
    </div>
@endif
@if(session()->has('Info'))
    <div class="alert alert-info">
        {{session('Info')}}
    </div>
@endif
