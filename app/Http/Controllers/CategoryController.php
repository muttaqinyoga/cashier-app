<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories/index');
    }
    public function getListCategory()
    {
        try {
            $categories = Category::orderBy('name')->get();
            return response()->json(['status' => 'success', 'data' => $categories], 200);
        } catch (QueryException $e) {
            return response()->json(['status' => 'failed', 'message' => 'Something Went Wrong in the server'], 500);
        }
    }

    public function save(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'category_name' => 'required|string|min:3|max:50',
            'category_image' => 'required|image|mimes:jpeg,png,jpg|max:100'
        ]);
        if ($validation->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validation->errors()], 400);
        }
        return response()->json(['data' => $request->all()]);
    }
}
