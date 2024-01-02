@extends('layouts.app', [
    'title' => 'Dashboard',
    'breadcrumbs' => [
        ['url' => '/cms/dashboard', 'name' => 'Dashboard'],
        ['url' => '', 'name' => 'Dashboard']
    ]
])

@section('style')
<style>
    .bg-secondary-trans { background-color: rgba(108, 117, 125, 0.15); }
    .bg-warning-trans { background-color: rgba(255, 193, 7, 0.15); }
</style>
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col">
            <div class="card mb-3 border">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-4 d-flex">
                            <div class="m-auto"><i class="bi bi-clock text-secondary h3"></i></div>
                        </div>
                        <div class="col-8 ps-0 d-flex flex-column">
                            <p class="mb-0 mt-auto fs-5 text-muted">Pending</p>
                            <p class="mb-auto text-black font-bold">{{ $payreqStatusCount->pending }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-3 border">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-4 d-flex">
                            <div class="m-auto"><i class="bi bi-chat-left-text text-primary h3"></i></div>
                        </div>
                        <div class="col-8 ps-0 d-flex flex-column">
                            <p class="mb-0 mt-auto fs-5 text-muted">On Review</p>
                            <p class="mb-auto text-black font-bold">{{ $payreqStatusCount->review }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-3 border">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-4 d-flex">
                            <div class="m-auto"><i class="bi bi-arrow-repeat text-warning h3"></i></div>
                        </div>
                        <div class="col-8 ps-0 d-flex flex-column">
                            <p class="mb-0 mt-auto fs-5 text-muted">Returned</p>
                            <p class="mb-auto text-black font-bold">{{ $payreqStatusCount->returned }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-3 border">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-4 d-flex">
                            <div class="m-auto"><i class="bi bi-x-circle text-danger h3"></i></div>
                        </div>
                        <div class="col-8 ps-0 d-flex flex-column">
                            <p class="mb-0 mt-auto fs-5 text-muted">Rejected</p>
                            <p class="mb-auto text-black font-bold">{{ $payreqStatusCount->rejected }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-3 border">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-4 d-flex">
                            <div class="m-auto"><i class="bi bi-check-circle text-success h3"></i></div>
                        </div>
                        <div class="col-8 ps-0 d-flex flex-column">
                            <p class="mb-0 mt-auto fs-5 text-muted">Approved</p>
                            <p class="mb-auto text-black font-bold">{{ $payreqStatusCount->approved }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-9">
            <div class="card mb-3 border h-100">
                <div class="card-header">
                    <h4 class="mb-0 card-title">Invoice in Categories</h4>
                </div>
                <div class="card-body">
                    <canvas id="invInCategoriesChart" style="width: 100%; height: 230px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card mb-3 border">
                <div class="card-header">
                    <h4 class="mb-0 card-title">Current Request</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <!-- <div class="col-3">
                            <div class="h-100 w-100 bg-secondary-trans rounded d-flex">
                                <i class="bi bi-clock text-secondary fs-4 mt-1 mx-2"></i>
                            </div>
                        </div> -->
                        <div class="col-12 d-flex flex-column">
                            <p class="mb-0 mt-auto h6 text-dark">PR2312277991</p>
                            <p class="mb-auto">
                                <i class="bi bi-clock text-secondary mt-1 me-1"></i> Pending
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- <div class="col-3">
                            <div class="h-100 w-100 bg-warning-trans rounded d-flex">
                                <i class="bi bi-arrow-repeat text-warning fs-4 mt-1 mx-2"></i>
                            </div>
                        </div> -->
                        <div class="col-12 d-flex flex-column">
                            <p class="mb-0 mt-auto h6 text-dark">PR2312277157</p>
                            <p class="mb-auto">
                                <i class="bi bi-arrow-repeat text-warning mt-1 me-1"></i> Returned
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-3">
                            <div class="h-100 w-100 bg-warning-trans rounded d-flex">
                                <i class="bi bi-x-circle text-danger fs-4 mt-1 mx-2"></i>
                            </div>
                        </div> -->
                        <div class="col-12 d-flex flex-column">
                            <p class="mb-0 mt-auto h6 text-dark">PR2312274992</p>
                            <p class="mb-auto">
                                <i class="bi bi-x-circle text-danger mt-1 me-1"></i> Rejected
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="{{ asset('extensions/chart.js/chart.umd.js') }}"></script>
<script>
    let invInCategoriesChart = document.getElementById('invInCategoriesChart').getContext('2d')
    new Chart(invInCategoriesChart, {
        type: 'bar',
        data: {
            labels: ['Kesehatan', 'Transportasi', 'Konsumsi', 'Operasional', 'Bahan Bakar', 'Lainnya'],
            datasets: [
                {
                    label: 'Cost Categories',
                    data: [3450000, 1500000, 2450000, 5600000, 1000000, 800000],
                    backgroundColor: ['rgba(255,105,180, 0.5)'],
                    borderColor: ['rgba(255,105,180, 0.2)'],
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
        }
    })
</script>
@endsection