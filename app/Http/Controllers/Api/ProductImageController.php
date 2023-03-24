<?php

namespace App\Http\Controllers\Api;

use DB;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    public function index()
    {
        $productImage = ProductImage::latest()->paginate(5);

        return new ApiResource(true, 'Product Image List', $productImage);
    }

    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $status = true;
            $message = 'Product Image Added Successfully!';
            $data = ProductImage::where([
                    'product_id' => $req->product_id,
                    'image_id' => $req->image_id,
                ])->first();

            $validator = Validator::make($req->all(), [
                'product_id' => 'required',
                'image_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $produk = Product::where('id', $req->product_id)->first();
            $image = Image::where('id', $req->image_id)->first();

            if (!empty($produk) && !empty($image)) {
                if (empty($data)) {
                    ProductImage::create([
                        'product_id' => $req->product_id,
                        'image_id' => $req->image_id,
                    ]);
                    $data = ProductImage::where([
                        'product_id' => $req->product_id,
                        'image_id' => $req->image_id,
                    ])->first();
                } else {
                    $status = false;
                    $message = 'Product Image Available!';
                    $data = [];
                }
            } else {
                $status = false;
                $message = 'Product or Image not found!';
                $data = [];
            }

            DB::commit();
            return new ApiResource($status, $message, $data);
        } catch (Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Add Product Image!', []);
        }
    }

    public function show($product_id, $image_id)
    {
        $data = ProductImage::where([
            'product_id' => $product_id,
            'image_id' => $image_id,
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

    public function update(Request $req, $product_id, $image_id)
    {
        DB::beginTransaction();
        try {
            $data = ProductImage::where([
                'product_id' => $product_id,
                'image_id' => $image_id,
            ])->first();
            
            $status = true;
            $message = 'Product Image Changed Successfully!';

            if(!empty($data)) {
                $validator = Validator::make($req->all(), [
                    'product_id' => 'required',
                    'image_id' => 'required',
                ]);
        
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                
                $checkExistingData = $data = ProductImage::where([
                    'product_id' => $req->product_id,
                    'image_id' => $req->image_id,
                ])->first();

                if (empty($checkExistingData)) {
                    ProductImage::where(['product_id' => $product_id, 'image_id' => $image_id])->update([
                        'product_id' => $req->product_id,
                        'image_id' => $req->image_id,
                    ]);

                    $data = ProductImage::where([
                        'product_id' => $req->product_id,
                        'image_id' => $req->image_id,
                    ])->first();
                } else {
                    $status = false;
                    $message = 'Product Image Available!';
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
            return new ApiResource(false, 'Failed to Change Product Image!', []);
        }
    }

    public function destroy($product_id, $image_id)
    {
        DB::beginTransaction();
        try {
            $data = ProductImage::where([
                'product_id' => $product_id,
                'image_id' => $image_id,
            ])->first();

            $status = true;
            $message = 'Product Image Deleted Successfully';

            if(!empty($data)) {
                $data = ProductImage::where([
                    'product_id' => $product_id,
                    'image_id' => $image_id,
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
            return new ApiResource(false, 'Failed to Delete Product Image!', []);
        }
    }

}
