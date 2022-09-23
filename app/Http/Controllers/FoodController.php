<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Food;

use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;

class FoodController extends Controller
{
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
        } catch (QueryException $e) {
            return response()->json(['status' => 'failed', 'message' => 'Failed to load data from server'], 500);
        }
    }
}
