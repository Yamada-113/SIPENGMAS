<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FakultasProdi extends Model
{
    protected $table = 'fakultas_prodis';
    protected $fillable = ['fakultas_id', 'nama'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }
}
