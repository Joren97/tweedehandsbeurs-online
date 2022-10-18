<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class EditionFilter extends ApiFilter
{
    protected $safeParms = [
        'year' => ['eq', 'gt', 'lt', 'gte', 'lte'],
    ];
}