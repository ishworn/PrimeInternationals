<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgenciesController extends Controller
{

    public function index()
    {
        return view(' backend.agencies.index');
    }
    //
}
