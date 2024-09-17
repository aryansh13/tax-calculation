<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxCalculationController extends Controller
{
    /**
     * Calculate net sales and tax amount (GET method)
     */
    public function calculateGet(Request $request)
    {
        $total = $request->query('total');
        $taxPercentage = $request->query('persen_pajak');

        return $this->calculate($total, $taxPercentage);
    }

    /**
     * Calculate net sales and tax amount (POST method)
     */
    public function calculatePost(Request $request)
    {
        $total = $request->input('total');
        $taxPercentage = $request->input('persen_pajak');

        return $this->calculate($total, $taxPercentage);
    }

    /**
     * Perform the calculation
     */
    private function calculate($total, $taxPercentage)
    {
        if (!is_numeric($total) || !is_numeric($taxPercentage)) {
            return response()->json(['error' => 'Invalid input. Both total and persen_pajak must be numbers.'], 400);
        }

        $total = floatval($total);
        $taxPercentage = floatval($taxPercentage);

        $netSales = $total / (1 + ($taxPercentage / 100));
        $taxAmount = $total - $netSales;

        return response()->json([
            'net_sales' => round($netSales, 2),
            'pajak_rp' => round($taxAmount, 2)
        ]);
    }
}