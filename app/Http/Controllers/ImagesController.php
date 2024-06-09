<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImagesController extends Controller
{
    public function index()
    {
        $images = Image::all();

        return response()->json($images);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'nullable|exists:products,id',
            'file_name' => 'required|string|max:255',
            'file_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->hasFile('file_path')) {
            // Get the uploaded file
            $file = $request->file('file_path');

            // Generate a unique file name
            $fileName = time().'_'.$file->getClientOriginalName();

            // Move the file to the desired location (e.g., storage/app/public/images)
            $filePath = $file->storeAs('images', $fileName, 'public');

            // Save the image information in the database
            $image = Image::create([
                'product_id' => $request->input('product_id'),
                'file_name' => $fileName,
                'file_path' => '/storage/'.$filePath,
            ]);

            return response()->json($image, 201);
        } else {
            return response()->json(['file' => 'No file uploaded'], 400);
        }
    }

    public function update(Request $request, Image $image)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'nullable|exists:products,id',
            'file_name' => 'required|string|max:255',
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = $request->only(['product_id', 'file_name']);

        if ($request->hasFile('file')) {
            // Delete the old file from storage
            if ($image->file_path) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $image->file_path));
            }

            // Get the new file
            $file = $request->file('file');

            // Generate a unique file name
            $fileName = time().'_'.$file->getClientOriginalName();

            // Move the file to the desired location (e.g., storage/app/public/images)
            $filePath = $file->storeAs('images', $fileName, 'public');

            // Update the file path in the database
            $data['file_path'] = 'storage/'.$filePath;
            $data['file_name'] = $fileName;
        }

        // Update the image information in the database
        $image->update($data);

        return response()->json($image);
    }

    public function destroy($id)
    {
        $image = Image::findOrfail($id);
        $image->delete();

        return response()->json('Successfully Deleted', 201);
    }
}
