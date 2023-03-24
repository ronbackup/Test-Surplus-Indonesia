<?php

namespace App\Http\Controllers\Api;

use DB;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::latest()->paginate(5);

        return new ApiResource(true, 'Category List', $category);
    }

    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required',
                'enable' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $category = Category::create([
                'name' => $req->name,
                'enable' => $req->enable,
            ]);

            DB::commit();
            return new ApiResource(true, 'Category Added Successfully!', $category);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Add Category!', []);
        }
    }

    public function show($id_category)
    {
        $data = Category::where('id', $id_category)->first();
        $status = true;
        $message = 'Data Found!';

        if ($data == null) {
            $status = false;
            $message = 'Data not found!';
            $data = [];
        }

        return new ApiResource($status, $message, $data);
    }

    public function update(Request $req, $id_category)
    {
        DB::beginTransaction();
        try {
            $data = Category::where('id', $id_category)->first();
            $status = true;
            $message = 'Category Changed Successfully!';

            if(!empty($data)) {
                $validator = Validator::make($req->all(), [
                    'name' => 'required',
                    'enable' => 'required',
                ]);
        
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                
                Category::where('id', $id_category)->update([
                    'name' => $req->name,
                    'enable' => $req->enable,
                ]);
                
                $data = Category::where('id', $id_category)->first();
            } else {
                $status = false;
                $message = 'Data not found!';
                $data = [];
            }
            
            DB::commit();
            return new ApiResource($status, $message, $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Change Category!', []);
        }
    }

    public function destroy($id_category)
    {
        DB::beginTransaction();
        try {
            $data = Category::where('id', $id_category)->first();
            $status = true;
            $message = 'Category Deleted Successfully';

            if(!empty($data)) {
                Category::where('id', $id_category)->delete();
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
            return new ApiResource(false, 'Failed to Delete Category!', []);
        }
    }

}
