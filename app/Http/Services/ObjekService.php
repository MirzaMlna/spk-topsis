<?php

namespace App\Http\Services;

use App\Http\Repositories\ObjekRepository;
use Illuminate\Support\Facades\Storage;

class ObjekService
{
    protected $objekRepository;

    public function __construct(ObjekRepository $objekRepository)
    {
        $this->objekRepository = $objekRepository;
    }

    public function getAll()
    {
        $data = $this->objekRepository->getAll();
        return $data;
    }

    public function simpanPostData($request)
    {
        $data = $request->validated();
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $path = $foto->store('objek', 'public');
            $data['foto'] = $path;
        }
        $data = $this->objekRepository->simpan($data);
        return $data;
    }

    public function ubahGetData($request)
    {
        $data = $this->objekRepository->getDataById($request->id);
        return $data;
    }

    public function perbaruiPostData($request)
    {
        $validate = $request->validated();
        $objek = $this->objekRepository->getDataById($request->id);
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($objek->foto && Storage::disk('public')->exists($objek->foto)) {
                Storage::disk('public')->delete($objek->foto);
            }
            $foto = $request->file('foto');
            $path = $foto->store('objek', 'public');
            $validate['foto'] = $path;
        }
        $data = [true, $this->objekRepository->perbarui($request->id, $validate)];
        return $data;
    }

    public function hapusPostData($request)
    {
        $objek = $this->objekRepository->getDataById($request);
        if ($objek->foto && Storage::disk('public')->exists($objek->foto)) {
            Storage::disk('public')->delete($objek->foto);
        }
        $data = $this->objekRepository->hapus($request);
        return $data;
    }

    public function import($request)
    {
        $data = $this->objekRepository->import($request);
        return $data;
    }
}
