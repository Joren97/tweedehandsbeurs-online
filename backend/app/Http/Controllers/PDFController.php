<?php

namespace App\Http\Controllers;

use App\Models\Productlist;
use PDF;
use Mail;

class PDFController extends Controller
{
    /**
     * Generate a pdf for the given productlist
     *  @param integer $id
     */
    public static function generateProductlistPdf($id)
    {
        $data['productlist'] = Productlist::findOrFail($id)->with('products')->first();
        $pdf = PDF::loadView('pdfs.list', $data);
        return $pdf;
    }
}