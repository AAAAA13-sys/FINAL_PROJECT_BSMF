@extends('layouts.app')

@section('title', 'Collector Profile - BSMF GARAGE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile-ui.css') }}">
@endpush

@section('content')
<div class="profile-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-5">
            <h2 class="text-white text-uppercase italic fw-black mb-0">COLLECTOR <span>PROFILE</span></h2>
        </div>
        <div class="profile-header-content">
            <div class="profile-avatar">
                <div class="avatar-inner">
                    <i class="fas fa-user-secret"></i>
                </div>
                <div class="badge-collector">
                    <i class="fas fa-certificate"></i> ELITE COLLECTOR
                </div>
            </div>
            <div class="profile-identity">
                <h1>{{ strtoupper($user->name) }}</h1>
                <p>Member since {{ $user->created_at->format('M Y') }} • {{ $user->orders()->count() }} Acquisitions</p>
            </div>
        </div>
    </div>
</div>

<section class="profile-section">
    <div class="container">
        <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
            @csrf
            @method('PUT')

            <div class="row g-5">
                <div class="col-lg-10 offset-lg-1">
                    <div class="glass-card mb-5">
                        <div class="card-header">
                            <i class="fas fa-id-card"></i>
                            <h3>USER'S INFORMATION</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <div class="phone-input-group d-flex align-items-center">
                                            <span class="phone-prefix">+63</span>
                                            <input type="text" name="phone" id="phone" class="custom-input ps-5" 
                                                value="{{ old('phone', preg_replace('/^\+63/', '', $user->phone)) }}" 
                                                placeholder="9XXXXXXXXX" 
                                                maxlength="11"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11)">
                                        </div>
                                        <label for="phone">PHONE NUMBER</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <input type="email" name="email" id="email" class="custom-input" value="{{ old('email', $user->email) }}" placeholder="Email Address" required>
                                        <label for="email">EMAIL ADDRESS</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <select name="region" id="region" class="custom-input custom-select" onchange="handleRegionChange()">
                                            <option value="" disabled {{ !old('region', $user->region) ? 'selected' : '' }}>Select Region</option>
                                            @foreach($regions as $code => $name)
                                                <option value="{{ $code }}" {{ old('region', $user->region) == $code ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="region">REGION</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <select name="city" id="city" class="custom-input custom-select">
                                            <option value="" disabled {{ !old('city', $user->city) ? 'selected' : '' }}>Select City</option>
                                            @if(old('region', $user->region) && isset($regionalCities[old('region', $user->region)]))
                                                @foreach($regionalCities[old('region', $user->region)] as $cityName => $km)
                                                    <option value="{{ $cityName }}" {{ old('city', $user->city) == $cityName ? 'selected' : '' }}>{{ $cityName }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="city">CITY / MUNICIPALITY</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating-custom">
                                        <textarea name="default_shipping_address" id="address" class="custom-input profile-textarea" placeholder="Unit / Street / House No. / Barangay">{{ old('default_shipping_address', $user->default_shipping_address) }}</textarea>
                                        <label for="address">HOUSE NO. / STREET / BARANGAY</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card mb-5">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-shield-alt"></i>
                                <h3 class="d-inline-block m-0 ms-2">SECURITY PROTOCOL</h3>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm px-3" id="btn-toggle-security" onclick="toggleSecurityProtocol()" style="border-radius: 8px; font-weight: 700; letter-spacing: 1px; font-size: 0.75rem;">
                                <i class="fas fa-key me-1"></i> MODIFY
                            </button>
                        </div>
                        <div class="card-body" id="security-protocol-body" style="display: none;">
                            <p class="text-muted mb-4 small">Leave blank if you do not wish to update your secure access credentials.</p>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <input type="password" name="password" id="password" class="custom-input password-input-wrapper" placeholder="New Password">
                                        <label for="password">NEW PASSWORD</label>
                                        <i class="fas fa-eye password-toggle-icon" onclick="togglePassword('password', this)"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="custom-input password-input-wrapper" placeholder="Confirm Password">
                                        <label for="password_confirmation">CONFIRM NEW PASSWORD</label>
                                        <i class="fas fa-eye password-toggle-icon" onclick="togglePassword('password_confirmation', this)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Close Security Protocol Card -->
                    
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-black text-uppercase italic tracking-wider shadow-lg" style="background: linear-gradient(135deg, #800C1F 0%, #e63946 100%); border: none;">
                            <i class="fas fa-save me-2"></i> UPDATE <span>PROFILE</span>
                        </button>
                    </div>

                </div> <!-- Close col-lg-10 -->
            </div> <!-- Close row -->
        </form>
    </div>
</section>

@push('scripts')
<script>
    const regionalCities = @json($regionalCities);

    function handleRegionChange() {
        const region = document.getElementById('region').value;
        const citySelect = document.getElementById('city');
        citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
        if (regionalCities[region]) {
            const cities = regionalCities[region];
            for (const city in cities) {
                const option = document.createElement('option');
                option.value = city;
                option.text = city;
                citySelect.appendChild(option);
            }
        }
    }

    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function toggleSecurityProtocol() {
        const body = document.getElementById('security-protocol-body');
        const btn = document.getElementById('btn-toggle-security');
        
        if (body.style.display === 'none') {
            body.style.display = 'block';
            body.classList.add('fade-in');
            btn.innerHTML = '<i class="fas fa-times me-1"></i> CANCEL';
            btn.classList.replace('btn-outline-secondary', 'btn-outline-danger');
        } else {
            body.style.display = 'none';
            body.classList.remove('fade-in');
            btn.innerHTML = '<i class="fas fa-key me-1"></i> MODIFY';
            btn.classList.replace('btn-outline-danger', 'btn-outline-secondary');
            
            // Clear password fields on cancel
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        }
    }
</script>
@endpush
@endsection
