<?php

namespace App\Http\Services;

use App\Models\LetterLost;

class LetterLostService
{
    public function store(array $data)
    {
        return LetterLost::create([
            'name' => $data['name'],
            'tempat_lahir' => $data['tempat-lahir'],
            'tgl_lahir' => $data['tanggal-lahir'],
            'gender' => $data['gender'],
            'nationality' => 'WNI',
            'religion' => 'Islam',
            'marriage' => $data['status-perkawinan'],
            'job' => $data['pekerjaan'],
            'education' => $data['pendidikan'],
            'nik' => $data['nik'],
            'no_kk' => $data['no-kk'],
            'address' => $data['alamat'],
            'information' => $data['barang-hilang'],
        ]);
    }
}
