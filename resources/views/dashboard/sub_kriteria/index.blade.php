@extends('dashboard.layouts.app')

@section('container')
    <div class="flex flex-wrap -mx-3">
        <div class="w-full px-3">
            @if ($data != null)
                @foreach ($data as $item)
                    <div class="bg-white shadow rounded-xl p-6 mb-6">
                        <div class="flex justify-between items-center mb-4 border-b pb-4">
                            <h6 class="text-lg font-semibold text-green-900">Tabel Kriteria <span
                                    class="text-green-900">{{ $item['kriteria'] }}</span></h6>
                            <label for="add_button" id="label_{{ $item['kriteria'] }}"
                                class="cursor-pointer inline-block px-5 py-2 font-bold text-white rounded-lg text-base bg-gradient-to-br from-green-900 to-green-700 shadow hover:scale-105 transition-all">
                                <i class="ri-add-fill"></i>
                                Tambah {{ $judul }}
                            </label>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="{{ 'tabel_data_' . $item['kriteria'] }}"
                                class="w-full text-sm border-t border-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="text-left py-2 px-4">Nama</th>
                                        <th class="text-left py-2 px-4">Nilai</th>
                                        <th class="text-left py-2 px-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item['sub_kriteria'] as $subKriteria)
                                        <tr class="border-b border-gray-200">
                                            <td class="py-2 px-4">{{ $subKriteria['nama'] }}</td>
                                            <td class="py-2 px-4">{{ $subKriteria['nilai'] }}</td>
                                            <td class="py-2 px-4 flex gap-x-3">
                                                <label for="edit_button" class="cursor-pointer"
                                                    onclick="return edit_button('{{ $subKriteria['id'] }}')">
                                                    <i class="ri-pencil-line text-xl text-green-900"></i>
                                                </label>
                                                <button
                                                    onclick="return delete_button('{{ $subKriteria['id'] }}', '{{ $item['kriteria'] }}', '{{ $subKriteria['nama'] }}');">
                                                    <i class="ri-delete-bin-line text-xl text-red-600"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white shadow rounded-xl p-6 mb-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-4">
                        <h6 class="text-lg font-semibold text-green-900">Tabel Sub Kriteria</h6>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabel_data" class="w-full text-sm border-t border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-left py-2 px-4">Nama</th>
                                    <th class="text-left py-2 px-4">Nilai</th>
                                    <th class="text-left py-2 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Form Tambah Data --}}
            <input type="checkbox" id="add_button" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box">
                    <form action="{{ route('sub_kriteria.simpan') }}" method="post" enctype="multipart/form-data">
                        <h3 class="font-bold text-lg text-green-900">Tambah {{ $judul }} <span
                                class="text-green-900" id="title_add_button"></span></h3>
                        @csrf
                        <input type="number" name="kriteria_id" id="kriteria_id_add_button" hidden>
                        <input type="text" name="kode" id="kode_add_button" value="kode" hidden>
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
                                <span class="label-text">Nilai</span>
                            </label>
                            <input type="number" name="nilai" placeholder="Type here"
                                class="input input-bordered w-full max-w-xs text-dark" value="{{ old('nilai') }}"
                                required />
                            <label class="label">
                                @error('nilai')
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
                    <form action="{{ route('sub_kriteria.perbarui') }}" method="post" enctype="multipart/form-data">
                        <h3 class="font-bold text-lg text-green-900">Ubah {{ $judul }}: <span class="text-green-900"
                                id="title_form"><span class="loading loading-dots loading-md"></span></span></h3>
                        @csrf
                        <input type="text" name="id" hidden />
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Nama</span>
                                <span class="label-text-alt" id="loading_edit1"></span>
                            </label>
                            <input type="text" name="nama" placeholder="Type here"
                                class="input input-bordered w-full max-w-xs text-dark" required />
                            <label class="label">
                                @error('nama')
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div class="form-control w-full max-w-xs">
                            <label class="label">
                                <span class="label-text">Nilai</span>
                                <span class="label-text-alt" id="loading_edit2"></span>
                            </label>
                            <input type="number" name="nilai" placeholder="Type here"
                                class="input input-bordered w-full max-w-xs text-dark" required />
                            <label class="label">
                                @error('nilai')
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
        // Script untuk mengisi kriteria_id pada form tambah
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll("[id^='label_']").forEach(function(label) {
                label.addEventListener("click", function() {
                    var kriteriaNama = this.id.replace("label_", "");
                    @foreach ($data as $item)
                        if ("{{ $item['kriteria'] }}" === kriteriaNama) {
                            document.getElementById("kriteria_id_add_button").value =
                                "{{ $item['kriteria_id'] }}";
                            document.getElementById("title_add_button").innerText = kriteriaNama;
                        }
                    @endforeach
                });
            });
        });

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
                icon: 'error',
                title: 'Gagal',
                text: @foreach ($errors->all() as $error)
                    '{{ $error }}'
                @endforeach ,
            })
        @endif

        function edit_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-greenPrimary"></span>`;
            $("#title_form").html(loading);
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('sub_kriteria.ubah') }}",
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

                    // console.log(items);

                    $("#title_form").html(`${items[7]['nama']}`);
                    $("input[name='id']").val(items[0]);
                    $("input[name='nama']").val(items[2]);
                    $("input[name='nilai']").val(items[3]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                }
            });
        }

        function delete_button(id, kriteria, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data tidak dapat dipulihkan kembali!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: Kriteria " + kriteria + " (" + nama + ")</b>",
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
                        url: "{{ route('sub_kriteria.hapus') }}",
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
