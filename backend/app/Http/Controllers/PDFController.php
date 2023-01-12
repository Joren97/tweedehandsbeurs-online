<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Productlist;
use PDF;

class PDFController extends Controller
{
    /**
     * Generate a pdf for the given productlist
     *  @param integer $id
     */
    public static function generateProductlistPdf($id)
    {
        $list = Productlist::where('id', $id)->with('products.price')->first();

        $data['list'] = $list;
        $pdf = PDF::loadView('pdfs.list', $data);
        return $pdf;
    }
}