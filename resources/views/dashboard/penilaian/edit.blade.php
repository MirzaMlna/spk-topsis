@extends('dashboard.layouts.app')

@section('container')
    <div class="px-4 py-6">
        <div class="max-w-3xl mx-auto bg-white shadow rounded-2xl p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Ubah {{ $judul }}:
                <span class="text-greenPrimary" id="title_form">{{ $data->alternatif->objek->nama }}</span>
            </h3>

            <form action="{{ route('penilaian.perbarui', $data->alternatif_id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="alternatif_id" value="{{ $data->alternatif_id }}" />

                @foreach ($subKriteria->unique('kriteria_id') as $item)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Sub Kriteria: <span class="text-greenPrimary">{{ $item->kriteria->nama }}</span>
                        </label>
                        <select class="select select-bordered w-full" name="kriteria_id[]" id="kriteria_id[]">
                            <option disabled selected>--Pilih--</option>
                            @foreach ($subKriteria->where('kriteria_id', $item->kriteria_id) as $value)
                                <option value="{{ $value->id }}"
                                    {{ $value->id == $data2->where('kriteria_id', $item->kriteria_id)->first()->sub_kriteria_id ? 'selected' : '' }}>
                                    {{ $value->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_kriteria_id')
                            <p class="text-sm text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="flex justify-end gap-2 pt-4">
                    <button type="submit" class="btn btn-success">Perbarui</button>
                    <a href="{{ route('penilaian') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
