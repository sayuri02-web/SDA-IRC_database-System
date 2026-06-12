@extends('layout')

@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-sm border-0 cert-index-card">
        <div class="card-body p-4">

            {{-- HEADER --}}
            <div class="cert-page-header mb-4">
                <div class="cert-page-header-left">
                    <div class="cert-page-icon" style="background: linear-gradient(135deg,#2449d8,#5c7cfa);">
                        <i class="mdi mdi-history"></i>
                    </div>
                    <div>
                        <h3 class="cert-page-title mb-0">Certificate Logs</h3>
                        <p class="cert-page-subtitle mb-0">History of all printed certificates</p>
                    </div>
                </div>
                <div class="cert-log-count-badge">
                    <i class="mdi mdi-file-document-multiple-outline me-1"></i>
                    {{ $logs->count() }} Records
                </div>
            </div>

            {{-- TABLE --}}
            <div class="cert-log-table-wrap">
                <table class="cert-log-table">
                    <thead>
                        <tr>
                            <th><i class="mdi mdi-pound me-1"></i>Certificate #</th>
                            <th><i class="mdi mdi-account-outline me-1"></i>Member</th>
                            <th><i class="mdi mdi-certificate-outline me-1"></i>Type</th>
                            <th><i class="mdi mdi-account-badge-outline me-1"></i>Printed By</th>
                            <th><i class="mdi mdi-calendar-clock me-1"></i>Date Printed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr class="cert-log-row">
                            <td>
                                <span class="cert-log-num">#{{ $log->certificate_number }}</span>
                            </td>
                            <td>
                                <div class="cert-log-member">
                                    <div class="cert-log-avatar">
                                        {{ strtoupper(substr($log->member->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <span>{{ $log->member->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="cert-log-type-badge">{{ $log->certificate_type }}</span>
                            </td>
                            <td class="cert-log-text">{{ $log->printed_by }}</td>
                            <td class="cert-log-text">
                                @if($log->printed_at)
                                    {{ \Carbon\Carbon::parse($log->printed_at)->format('M d, Y h:i A') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a href="/certificate-logs/{{ $log->id }}/pdf"
                                   class="cert-log-pdf-btn">
                                    <i class="mdi mdi-file-pdf-box me-1"></i> PDF
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="cert-empty-state py-5">
                                    <div class="cert-empty-icon">
                                        <i class="mdi mdi-history"></i>
                                    </div>
                                    <h4 class="cert-empty-title">No Certificate Logs</h4>
                                    <p class="cert-empty-text">Certificate printing history will appear here.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection
