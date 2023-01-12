<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class UserFilter extends ApiFilter
{
    protected $columnMap = [];

    protected $safeParms = [
        'firstname' => ['like'],
        'lastname' => ['like'],
        'email' => ['like']
    ];
}