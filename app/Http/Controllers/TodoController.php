<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Validation\Rule;

class TodoController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::where('status', TODO::PUBLIC)->get();
        return response()->json([
            'data' => $todos
        ], 200);
    }
}
