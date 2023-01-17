<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class ProductListFilter extends ApiFilter
{
    protected $columnMap = [
        'userId' => 'user_id',
    ];

    protected $safeParms = [
        'userId' => ['eq'],
    ];

}