<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    use ApiResponser;

    function role()
    {
        return auth()->user()->role;
    }

    function hasAdminRole()
    {
        return $this->role() == 'admin';
    }

    function hasEmployeeRole()
    {
        return $this->role() == 'employee';
    }

    public function appInfo()
    {
        return [
            'app_name' => config('app.name'),
            'app_version' => config('app.version'),
        ];
    }
}