<?php

namespace App\Http\Controllers\Api;

use DB;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::latest()->paginate(5);

        return new ApiResource(true, 'Product List', $product);
    }

    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required',
                'description' => 'required',
                'enable' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $product = Product::create([
                'name' => $req->name,
                'description' => $req->description,
                'enable' => $req->enable,
            ]);

            DB::commit();
            return new ApiResource(true, 'Product Added Successfully!', $product);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Add Product!', []);
        }
    }

    public function show($id_product)
    {
        $data = Product::where('id', $id_product)->first();
        $status = true;
        $message = 'Data Found!';

        if ($data == null) {
            $status = false;
            $message = 'Data not found!';
            $data = [];
        }

        return new ApiResource($status, $message, $data);
    }

    public function update(Request $req, $id_product)
    {
        DB::beginTransaction();
        try {
            $data = Product::where('id', $id_product)->first();
            $status = true;
            $message = 'Product Changed Successfully!';

            if(!empty($data)) {
                $validator = Validator::make($req->all(), [
                    'name' => 'required',
                    'description' => 'required',
                    'enable' => 'required',
                ]);
        
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                
                Product::where('id', $id_product)->update([
                    'name' => $req->name,
                    'description' => $req->description,
                    'enable' => $req->enable,
                ]);
                
                $data = Product::where('id', $id_product)->first();
            } else {
                $status = false;
                $message = 'Data not found!';
                $data = [];
            }
            
            DB::commit();
            return new ApiResource($status, $message, $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Change Product!', []);
        }
    }

    public function destroy($id_product)
    {
        DB::beginTransaction();
        try {
            $data = Product::where('id', $id_product)->first();
            $status = true;
            $message = 'Product Deleted Successfully';

            if(!empty($data)) {
                Product::where('id', $id_product)->delete();
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
            return new ApiResource(false, 'Failed to Delete Product!', []);
        }
    }

}
