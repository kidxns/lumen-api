<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index()
    {
        $paginate = request('paginate', 30);
        $sort_by = request('sort_by', 'id');
        $sort_type = request('sort_type', 'desc');
        $products = DB::table('products')->orderBy($sort_by, $sort_type)->paginate($paginate);
        return response()->json(['products' => $products, 'status' => 'success', 'code' => 200], 200);
    }


    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }


    public function update(Request $request, $id)
    {
    }


    public function destroy($id)
    {
    }
}
