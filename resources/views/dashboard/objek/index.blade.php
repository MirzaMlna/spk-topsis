@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="w-full px-3">
            <div class="bg-white shadow rounded-xl p-6 mb-6">
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <h6 class="text-lg font-semibold text-green-900">Tabel {{ $judul }}</h6>
                    <div class="flex gap-2">
                        <label for="add_button"
                            class="cursor-pointer inline-flex items-center gap-2 px-5 py-2 font-bold text-white rounded-lg text-base bg-gradient-to-br from-green-900 to-green-700 shadow hover:scale-105 transition-all">
                            <i class="ri-add-fill text-lg"></i> Tambah {{ $judul }}
                        </label>
                        <label for="import_button"
                            class="cursor-pointer inline-flex items-center gap-2 px-5 py-2 font-bold text-white rounded-lg text-base bg-gradient-to-br from-green-900 to-green-700 shadow hover:scale-105 transition-all">
                            <i class="ri-file-excel-line text-lg"></i> Import Data
                        </label>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table id="tabel_data" class="w-full text-sm border-t border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left">Nama</th>
                                <th class="py-3 px-4 text-left">Foto</th>
                                <th class="py-3 px-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr class="border-t hover:bg-gray-50 transition">
                                    <td class="py-2 px-4">{{ $item->nama }}</td>
                                    <td class="py-2 px-4">
                                        @if ($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto"
                                                class="w-16 h-16 object-cover rounded" />
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4">
                                        <div class="flex items-center gap-3">
                                            <label for="edit_button" class="cursor-pointer text-green-900"
                                                onclick="return edit_button('{{ $item->id }}')">
                                                <i class="ri-pencil-line text-lg"></i>
                                            </label>
                                            <button class="text-red-600"
                                                onclick="return delete_button('{{ $item->id }}', '{{ $item->nama }}');">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Form Tambah --}}
            <input type="checkbox" id="add_button" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box">
                    <form action="{{ route('objek.simpan') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <h3 class="font-bold text-lg text-green-900 mb-4">Tambah {{ $judul }}</h3>
                        <div class="form-control w-full">
                            <label class="label font-medium">Nama</label>
                            <input type="text" name="nama" placeholder="Ketik nama..."
                                class="input input-bordered w-full text-dark" value="{{ old('nama') }}" required />
                            @error('nama')
                                <span class="text-error text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-control w-full mt-2">
                            <label class="label font-medium">Foto (jpg, jpeg, png, max 2MB)</label>
                            <input type="file" name="foto" accept="image/*"
                                class="file-input file-input-bordered w-full max-w-xs" />
                            @error('foto')
                                <span class="text-error text-sm mt-1">{{ $message }}</span>
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

            {{-- Form Edit --}}
            <input type="checkbox" id="edit_button" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box" id="edit_form">
                    <form action="{{ route('objek.perbarui') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" />
                        <h3 class="font-bold text-lg text-green-900 mb-4">Ubah {{ $judul }}: <span
                                class="text-green-900" id="title_form"><span
                                    class="loading loading-dots loading-md"></span></span></h3>
                        <div class="form-control w-full">
                            <label class="label font-medium">Nama <span id="loading_edit1"></span></label>
                            <input type="text" name="nama" class="input input-bordered w-full text-dark" required />
                            @error('nama')
                                <span class="text-error text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-control w-full mt-2">
                            <label class="label font-medium">Foto (jpg, jpeg, png, max 2MB)</label>
                            <input type="file" name="foto" accept="image/*"
                                class="file-input file-input-bordered w-full max-w-xs" />
                            @error('foto')
                                <span class="text-error text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-action">
                            <button type="submit" class="btn bg-green-900 text-white hover:bg-green-800">Perbarui</button>
                            <label for="edit_button" class="btn">Batal</label>
                        </div>
                    </form>
                </div>
                <label class="modal-backdrop" for="edit_button">Close</label>
            </div>

            {{-- Import --}}
            <input type="checkbox" id="import_button" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box">
                    <form action="{{ route('objek.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <h3 class="font-bold text-lg text-green-900 mb-4">Import {{ $judul }}</h3>
                        <div class="form-control w-full">
                            <label class="label font-medium">File Import</label>
                            <input type="file" name="import_data"
                                class="file-input file-input-bordered w-full max-w-xs" required />
                            @error('import_data')
                                <span class="text-error text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-action">
                            <button type="submit" class="btn bg-green-900 text-white hover:bg-green-800">Import</button>
                            <label for="import_button" class="btn">Batal</label>
                        </div>
                    </form>
                </div>
                <label class="modal-backdrop" for="import_button">Close</label>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // SweetAlert untuk notifikasi sukses
        @if (session()->has('berhasil'))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('berhasil') }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        // SweetAlert untuk notifikasi gagal
        @if (session()->has('gagal'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session('gagal') }}',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        // Validasi error dari backend (form)
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

        // Fungsi untuk membuka modal edit dan mengambil data via AJAX
        function edit_button(id) {
            const loadingHTML = `<span class="loading loading-dots loading-md text-greenPrimary"></span>`;
            $("#title_form").html(loadingHTML);
            $("#loading_edit1").html(loadingHTML);

            $.ajax({
                type: "GET",
                url: "{{ route('objek.ubah') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(data) {
                    const items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("#title_form").html(items[1]);
                    $("input[name='id']").val(items[0]);
                    $("input[name='nama']").val(items[1]);

                    $("#loading_edit1").html('');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat mengambil data!',
                    });
                    $("#loading_edit1").html('');
                }
            });
        }

        // Fungsi untuk konfirmasi and menghapus data
        function delete_button(id, nama) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                html: `
            <div class="text-gray-700 text-sm">
                <p class="mb-2">Data tidak dapat dipulihkan kembali setelah dihapus!</p>
                <div class="my-2 border-t border-gray-200"></div>
                <p><span class="font-semibold text-red-600">Data:</span> ${nama}</p>
            </div>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#22C55E', // Hijau lembut
                cancelButtonColor: '#EF4444', // Merah Tailwind
                confirmButtonText: '<i class="ri-delete-bin-6-line"></i> Hapus',
                cancelButtonText: '<i class="ri-close-line"></i> Batal',
                customClass: {
                    popup: 'rounded-xl',
                    title: 'text-lg font-semibold text-gray-800',
                    confirmButton: 'px-4 py-2',
                    cancelButton: 'px-4 py-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('objek.hapus') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success: function() {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data telah dihapus.',
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK',
                                customClass: {
                                    popup: 'rounded-xl',
                                    title: 'text-lg font-semibold text-green-700',
                                    confirmButton: 'px-4 py-2'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal menghapus data!',
                                text: 'Terjadi kesalahan saat menghapus.',
                                confirmButtonColor: '#EF4444',
                                confirmButtonText: 'Tutup',
                                customClass: {
                                    popup: 'rounded-xl',
                                    title: 'text-lg font-semibold text-red-700',
                                    confirmButton: 'px-4 py-2'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
