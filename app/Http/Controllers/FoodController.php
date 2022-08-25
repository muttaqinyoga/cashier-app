<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use Illuminate\Database\QueryException;

class FoodController extends Controller
{
    public function index()
    {
        return view('food/index');
    }
    public function getListFood()
    {
        // try {
        //     // $foodList = Food::orderBy()
        // } catch(QueryException $ex){
        //     return res
        // }
    }
}
