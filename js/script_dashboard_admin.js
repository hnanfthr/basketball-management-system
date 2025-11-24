// js/script_dashboard_admin.js

document.addEventListener('DOMContentLoaded', async function() {

    // Elemen Statistik
    const statTotalAnggota = document.getElementById('stat-total-anggota');
    const statTotalPending = document.getElementById('stat-total-pending');

    // Elemen Canvas Chart
    const ctxAbsensi = document.getElementById('chartAbsensi');
    const ctxPendaftar = document.getElementById('chartPendaftar');

    try {
        // Panggil API untuk ambil data
        const response = await fetch('../api/get_dashboard_stats.php');
        const result = await response.json();

        if (!result.success) {
            throw new Error(result.message);
        }

        const stats = result.data;

        // 1. Isi Kartu Statistik
        statTotalAnggota.textContent = stats.total_anggota;
        statTotalPending.textContent = stats.total_pending;

        // 2. Buat Grafik Absensi (Pie Chart)
        const dataAbsensi = stats.rekap_absensi_30hari;
        new Chart(ctxAbsensi, {
            type: 'pie',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alfa'],
                datasets: [{
                    label: 'Rekap Absensi',
                    data: [
                        dataAbsensi.Hadir,
                        dataAbsensi.Izin,
                        dataAbsensi.Sakit,
                        dataAbsensi.Alfa
                    ],
                    backgroundColor: [
                        'hsl(145, 63%, 40%)', // success-green
                        'hsl(45, 100%, 51%)', // kuning
                        'hsl(215, 70%, 55%)', // primary-blue
                        'hsl(354, 70%, 54%)'  // danger-red
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // 3. Buat Grafik Pendaftar (Bar Chart)
        const dataPendaftar = stats.pendaftar_6bulan;
        new Chart(ctxPendaftar, {
            type: 'bar',
            data: {
                labels: dataPendaftar.labels,
                datasets: [{
                    label: 'Jumlah Pendaftar Baru',
                    data: dataPendaftar.data,
                    backgroundColor: 'hsla(215, 70%, 55%, 0.7)', // primary-blue transparan
                    borderColor: 'hsl(215, 70%, 55%)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Pastikan hanya angka bulat (integer) di sumbu Y
                            stepSize: 1,
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda
                    }
                }
            }
        });


    } catch (error) {
        console.error('Gagal memuat dashboard:', error);
        // Tampilkan pesan error jika gagal
        document.querySelector('main').innerHTML = `<p style="color: red; text-align: center;">Gagal memuat statistik dashboard: ${error.message}</p>`;
    }
});