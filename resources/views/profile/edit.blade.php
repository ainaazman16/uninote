@extends('layouts.app')

@section('content')
    <div class="container py-5">
        @php
            $photoPath = auth()->user()->profile_photo;
            $photoUrl =
                $photoPath && Storage::disk('public')->exists($photoPath)
                    ? Storage::disk('public')->url($photoPath)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random';
        @endphp
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Page Title --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark">Account Settings</h2>
                    <a href="{{ route('profile.show', auth()->user()) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('PATCH')

                    <div class="card shadow-lg border-0 overflow-hidden rounded-4">
                        <div class="row g-0">

                            {{-- LEFT COLUMN: Profile Visuals --}}
                            <div class="col-md-4 bg-light border-end d-flex flex-column align-items-center text-center p-5">

                                {{-- Image Wrapper --}}
                                <div class="position-relative mb-4">
                                    <div class="ratio ratio-1x1 rounded-circle overflow-hidden shadow-sm border border-3 border-white"
                                        style="width: 150px; margin: 0 auto;">
                                        <img src="{{ $photoUrl }}" id="avatarPreview"
                                            class="object-fit-cover w-100 h-100" alt="Profile Picture">
                                    </div>

                                    {{-- Camera Button (Triggers File Input) --}}
                                    <label for="photoInput"
                                        class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0 translate-middle-x mb-1 shadow-sm"
                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-camera-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2 2.5 0 0 1 5 0z" />
                                            <path
                                                d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z" />
                                        </svg>
                                    </label>

                                    {{-- Hidden Input --}}
                                    <input type="file" name="profile_photo" id="photoInput" class="d-none"
                                        accept="image/*" onchange="previewImage(event)">
                                </div>

                                <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                                <p class="text-muted small mb-4">{{ auth()->user()->email }}</p>

                                <div class="alert alert-info border-0 py-2 px-3 small w-100">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <span class="fw-semibold">Pro Tip:</span> Use a square image for best results.
                                </div>
                            </div>

                            {{-- RIGHT COLUMN: The Form --}}
                            <div class="col-md-8 p-4 p-md-5">

                                <h5 class="mb-4 text-uppercase fw-bold text-secondary small tracking-wide">Public Profile
                                </h5>

                                {{-- Name (Read Only) --}}
                                <div class="form-floating mb-4">
                                    <input type="text" class="form-control bg-light" id="name"
                                        value="{{ auth()->user()->name }}" disabled readonly>
                                    <label for="name">Full Name <small class="text-muted">(Contact admin to
                                            change)</small></label>
                                </div>

                                {{-- Academic Group --}}
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" name="university"
                                                class="form-control @error('university') is-invalid @enderror"
                                                id="uni" value="{{ old('university', auth()->user()->university) }}"
                                                placeholder="University">
                                            <label for="uni">University Name</label>
                                            @error('university')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-floating">
                                            <input type="text" name="programme"
                                                class="form-control @error('programme') is-invalid @enderror" id="prog"
                                                value="{{ old('programme', auth()->user()->programme) }}"
                                                placeholder="Programme">
                                            <label for="prog">Programme</label>
                                            @error('programme')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select name="year_of_study"
                                                class="form-select @error('year_of_study') is-invalid @enderror"
                                                id="year">
                                                <option value="" disabled>Select Year</option>
                                                @foreach (['1st Year', '2nd Year', '3rd Year', '4th Year', 'Postgrad', 'Graduated'] as $year)
                                                    <option value="{{ $year }}"
                                                        {{ auth()->user()->year_of_study == $year ? 'selected' : '' }}>
                                                        {{ $year }}</option>
                                                @endforeach
                                            </select>
                                            <label for="year">Current Year</label>
                                            @error('year_of_study')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Bio --}}
                                <div class="form-floating mb-4">
                                    <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" id="bio" style="height: 120px"
                                        placeholder="Bio">{{ old('bio', auth()->user()->bio) }}</textarea>
                                    <label for="bio">Short Bio / About Me</label>
                                    <div class="form-text text-end" id="charCount">Max 250 words</div>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end gap-3 align-items-center">
                                    @if (session('success'))
                                        <span class="text-success fw-bold small fade-in">
                                            <i class="bi bi-check-circle-fill me-1"></i> Saved!
                                        </span>
                                    @endif
                                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                        Save Changes
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </form> {{-- End Form --}}

                {{-- PASSWORD CHANGE SECTION --}}
                <div class="card shadow-lg border-0 overflow-hidden rounded-4 mt-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="mb-4 text-uppercase fw-bold text-secondary small tracking-wide">
                            <i class="bi bi-shield-lock me-2"></i>Change Password
                        </h5>

                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PATCH')

                            {{-- Current Password --}}
                            <div class="form-floating mb-3">
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" placeholder="Current Password" required>
                                <label for="current_password">Current Password</label>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="form-floating mb-3">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="New Password" required>
                                <label for="password">New Password</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">At least 8 characters</div>
                            </div>

                            {{-- Confirm New Password --}}
                            <div class="form-floating mb-4">
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" placeholder="Confirm New Password" required>
                                <label for="password_confirmation">Confirm New Password</label>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-3 align-items-center">
                                @if (session('password_success'))
                                    <span class="text-success fw-bold small fade-in">
                                        <i class="bi bi-check-circle-fill me-1"></i> Password Updated!
                                    </span>
                                @endif
                                <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow-sm">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

    {{-- SIMPLE JAVASCRIPT FOR IMAGE PREVIEW --}}
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('avatarPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <style>
        /* Optional Polish */
        .tracking-wide {
            letter-spacing: 0.1em;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .fade-in {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
@endsection
