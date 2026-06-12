@extends('layout')

@section('content')

<div class="col-lg-12 grid-margin stretch-card cert-index-wrapper">
    <div class="card shadow-sm border-0 cert-index-card">
        <div class="card-body p-4 cert-index-body">

            {{-- HEADER --}}
            <div class="cert-page-header mb-3">
                <div class="cert-page-header-left">
                    <div class="cert-page-icon">
                        <i class="mdi mdi-certificate-outline"></i>
                    </div>
                    <div>
                        <h3 class="cert-page-title mb-0">Certificate Templates</h3>
                        <p class="cert-page-subtitle mb-0">Print and manage church certifications</p>
                    </div>
                </div>
                <a href="{{ route('certificates.create') }}" class="btn btn-outline-success btn-sm">
                    <i class="mdi mdi-plus me-1"></i> Add New
                </a>
            </div>

            <hr class="mb-3" style="flex-shrink:0;">

            {{-- CARDS GRID — only this scrolls --}}
            <div class="cert-cards-scroll">
                <div class="row g-3">

                    @forelse($certificates as $item)

                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="cert-card h-100"
                             data-aos="fade-up"
                             data-aos-delay="{{ ($loop->index % 4) * 100 }}"
                             data-aos-duration="700">
                            <div class="cert-card-accent"></div>

                            <div class="cert-card-body">
                                {{-- LOGO --}}
                                <div class="cert-avatar">
                                    <img src="{{ asset('assets/images/angels.jpg') }}"
                                         alt="Logo"
                                         class="cert-avatar-img">
                                </div>

                                {{-- NAME --}}
                                <h5 class="cert-card-name">{{ $item->certificate_name }}</h5>
                                <p class="cert-card-desc">Official church certificate template</p>

                                {{-- DIVIDER --}}
                                <div class="cert-card-divider"></div>

                                {{-- ACTIONS --}}
                                <div class="cert-card-actions">
                                    <button type="button"
                                            class="cert-action-btn cert-print-btn print-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#searchMemberModal"
                                            data-certificate="{{ $item->certificate_name }}">
                                        <i class="mdi mdi-printer-outline me-1"></i> Print
                                    </button>

                                    <a href="{{ route('certificates.edit', $item->id) }}"
                                       class="cert-action-btn cert-edit-btn">
                                        <i class="mdi mdi-pencil-outline me-1"></i> Edit
                                    </a>

                                    <form action="{{ route('certificates.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="cert-action-btn cert-delete-btn"
                                                onclick="return confirm('Delete this certificate template?')">
                                            <i class="mdi mdi-trash-can-outline me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty

                    <div class="col-12">
                        <div class="cert-empty-state">
                            <div class="cert-empty-icon">
                                <i class="mdi mdi-certificate-outline"></i>
                            </div>
                            <h4 class="cert-empty-title">No Certificate Templates</h4>
                            <p class="cert-empty-text">Create your first certificate template to get started.</p>
                            <a href="{{ route('certificates.create') }}" class="btn btn-outline-success btn-sm">
                                <i class="mdi mdi-plus me-1"></i> Add New
                            </a>
                        </div>
                    </div>

                    @endforelse

                </div>{{-- end row --}}
            </div>{{-- end cert-cards-scroll --}}

        </div>{{-- end cert-index-body --}}
    </div>{{-- end cert-index-card --}}
</div>{{-- end cert-index-wrapper --}}

{{-- SEARCH MEMBER MODAL --}}
<div class="modal fade" id="searchMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content cert-modal">

            <div class="cert-modal-header-bar"></div>

            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="cert-modal-header-icon">
                        <i class="mdi mdi-certificate-outline"></i>
                    </div>
                    <div>
                        <h5 class="cert-modal-title mb-0">Print Certificate</h5>
                        <p class="cert-modal-subtitle mb-0">Search a member to generate their certificate</p>
                    </div>
                </div>
                <button type="button" class="btn-close cert-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 pb-4 pt-3">
                <div class="cert-modal-search-wrap mb-4">
                    <i class="mdi mdi-magnify cert-modal-search-icon"></i>
                    <input type="text"
                           id="memberSearch"
                           class="form-control cert-modal-search"
                           placeholder="Type member name to search...">
                </div>
                <div id="searchResults"></div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    window.baptismSearchRoute = "{{ route('certificates.baptism.search') }}";
</script>
@endpush

@endsection
