@extends('profile-steps.partials.layout')

@php
    $step = 4;
    $title = 'Legal representative verification';
    $description = 'Upload representative details and verification document.';
@endphp

@section('content')

<div class="onb-main__head">
    <h1>Legal Representative</h1>
    <p>Step 4 of 4</p>
</div>

<form method="POST"
      action="{{ route('profile.step4.post') }}"
      enctype="multipart/form-data">

    @csrf

    <div class="onb-form">

        <!-- Representative Name -->
        <div class="onb-field">
            <label>
                Representative Full Name
            </label>

            <input
                type="text"
                name="rep_full_name"
                class="onb-input"
                placeholder="Marco Bianchi"
                value="{{ old('rep_full_name', auth()->user()->rep_full_name) }}"
            >

            <div class="onb-field__hint">
                Person legally authorised to represent the company.
            </div>
        </div>

        <!-- Position -->
        <div class="onb-field">
            <label>
                Position
            </label>

            <select name="rep_position" class="onb-input">

                <option value="">-- Select Position --</option>

                <option value="CEO"
                    {{ old('rep_position', auth()->user()->rep_position) == 'CEO' ? 'selected' : '' }}>
                    CEO
                </option>

                <option value="Founder"
                    {{ old('rep_position', auth()->user()->rep_position) == 'Founder' ? 'selected' : '' }}>
                    Founder
                </option>

                <option value="Director"
                    {{ old('rep_position', auth()->user()->rep_position) == 'Director' ? 'selected' : '' }}>
                    Director
                </option>

                <option value="Legal Representative"
                    {{ old('rep_position', auth()->user()->rep_position) == 'Legal Representative' ? 'selected' : '' }}>
                    Legal Representative
                </option>

                <option value="Authorised Signatory"
                    {{ old('rep_position', auth()->user()->rep_position) == 'Authorised Signatory' ? 'selected' : '' }}>
                    Authorised Signatory
                </option>

            </select>

            <div class="onb-field__hint">
                Official position inside the company.
            </div>
        </div>

        <!-- Fiscal Code -->
        <div class="onb-field full">
            <label>
                Representative Fiscal Code
            </label>

            <input
                type="text"
                name="rep_fiscal_code"
                class="onb-input"
                maxlength="16"
                placeholder="RSSMRA80A01H501U"
                value="{{ old('rep_fiscal_code', auth()->user()->rep_fiscal_code) }}"
            >

            <div class="onb-field__hint">
                Italian personal fiscal code (16 characters).
            </div>
        </div>

        <!-- Upload Document -->
        <div class="onb-field full">

            <label>
                Identity Document
            </label>

            <label class="onb-upload">

                <div class="onb-upload__icon">
                    📄
                </div>

                <h5>Upload Document</h5>

                <p>
                    JPG, PNG or PDF · Max 5MB
                </p>

                <input
                    type="file"
                    name="rep_document"
                    accept=".jpg,.jpeg,.png,.pdf"
                >

            </label>

            @if(auth()->user()->rep_document)
                <div style="margin-top:10px;">
                    <a href="{{ asset(auth()->user()->rep_document) }}"
                       target="_blank">
                        View Uploaded Document
                    </a>
                </div>
            @endif

            <div class="onb-field__hint">
                Passport, ID card or driving licence accepted.
            </div>

        </div>

    </div>

    <div class="onb-footer">

        <a href="{{ route('profile.step3') }}"
           style="
                color:white;
                text-decoration:none;
                padding:14px 20px;
                border-radius:12px;
                background:#1e293b;
           ">
            Back
        </a>

        <button type="submit" class="onb-continue">
            Complete Profile
        </button>

    </div>

</form>

@endsection