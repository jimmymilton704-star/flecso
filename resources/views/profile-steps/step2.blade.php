@extends('profile-steps.partials.layout')

@php
    $step = 2;
    $title = 'Address & communication';
    $description = 'Provide registered office and invoicing details.';
@endphp

@section('content')

<div class="onb-main__head">
    <h1>Address & Communication</h1>
    <p>Step 2 of 4</p>
</div>

<form method="POST" action="{{ route('profile.step2.post') }}">
    @csrf

    <div class="onb-form">

        <div class="onb-field">
            <label>PEC Email</label>
            <input type="email" name="pec_email" class="onb-input"
                   value="{{ old('pec_email', auth()->user()->pec_email) }}">
        </div>

        <div class="onb-field">
            <label>SDI Code</label>
            <input type="text" name="sdi_code" class="onb-input"
                   value="{{ old('sdi_code', auth()->user()->sdi_code) }}">
        </div>

        <div class="onb-field full">
            <label>Registered Address</label>
            <input type="text" name="registered_address" class="onb-input"
                   value="{{ old('registered_address', auth()->user()->registered_address) }}">
        </div>

        <div class="onb-field">
            <label>City</label>
            <input type="text" name="city" class="onb-input"
                   value="{{ old('city', auth()->user()->city) }}">
        </div>

        <div class="onb-field">
            <label>Province</label>
            <input type="text" name="province" class="onb-input"
                   value="{{ old('province', auth()->user()->province) }}">
        </div>

        <div class="onb-field">
            <label>Zip Code</label>
            <input type="text" name="zip_code" class="onb-input"
                   value="{{ old('zip_code', auth()->user()->zip_code) }}">
        </div>

    </div>

    <div class="onb-footer">
        <a href="{{ route('profile.step1') }}" style="color:white">
            Back
        </a>

        <button class="onb-continue">
            Continue
        </button>
    </div>

</form>

@endsection