@if(session()->has('Success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif
@if(session()->has('Info'))
    <div class="alert alert-info">
        {{session('Info')}}
    </div>
@endif
