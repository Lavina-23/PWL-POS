<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //
    public function foodBaverage()
    {
        return view("category/food-baverage");
    }

    public function babyKids()
    {
        return view("category/baby-kid");
    }

    public function beautyHealth()
    {
        return view("category/beauty-health");
    }

    public function homeCare()
    {
        return view("category/home-care");
    }
}
