<?php

namespace App\Http\Controllers\Api;

use DB;

use App\Models\Image;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function index()
    {
        $image = Image::latest()->paginate(5);

        return new ApiResource(true, 'Image List', $image);
    }

    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'enable' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $file = $req->file('file');
            $file->storeAs('public/file', $file->hashName());
            
            $image = Image::create([
                'name' => $req->name,
                'file' => $file->hashName(),
                'enable' => $req->enable,
            ]);

            DB::commit();
            return new ApiResource(true, 'Image Added Successfully!', $image);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Add Image!', []);
        }
    }

    public function show($id_image)
    {
        $data = Image::where('id', $id_image)->first();
        $status = true;
        $message = 'Data Found!';

        if ($data == null) {
            $status = false;
            $message = 'Data not found!';
            $data = [];
        }

        return new ApiResource($status, $message, $data);
    }

    public function update(Request $req, $id_image)
    {
        DB::beginTransaction();
        try {
            $data = Image::where('id', $id_image)->first();
            $status = true;
            $message = 'Image Changed Successfully!';

            if(!empty($data)) {
                $validator = Validator::make($req->all(), [
                    'name' => 'required',
                    'enable' => 'required',
                ]);
        
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                if ($req->hasFile('file')) {
                    $file = $req->file('file');
                    $file->storeAs('public/file', $file->hashName());

                    Storage::delete('public/file/'.$data->file);
                    
                    Image::where('id', $id_image)->update([
                        'name' => $req->name,
                        'file' => $file->hashName(),
                        'enable' => $req->enable,
                    ]);
                } else {
                    Image::where('id', $id_image)->update([
                        'name' => $req->name,
                        'enable' => $req->enable,
                    ]);
                }
                
                $data = Image::where('id', $id_image)->first();
            } else {
                $status = false;
                $message = 'Data not found!';
                $data = [];
            }
            
            DB::commit();
            return new ApiResource($status, $message, $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Failed to Change Image!', []);
        }
    }

    public function destroy($id_image)
    {
        DB::beginTransaction();
        try {
            $data = Image::where('id', $id_image)->first();
            $status = true;
            $message = 'Image Deleted Successfully';

            if(!empty($data)) {
                Image::where('id', $id_image)->delete();
                Storage::delete('public/file/'.$data->file);
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
            return new ApiResource(false, 'Failed to Delete Image!', []);
        }
    }

}
