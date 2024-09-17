<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OjolRevenueShareController extends Controller
{
    public function calculateShare(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'harga_sebelum_markup' => 'required|numeric|min:0',
            'markup_persen' => 'required|numeric|min:0|max:100',
            'share_persen' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $hargaSebelumMarkup = $request->input('harga_sebelum_markup');
        $markupPersen = $request->input('markup_persen') / 100;
        $sharePersen = $request->input('share_persen') / 100;

        // Hitung harga setelah markup
        $hargaSetelahMarkup = $hargaSebelumMarkup * (1 + $markupPersen);

        // Hitung share untuk ojol
        $shareUntukOjol = $hargaSetelahMarkup * $sharePersen;

        // Hitung net untuk resto
        $netUntukResto = $hargaSetelahMarkup - $shareUntukOjol;

        // Pembulatan ke bawah untuk menghindari angka desimal
        $netUntukResto = floor($netUntukResto);
        $shareUntukOjol = floor($shareUntukOjol);

        return response()->json([
            'net_untuk_resto' => $netUntukResto,
            'share_untuk_ojol' => $shareUntukOjol,
        ]);
    }
}