<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'price', 'description', 'photo'];
    protected $table = 'products';


    //Validation before insert or update
    public function validation($req, $id)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required|string',
            'price' => 'required|numeric',
            'photo' => $id ? 'mimes:jpg,png,jpeg|max:10120' : 'mimes:jpg,png,jpeg|max:10120|required',
            'description' => 'required|string'
        ]);
        if ($validator->fails()) {
            throw new \Exception(strval($validator->errors()), 400);
        }
        return true;
    }

    //Find product by id
    public static function findProductById($id)
    {
        $product = self::where('id', $id)->first();
        if (!empty($product)) {
            return $product;
        }
        throw new \Exception('The product id doesnt exists', 404);
    }


    //Insert data query
    public static function insertProductQuery($params)
    {
        return self::insert($params);
    }

    //Update data query
    public static function updateProductQuery($params, $id)
    {
        return self::findProductById($id)->update($params);
    }

    //Delete product query
    public static function deleteProductQuery($id)
    {
        return self::table('products')->delete($id);
    }

    //Handle image upload
    public static function handleImagesUpload($req)
    {
        if ($req->hasFile('photo')) {
            $file = $req->file('photo');
            $name = time() . $file->getClientOriginalName();
            $upload_photo = $file->move('images', $name);
            if (!empty($upload_photo)) {
                return $name;
            }
            throw new \Exception('Cant upload photo', 500);
        }
    }

    // Get all product
    public static function getWithAllProducts()
    {
        $limit = request('limit', 30);
        $sort_by = request('sort_by', 'id');
        $sort_type = request('sort_type', 'desc');
        $products = self::orderBy($sort_by, $sort_type)->paginate($limit);
        if ($products->count() >0) {
            return self::responseSuccessJson('success', $products);
        }
        return self::responseSuccessJson('the list is empty', null);
    }

    //Create new product
    public static function createNewProduct($req, $id)
    {
        try {
            if (self::validation($req, $id)) {
                $params = $req->except('photo');
                $params['created_at'] = date('Y-m-d H:i:s');
                $params['photo'] = self::handleImagesUpload($req);
                if (self::insertProductQuery($params)) {
                    return self::responseSuccessJson('Product created sucessfully', null);
                }
                throw new \Exception("Something went wrong", 500);
            }
        } catch (\Exception  $e) {
            return self::responseErrorJson($e->getMessage(), $e->getCode());
        }
    }

    //Update product
    public static function updateProduct($req, $id)
    {
        try {
            if (self::validation($req, $id)) {
                $product = self::findProductById($id);
                if (!empty($product)) {
                    $params = $req->except('photo');
                    $params['updated_at'] = date('Y-m-d H:i:s');
                    $params['photo'] = self::handleImagesUpload($req) ?? $product->photo;

                    if (self::updateProductQuery($params, $id)) {
                        return self::responseSuccessJson('Product created sucessfully', null);
                    }
                    throw new \Exception("Something went wrong", 500);
                }
            }
        } catch (\Exception $e) {
            return self::responseErrorJson($e->getMessage(), $e->getCode());
        }
    }

    //Show product info
    public static function showProductInfo($id)
    {
        try {
            $product = self::findProductById($id);
            if ($product) {
                return self::responseSuccessJson('success', $product);
            }
        } catch (\Exception $e) {
            return self::responseErrorJson($e->getMessage(), $e->getCode());
        }
    }

    //Delete product
    public static function deleteProduct($id)
    {
        try {
            if (self::deleteProductQuery($id)) {
                return self::responseSuccessJson('The product deleted', null);
            }
            throw new \Exception('Something went wrong!', 500);
        } catch (\Exception $e) {
            return self::responseErrorJson($e->getMessage(), $e->getCode());
        }
    }

    //Response success status.
    public static function responseSuccessJson($msg, $data)
    {
        return response()->json(['data' => $data, 'message' => $msg, 'status' => true, 'code' => 200], 200);
    }

    //Response success status.
    public static function responseErrorJson($msg, $code)
    {
        $code = ($code >= 100 && $code <= 599) ? $code : 500;
        return response()->json(['message' => $msg, 'status' => false, 'code' => $code], $code);
    }
}
