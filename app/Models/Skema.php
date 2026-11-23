<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    protected $table = 'skemas';

    protected $fillable = ['skala_id', 'label_dropdown', 'nama', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function skala()
    {
        return $this->belongsTo(Skala::class);
    }
}
