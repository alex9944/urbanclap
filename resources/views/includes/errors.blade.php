@if ($message = Session::get('message'))
    <div class="alert alert-danger">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('success_message'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-warning">
        <p>{{ $message }}</p>
    </div>
@endif
@if(session()->has('errors'))
    <div class="alert alert-danger fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       <!-- <h4>Following errors occurred:</h4>-->
        <ul style="list-style:none;">
            @foreach($errors->all() as $error)
                <li style="line-height: 30px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif