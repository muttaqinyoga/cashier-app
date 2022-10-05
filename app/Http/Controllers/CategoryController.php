<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Throwable;

class CategoryController extends Controller
{
    const DEFAULT_IMAGE_CATEGORY = 'category-foods.png';

    public function index()
    {
        return view('categories/index');
    }
    public function getListCategory()
    {
        try {
            $categories = Category::orderBy('name')->get();
            return response()->json(['status' => 'success', 'data' => $categories], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Something Went Wrong in the server'], 500);
        }
    }



    public function save(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'category_name' => 'required|string|min:3|max:50|unique:categories,name',
            'category_image' => 'image|mimes:jpeg,png,jpg|max:100'
        ]);
        if ($validation->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validation->errors()], 400);
        }
        try {
            $newCategory = new Category;
            $newCategory->id = Uuid::uuid4()->getHex();
            $newCategory->name = $request->category_name;
            $newCategory->slug = \Str::slug($request->category_name, '-');
            if ($request->file('category_image')) {
                $imageName = time() . $newCategory->slug . '.' . $request->file('category_image')->getClientOriginalExtension();
                $request->file('category_image')->move(public_path('/images/categories'), $imageName);
                $newCategory->image = $imageName;
            } else {
                $newCategory->image = self::DEFAULT_IMAGE_CATEGORY;
            }
            $newCategory->save();
            $payload = [
                'id' => $newCategory->id,
                'name' => $newCategory->name,
                'image' => $newCategory->image,
                'created_at' => $newCategory->created_at
            ];
            return response()->json(['status' => 'created', 'message' => 'New category added', 'data' => $payload], 201);
        } catch (Throwable $e) {
            return response()->json(['status' => 'failed', 'message' => 'Could not save input request'], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validation = \Validator::make($request->all(), [
                'category_edit_name' => 'required|string|min:3|max:50|unique:categories,name,' . $request->category_edit_id . ',id',
                'category_edit_image' => 'image|mimes:jpeg,png,jpg|max:100'
            ]);
            if ($validation->fails()) {
                return response()->json(['status' => 'failed', 'errors' => $validation->errors()], 400);
            }
            $category = Category::findOrFail($request->category_edit_id);
            $category->name = $request->category_edit_name;
            $category->slug = \Str::slug($request->category_edit_name, '-');
            if ($request->file('category_edit_image')) {
                if ($category->image != self::DEFAULT_IMAGE_CATEGORY) {
                    unlink(public_path('/images/categories/' . $category->image));
                }
                $imageName = time() . $category->slug . '.' . $request->file('category_edit_image')->getClientOriginalExtension();
                $request->file('category_edit_image')->move(public_path('/images/categories'), $imageName);
                $category->image = $imageName;
            }
            $category->update();
            $payload = [
                'id' => $category->id,
                'name' => $category->name,
                'image' => $category->image,
                'updated_at' => $category->updated_at
            ];
            return response()->json(['status' => 'success', 'message' => 'Category data has beens updated', 'data' => $payload], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'failed', 'message' => 'Could not update requested data'], 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            $category = Category::findOrFail($request->category_delete_id);
            $category->delete();
            return response()->json(['status' => 'success', 'message' => "$category->name has been deleted"]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'failed', 'message' => 'Could not delete requested data'], 400);
        } catch (QueryException $e) {
            return response()->json(['status' => 'failed', 'message' => 'Could not delete because this category had references to other data'], 500);
        }
    }
}
