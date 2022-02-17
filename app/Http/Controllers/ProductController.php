<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    //INDEX
    public function index()
    {
        return Product::getWithAllProducts();
    }

    //STORE NEW RECORD
    public function store(Request $req)
    {
        return Product::createNewProduct($req, null);
    }

    //SHOW
    public function show($id)
    {
        return Product::showProductInfo($id);
    }

    //UPDATE
    public function update(Request $req, $id)
    {
        return Product::updateProduct($req, $id);
    }

    //REMOVE
    public function destroy($id)
    {
        return Product::deleteProduct($id);
    }
}
