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
            <input type="email"
                   name="pec_email"
                   class="onb-input"
                   value="{{ old('pec_email', auth()->user()->pec_email) }}">
        </div>

        <div class="onb-field">
            <label>SDI Code</label>
            <input type="text"
                   name="sdi_code"
                   class="onb-input"
                   value="{{ old('sdi_code', auth()->user()->sdi_code) }}">
        </div>

        {{-- ADDRESS --}}
        <div class="onb-field full">
            <label>Registered Address</label>

            <input type="text"
                   id="autocomplete"
                   name="registered_address"
                   class="onb-input"
                   placeholder="Start typing address..."
                   autocomplete="off"
                   value="{{ old('registered_address', auth()->user()->registered_address) }}">
        </div>

        <div class="onb-field">
            <label>City</label>

            <input type="text"
                   id="city"
                   name="city"
                   class="onb-input"
                   value="{{ old('city', auth()->user()->city) }}">
        </div>

        <div class="onb-field">
            <label>Province</label>

            <input type="text"
                   id="province"
                   name="province"
                   class="onb-input"
                   value="{{ old('province', auth()->user()->province) }}">
        </div>

        <div class="onb-field">
            <label>Zip Code</label>

            <input type="text"
                   id="zip_code"
                   name="zip_code"
                   class="onb-input"
                   value="{{ old('zip_code', auth()->user()->zip_code) }}">
        </div>

    </div>

    <div class="onb-footer">

        <a href="{{ route('profile.step1') }}" class="onb-back-btn" style="color: white; text-decoration: none; padding: 14px 20px; border-radius: 12px; background: #1e293b;">
            Back
        </a>

        <button type="submit" class="onb-continue">
            Continue
        </button>

    </div>

</form>

{{-- GOOGLE MAPS API --}}
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQqP59sFi_cXyk8Afq_AY4Dkg4DCf-xj0&libraries=places">
</script>

<script>
    let autocomplete;

    function initAutocomplete() {

        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('autocomplete'),
            {
                types: ['address']
            }
        );

        autocomplete.addListener('place_changed', fillAddressData);
    }

    function fillAddressData() {

        const place = autocomplete.getPlace();

        let city = '';
        let province = '';
        let zip = '';

        for (const component of place.address_components) {

            const types = component.types;

            // CITY
            if (types.includes('locality')) {
                city = component.long_name;
            }

            // PROVINCE / STATE
            if (types.includes('administrative_area_level_1')) {
                province = component.short_name;
            }

            // ZIP CODE
            if (types.includes('postal_code')) {
                zip = component.long_name;
            }
        }

        document.getElementById('city').value = city;
        document.getElementById('province').value = province;
        document.getElementById('zip_code').value = zip;
    }

    window.onload = initAutocomplete;
</script>

@endsection