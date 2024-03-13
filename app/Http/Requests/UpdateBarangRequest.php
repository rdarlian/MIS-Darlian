<?php

namespace App\Http\Requests;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBarangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        $barang = Barang::find($request->id);
        return [
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => ['required', Rule::unique('barangs', 'kode_barang')->ignore($barang)],
            'stok' => 'required|integer',
            'harga' => 'required|integer',
            'user' => 'required|exists:users,username',
        ];
    }
}
