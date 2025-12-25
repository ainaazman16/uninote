

<h3>Available Providers</h3>

@foreach($providers as $provider)
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $provider->name }}</h5>
            <p>{{ $provider->email }}</p>

            <a href="{{ route('providers.show', $provider->id) }}"
               class="btn btn-outline-primary">
               View Profile
            </a>
        </div>
    </div>
@endforeach
