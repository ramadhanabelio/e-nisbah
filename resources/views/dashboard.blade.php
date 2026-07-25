@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">E-Nisbah - PT Bank Riau Kepri Syariah (Perseroda)</h6>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <div class="icon-big text-center">
                                    <i class="fas fa-envelope fa-2x"></i>
                                </div>
                            </div>
                            <div class="col-8 col-stats px-0">
                                <div class="numbers">
                                    <p class="card-category text-white-50">Total Semua Surat</p>
                                    <h4 class="card-title text-white">{{ $totalSemuaSurat }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round bg-info text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <div class="icon-big text-center">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                            </div>
                            <div class="col-8 col-stats px-0">
                                <div class="numbers">
                                    <p class="card-category text-white-50">Surat Bulan Ini</p>
                                    <h4 class="card-title text-white">{{ $suratBulanIni }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round bg-warning text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <div class="icon-big text-center">
                                    <i class="fas fa-hourglass-half fa-2x"></i>
                                </div>
                            </div>
                            <div class="col-8 col-stats px-0">
                                <div class="numbers">
                                    <p class="card-category text-white-50">Menunggu Approval</p>
                                    <h4 class="card-title text-white">{{ $totalMenungguApproval }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @auth
            @if (auth()->user()->role === 'cs')
                <div class="row mb-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-circle-notch text-info"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Draft</p>
                                            <h4 class="card-title">{{ $totalDrafts }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-clock text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Proses</p>
                                            <h4 class="card-title">{{ $totalProcesses }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-sync-alt text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Revisi</p>
                                            <h4 class="card-title">{{ $totalRevisions }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Selesai</p>
                                            <h4 class="card-title">{{ $totalCompleted }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        <div class="row">
            <div class="col-md-6">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">Proporsi Status Surat</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="min-height: 300px;">
                            <canvas id="suratStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">Timeline Progress Approval Terbaru</div>
                    </div>
                    <div class="card-body">
                        @if ($timelineApprovals->isEmpty())
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <p>Belum ada aktivitas approval surat.</p>
                            </div>
                        @else
                            <ol class="activity-feed"
                                style="list-style: none; padding-left: 15px; border-left: 2px solid #e8e8e8;">
                                @foreach ($timelineApprovals as $approval)
                                    <li class="feed-item pb-3" style="position: relative; padding-left: 20px;">
                                        <span
                                            style="position: absolute; left: -7px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background-color: 
                                            {{ $approval->status === 'approved' ? '#28a745' : ($approval->status === 'rejected' ? '#dc3545' : '#ffc107') }};">
                                        </span>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="font-weight-bold text-dark">
                                                <strong>{{ $approval->surat->nomor_surat ?? 'Surat Tanpa Nomor' }}</strong>
                                            </span>
                                            <small class="text-muted">{{ $approval->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="text-muted d-block small">
                                            {{ \Str::limit(strtoupper($approval->role), 40) }}
                                        </span>
                                        <span class="d-block mt-1">
                                            Oleh: <strong>{{ $approval->user->name ?? 'Sistem' }}</strong>
                                            <span
                                                class="badge 
                                                {{ $approval->status === 'approved' ? 'badge-success' : ($approval->status === 'rejected' ? 'badge-danger' : 'badge-warning') }} mb-1">
                                                {{ ucfirst($approval->status) }}
                                            </span>
                                        </span>
                                        @if ($approval->catatan)
                                            <div class="mt-1 p-2 bg-light rounded text-muted small italic">
                                                <i class="fas fa-comment-dots mr-1"></i> "{{ $approval->catatan }}"
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('suratStatusChart').getContext('2d');
            var suratStatusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Draft', 'Proses', 'Revisi', 'Selesai'],
                    datasets: [{
                        data: [
                            {{ $totalDrafts }},
                            {{ $totalProcesses }},
                            {{ $totalRevisions }},
                            {{ $totalCompleted }}
                        ],
                        backgroundColor: ['#17a2b8', '#ffc107', '#dc3545', '#28a745'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    },
                    layout: {
                        padding: {
                            top: 20
                        }
                    }
                }
            });
        });
    </script>
@endpush
