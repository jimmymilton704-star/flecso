@extends('layouts.app')

@section('title', 'Edit Truck')
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

        .current-file {
            margin-top: 8px;
            font-size: 12px;
            color: #374151;
        }

        .current-file a {
            color: #4f46e5;
            text-decoration: none;
        }

        .current-file a:hover {
            text-decoration: underline;
        }
    </style>

    <section class="page">
        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ Trucks / Edit</span></div>
                <h1>Edit Truck</h1>
                <div class="page-head__sub">Update vehicle information in your fleet</div>
            </div>
        </div>

        <form action="{{ route('trucks.update', $truck->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            

            <div class="card">

                {{-- 1. BASIC --}}
                <div class="card__head">
                    <h3>1. Basic Information</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Truck Number*</label>
                            <input class="input" type="text" name="truck_number"
                                   value="{{ old('truck_number', $truck->truck_number) }}" required>
                        </div>

                        <div class="field">
                            <label>Truck License Number*</label>
                            <input class="input" type="text" name="truck_license_number"
                                   value="{{ old('truck_license_number', $truck->truck_license_number) }}" required>
                        </div>

                        <div class="field">
                            <label>Capacity (Tons)*</label>
                            <input class="input" type="number" name="capacity_tons"
                                   value="{{ old('capacity_tons', $truck->capacity_tons) }}" required>
                        </div>

                        <div class="field">
                            <label>Category*</label>
                            <select name="truck_type_category" required>
                                <option value="Semi-Truck" {{ old('truck_type_category', $truck->truck_type_category) == 'Semi-Truck' ? 'selected' : '' }}>Semi-Truck</option>
                                <option value="Box Truck" {{ old('truck_type_category', $truck->truck_type_category) == 'Box Truck' ? 'selected' : '' }}>Box Truck</option>
                                <option value="Refrigerated" {{ old('truck_type_category', $truck->truck_type_category) == 'Refrigerated' ? 'selected' : '' }}>Refrigerated</option>
                                <option value="Flatbed" {{ old('truck_type_category', $truck->truck_type_category) == 'Flatbed' ? 'selected' : '' }}>Flatbed</option>
                                <option value="Tanker" {{ old('truck_type_category', $truck->truck_type_category) == 'Tanker' ? 'selected' : '' }}>Tanker</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Type*</label>
                            <select name="type" required>
                                <option value="Heavy" {{ old('type', $truck->type) == 'Heavy' ? 'selected' : '' }}>Heavy</option>
                                <option value="Medium" {{ old('type', $truck->type) == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="Light" {{ old('type', $truck->type) == 'Light' ? 'selected' : '' }}>Light</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Status*</label>
                            <select name="status" required>
                                <option value="active" {{ old('status', $truck->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="maintenance" {{ old('status', $truck->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="idle" {{ old('status', $truck->status) == 'idle' ? 'selected' : '' }}>Idle</option>
                                <option value="inactive" {{ old('status', $truck->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="field full">
                            <label>Truck Images</label>

                            <label class="upload-box">
                                <input type="file" name="image" id="docUpload" hidden>

                                <div class="upload-content">
                                    <svg width="28" height="28" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <path d="M17 8l-5-5-5 5" />
                                        <path d="M12 3v12" />
                                    </svg>

                                    <strong id="uploadText">
                                        {{ $truck->image ? basename($truck->image) : 'Click or drop file here' }}
                                    </strong>
                                    <span id="uploadSub">
                                        {{ $truck->image ? 'Current image uploaded' : 'PDF, DOC up to 10MB' }}
                                    </span>
                                </div>
                            </label>

                            @if($truck->image)
                                <div class="current-file">
                                    Current file:
                                    <a href="{{ asset($truck->image) }}" target="_blank">View Image</a>
                                </div>
                            @endif

                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- 2. IDENTITY --}}
                <div class="card__head">
                    <h3>2. Identity & Legal</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>License Plate Number*</label>
                            <input class="input" type="text" name="license_plate_number"
                                   value="{{ old('license_plate_number', $truck->license_plate_number) }}" required>
                        </div>

                        <div class="field">
                            <label>VIN Number*</label>
                            <input class="input" type="text" name="vin_number"
                                   value="{{ old('vin_number', $truck->vin_number) }}" required>
                        </div>

                        <div class="field">
                            <label>Registration Date*</label>
                            <input class="input" type="date" name="first_registration_date"
                                   value="{{ old('first_registration_date', $truck->first_registration_date ? \Carbon\Carbon::parse($truck->first_registration_date)->format('Y-m-d') : '') }}" required>
                        </div>

                        <div class="field">
                            <label>Usage Type*</label>
                            <select name="usage_type" required>
                                <option value="Owned" {{ old('usage_type', $truck->usage_type) == 'Owned' ? 'selected' : '' }}>Owned</option>
                                <option value="Leased" {{ old('usage_type', $truck->usage_type) == 'Leased' ? 'selected' : '' }}>Leased</option>
                                <option value="Rented" {{ old('usage_type', $truck->usage_type) == 'Rented' ? 'selected' : '' }}>Rented</option>
                            </select>
                        </div>

                        <div class="field full">
                            <label>Legal Documents</label>

                            <label class="upload-box">
                                <input type="file" name="documento_unico" id="docUploadFile" accept=".pdf,.doc,.docx" hidden>

                                <div class="upload-content">
                                    <svg width="28" height="28" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <path d="M17 8l-5-5-5 5" />
                                        <path d="M12 3v12" />
                                    </svg>

                                    <strong id="docUploadText">
                                        {{ $truck->documento_unico ? basename($truck->documento_unico) : 'Click or upload document' }}
                                    </strong>
                                    <span id="docUploadSub">
                                        {{ $truck->documento_unico ? 'Current document uploaded' : 'PDF, DOC, DOCX (Max 10MB)' }}
                                    </span>
                                </div>
                            </label>

                            @if($truck->documento_unico)
                                <div class="current-file">
                                    Current file:
                                    <a href="{{ asset($truck->documento_unico) }}" target="_blank">View Document</a>
                                </div>
                            @endif

                            @error('documento_unico')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- 3. TECHNICAL --}}
                <div class="card__head">
                    <h3>3. Technical Specifications</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Vehicle Category*</label>
                            <select name="vehicle_category" required>
                                <option value="N1" {{ old('vehicle_category', $truck->vehicle_category) == 'N1' ? 'selected' : '' }}>N1</option>
                                <option value="N2" {{ old('vehicle_category', $truck->vehicle_category) == 'N2' ? 'selected' : '' }}>N2</option>
                                <option value="N3" {{ old('vehicle_category', $truck->vehicle_category) == 'N3' ? 'selected' : '' }}>N3</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>GVW (kg)*</label>
                            <input class="input" type="number" name="gvw_kg"
                                   value="{{ old('gvw_kg', $truck->gvw_kg) }}" required>
                        </div>

                        <div class="field">
                            <label>Payload Capacity (kg)*</label>
                            <input class="input" type="number" name="payload_capacity_kg"
                                   value="{{ old('payload_capacity_kg', $truck->payload_capacity_kg) }}" required>
                        </div>

                        <div class="field">
                            <label>Axles*</label>
                            <input class="input" type="number" name="number_of_axles"
                                   value="{{ old('number_of_axles', $truck->number_of_axles) }}" required>
                        </div>

                        <div class="field">
                            <label>Engine Class*</label>
                            <input class="input" type="text" name="engine_class"
                                   value="{{ old('engine_class', $truck->engine_class) }}" required>
                        </div>

                        <div class="field">
                            <label>Fuel Type*</label>
                            <select name="fuel_type" required>
                                <option value="Diesel" {{ old('fuel_type', $truck->fuel_type) == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="Electric" {{ old('fuel_type', $truck->fuel_type) == 'Electric' ? 'selected' : '' }}>Electric</option>
                                <option value="Hybrid" {{ old('fuel_type', $truck->fuel_type) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>

                    </div>
                </div>

                {{-- 4. COMPLIANCE --}}
                <div class="card__head">
                    <h3>4. Compliance</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Next Inspection*</label>
                            <input class="input" type="date" name="next_inspection_date"
                                   value="{{ old('next_inspection_date', $truck->next_inspection_date ? \Carbon\Carbon::parse($truck->next_inspection_date)->format('Y-m-d') : '') }}" required>
                        </div>

                        <div class="field">
                            <label>Insurance Policy*</label>
                            <input class="input" type="text" name="insurance_policy_number"
                                   value="{{ old('insurance_policy_number', $truck->insurance_policy_number) }}" required>
                        </div>

                        <div class="field">
                            <label>Insurance Expiry*</label>
                            <input class="input" type="date" name="insurance_expiry_date"
                                   value="{{ old('insurance_expiry_date', $truck->insurance_expiry_date ? \Carbon\Carbon::parse($truck->insurance_expiry_date)->format('Y-m-d') : '') }}" required>
                        </div>

                        <div class="field">
                            <label>Tachograph Expiry*</label>
                            <input class="input" type="date" name="tachograph_calibration_expiry"
                                   value="{{ old('tachograph_calibration_expiry', $truck->tachograph_calibration_expiry ? \Carbon\Carbon::parse($truck->tachograph_calibration_expiry)->format('Y-m-d') : '') }}" required>
                        </div>

                        <div class="field">
                            <label>Bollo Expiry*</label>
                            <input class="input" type="date" name="bollo_expiry_date"
                                   value="{{ old('bollo_expiry_date', $truck->bollo_expiry_date ? \Carbon\Carbon::parse($truck->bollo_expiry_date)->format('Y-m-d') : '') }}" required>
                        </div>

                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="card__body">
                    <div style="display:flex; gap:10px;">
                        <a href="{{ route('trucks.index') }}" class="btn btn--ghost">Cancel</a>
                        <button type="submit" class="btn btn--primary">Update Truck</button>
                    </div>
                </div>

            </div>
        </form>
    </section>

    <script>
        document.getElementById('docUpload').addEventListener('change', function() {
            let fileName = this.files[0]?.name;
            if (fileName) {
                document.getElementById('uploadText').innerText = fileName;
                document.getElementById('uploadSub').innerText = "File selected";
            }
        });
    </script>

    <script>
        document.getElementById('docUploadFile').addEventListener('change', function() {
            let file = this.files[0];

            if (file) {
                let allowed = ['pdf', 'doc', 'docx'];
                let ext = file.name.split('.').pop().toLowerCase();

                if (!allowed.includes(ext)) {
                    alert('Only PDF, DOC, DOCX allowed');
                    this.value = '';
                    return;
                }

                document.getElementById('docUploadText').innerText = file.name;
                document.getElementById('docUploadSub').innerText = "Document selected";
            }
        });
    </script>
@endsection