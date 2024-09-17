<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    public function calculateDiscount(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'discounts' => 'required|array',
            'discounts.*.diskon' => 'required|numeric|min:0|max:100',
            'total_sebelum_diskon' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $discounts = $request->input('discounts');
        $totalSebelumDiskon = $request->input('total_sebelum_diskon');

        $totalDiskon = 0;
        $hargaSetelahDiskon = $totalSebelumDiskon;

        // Hitung diskon bertingkat
        foreach ($discounts as $discount) {
            $diskonPersen = $discount['diskon'] / 100;
            $nilaiDiskon = $hargaSetelahDiskon * $diskonPersen;
            $totalDiskon += $nilaiDiskon;
            $hargaSetelahDiskon -= $nilaiDiskon;
        }

        // Pembulatan ke bawah untuk menghindari angka desimal
        $totalDiskon = floor($totalDiskon);
        $hargaSetelahDiskon = floor($hargaSetelahDiskon);

        return response()->json([
            'total_diskon' => $totalDiskon,
            'total_harga_setelah_diskon' => $hargaSetelahDiskon,
        ]);
    }
}