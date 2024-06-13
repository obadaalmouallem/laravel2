<?php

namespace App\Http\Controllers;

use App\Models\Size;

class SizesController extends Controller
{
    public function index()
    {
        $sizes = Size::all();

        return response()->json($sizes);
    }
}
