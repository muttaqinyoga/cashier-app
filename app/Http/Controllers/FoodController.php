<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Food;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Throwable;

class FoodController extends Controller
{
    const DEFAULT_IMAGE_FOOD = 'food-placeholder.jpeg';
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('food/index', compact('categories'));
    }
    public function getListFood()
    {
        try {
            $foodList = Food::with(['categories'])->get();
            return response()->json(['status' => 'success', 'data' => $foodList]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Failed to load data from server'], 500);
        }
    }

    public function save(Request $request)
    {

        $validation = \Validator::make($request->all(), [
            'food_name' => 'required|string|min:3|max:50|unique:foods,name',
            'food_image' => 'image|mimes:jpeg,png,jpg|max:100',
            'food_price' => 'required|numeric|min:0',
            'food_description' => 'max:100',
            'food_categories' => 'required'
        ]);
        if ($validation->fails()) {
            return response()->json(['status' => 'failed', 'errors' => $validation->errors()], 400);
        }
        // validate categories
        $reqCategories = explode(",", $request->food_categories);
        try {
            foreach ($reqCategories as $c) {
                $validCategories = DB::table('categories')->select('id')->where('id', '=', $c)->get();
                if ($validCategories->isEmpty()) {
                    return response()->json(['status' => 'failed', 'errors' => ['food_categories' => ['The food categories does not exists']]], 400);
                }
            }
        } catch (QueryException $e) {
            return response()->json(['status' => 'failed', 'errors' => ['food_categories' => ['Could not get categories']]], 400);
        }
        // Save
        DB::beginTransaction();

        try {
            $newFood = new Food;
            $newFood->id = Uuid::uuid4()->getHex();
            $newFood->name = $request->food_name;
            $newFood->price = $request->food_price;
            $newFood->description = $request->food_description;
            if ($request->file('food_image')) {
                $imageName = time() . $newFood->name . '.' . $request->file('food_image')->getClientOriginalExtension();
                $request->file('food_image')->move(public_path('/images/foods'), $imageName);
                $newFood->image = $imageName;
            } else {
                $newFood->image = self::DEFAULT_IMAGE_FOOD;
            }
            $newFood->status_stock = 'Tersedia';
            $newFood->save();
            $newFood->categories()->attach($reqCategories);
            $payload = [
                'id' => $newFood->id,
                'name' => $newFood->name,
                'price' => $newFood->price,
                'image' => $newFood->image,
                'status_stock' => $newFood->status_stock,
                'description' => $newFood->description,
                'created_at' => $newFood->created_at,
                'categories' => $newFood->categories()->get()
            ];
            DB::commit();
            return response()->json(['status' => 'created', 'message' => 'New food added', 'data' => $payload], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 500);
        }
    }
}
