@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <!--li class="breadcrumb-item active">Dashboard</li-->
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <div class="text-center">
                                    <h5>Welcome to the "Dashboard Quality Control"</h5>
                                    <p class="text-muted">Here you can Manage Quality Control on the system PT Olefina Tifaplas Polikemindo</p>

                                    <div style="width: 70%; height: 500px; margin: auto;"> <canvas id="myChart" height="400"></canvas>
                                        </div>
                                    
                                        <script>
                                            const ctx = document.getElementById('myChart').getContext('2d');
                                            const chartData = @json($chartData);
                                            const typeStocks = @json($typeStocks);
                                    
                                            const colors = {
                                                FG: 'rgba(255, 99, 132, 0.2)',
                                                WIP: 'rgba(54, 162, 235, 0.2)',
                                                RM: 'rgba(255, 206, 86, 0.2)',
                                            };
                                    
                                            const borderColors = {
                                                FG: 'rgba(255, 99, 132, 1)',
                                                WIP: 'rgba(54, 162, 235, 1)',
                                                RM: 'rgba(255, 206, 86, 1)',
                                            };
                                    
                                            const datasets = Object.keys(chartData).map(product => ({
                                                label: product,
                                                data: typeStocks.map(stock => chartData[product][stock]),
                                                backgroundColor: colors[product],
                                                borderColor: borderColors[product],
                                                borderWidth: 1
                                            }));
                                    
                                            const myChart = new Chart(ctx, {
                                                type: 'bar',
                                                data: {
                                                    labels: typeStocks,
                                                    datasets: datasets
                                                },
                                                options: {
                                                    plugins: {
                                                        legend: {
                                                            display: true,
                                                            position: 'top',
                                                            labels: {
                                                                font: {
                                                                    size: 14
                                                                }
                                                            }
                                                        },
                                                        tooltip: {
                                                            enabled: true,
                                                            backgroundColor: 'rgba(0,0,0,0.7)',
                                                            titleFont: {
                                                                size: 16
                                                            },
                                                            bodyFont: {
                                                                size: 14
                                                            }
                                                        }
                                                    },
                                                    scales: {
                                                        y: {
                                                            beginAtZero: true,
                                                            ticks: {
                                                                font: {
                                                                    size: 14
                                                                }
                                                            },
                                                            title: {
                                                                display: true,
                                                                text: 'Count',
                                                                font: {
                                                                    size: 16
                                                                }
                                                            }
                                                        },
                                                        x: {
                                                            ticks: {
                                                                font: {
                                                                    size: 14
                                                                }
                                                            },
                                                            title: {
                                                                display: true,
                                                                text: 'Type Stock',
                                                                font: {
                                                                    size: 16
                                                                }
                                                            }
                                                        }
                                                    },
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    layout: {
                                                        padding: 20
                                                    }
                                                }
                                            });
                                        </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection