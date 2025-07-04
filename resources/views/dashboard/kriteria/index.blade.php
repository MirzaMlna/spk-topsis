@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="w-full px-3">
            <div class="bg-white shadow rounded-xl p-6 mb-6">
                <div class="flex flex-row items-center justify-between mb-4 border-b pb-4">
                    <h6 class="text-lg font-semibold text-green-900">Tabel {{ $judul }}</h6>
                    @if ($sumBobot < 1)
                        <label for="add_button"
                            class="cursor-pointer inline-block px-5 py-2 font-bold text-white rounded-lg text-base bg-gradient-to-br from-green-900 to-green-700 shadow hover:scale-105 transition-all">
                            <i class="ri-add-fill"></i>
                            Tambah {{ $judul }}
                        </label>
                    @else
                        <button for="add_button"
                            class="inline-block px-5 py-2 font-bold text-white rounded-lg text-base bg-gradient-to-br from-green-900 to-green-700 opacity-50 shadow"
                            @readonly(true)>
                            <i class="ri-add-fill"></i>
                            Tambah {{ $judul }}
                        </button>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-t border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left py-2 px-4">Kode</th>
                                <th class="text-left py-2 px-4">Nama</th>
                                <th class="text-left py-2 px-4">Bobot</th>
                                <th class="text-left py-2 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr class="border-b border-gray-200">
                                    <td class="py-2 px-4">{{ $item->kode }}</td>
                                    <td class="py-2 px-4">{{ $item->nama }}</td>
                                    <td class="py-2 px-4">{{ $item->bobot }}</td>
                                    <td class="py-2 px-4 flex gap-x-3">
                                        <label for="edit_button" class="cursor-pointer"
                                            onclick="return edit_button('{{ $item->id }}')">
                                            <i class="ri-pencil-line text-xl text-green-900"></i>
                                        </label>
                                        <button
                                            onclick="return delete_button('{{ $item->id }}', '{{ $item->nama }}');">
                                            <i class="ri-delete-bin-line text-xl text-red-600"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="font-bold text-center py-2 px-4" colspan="2">Total Bobot:</td>
                                <td class="font-bold py-2 px-4" colspan="2">
                                    @if ($sumBobot < 1)
                                        {{ $sumBobot }}
                                    @else
                                        {{ $sumBobot }} <span class="text-red-600"> (max)</span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Form Tambah Data --}}
            <input type="checkbox" id="add_button" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box">
                    <form action="{{ route('kriteria.simpan') }}" method="post" enctype="multipart/form-data">
                        <h3 class="font-bold text-lg text-green-900">Tambah {{ $judul }}</h3>
                        @csrf
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Kode</span>
                            </label>
                            <input type="text" name="kode" placeholder="Type here"
                                class="input input-bordered w-full max-w-xs text-dark bg-slate-100"
                                value="{{ $kode }}" required readonly />
                            <label class="label">
                                @error('kode')
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Nama</span>
                            </label>
                            <input type="text" name="nama" placeholder="Type here"
                                class="input input-bordered w-full max-w-xs text-dark" value="{{ old('nama') }}"
                                required />
                            <label class="label">
                                @error('nama')
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Bobot</span>
                            </label>
                            <input type="number" step="0.1" name="bobot" placeholder="Type here"
                                class="input input-bordered w-full max-w-xs text-dark" value="{{ old('bobot') }}"
                                required />
                            <label class="label">
                                @error('bobot')
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div class="modal-action">
                            <button type="submit" class="btn bg-green-900 text-white hover:bg-green-800">Simpan</button>
                            <label for="add_button" class="btn">Batal</label>
                        </div>
                    </form>
                </div>
                <label class="modal-backdrop" for="add_button">Close</label>
            </div>

            {{-- Form Ubah Data --}}
            <input type="checkbox" id="edit_button" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box" id="edit_form">
                    <form action="{{ route('kriteria.perbarui') }}" method="post" enctype="multipart/form-data">
                        <h3 class="font-bold text-lg text-green-900">Ubah {{ $judul }}: <span class="text-green-900"
                                id="title_form"><span class="loading loading-dots loading-md"></span></span></h3>
                        @csrf
                        <input type="text" name="id" hidden />
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Kode</span>
                                <span class="label-text-alt" id="loading_edit1"></span>
                            </label>
                            <input type="text" name="kode" placeholder="Type here"
                                class="input input-bordered w-full text-dark" required />
                            <label class="label">
                                @error('kode')
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Nama</span>
                                <span class="label-text-alt" id="loading_edit2"></span>
                            </label>
                            <input type="text" name="nama" placeholder="Type here"
                                class="input input-bordered w-full text-dark" required />
                            <label class="label">
                                @error('nama')
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Bobot</span>
                                <span class="label-text-alt" id="loading_edit3"></span>
                            </label>
                            <input type="number" step="0.1" name="bobot" placeholder="Type here"
                                class="input input-bordered w-full text-dark" required />
                            <label class="label">
                                @error('bobot')
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div class="modal-action">
                            <button type="submit"
                                class="btn bg-green-900 text-white hover:bg-green-800">Perbarui</button>
                            <label for="edit_button" class="btn">Batal</label>
                        </div>
                    </form>
                </div>
                <label class="modal-backdrop" for="edit_button">Close</label>
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
            })
        @endif

        function edit_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-greenPrimary"></span>`;
            $("#title_form").html(loading);
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('kriteria.ubah') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("#title_form").html(`${items[2]}`);
                    $("input[name='id']").val(items[0]);
                    $("input[name='kode']").val(items[1]);
                    $("input[name='nama']").val(items[2]);
                    $("input[name='bobot']").val(items[3]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                }
            });
        }

        function delete_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data tidak dapat dipulihkan kembali!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus Data!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('kriteria.hapus') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Data berhasil dihapus!',
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Data gagal dihapus!',
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection
