@extends('layouts.app')

@section('title', 'Add Truck')
@section('body-class', 'page-dashboard')


@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
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
            border-color: #ff8a2b;
            background: #fff5ec;
            color: #ff8a2b;
        }

        .upload-box:hover .upload-content span {
            color: #e08e4a;
        }

        .upload-box:hover .upload-content svg {
            color: #c43e04;
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

        h3 {
            font-family: "Space Grotesk", "Inter", sans-serif;
        }

        .count {
            padding: 10px;
            border-radius: 7px;
            background: var(--orange-50);
            color: var(--orange-700);
            font-size: 11px;
            font-weight: 700;
            place-items: center;
        }
    </style>

    <section class="page">
        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ Trucks / Add</span></div>
                <h1>Add New Truck</h1>
                <div class="page-head__sub">Register a new vehicle into your fleet</div>
            </div>
        </div>
        {{-- CSV IMPORT --}}
        <div class="card" style="margin-bottom:20px;">

            <div class="card__head">
                <h3>Import Trucks From CSV</h3>
            </div>

            <div class="card__body">

                <form action="{{ route('trucks.import') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="form-grid">

                        <div class="field full">

                            <label>Upload CSV File</label>

                            <label class="upload-box">

                                <input type="file" name="csv_file" id="trucksCsvUpload" accept=".csv" hidden required>

                                <div class="upload-content">

                                    <strong id="trucksCsvText">
                                        Click to upload CSV
                                    </strong>

                                    <span id="trucksCsvSub">
                                        CSV format only
                                    </span>

                                </div>

                            </label>

                        </div>

                    </div>

                    <div style="margin-top:15px; display:flex; gap:10px; flex-wrap:wrap;">

                        <button type="submit" class="btn btn--primary">
                            Import Trucks
                        </button>

                        <a href="{{ asset('samples/trucks-sample.csv') }}" class="btn btn--ghost" download>
                            Download Sample CSV
                        </a>

                    </div>

                </form>

            </div>

        </div>

        <form action="{{ route('trucks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf


            <div class="card">
                @if ($errors->any())
                    <div class="alert alert--danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- 1. BASIC --}}
                <div class="card__head">
                    <h3><span class="count">1.</span> Basic Information</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Truck Number*</label>
                            <input class="input" type="text" name="truck_number" required>
                        </div>

                        <div class="field">
                            <label>Truck License Number*</label>
                            <input class="input" type="text" name="truck_license_number" required>
                        </div>

                        <div class="field">
                            <label>Capacity (Tons)*</label>
                            <input class="input" type="number" name="capacity_tons" required>
                        </div>

                        <div class="field">
                            <label>Category*</label>
                            <select name="truck_type_category" required>
                                <option value="Semi-Truck">Semi-Truck</option>
                                <option value="Box Truck">Box Truck</option>
                                <option value="Refrigerated">Refrigerated</option>
                                <option value="Flatbed">Flatbed</option>
                                <option value="Tanker">Tanker</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Type*</label>
                            <select name="type" required>
                                <option value="Heavy">Heavy</option>
                                <option value="Medium">Medium</option>
                                <option value="Light">Light</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Status*</label>
                            <select name="status" required>
                                <option value="active">Active</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="idle">Idle</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>



                        <div class="field full">
                            <label>Truck Images</label>

                            <label class="upload-box">
                                <input type="file" name="image" id="docUpload" hidden>

                                <div class="upload-content">
                                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <path d="M17 8l-5-5-5 5" />
                                        <path d="M12 3v12" />
                                    </svg>

                                    <strong id="uploadText">Click or drop file here</strong>
                                    <span id="uploadSub">PDF, DOC up to 10MB</span>
                                </div>
                            </label>

                            @error('documento_unico')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                    </div>
                </div>

                {{-- 2. IDENTITY --}}
                <div class="card__head">
                    <h3><span class="count">2.</span> Identity & Legal</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>License Plate Number*</label>
                            <input class="input" type="text" name="license_plate_number" required>
                        </div>

                        <div class="field">
                            <label>VIN Number</label>
                            <input class="input" type="text" name="vin_number">
                        </div>

                        <div class="field">
                            <label>Registration Date</label>
                            <input class="input" type="date" name="first_registration_date">
                        </div>

                        <div class="field">
                            <label>Usage Type</label>
                            <select name="usage_type">
                                <option value="Owned">Owned</option>
                                <option value="Leased">Leased</option>
                                <option value="Rented">Rented</option>
                            </select>
                        </div>

                        <div class="field full">
                            <label>Legal Documents</label>

                            <label class="upload-box">
                                <input type="file" name="documento_unico" id="docUploadFile" accept=".pdf,.doc,.docx"
                                    hidden>

                                <div class="upload-content">
                                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <path d="M17 8l-5-5-5 5" />
                                        <path d="M12 3v12" />
                                    </svg>

                                    <strong id="docUploadText">Click or upload document</strong>
                                    <span id="docUploadSub">PDF, DOC, DOCX (Max 10MB)</span>
                                </div>
                            </label>

                            @error('documento_unico')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- 3. TECHNICAL --}}
                <div class="card__head">
                    <h3><span class="count">3.</span> Technical Specifications</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Vehicle Category</label>
                            <select name="vehicle_category">
                                <option value="N1">N1</option>
                                <option value="N2">N2</option>
                                <option value="N3">N3</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>GVW (kg)</label>
                            <input class="input" type="number" name="gvw_kg">
                        </div>

                        <div class="field">
                            <label>Payload Capacity (kg)</label>
                            <input class="input" type="number" name="payload_capacity_kg">
                        </div>

                        <div class="field">
                            <label>Axles</label>
                            <input class="input" type="number" name="number_of_axles">
                        </div>

                        <div class="field">
                            <label>Engine Class</label>
                            <input class="input" type="text" name="engine_class">
                        </div>

                        <div class="field">
                            <label>Fuel Type</label>
                            <select name="fuel_type">
                                <option value="Diesel">Diesel</option>
                                <option value="Electric">Electric</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Assigned driver*</label>
                            <select name="driver_id">
                                <option value="">-- Select Driver --</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>

                                @endforeach

                            </select>
                        </div>

                    </div>
                </div>

                {{-- 4. COMPLIANCE --}}
                <div class="card__head">
                    <h3><span class="count">4.</span> Compliance</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Next Inspection</label>
                            <input class="input" type="date" name="next_inspection_date">
                        </div>

                        <div class="field">
                            <label>Insurance Policy</label>
                            <input class="input" type="text" name="insurance_policy_number">
                        </div>

                        <div class="field">
                            <label>Insurance Expiry</label>
                            <input class="input" type="date" name="insurance_expiry_date">
                        </div>

                        <div class="field">
                            <label>Tachograph Expiry</label>
                            <input class="input" type="date" name="tachograph_calibration_expiry">
                        </div>

                        <div class="field">
                            <label>Bollo Expiry</label>
                            <input class="input" type="date" name="bollo_expiry_date">
                        </div>

                    </div>
                </div>

                <div class="card__head">
                    <h3><span class="count">5.</span> Health</h3>
                </div>
                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Current Mileage*</label>
                            <input class="input" type="number" name="current_km" required>
                        </div>

                        <div class="field">
                            <label>Estimate Mileage*</label>
                            <input class="input" type="number" name="estimate_km" required>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="card__body">
                    <div style="display:flex; gap:10px;">
                        <a href="{{ route('trucks.index') }}" class="btn btn--ghost">Cancel</a>
                        <button type="submit" class="btn btn--primary">Save Truck</button>
                    </div>
                </div>

            </div>
        </form>
    </section>
    <script>
        document.getElementById('docUpload').addEventListener('change', function () {
            let fileName = this.files[0]?.name;
            if (fileName) {
                document.getElementById('uploadText').innerText = fileName;
                document.getElementById('uploadSub').innerText = "File selected";
            }
        });
    </script>
    <script>
        document.getElementById('docUploadFile').addEventListener('change', function () {
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

    <script>
        document.getElementById('trucksCsvUpload').addEventListener('change', function () {

            let fileName = this.files[0]?.name;

            if (fileName) {

                document.getElementById('trucksCsvText').innerText = fileName;

                document.getElementById('trucksCsvSub').innerText =
                    "CSV selected successfully";
            }
        });
    </script>
@endsection