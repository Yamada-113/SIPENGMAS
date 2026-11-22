<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreJenisKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255', 'unique:jenis_kegiatans,nama'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama jenis kegiatan',
        ];
    }
}
