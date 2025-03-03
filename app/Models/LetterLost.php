<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterLost extends Model
{
    protected $fillable = [
        'no_letter',
        'name',
        'tempat_lahir',
        'tgl_lahir',
        'gender',
        'nationality',
        'religion',
        'marriage',
        'job',
        'education',
        'nik',
        'no_kk',
        'address',
        'information',
        'no_wa'
    ];
}
