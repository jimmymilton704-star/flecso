@extends('layouts.app')

@section('title', 'Add Container')
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
</style>

<section class="page">
    <div class="page-head">
        <div>
            <div class="breadcrumb">Operations <span>/ Containers / Add</span></div>
            <h1>Add New Container</h1>
            <div class="page-head__sub">Register a new container into your fleet</div>
        </div>
    </div>

    <form action="{{ route('containers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card">

            {{-- 1. BASIC --}}
            <div class="card__head">
                <h3>1. Basic Information</h3>
            </div>
            <div class="card__body">
                <div class="form-grid">

                    <div class="field">
                        <label>Container ID*</label>
                        <input class="input" type="text" name="container_id" required>
                    </div>

                    <div class="field">
                        <label>License Number*</label>
                        <input class="input" type="text" name="container_license_number" required>
                    </div>

                    <div class="field">
                        <label>Container Type*</label>
                        <input class="input" type="text" name="container_type" required>
                    </div>

                    <div class="field">
                        <label>Status*</label>
                        <select name="status" required>
                            <option value="active">Active</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Weight Capacity (kg)*</label>
                        <input class="input" type="number" name="weight_capacity" required>
                    </div>

                    <div class="field">
                        <label>Container Status*</label>
                        <select name="container_status" required>
                            <option value="empty">Empty</option>
                            <option value="full">Full</option>
                        </select>
                    </div>

                    <div class="field full">
                        <label>Container Image</label>

                        <label class="upload-box">
                            <input type="file" name="image" id="imgUpload" hidden>

                            <div class="upload-content">
                                <strong id="imgText">Click or upload image</strong>
                                <span id="imgSub">JPG, PNG, WEBP (Max 2MB)</span>
                            </div>
                        </label>
                    </div>

                </div>
            </div>

            {{-- 2. ISO IDENTIFICATION --}}
            <div class="card__head">
                <h3>2. ISO Identification</h3>
            </div>
            <div class="card__body">
                <div class="form-grid">

                    <div class="field">
                        <label>Owner Code*</label>
                        <input class="input" type="text" name="owner_code" maxlength="3" required>
                    </div>

                    <div class="field">
                        <label>Category Identifier*</label>
                        <input class="input" type="text" name="category_identifier" maxlength="1" required>
                    </div>

                    <div class="field">
                        <label>Serial Number*</label>
                        <input class="input" type="text" name="serial_number" maxlength="6" required>
                    </div>

                    <div class="field">
                        <label>Check Digit*</label>
                        <input class="input" type="text" name="check_digit" maxlength="1" required>
                    </div>

                    <div class="field">
                        <label>ISO Size Code*</label>
                        <input class="input" type="text" name="iso_type_size_code" maxlength="4" required>
                    </div>

                </div>
            </div>

            {{-- 3. TECHNICAL --}}
            <div class="card__head">
                <h3>3. Technical Details</h3>
            </div>
            <div class="card__body">
                <div class="form-grid">

                    <div class="field">
                        <label>Manufacturer Serial*</label>
                        <input class="input" type="text" name="manufacturer_serial_number" required>
                    </div>

                    <div class="field">
                        <label>Manufacture Date*</label>
                        <input class="input" type="date" name="manufacture_date" required>
                    </div>

                    <div class="field">
                        <label>Max Operating Weight*</label>
                        <input class="input" type="number" name="max_operating_weight" required>
                    </div>

                    <div class="field">
                        <label>Stacking Weight*</label>
                        <input class="input" type="number" name="stacking_weight" required>
                    </div>

                    <div class="field">
                        <label>Next Examination*</label>
                        <input class="input" type="date" name="next_examination_date" required>
                    </div>

                </div>
            </div>

            {{-- 4. CUSTOM --}}
            <div class="card__head">
                <h3>4. Additional Info</h3>
            </div>
            <div class="card__body">
                <div class="form-grid">

                    <div class="field">
                        <label>EORI Number</label>
                        <input class="input" type="text" name="eori_number">
                    </div>

                    <div class="field">
                        <label>Seal Number</label>
                        <input class="input" type="text" name="seal_number">
                    </div>

                    <div class="field">
                        <label>Owner / Lessor</label>
                        <input class="input" type="text" name="owner_lessor">
                    </div>

                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="card__body">
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('containers.index') }}" class="btn btn--ghost">Cancel</a>
                    <button type="submit" class="btn btn--primary">Save Container</button>
                </div>
            </div>

        </div>
    </form>
</section>

<script>
    document.getElementById('imgUpload').addEventListener('change', function() {
        let fileName = this.files[0]?.name;
        if (fileName) {
            document.getElementById('imgText').innerText = fileName;
            document.getElementById('imgSub').innerText = "Image selected";
        }
    });
</script>

@endsection