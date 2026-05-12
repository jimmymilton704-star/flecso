@extends('layouts.app')

@section('title', 'Add Driver')
@section('body-class', 'page-dashboard')

@section('content')
    <style>
        .upload-box {
            display: block;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafafa;
            margin-bottom: 10px;
        }

        .upload-box:hover {
            border-color: #4f46e5;
            background: #f5f7ff;
        }

        .upload-content svg {
            margin-bottom: 10px;
            color: #6b7280;
        }

        .upload-content strong {
            display: block;
            font-size: 14px;
            color: #111827;
        }

        .upload-content span {
            font-size: 12px;
            color: #6b7280;
        }
    </style>

    <section class="page">
        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ Drivers / Add</span></div>
                <h1>Add New Driver</h1>
                <div class="page-head__sub">Create a new driver profile and upload necessary credentials</div>
            </div>
        </div>

        <form action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card">
                @if ($errors->any())
                    <div class="alert alert--error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- 1. BASIC INFORMATION & ACCOUNT --}}
                <div class="card__head">
                    <h3>1. Basic Information & Account</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="field">
                            <label>Full Name*</label>
                            <input class="input" type="text" name="full_name" required value="{{ old('full_name') }}">
                        </div>

                        <div class="field">
                            <label>Email Address*</label>
                            <input class="input" type="email" name="email" required value="{{ old('email') }}">
                        </div>

                        <div class="field">
                            <label>Phone Number*</label>
                            <input class="input" type="text" name="phone" required value="{{ old('phone') }}">
                        </div>

                        <div class="field">
                            <label>Password*</label>
                            <input class="input" type="password" name="password" required>
                        </div>

                        <div class="field">
                            <label>Status*</label>
                            <select name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="on_leave">On Leave</option>
                                <option value="on_trip">On Trip</option>
                            </select>
                        </div>

                        {{-- <div class="field">
                            <label class="upload-box">Driver Photo</label>
                            <input class="input" type="file" name="driver_photo" accept="image/*">
                        </div> --}}
                        <div class="field">
                            <label>Driver Photo</label>
                            <label class="upload-box">
                                <input type="file" name="driver_photo" class="file-input" hidden accept="image/*">
                                <div class="upload-content">
                                    <strong>Click to upload Driver Photo</strong>

                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- 2. PERSONAL DETAILS --}}
                <div class="card__head">
                    <h3>2. Personal & Identity Details</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="field">
                            <label>Place of Birth</label>
                            <input class="input" type="text" name="place_of_birth" value="{{ old('place_of_birth') }}">
                        </div>

                        <div class="field">
                            <label>Date of Birth</label>
                            <input class="input" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        </div>

                        <div class="field">
                            <label>Fiscal Code (Codice Fiscale)</label>
                            <input class="input" type="text" name="fiscal_code" maxlength="16"
                                value="{{ old('fiscal_code') }}">
                        </div>

                        <div class="field">
                            <label>Nationality</label>
                            <input class="input" type="text" name="nationality" value="{{ old('nationality') }}">
                        </div>

                        <div class="field full">
                            <label>Residential Address</label>

                            <input id="residential_address" class="input" type="text" name="residential_address"
                                placeholder="Start typing address..." value="{{ old('residential_address') }}">
                        </div>
                    </div>
                </div>

                {{-- 3. PROFESSIONAL LICENSES --}}
                <div class="card__head">
                    <h3>3. Professional Licenses & CQC</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="field">
                            <label>License Number</label>
                            <input class="input" type="text" name="license_number" value="{{ old('license_number') }}">
                        </div>

                        <div class="field">
                            <label>License Category</label>
                            <input class="input" type="text" name="driving_license_category" placeholder="e.g. C, CE"
                                value="{{ old('driving_license_category') }}">
                        </div>

                        <div class="field">
                            <label>License Expiry Date</label>
                            <input class="input" type="date" name="license_expiry" value="{{ old('license_expiry') }}">
                        </div>

                        <div class="field">
                            <label>CQC Number</label>
                            <input class="input" type="text" name="cqc_number" value="{{ old('cqc_number') }}">
                        </div>

                        <div class="field">
                            <label>CQC Expiry Date</label>
                            <input class="input" type="date" name="cqc_expiry" value="{{ old('cqc_expiry') }}">
                        </div>

                        <div class="field">
                            <label>Tachograph Card Number</label>
                            <input class="input" type="text" name="tachograph_card_number"
                                value="{{ old('tachograph_card_number') }}">
                        </div>
                    </div>
                </div>

                {{-- 4. PERMITS & HEALTH --}}
                <div class="card__head">
                    <h3>4. Permits & Health</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="field">
                            <label>Work Permit Number</label>
                            <input class="input" type="text" name="work_permit_number"
                                value="{{ old('work_permit_number') }}">
                        </div>

                        <div class="field">
                            <label>Work Permit Expiry</label>
                            <input class="input" type="date" name="work_permit_expiry"
                                value="{{ old('work_permit_expiry') }}">
                        </div>

                        <div class="field">
                            <label>Medical Fitness Date</label>
                            <input class="input" type="date" name="medical_fitness_date"
                                value="{{ old('medical_fitness_date') }}">
                        </div>

                        <div class="field">
                            <label>Criminal Record Check Status</label>
                            <input class="input" type="text" name="criminal_record_check"
                                value="{{ old('criminal_record_check') }}">
                        </div>
                    </div>
                </div>

                {{-- 5. DOCUMENT UPLOADS --}}
                <div class="card__head">
                    <h3>5. Document Uploads (Files)</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        {{-- License Front --}}
                        <div class="field">
                            <label>License Front</label>
                            <label class="upload-box">
                                <input type="file" name="license_front" class="file-input" hidden accept=".pdf,.jpg,.png">
                                <div class="upload-content">
                                    <strong>Click to upload License Front</strong>
                                    <span>PDF, JPG (Max 10MB)</span>
                                </div>
                            </label>
                        </div>

                        {{-- License Back --}}
                        <div class="field">
                            <label>License Back</label>
                            <label class="upload-box">
                                <input type="file" name="license_back" class="file-input" hidden accept=".pdf,.jpg,.png">
                                <div class="upload-content">
                                    <strong>Click to upload License Back</strong>
                                    <span>PDF, JPG (Max 10MB)</span>
                                </div>
                            </label>
                        </div>

                        {{-- CQC Card --}}
                        <div class="field">
                            <label>CQC Card Document</label>
                            <label class="upload-box">
                                <input type="file" name="cqc_card" class="file-input" hidden accept=".pdf,.jpg,.png">
                                <div class="upload-content">
                                    <strong>Click to upload CQC Card</strong>
                                    <span>PDF, JPG (Max 10MB)</span>
                                </div>
                            </label>
                        </div>

                        {{-- Work Permit File --}}
                        <div class="field">
                            <label>Work Permit File</label>
                            <label class="upload-box">
                                <input type="file" name="work_permit_file" class="file-input" hidden
                                    accept=".pdf,.doc,.docx">
                                <div class="upload-content">
                                    <strong>Click to upload Work Permit</strong>
                                    <span>PDF, DOC (Max 10MB)</span>
                                </div>
                            </label>
                        </div>

                        {{-- Medical Certificate --}}
                        <div class="field full">
                            <label>Medical Certificate</label>
                            <label class="upload-box">
                                <input type="file" name="medical_certificate" class="file-input" hidden accept=".pdf,.jpg">
                                <div class="upload-content">
                                    <strong>Click to upload Medical Certificate</strong>
                                    <span>PDF, JPG (Max 10MB)</span>
                                </div>
                            </label>
                        </div>

                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="card__body">
                    <div style="display:flex; gap:10px;">
                        <a href="{{ route('drivers.index') }}" class="btn btn--ghost">Cancel</a>
                        <button type="submit" class="btn btn--primary">Save Driver Profile</button>
                    </div>
                </div>

            </div>
        </form>
    </section>

    <script>
        // Generic logic for all upload boxes to show filename
        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', function () {
                let fileName = this.files[0]?.name;
                let container = this.closest('.upload-box').querySelector('.upload-content strong');
                let subtext = this.closest('.upload-box').querySelector('.upload-content span');

                if (fileName) {
                    container.innerText = fileName;
                    subtext.innerText = "File selected successfully";
                    container.style.color = "#4f46e5";
                }
            });
        });
    </script>

    {{-- GOOGLE MAPS API --}}
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQqP59sFi_cXyk8Afq_AY4Dkg4DCf-xj0&libraries=places"></script>

    <script>
        function initAddressAutocomplete() {

            const input = document.getElementById('residential_address');

            if (!input) return;

            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['address'],
            });

            autocomplete.addListener('place_changed', function () {

                const place = autocomplete.getPlace();

                console.log(place);

                // Full formatted address
                if (place.formatted_address) {
                    input.value = place.formatted_address;
                }
            });
        }

        window.addEventListener('load', initAddressAutocomplete);
    </script>
@endsection