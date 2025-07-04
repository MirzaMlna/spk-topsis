@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="w-full px-3">

            <div class="bg-white shadow rounded-xl p-4 mb-6 flex gap-2">
                <form action="{{ 'hitung_topsis' }}" method="post">
                    @csrf
                    <button type="submit" class="btn bg-green-600 text-white hover:bg-green-500">Hitung TOPSIS</button>
                </form>
                <form action="{{ 'pdf_topsis' }}" method="post" target="_blank">
                    @csrf
                    <button type="submit" class="btn bg-red-600 text-white hover:bg-red-500">
                        <i class="ri-file-pdf-line mr-1"></i> Export PDF
                    </button>
                </form>
            </div>

            @php
                $tables = [
                    [
                        'id' => 'tabel_data_bobot',
                        'title' => 'Bobot Kriteria (W)',
                        'headers' => $kriteria->pluck('nama'),
                        'rows' => [$kriteria->pluck('bobot')],
                    ],
                    [
                        'id' => 'tabel_data_penilaian',
                        'title' => 'Penilaian',
                        'headers' => $penilaian->unique('kriteria_id')->pluck('kriteria.nama')->prepend('Nama'),
                        'rows' => $penilaian->unique('alternatif_id')->map(function ($item) use ($penilaian) {
                            return array_merge(
                                [$item->alternatif->objek->nama],
                                $penilaian
                                    ->where('alternatif_id', $item->alternatif_id)
                                    ->map(function ($val) {
                                        return optional($val->subKriteria)->nilai;
                                    })
                                    ->toArray(),
                            );
                        }),
                    ],
                    [
                        'id' => 'tabel_data_matriks_keputusan',
                        'title' => 'Matriks Keputusan (X)',
                        'headers' => $matriksKeputusan->pluck('nama_kriteria'),
                        'rows' => [$matriksKeputusan->pluck('nilai')->map(fn($n) => round($n, 2))],
                    ],
                    [
                        'id' => 'tabel_data_matriks_normalisasi',
                        'title' => 'Matriks Normalisasi (R)',
                        'headers' => $matriksNormalisasi
                            ->unique('kriteria_id')
                            ->pluck('nama_kriteria')
                            ->prepend('Nama'),
                        'rows' => $matriksNormalisasi
                            ->unique('alternatif_id')
                            ->map(function ($item) use ($matriksNormalisasi) {
                                return array_merge(
                                    [$item->nama_objek],
                                    $matriksNormalisasi
                                        ->where('alternatif_id', $item->alternatif_id)
                                        ->pluck('nilai')
                                        ->map(fn($v) => round($v, 2))
                                        ->toArray(),
                                );
                            }),
                    ],
                    [
                        'id' => 'tabel_data_matriks_y',
                        'title' => 'Matriks Y',
                        'headers' => $matriksY->unique('kriteria_id')->pluck('nama_kriteria')->prepend('Nama'),
                        'rows' => $matriksY->unique('alternatif_id')->map(function ($item) use ($matriksY) {
                            return array_merge(
                                [$item->nama_objek],
                                $matriksY
                                    ->where('alternatif_id', $item->alternatif_id)
                                    ->pluck('nilai')
                                    ->map(fn($v) => round($v, 3))
                                    ->toArray(),
                            );
                        }),
                    ],
                    [
                        'id' => 'tabel_data_ideal_positif',
                        'title' => 'Ideal Positif (A⁺)',
                        'headers' => $idealPositif->unique('kriteria_id')->pluck('nama_kriteria')->prepend('Nama'),
                        'rows' => $idealPositif->unique('alternatif_id')->map(function ($item) use ($idealPositif) {
                            return array_merge(
                                [$item->nama_objek],
                                $idealPositif
                                    ->where('alternatif_id', $item->alternatif_id)
                                    ->pluck('nilai')
                                    ->map(fn($v) => number_format($v, 6))
                                    ->toArray(),
                            );
                        }),
                    ],
                    [
                        'id' => 'tabel_data_ideal_negatif',
                        'title' => 'Ideal Negatif (A⁻)',
                        'headers' => $idealNegatif->unique('kriteria_id')->pluck('nama_kriteria')->prepend('Nama'),
                        'rows' => $idealNegatif->unique('alternatif_id')->map(function ($item) use ($idealNegatif) {
                            return array_merge(
                                [$item->nama_objek],
                                $idealNegatif
                                    ->where('alternatif_id', $item->alternatif_id)
                                    ->pluck('nilai')
                                    ->map(fn($v) => number_format($v, 6))
                                    ->toArray(),
                            );
                        }),
                    ],
                    [
                        'id' => 'tabel_data_solusi_ideal_positif',
                        'title' => 'Solusi Ideal Positif (Si⁺)',
                        'headers' => ['Nama', 'Nilai'],
                        'rows' => $solusiIdealPositif->map(fn($x) => [$x->nama_objek, round($x->nilai, 3)]),
                    ],
                    [
                        'id' => 'tabel_data_solusi_ideal_negatif',
                        'title' => 'Solusi Ideal Negatif (Si⁻)',
                        'headers' => ['Nama', 'Nilai'],
                        'rows' => $solusiIdealNegatif->map(fn($x) => [$x->nama_objek, round($x->nilai, 3)]),
                    ],
                    [
                        'id' => 'tabel_data_hasil',
                        'title' => 'Kedekatan Relatif terhadap Solusi Ideal (Ci)',
                        'headers' => ['Nama', 'Nilai'],
                        'rows' => $hasilTopsis->map(fn($x) => [$x->nama_objek, round($x->nilai, 3)]),
                    ],
                ];
            @endphp

            @foreach ($tables as $table)
                <div class="bg-white shadow rounded-xl p-4 mb-6">
                    <h6 class="text-md font-semibold mb-2">{{ $table['title'] }}</h6>
                    <div class="overflow-auto">
                        <table class="min-w-full text-sm border">
                            <thead class="bg-gray-100">
                                <tr>
                                    @foreach ($table['headers'] as $header)
                                        <th class="text-left py-2 px-3 border-b">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($table['rows'] as $row)
                                    <tr class="border-t">
                                        @foreach ($row as $val)
                                            <td class="py-2 px-3">{{ $val }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
