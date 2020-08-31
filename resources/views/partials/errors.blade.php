<div>
<div class="alert alert-success {{ !session()->has('success_message') ? 'hide' : '' }}">
    @if (session()->has('success_message'))
        {{ session()->get('success_message') }}
    @endif
</div>
<div class="alert alert-danger {{ !count($errors) > 0 ? 'hide' : '' }}">
    @if(count($errors) > 0)
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>
</div>