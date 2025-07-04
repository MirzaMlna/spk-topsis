@extends('dashboard.layouts.app')

@section('container')
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        @foreach ([['label' => 'Kriteria', 'value' => $jml_kriteria, 'icon' => 'ri-table-fill'], ['label' => 'Sub Kriteria', 'value' => $subKriteria, 'icon' => 'ri-collage-fill'], ['label' => 'Objek', 'value' => $objek, 'icon' => 'ri-brackets-fill'], ['label' => 'Alternatif', 'value' => $alternatif, 'icon' => 'ri-braces-fill']] as $card)
            <div
                class="bg-gradient-to-br from-green-900 to-green-700 shadow-lg rounded-2xl p-6 flex items-center justify-between hover:scale-105 transition-transform">
                <div>
                    <p class="text-sm font-semibold text-green-100">{{ $card['label'] }}</p>
                    <h5 class="text-3xl font-extrabold text-white mt-2">{{ $card['value'] }}</h5>
                </div>
                <i class="{{ $card['icon'] }} text-4xl text-green-200 bg-green-800 rounded-full p-3 shadow"></i>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <div class="bg-white shadow-lg rounded-2xl p-8 flex flex-col justify-between">
            <h5 class="text-xl font-bold mb-4 text-green-900">Sistem Pendukung Keputusan - TOPSIS</h5>
            <p class="text-base text-gray-700 mb-6 text-justify">
                Topsis adalah metode pengambilan keputusan multi kriteria dengan dasar alternatif yang dipilih memiliki
                jarak terdekat dengan solusi ideal positif dan memiliki jarak terjauh dari solusi ideal negatif.
            </p>
            <a href="{{ route('hitung_topsis') }}"
                class="inline-block px-5 py-2 bg-green-900 text-white font-semibold rounded-lg shadow hover:bg-green-800 transition">Mulai
                Perhitungan</a>
        </div>

        <div class="bg-white shadow-lg rounded-2xl p-8">
            <h5 class="text-xl font-bold mb-4 text-green-900">Kegunaan TOPSIS</h5>
            <ul class="list-disc list-inside text-base text-gray-700 space-y-2">
                <li>Konsepnya sederhana dan mudah dipahami.</li>
                <li>Komputasi efisien dan cepat.</li>
                <li>Dapat mengukur kinerja relatif alternatif keputusan secara sederhana.</li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <div class="bg-white shadow-lg rounded-2xl p-8">
            <h6 class="text-lg font-bold mb-6 text-green-900">Kriteria dan Bobot</h6>
            <canvas id="chart-bars" height="200"></canvas>
        </div>
        <div class="bg-white shadow-lg rounded-2xl p-8">
            <h6 class="text-lg font-bold mb-6 text-green-900">Hasil Perhitungan TOPSIS</h6>
            <canvas id="chart-line" height="200"></canvas>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const kriteriaId = @json($kriteria->pluck('id'));
        const kriteriaBobot = @json($kriteria->pluck('bobot'));
        const alternatif = @json($hasilTopsis->pluck('nama_objek'));
        const nilai = @json($hasilTopsis->pluck('nilai'));

        new Chart(document.getElementById("chart-bars"), {
            type: "bar",
            data: {
                labels: kriteriaId,
                datasets: [{
                    label: "Bobot",
                    backgroundColor: "#14532d",
                    data: kriteriaBobot,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        ticks: {
                            color: "#333"
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById("chart-line"), {
            type: "line",
            data: {
                labels: alternatif,
                datasets: [{
                    label: "Nilai",
                    borderColor: "#14532d",
                    backgroundColor: "#bbf7d0",
                    data: nilai,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        ticks: {
                            color: "#333"
                        }
                    }
                }
            }
        });
    </script>
@endsection
