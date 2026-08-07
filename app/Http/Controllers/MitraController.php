<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'type' => 'required|in:petani,umkm,buyer',
            'phone' => ['required', 'string', 'max:20', function($attribute, $value, $fail) {
                if (!preg_match('/^(\+62|0)[0-9]{9,12}$/', $value)) {
                    $fail('Format nomor telepon tidak valid (gunakan format: +62xxx atau 0xxx).');
                }
            }],
            'email' => 'nullable|email|max:255',
            'city' => 'required|string|min:2|max:100',
            'province' => 'required|string|min:2|max:100',
            'message' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'type.required' => 'Tipe mitra wajib dipilih.',
            'type.in' => 'Tipe mitra harus berupa petani, umkm, atau buyer.',
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak valid (gunakan format: +62xxx atau 0xxx).',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'city.required' => 'Kota wajib diisi.',
            'city.min' => 'Nama kota minimal 2 karakter.',
            'city.max' => 'Nama kota maksimal 100 karakter.',
            'province.required' => 'Provinsi wajib diisi.',
            'province.min' => 'Nama provinsi minimal 2 karakter.',
            'province.max' => 'Nama provinsi maksimal 100 karakter.',
            'message.max' => 'Pesan maksimal 1000 karakter.',
        ]);

        $mitra = Mitra::create($validated);

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Tim kami akan segera menghubungi Anda.');
    }
}
