@if(session('success'))
    <div class="alert-box success">
        <span>✅</span> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert-box error">
        <span>❌</span> {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert-box warning">
        <ul>
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
