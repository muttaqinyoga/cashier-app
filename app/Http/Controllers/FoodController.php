<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;

class FoodController extends Controller
{
    public function index()
    {
        return view('food/index');
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
