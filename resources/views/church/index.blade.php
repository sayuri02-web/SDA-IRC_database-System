@extends('layout')

@section('content')

<div class="col-lg-12 grid-margin stretch-card church-index-wrapper">
    <div class="card shadow-sm border-0 church-index-card">
        <div class="card-body p-4 church-index-body">

            {{-- HEADER: title + Add button only --}}
            <div class="church-page-header mb-3">
                <div class="church-page-header-left">
                    <div class="church-page-icon">
                        <i class="mdi mdi-home"></i>
                    </div>
                    <div>
                        <h3 class="church-page-title mb-0">Church Registration</h3>
                        <p class="church-page-subtitle mb-0">Manage registered churches and clusters</p>
                    </div>
                </div>
                <a href="{{ url('/church/create') }}" class="btn btn-outline-success btn-sm">
                    <i class="mdi mdi-plus me-1"></i> Add New
                </a>
            </div>

            {{-- STATS ROW — Bootstrap 5 d-flex, stats left / controls right --}}
            <div class="d-flex align-items-center justify-content-between church-stats-row mb-3 px-3 pe-3">

                {{-- LEFT: counts --}}
                <div class="d-flex align-items-center gap-2">
                    <div class="church-stat-item">
                        <span class="church-stat-num">{{ $churches->count() }}</span>
                        <span class="church-stat-label">Total</span>
                    </div>
                    <div class="church-stat-divider mx-1"></div>
                    @foreach(['Cluster 1','Cluster 2','Cluster 3','Cluster 4'] as $cl)
                    <div class="church-stat-item">
                        <span class="church-stat-num">{{ $churches->where('cluster', $cl)->count() }}</span>
                        <span class="church-stat-label">{{ $cl }}</span>
                    </div>
                    @if(!$loop->last)<div class="church-stat-divider mx-1"></div>@endif
                    @endforeach
                </div>

                {{-- RIGHT: search + dropdown (both fixed-width via CSS flex shorthand) --}}
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <div class="church-search-wrap">
                        <i class="mdi mdi-magnify church-search-icon"></i>
                        <input type="text"
                               id="searchInput"
                               class="form-control church-search-input"
                               placeholder="Search church..."
                               value="{{ request('search') }}">
                    </div>
                    <select id="clusterFilter" class="form-select church-cluster-filter">
                        <option value="">All Clusters</option>
                        <option value="Cluster 1">Cluster 1</option>
                        <option value="Cluster 2">Cluster 2</option>
                        <option value="Cluster 3">Cluster 3</option>
                        <option value="Cluster 4">Cluster 4</option>
                    </select>
                </div>

            </div>
            <hr class="mb-4">

            {{-- CHURCH CARDS — this is the ONLY scrollable area --}}
            <div class="church-cards-scroll">
                <div class="row g-3" id="churchContainer">

                    @forelse($churches as $church)
                    @php
                        $clusterClass = match($church->cluster) {
                            'Cluster 1' => 'cluster-one',
                            'Cluster 2' => 'cluster-two',
                            'Cluster 3' => 'cluster-three',
                            'Cluster 4' => 'cluster-four',
                            default     => 'cluster-default'
                        };
                        $clusterColor = match($church->cluster) {
                            'Cluster 1' => '#2457ff',
                            'Cluster 2' => '#11a75c',
                            'Cluster 3' => '#ff8a00',
                            'Cluster 4' => '#8e3dff',
                            default     => '#6c757d'
                        };
                    @endphp

                    <div class="col-xl-3 col-md-4 col-sm-6 church-item"
                         data-name="{{ strtolower($church->church_name) }}"
                         data-cluster="{{ $church->cluster }}">

                         <div class="church-card h-100"
                                style="--cluster-color: {{ $clusterColor }};"
                                    data-aos="fade-up"
                                    data-aos-delay="{{ ($loop->index % 4) * 100 }}"
                                    data-aos-duration="700">
                            <div class="church-card-accent" style="background: {{ $clusterColor }};"></div>

                            <div class="church-card-body">
                                {{-- AVATAR --}}
                                <div class="church-avatar" style="background: {{ $clusterColor }}1a; color: {{ $clusterColor }};">
                                    <i class="mdi mdi-home"></i>
                                </div>

                                {{-- NAME --}}
                                <h5 class="church-card-name">{{ $church->church_name }}</h5>

                                {{-- ADDRESS --}}
                                <p class="church-card-address">
                                    <i class="mdi mdi-map-marker-outline me-1"></i>
                                    {{ $church->street }}, {{ $church->barangay }}, {{ $church->city }}
                                </p>

                                {{-- CLUSTER BADGE --}}
                                <div class="mb-3 text-center">
                                    <span class="cluster-pill {{ $clusterClass }}">
                                        <span class="cluster-dot"></span>{{ $church->cluster }}
                                    </span>
                                </div>

                                {{-- MEMBER COUNT --}}
                                <div class="church-member-count">
                                    <i class="mdi mdi-account-group-outline me-1"></i>
                                    <strong>{{ $church->members_count ?? 0 }}</strong>
                                    <span>Members</span>
                                </div>

                                <div class="church-card-divider"></div>

                                {{-- ACTIONS --}}
                                <div class="church-card-actions">
                                    <a href="{{ url('/church/' . $church->id . '/edit') }}"
                                       class="church-action-btn church-edit-btn">
                                        <i class="mdi mdi-pencil-outline me-1"></i> Edit
                                    </a>
                                    <form action="{{ url('/church/' . $church->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="church-action-btn church-delete-btn"
                                                data-delete-confirm
                                                data-delete-title="Delete Church"
                                                data-delete-msg="Are you sure you want to delete this church? This action cannot be undone.">
                                            <i class="mdi mdi-trash-can-outline me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    @empty

                    <div class="col-12">
                        <div class="church-empty-state">
                            <div class="church-empty-icon">
                                <i class="mdi mdi-home"></i>
                            </div>
                            <h4 class="church-empty-title">No Churches Registered</h4>
                            <p class="church-empty-text">Start by adding your first church to the system.</p>
                            <a href="{{ url('/church/create') }}" class="btn btn-outline-success btn-sm">
                                <i class="mdi mdi-plus me-1"></i> Add New
                            </a>
                        </div>
                    </div>

                    @endforelse

                </div>{{-- end #churchContainer --}}

                {{-- NO RESULTS (shown via JS) --}}
                <div id="noResults" class="church-empty-state" style="display:none;">
                    <div class="church-empty-icon">
                        <i class="mdi mdi-magnify"></i>
                    </div>
                    <h4 class="church-empty-title">No Results Found</h4>
                    <p class="church-empty-text">Try a different search term or cluster filter.</p>
                </div>

            </div>{{-- end .church-cards-scroll --}}

        </div>{{-- end .church-index-body --}}
    </div>{{-- end .card --}}
</div>{{-- end .col-lg-12 --}}

@push('scripts')
<script>
    const searchInput   = document.getElementById('searchInput');
    const clusterFilter = document.getElementById('clusterFilter');
    const noResults     = document.getElementById('noResults');

    function filterChurches() {
        const term    = searchInput.value.toLowerCase().trim();
        const cluster = clusterFilter.value;
        const items   = document.querySelectorAll('.church-item');
        let visible   = 0;

        items.forEach(item => {
            const nameMatch    = item.dataset.name.includes(term);
            const clusterMatch = !cluster || item.dataset.cluster === cluster;
            const show         = nameMatch && clusterMatch;
            item.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        noResults.style.display = visible === 0 ? 'flex' : 'none';
    }

    searchInput.addEventListener('input', filterChurches);
    clusterFilter.addEventListener('change', filterChurches);
</script>
@endpush

@endsection
