@extends('profile-steps.partials.layout')

@php
    $step = 3;
    $title = 'Your fleet & operations';
    $description = 'Tell us about your fleet, licences and operational setup.';
@endphp

@section('content')

<div class="onb-main__head">
    <h1>Operations & Fleet</h1>
    <p>Step 3 of 4</p>
</div>

<form method="POST" action="{{ route('profile.step3.post') }}">
    @csrf

    <div class="onb-form">

        <div class="onb-field">
            <label>
                REN Number
                <span class="req">*</span>
            </label>

            <input
                type="text"
                name="ren_number"
                class="onb-input"
                placeholder="REN-IT-0123456"
                value="{{ old('ren_number', auth()->user()->ren_number) }}"
            >

            <div class="onb-field__hint">
                Registro Elettronico Nazionale number.
            </div>
        </div>

        <div class="onb-field">
            <label>
                EU Licence Number
            </label>

            <input
                type="text"
                name="eu_license_number"
                class="onb-input"
                placeholder="EU-LIC-12345"
                value="{{ old('eu_license_number', auth()->user()->eu_license_number) }}"
            >

            <div class="onb-field__hint">
                Optional for cross-border operations.
            </div>
        </div>

        <div class="onb-field full">
            <label>
                Insurance Policy Number
                <span class="req">*</span>
            </label>

            <input
                type="text"
                name="insurance_policy_number"
                class="onb-input"
                placeholder="GEN-2026-887412"
                value="{{ old('insurance_policy_number', auth()->user()->insurance_policy_number) }}"
            >

            <div class="onb-field__hint">
                Active fleet insurance policy number.
            </div>
        </div>

        <!-- Fleet Trucks -->
        <div class="onb-field">
            <label>
                Fleet Trucks
                <span class="req">*</span>
            </label>

            <input
                type="number"
                min="0"
                name="fleet_trucks"
                class="onb-input"
                value="{{ old('fleet_trucks', auth()->user()->fleet_trucks ?? 0) }}"
            >

            <div class="onb-field__hint">
                Number of trucks in your fleet.
            </div>
        </div>

        <!-- Fleet Vans -->
        <div class="onb-field">
            <label>
                Fleet Vans
                <span class="req">*</span>
            </label>

            <input
                type="number"
                min="0"
                name="fleet_vans"
                class="onb-input"
                value="{{ old('fleet_vans', auth()->user()->fleet_vans ?? 0) }}"
            >

            <div class="onb-field__hint">
                Number of vans in your fleet.
            </div>
        </div>

        <!-- Fleet Containers -->
        <div class="onb-field">
            <label>
                Fleet Containers
                <span class="req">*</span>
            </label>

            <input
                type="number"
                min="0"
                name="fleet_containers"
                class="onb-input"
                value="{{ old('fleet_containers', auth()->user()->fleet_containers ?? 0) }}"
            >

            <div class="onb-field__hint">
                Number of containers in your fleet.
            </div>
        </div>

    </div>

    <div class="onb-footer">

        <a href="{{ route('profile.step2') }}"
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
            Continue
        </button>

    </div>

</form>

@endsection