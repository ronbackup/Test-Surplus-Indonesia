<?php

namespace App\Http\Controllers\Api;

use DB;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryProductController extends Controller
{
    public function index()
    {
        $category = CategoryProduct::latest()->paginate(5);

        return new ApiResource(true, 'Category Product List', $category);
    }

    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $status = true;
            $message = 'Category Product Added Successfully!';
            $data = CategoryProduct::where([
                    'product_id' => $req->product_id,
                    'category_id' => $req->category_id,
                ])->first();

            $validator = Validator::make($req->all(), [
                'product_id' => 'required',
                'category_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $produk = Product::where('id', $req->product_id)->first();
            $category = Category::where('id', $req->category_id)->first();

            if (!empty($produk) && !empty($category)) {
                if (empty($data)) {
                    CategoryProduct::create([
                        'product_id' => $req->product_id,
                        'category_id' => $req->category_id,
                    ]);
                    $data = CategoryProduct::where([
                        'product_id' => $req->product_id,
                        'category_id' => $req->category_id,
                    ])->first();
                } else {
                    $status = false;
                    $message = 'Product Category Available!';
                    $data = [];
                }
            } else {
                $status = false;
                $message = 'Product or Category not found!';
                $data = [];
            }

            DB::commit();
            return new ApiResource($status, $message, $data);
        } catch (Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Add Category Product!', []);
        }
    }

    public function show($product_id, $category_id)
    {
        $data = CategoryProduct::where([
            'product_id' => $product_id,
            'category_id' => $category_id,
        ])->first();

        $status = true;
        $message = 'Data Found!';

        if ($data == null) {
            $status = false;
            $message = 'Data not found!';
            $data = [];
        }

        return new ApiResource($status, $message, $data);
    }

    public function update(Request $req, $product_id, $category_id)
    {
        DB::beginTransaction();
        try {
            $data = CategoryProduct::where([
                'product_id' => $product_id,
                'category_id' => $category_id,
            ])->first();
            
            $status = true;
            $message = 'Category Product Changed Successfully!';

            if(!empty($data)) {
                $validator = Validator::make($req->all(), [
                    'product_id' => 'required',
                    'category_id' => 'required',
                ]);
        
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                
                $checkExistingData = $data = CategoryProduct::where([
                    'product_id' => $req->product_id,
                    'category_id' => $req->category_id,
                ])->first();

                if (empty($checkExistingData)) {
                    CategoryProduct::where(['product_id' => $product_id, 'category_id' => $category_id])->update([
                        'product_id' => $req->product_id,
                        'category_id' => $req->category_id,
                    ]);

                    $data = CategoryProduct::where([
                        'product_id' => $req->product_id,
                        'category_id' => $req->category_id,
                    ])->first();
                } else {
                    $status = false;
                    $message = 'Product Category Available!';
                    $data = [];
                }
            } else {
                $status = false;
                $message = 'Data not found!';
                $data = [];
            }
            
            DB::commit();
            return new ApiResource($status, $message, $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Change Category Product!', []);
        }
    }

    public function destroy($product_id, $category_id)
    {
        DB::beginTransaction();
        try {
            $data = CategoryProduct::where([
                'product_id' => $product_id,
                'category_id' => $category_id,
            ])->first();

            $status = true;
            $message = 'Category Product Deleted Successfully';

            if(!empty($data)) {
                $data = CategoryProduct::where([
                    'product_id' => $product_id,
                    'category_id' => $category_id,
                ])->delete();
                $data = [];
            } else {
                $status = false;
                $message = 'Data not found!';
                $data = [];
            }
            
            DB::commit();
            return new ApiResource($status, $message, $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Delete Category Product!', []);
        }
    }

}
