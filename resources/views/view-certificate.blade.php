@extends('layouts.admin')

@section('title', 'Certificate Details')

@push('styles')
<style>
    .certificate-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div>
        <h1>Certificate Details</h1>
        <p>{{ $certificate->certificate_number }} — {{ $certificate->client_name }}</p>
    </div>
    <div class="certificate-actions">
        <a href="{{ route('certificates.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>

        @if($certificate->status !== 'Deleted')
            @canMutate
            <a href="{{ route('certificate.edit', $certificate->id) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
            </a>
            @endcanMutate

            @if($certificate->certificate_pdf)
                <a href="{{ route('certificate.downloadPdf', $certificate->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                </a>
            @endif

            @canMutate
            @if(Auth::user()->id == $certificate->review_by_id && $certificate->status == 'Pending Review')
                <form action="{{ route('certificate.review', $certificate->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm" data-confirm="Mark this certificate as Reviewed?">
                        <i class="fa-solid fa-thumbs-up me-1"></i> Mark as Reviewed
                    </button>
                </form>
            @endif

            @if(Auth::user()->id == $certificate->approval_by_id && $certificate->status == 'Pending Approval')
                <form action="{{ route('certificate.approve', $certificate->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm" data-confirm="Mark this certificate as Approved?">
                        <i class="fa-solid fa-check me-1"></i> Mark as Approved
                    </button>
                </form>
            @endif

            <form action="{{ route('certificate.delete', $certificate->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Delete this certificate?">
                    <i class="fa-solid fa-trash me-1"></i> Delete
                </button>
            </form>
            @endcanMutate
        @endif
    </div>
</div>

<section class="admin-card">
    <div class="admin-card-header"><h2>Record Summary</h2></div>
    <div class="admin-card-body">
        @canMutate
        @if(
            $certificate->status !== 'Deleted' &&
            (
                Auth::user()->id == $certificate->created_by_id ||
                Auth::user()->id == $certificate->review_by_id ||
                Auth::user()->id == $certificate->approval_by_id
            )
        )
            <form action="{{ route('certificate.uploadPdf', $certificate->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                @csrf
                <div class="input-group" style="max-width: 600px;">
                    <input type="file" name="certificate_pdf" class="form-control" accept="application/pdf" required>
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-upload me-1"></i>
                        {{ $certificate->certificate_pdf ? 'Re-upload Certificate' : 'Upload Certificate' }}
                    </button>
                </div>
            </form>
        @endif
        @endcanMutate

        @if($certificate->certificate_pdf)
            <div class="mb-3 text-muted small">
                Last uploaded by <strong>{{ $certificate->pdf_uploaded_by }}</strong>
                on {{ \Carbon\Carbon::parse($certificate->pdf_uploaded_at)->format('d M Y \a\t H:i') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <tbody>
                    <tr><th>Certificate Number</th><td>{{ $certificate->certificate_number }}</td></tr>
                    <tr>
                        <th>Certificate Validity</th>
                        <td>
                            @if ($certificate->status === 'Deleted')
                                <span class="text-danger">This certificate has been deleted</span>
                            @elseif ($certificate->status === 'Pending Review')
                                <span class="text-warning">Certificate Pending Review</span>
                            @elseif ($certificate->status === 'Pending Approval')
                                <span class="text-warning">Certificate Pending Approval</span>
                            @elseif (empty($certificate->validity_date) || \Carbon\Carbon::now() <= \Carbon\Carbon::parse($certificate->validity_date))
                                <span class="text-success">Certificate Valid</span>
                            @else
                                <span class="text-danger">Certificate Expired</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Approval Status</th>
                        <td><x-admin.status-badge :status="$certificate->status" /></td>
                    </tr>
                    <tr><th>Calibration Engineer</th><td>{{ $certificate->calibrator }}</td></tr>
                    <tr><th>Client</th><td>{{ $certificate->client_name }}</td></tr>
                    <tr><th>Location</th><td>{{ $certificate->location }}</td></tr>
                    <tr><th>Equipment Name</th><td>{{ $certificate->equipment_name }}</td></tr>
                    <tr><th>Manufacturer / Brand</th><td>{{ $certificate->equipment_brand }}</td></tr>
                    <tr><th>Equipment ID</th><td>{{ $certificate->equipment_id }}</td></tr>
                    <tr><th>Calibration Date</th><td>{{ $certificate->calibration_date ? \Carbon\Carbon::parse($certificate->calibration_date)->format('d M Y') : 'N/A' }}</td></tr>
                    <tr><th>Report Issue Date</th><td>{{ $certificate->report_issue_date ? \Carbon\Carbon::parse($certificate->report_issue_date)->format('d M Y') : 'N/A' }}</td></tr>
                    <tr>
                        <th>Valid Till</th>
                        <td>
                            @if (!empty($certificate->validity_date))
                                {{ \Carbon\Carbon::parse($certificate->validity_date)->format('d M Y') }}
                            @else
                                No Expiry Date
                            @endif
                        </td>
                    </tr>
                    <tr><th>Calibration Remarks</th><td>{{ $certificate->calibration_remarks ?: 'N/A' }}</td></tr>
                    <tr><th>Internal Notes</th><td>{{ $certificate->calibration_internal_notes ?: 'N/A' }}</td></tr>
                    <tr>
                        <th>Certificate PDF</th>
                        <td>
                            @if($certificate->certificate_pdf)
                                <a href="{{ route('certificate.viewPdf', $certificate->id) }}" target="_blank">{{ $certificate->certificate_pdf }}</a>
                            @else
                                <span class="text-muted">No certificate PDF uploaded yet</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Review By</th><td>{{ $certificate->review_by ?: 'N/A' }}</td></tr>
                    <tr>
                        <th>Reviewed on</th>
                        <td>{{ $certificate->reviewed_at ? $certificate->reviewed_at->format('d M Y \a\t H:i:s') : 'Not yet reviewed' }}</td>
                    </tr>
                    <tr><th>Approval By</th><td>{{ $certificate->approval_by ?: 'N/A' }}</td></tr>
                    <tr>
                        <th>Approved on</th>
                        <td>{{ $certificate->approved_at ? $certificate->approved_at->format('d M Y \a\t H:i:s') : 'Not yet approved' }}</td>
                    </tr>
                    <tr>
                        <th>QR Code</th>
                        <td>
                            <img width="120" height="120" alt="QR code"
                                src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/').'?search='.$certificate->certificate_number) }}">
                        </td>
                    </tr>
                    <tr><th>Created By</th><td>{{ $certificate->created_by }}</td></tr>
                    <tr><th>Created On</th><td>{{ $certificate->created_at ? $certificate->created_at->format('d M Y \a\t H:i:s') : 'N/A' }}</td></tr>
                    <tr><th>Last Updated By</th><td>{{ $certificate->updated_by ?: 'N/A' }}</td></tr>
                    <tr><th>Updated On</th><td>{{ $certificate->updated_at ? $certificate->updated_at->format('d M Y \a\t H:i:s') : 'N/A' }}</td></tr>
                    <tr><th>Deleted by</th><td>{{ $certificate->status === 'Deleted' ? $certificate->deleted_by : 'N/A' }}</td></tr>
                    <tr>
                        <th>Deleted on</th>
                        <td>
                            @if ($certificate->deleted_by && $certificate->deleted_at)
                                {{ $certificate->deleted_at->format('d M Y \a\t H:i:s') }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($certificate->certificate_pdf)
            <div class="mt-4">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('certificate.viewPdf', $certificate->id) }}" target="_blank">
                    <i class="fa-solid fa-file-pdf me-1"></i> View PDF inline
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
