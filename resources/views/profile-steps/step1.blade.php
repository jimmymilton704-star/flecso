@extends('profile-steps.partials.layout')

@php
    $step = 1;
    $title = 'Tell us about your business';
    $description = 'We need company legal information before dashboard access.';
@endphp

@section('content')

<div class="onb-main__head">
    <h1>Company Information</h1>
    <p>Step 1 of 4</p>
</div>

<form method="POST" action="{{ route('profile.step1.post') }}">
    @csrf

    <div class="onb-form">

        <div class="onb-field full">
            <label>Company legal name <span class="req">*</span></label>
            <input type="text" name="company_legal_name" class="onb-input"
                   value="{{ old('company_legal_name', auth()->user()->company_legal_name) }}">
        </div>

        <div class="onb-field">
            <label>Company type <span class="req">*</span></label>

            <select name="company_type">
                <option value="">Select</option>
                <option value="S.p.A.">S.p.A.</option>
                <option value="S.r.l.">S.r.l.</option>
            </select>
        </div>

        <div class="onb-field">
            <label>VAT Number <span class="req">*</span></label>
            <input type="text" name="vat_number" class="onb-input"
                   value="{{ old('vat_number', auth()->user()->vat_number) }}">
        </div>

        <div class="onb-field">
            <label>Fiscal code <span class="req">*</span></label>
            <input type="text" name="fiscal_code" class="onb-input"
                   value="{{ old('fiscal_code', auth()->user()->fiscal_code) }}">
        </div>

        <div class="onb-field">
            <label>REA Number <span class="req">*</span></label>
            <input type="text" name="rea_number" class="onb-input"
                   value="{{ old('rea_number', auth()->user()->rea_number) }}">
        </div>

    </div>

    <div class="onb-footer">
        <div></div>

        <button class="onb-continue">
            Continue
        </button>
    </div>

</form>

@endsection