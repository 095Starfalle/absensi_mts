<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f7f8fa; }
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 70px;
            background: #fff;
            border-right: 1px solid #eee;
            z-index: 1000;
            padding-top: 60px;
        }
        .sidebar .nav-link {
            color: #4f5d73;
            margin: 20px 0;
            text-align: center;
            font-size: 1.5rem;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            color: #4e73df;
            background: #f1f3fa;
            border-radius: 10px;
        }
        .main-content {
            margin-left: 90px;
            padding: 30px 40px;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .card-stat {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            text-align: center;
            padding: 25px 10px;
            margin-bottom: 20px;
        }
        .card-stat .icon {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        .chart-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding: 30px;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 15px; }
            .sidebar { display: none; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column align-items-center py-3">
        <a href="#" class="nav-link active"><i class="fas fa-home"></i></a>
        <a href="#" class="nav-link"><i class="fas fa-user"></i></a>
        <a href="#" class="nav-link"><i class="fas fa-users"></i></a>
        <a href="#" class="nav-link"><i class="fas fa-chart-bar"></i></a>
        <a href="#" class="nav-link"><i class="fas fa-cog"></i></a>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-0">DASHBOARD FINGER</h3>
            </div>
            <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name=Ruan+Mei&background=4e73df&color=fff" class="rounded-circle me-2" width="40" height="40" alt="User">
                <span class="fw-semibold">Ruan Mei</span>
            </div>
        </div>
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Dasboard Statistik</a>
            </li>
        </ul>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-stat">
                    <div class="icon text-primary"><i class="fas fa-user"></i></div>
                    <div class="fs-3 fw-bold"><?= $jumlah_admin ?></div>
                    <div class="text-muted">Administrator</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-stat">
                    <div class="icon text-danger"><i class="fas fa-user-tie"></i></div>
                    <div class="fs-3 fw-bold"><?= $jumlah_wali_kelas ?></div>
                    <div class="text-muted">Wali Kelas</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-stat">
                    <div class="icon text-success"><i class="fas fa-users"></i></div>
                    <div class="fs-3 fw-bold"><?= $jumlah_siswa ?></div>
                    <div class="text-muted">Siswa</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-stat">
                    <div class="icon text-pink"><i class="fas fa-calendar-check"></i></div>
                    <div class="fs-3 fw-bold"><?= $jumlah_siswa_hadir ?></div>
                    <div class="text-muted">Siswa Hadir</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-stat">
                    <div class="icon text-purple"><i class="fas fa-hourglass-half"></i></div>
                    <div class="fs-3 fw-bold"><?= $jumlah_siswa_terlambat ?></div>
                    <div class="text-muted">Siswa Terlambat</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-stat">
                    <div class="icon text-orange"><i class="fas fa-user-clock"></i></div>
                    <div class="fs-3 fw-bold"><?= $jumlah_siswa_belum_absen ?></div>
                    <div class="text-muted">Belum Absensi</div>
                </div>
            </div>
        </div>
        <div class="chart-container mt-4">
            <h5 class="mb-4 fw-bold">Siswa Hadir Tepat Waktu</h5>
            <canvas id="attendanceChart" height="90"></canvas>
        </div>
    </div>
    <script>
        // Data untuk grafik dari PHP
        var labels = <?= json_encode($grafik_tanggal) ?>;
        var dataHadir = <?= json_encode($grafik_hadir) ?>;
        var ctx = document.getElementById('attendanceChart').getContext('2d');
        var attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Siswa Hadir',
                    data: dataHadir,
                    borderColor: 'rgba(78, 115, 223, 1)',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Siswa';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Siswa Hadir' }
                    },
                    x: {
                        title: { display: true, text: 'Tanggal' }
                    }
                }
            }
        });
    </script>
</body>
</html>
