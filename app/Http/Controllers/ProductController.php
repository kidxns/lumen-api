<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    //INDEX
    public function index()
    {
        $limit = request('limit', 30);
        $sort_by = request('sort_by', 'id');
        $sort_type = request('sort_type', 'desc');
        $products = DB::table('products')->orderBy($sort_by, $sort_type)->paginate($limit);
        return $this->responseSuccessWithData('success', $products);
    }

    //STORE NEW RECORD
    public function store(Request $req)
    {
        $this->validate($req, [
            'title' => 'required|string',
            'price' => 'required|numeric',
            'photo' => 'required|mimes:jpg,png,jpeg|max:10120',
            'description' => 'required|string'
        ]);

        $input = $req->except('photo');
        $input['created_at'] = date('Y-m-d H:i:s');

        if ($req->hasFile('photo')) {
            $file = $req->file('photo');
            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);
            $input['photo'] = $name;
        }

        $product = DB::table('products')->insert($input);
        if ($product) {
            return $this->responseSuccessWithData('success', $product);
        }
        throw new Exception('Something went wrong', 500);
    }

    //SHOW
    public function show($id)
    {
        $product = DB::table('products')->where('id', $id)->get();
        if (!empty(count($product) > 0)) {
            return $this->responseSuccessWithData('success', $product);
        }
        throw new Exception('Not found', 404);
    }

    //UPDATE
    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'title' => 'required|string',
            'price' => 'required|numeric',
            'photo' => 'mimes:jpg,png,jpeg|max:10120',
            'description' => 'required|string'
        ]);
        $whereQr = DB::table('products')->where('id', $id);
        $product = $whereQr->first();
        if (!empty($product)) {
            $input = $req->except('photo');
            $input['updated_at'] = date('Y-m-d H:i:s');

            if ($req->hasFile('photo')) {

                $old_file = $product->photo;
                if (!empty($old_file)) {
                    $remove_old_file =  File::delete('images/' . $old_file);
                    if (!$remove_old_file) {
                        throw new Exception('Cant upload new image', 500);
                    } else {
                        $file = $req->file('photo');
                        $name = time() . $file->getClientOriginalName();
                        $file->move('images', $name);
                        $input['photo'] = $name;
                    }
                }
            }

            $update = $whereQr->update($input);
            if ($update) {
                return $this->responseSuccessWithData('success', $whereQr->get());
            }
            throw new Exception('Something went wrong');
        }
        throw new Exception('Not found', 404);
    }

    //REMOVE
    public function destroy($id)
    {
        $remove = DB::table('products')->delete($id);
        if (!empty($remove)) {
            return $this->responseSuccessWithData('The product deleted!', null);
        }
        throw new Exception('The product doesnt exists', 400);
    }
}
