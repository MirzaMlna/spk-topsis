@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="w-full px-3">
            <div class="bg-white shadow rounded-xl p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h6 class="text-lg font-semibold">Hasil Perhitungan TOPSIS</h6>
                    <form action="{{ 'pdf_hasil' }}" method="post" target="_blank">
                        @csrf
                        <button type="submit" class="btn btn-sm bg-red-600 text-white hover:bg-red-500">
                            <i class="ri-file-pdf-line mr-1"></i> Export PDF
                        </button>
                    </form>
                </div>
                <table class="w-full text-sm border-t border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left py-2 px-4">Nama</th>
                            <th class="text-left py-2 px-4">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hasilTopsis as $item)
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4">{{ $item->nama_objek }}</td>
                                <td class="py-2 px-4">{{ round($item->nilai, 3) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
