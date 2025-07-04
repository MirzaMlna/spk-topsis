@extends('dashboard.layouts.app')

@section('container')
    <div class="px-4 py-6">
        <div class="bg-white shadow rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h6 class="text-lg font-semibold text-gray-800">Tabel {{ $judul }}</h6>
            </div>

            <div class="p-6 overflow-x-auto">
                <table id="tabel_data" class="stripe hover w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 font-medium text-gray-600">Nama</th>
                            @foreach ($data->unique('kriteria_id') as $item)
                                <th class="py-2 px-4 font-medium text-gray-600">{{ $item->kriteria->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->unique('alternatif_id') as $item)
                            <tr class="border-t">
                                <td class="py-2 px-4 font-medium text-gray-800 flex items-center gap-2">
                                    {{ $item->alternatif->objek->nama }}
                                    <a href="{{ route('penilaian.ubah', $item->alternatif_id) }}"
                                        class="text-yellow-500 hover:text-yellow-600">
                                        <i class="ri-pencil-fill text-lg"></i>
                                    </a>
                                </td>
                                @foreach ($data->where('alternatif_id', $item->alternatif_id) as $value)
                                    <td class="py-2 px-4 text-gray-700">
                                        @if ($value->subKriteria != null)
                                            {{ $value->subKriteria->nama }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        @if (session()->has('berhasil'))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('berhasil') }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has('gagal'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session('gagal') }}',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Gagal',
                text: @foreach ($errors->all() as $error)
                    '{{ $error }}'
                @endforeach ,
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif
    </script>
@endsection
