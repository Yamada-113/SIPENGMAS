<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skala extends Model {
    protected $fillable = ['nama'];
    public function skemas() { return $this->hasMany(Skema::class); }
}
