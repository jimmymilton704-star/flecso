@extends('layouts.app')

@section('title', 'Edit Container')
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
            <div class="breadcrumb">Operations <span>/ Containers / Edit</span></div>
            <h1>Edit Container</h1>
            <div class="page-head__sub">Update container information</div>
        </div>
    </div>

    <form action="{{ route('containers.update', $container->id) }}" method="POST" enctype="multipart/form-data">
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
                        <input class="input" type="text" name="container_id" value="{{ $container->container_id }}" required>
                    </div>

                    <div class="field">
                        <label>License Number*</label>
                        <input class="input" type="text" name="container_license_number" value="{{ $container->container_license_number }}" required>
                    </div>

                    <div class="field">
                        <label>Container Type*</label>
                        <input class="input" type="text" name="container_type" value="{{ $container->container_type }}" required>
                    </div>

                    <div class="field">
                        <label>Status*</label>
                        <select name="status" required>
                            <option value="active" {{ $container->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ $container->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="inactive" {{ $container->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Weight Capacity (kg)*</label>
                        <input class="input" type="number" name="weight_capacity" value="{{ $container->weight_capacity }}" required>
                    </div>

                    <div class="field">
                        <label>Container Status*</label>
                        <select name="container_status" required>
                            <option value="empty" {{ $container->container_status == 'empty' ? 'selected' : '' }}>Empty</option>
                            <option value="full" {{ $container->container_status == 'full' ? 'selected' : '' }}>Full</option>
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

                        @if($container->image)
                            <img src="{{ asset($container->image) }}" style="margin-top:10px;height:80px;border-radius:8px;">
                        @endif
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
                        <label>Owner Code</label>
                        <input class="input" type="text" name="owner_code" value="{{ $container->owner_code }}" maxlength="3">
                    </div>

                    <div class="field">
                        <label>Category Identifier</label>
                        <input class="input" type="text" name="category_identifier" value="{{ $container->category_identifier }}" maxlength="1">
                    </div>

                    <div class="field">
                        <label>Serial Number</label>
                        <input class="input" type="text" name="serial_number" value="{{ $container->serial_number }}" maxlength="6">
                    </div>

                    <div class="field">
                        <label>Check Digit</label>
                        <input class="input" type="text" name="check_digit" value="{{ $container->check_digit }}" maxlength="1">
                    </div>

                    <div class="field">
                        <label>ISO Size Code</label>
                        <input class="input" type="text" name="iso_type_size_code" value="{{ $container->iso_type_size_code }}" maxlength="4">
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
                        <label>Manufacturer Serial</label>
                        <input class="input" type="text" name="manufacturer_serial_number" value="{{ $container->manufacturer_serial_number }}">
                    </div>

                    <div class="field">
                        <label>Manufacture Date</label>
                        <input class="input" type="date" name="manufacture_date" value="{{ $container->manufacture_date }}">
                    </div>

                    <div class="field">
                        <label>Max Operating Weight</label>
                        <input class="input" type="number" name="max_operating_weight" value="{{ $container->max_operating_weight }}">
                    </div>

                    <div class="field">
                        <label>Stacking Weight</label>
                        <input class="input" type="number" name="stacking_weight" value="{{ $container->stacking_weight }}">
                    </div>

                    <div class="field">
                        <label>Next Examination</label>
                        <input class="input" type="date" name="next_examination_date" value="{{ $container->next_examination_date }}">
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
                        <input class="input" type="text" name="eori_number" value="{{ $container->eori_number }}">
                    </div>

                    <div class="field">
                        <label>Seal Number</label>
                        <input class="input" type="text" name="seal_number" value="{{ $container->seal_number }}">
                    </div>

                    <div class="field">
                        <label>Owner / Lessor</label>
                        <input class="input" type="text" name="owner_lessor" value="{{ $container->owner_lessor }}">
                    </div>

                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="card__body">
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('containers.index') }}" class="btn btn--ghost">Cancel</a>
                    <button type="submit" class="btn btn--primary">Update Container</button>
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