<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class EditionFilter extends ApiFilter
{
    protected $columnMap = [
        'isActive' => 'is_active',
    ];

    protected $safeParms = [
        'year' => ['eq', 'gt', 'lt', 'gte', 'lte'],
        'isActive' => ['eq']
    ];
}
