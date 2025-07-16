@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="w-full px-3">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h6 class="text-lg font-semibold">Tabel {{ $judul }}</h6>
                    <label for="add_button"
                        class="cursor-pointer px-3 py-2 font-bold text-white rounded-lg text-sm bg-green-600 hover:bg-green-500 transition">
                        <i class="ri-add-fill"></i> Tambah {{ $judul }}
                    </label>
                </div>
                <table class="w-full text-sm border-t border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left py-2 px-4">Nama</th>
                            <th class="text-left py-2 px-4">Foto</th>
                            <th class="text-left py-2 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4">{{ $item->objek->nama }}</td>
                                <td class="py-2 px-4">
                                    @if ($item->objek->foto)
                                        @if (Str::endsWith($item->objek->foto, '.pdf'))
                                            <a href="{{ asset('storage/' . $item->objek->foto) }}" target="_blank"
                                                class="text-blue-600 underline">Lihat PDF</a>
                                        @else
                                            <img src="{{ asset('storage/' . $item->objek->foto) }}" alt="Foto"
                                                class="w-16 h-16 object-cover rounded" />
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4">
                                    <button
                                        onclick="return delete_button('{{ $item->id }}', '{{ $item->objek->nama }}');"
                                        class="text-red-500 hover:text-red-700">
                                        <i class="ri-delete-bin-line text-xl"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Form Tambah Data -->
            <input type="checkbox" id="add_button" class="modal-toggle">
            <div class="modal">
                <div class="modal-box">
                    <form action="{{ route('alternatif.simpan') }}" method="post">
                        @csrf
                        <h3 class="font-bold text-lg mb-4">Tambah {{ $judul }}</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Pilih Objek</label>
                            <select class="w-full border rounded px-3 py-2" name="objek_id[]" id="objek_id" multiple>
                                @foreach ($objek as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('objek_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('objek_id')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="modal-action">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <label for="add_button" class="btn">Batal</label>
                        </div>
                    </form>
                </div>
                <label class="modal-backdrop" for="add_button">Close</label>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#objek_id').select2({
                placeholder: "Pilih Objek",
                allowClear: true
            });
        });

        @if (session()->has('berhasil'))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('berhasil') }}',
                icon: 'success',
                confirmButtonColor: '#16a34a',
            });
        @endif

        @if (session()->has('gagal'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session('gagal') }}',
                icon: 'error',
                confirmButtonColor: '#ef4444',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Gagal',
                text: @foreach ($errors->all() as $error)
                    '{{ $error }}'
                @endforeach ,
                icon: 'error',
                confirmButtonColor: '#ef4444',
            });
        @endif

        function delete_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data tidak dapat dipulihkan kembali!</p><div class='divider'></div><b>Data: " + nama +
                    "</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Hapus Data!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('alternatif.hapus') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function() {
                            Swal.fire({
                                title: 'Data berhasil dihapus!',
                                icon: 'success',
                                confirmButtonColor: '#16a34a',
                            }).then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Data gagal dihapus!',
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
