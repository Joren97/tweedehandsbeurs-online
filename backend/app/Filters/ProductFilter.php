<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class ProductFilter extends ApiFilter
{
    protected $columnMap = [
        'productNumber' => 'product_number',
    ];

    protected $safeParms = [
        'productNumber' => ['eq'],
    ];
}