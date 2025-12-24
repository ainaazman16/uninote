@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h3 class="fw-bold mb-4">My Profile</h3>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4 text-center">

                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                    class="rounded-circle mb-2"
                                    width="120" height="120">
                            @else
                                <div class="text-muted mb-2">No profile picture</div>
                            @endif

                            <input type="file" name="profile_photo"
                                class="form-control"
                                accept="image/*">

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control"
                                   value="{{ auth()->user()->name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">University</label>
                            <input type="text" name="university"
                                   class="form-control"
                                   value="{{ auth()->user()->university }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Programme</label>
                            <input type="text" name="programme"
                                   class="form-control"
                                   value="{{ auth()->user()->programme }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Year of Study</label>
                            <input type="text" name="year_of_study"
                                   class="form-control"
                                   value="{{ auth()->user()->year_of_study }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Short Bio</label>
                            <textarea name="bio" rows="3"
                                      class="form-control">{{ auth()->user()->bio }}</textarea>
                        </div>

                        <button class="btn btn-primary w-100">
                            Save Profile
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
