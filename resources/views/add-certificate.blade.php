@extends('layouts.admin')

@section('title', 'Add Certificate')

@push('styles')
<style>
    label { font-weight: 600; }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div>
        <h1>Add Certificate</h1>
        <p>Create a new calibration certificate record. * Required fields</p>
    </div>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('certificates.index') }}">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Certificates
    </a>
</div>

<section class="admin-card">
    <div class="admin-card-header"><h2>Certificate Details</h2></div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('certificate.create') }}">
            @csrf

            <div class="mb-3">
                <label for="certificate_number">Certificate Number *</label>
                @error('certificate_number') <div class="text-danger">{{ $message }}</div> @enderror
                <input type="text" name="certificate_number" id="certificate_number" class="form-control"
                    value="{{ old('certificate_number', 'CAL-TUVAT-' . $currentYear . '-' . $currentMonthDay . '-') }}">
            </div>

            <div class="mb-3">
                <label for="calibrator">Calibration Engineer *</label>
                @error('calibrator') <div class="text-danger">{{ $message }}</div> @enderror
                <input type="text" name="calibrator" id="calibrator" class="form-control" value="{{ old('calibrator') }}">
            </div>

            <div class="mb-3">
                <label for="client_name">Client Name *</label>
                @error('client_name') <div class="text-danger">{{ $message }}</div> @enderror
                <input type="text" name="client_name" id="client_name" class="form-control" value="{{ old('client_name') }}">
            </div>

            <div class="mb-3">
                <label for="location">Location *</label>
                @error('location') <div class="text-danger">{{ $message }}</div> @enderror
                <textarea name="location" id="location" class="form-control">{{ old('location') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="equipment_name">Equipment Name *</label>
                @error('equipment_name') <div class="text-danger">{{ $message }}</div> @enderror
                <input type="text" name="equipment_name" id="equipment_name" class="form-control" value="{{ old('equipment_name') }}">
            </div>

            <div class="mb-3">
                <label for="equipment_brand">Equipment Brand *</label>
                @error('equipment_brand') <div class="text-danger">{{ $message }}</div> @enderror
                <input type="text" name="equipment_brand" id="equipment_brand" class="form-control" value="{{ old('equipment_brand') }}">
            </div>

            <div class="mb-3">
                <label for="equipment_id">Equipment ID *</label>
                @error('equipment_id') <div class="text-danger">{{ $message }}</div> @enderror
                <input type="text" name="equipment_id" id="equipment_id" class="form-control" value="{{ old('equipment_id') }}">
            </div>

            <div class="mb-3">
                <label for="calibration_date">Calibration Date *</label>
                @error('calibration_date') <div class="text-danger">{{ $message }}</div> @enderror
                <input type="date" name="calibration_date" id="calibration_date" class="form-control" value="{{ old('calibration_date') }}">
            </div>

            <div class="mb-3">
                <label for="report_issue_date">Report Issue Date *</label>
                @error('report_issue_date') <div class="text-danger">{{ $message }}</div> @enderror
                <input type="date" name="report_issue_date" id="report_issue_date" class="form-control" value="{{ old('report_issue_date') }}">
            </div>

            <div class="mb-3">
                <label for="validity_date">Validity Date</label>
                @error('validity_date') <div class="text-danger">{{ $message }}</div> @enderror
                <input type="date" name="validity_date" id="validity_date" class="form-control" value="{{ old('validity_date') }}">
            </div>

            <div class="mb-3">
                <label for="calibration_remarks">Calibration Remarks</label>
                @error('calibration_remarks') <div class="text-danger">{{ $message }}</div> @enderror
                <textarea name="calibration_remarks" id="calibration_remarks" class="form-control">{{ old('calibration_remarks') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="calibration_internal_notes">Internal Notes</label>
                @error('calibration_internal_notes') <div class="text-danger">{{ $message }}</div> @enderror
                <textarea name="calibration_internal_notes" id="calibration_internal_notes" class="form-control">{{ old('calibration_internal_notes') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="review_by">Review by *</label>
                @error('review_by') <div class="text-danger">{{ $message }}</div> @enderror
                <select name="review_by" id="review_by" class="form-control">
                    <option value="">Select Reviewer</option>
                    @foreach($users as $user)
                        <option value="{{ $user->name }}" {{ old('review_by') == $user->name ? 'selected' : '' }}>
                            {{ $user->name }} | {{ $user->designation }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="approval_by">Approval by *</label>
                @error('approval_by') <div class="text-danger">{{ $message }}</div> @enderror
                <select name="approval_by" id="approval_by" class="form-control">
                    <option value="">Select Approver</option>
                    @foreach($users as $user)
                        <option value="{{ $user->name }}" {{ old('approval_by') == $user->name ? 'selected' : '' }}>
                            {{ $user->name }} | {{ $user->designation }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-check me-1"></i> Add Details
            </button>
        </form>
    </div>
</section>
@endsection
