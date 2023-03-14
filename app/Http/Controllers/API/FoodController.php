<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Food;

class FoodController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $types = $request->input('types');

        $price_form = $request->input('price_from');
        $price_to = $request->input('price_to');

        $rate_form = $request->input('rate_from');
        $rate_to = $request->input('rate_to');

        if ($id) 
        {
            $food = Food::find($id);
            if ($food) {
                return ResponseFormatter::success($food, 'Data produk berhasil diambil');
            }else{
                return ResponseFormatter::error($food,'Data produk tidak ada',404);
            }
        }

        $food = Food::query();
        if ($name) {
            $food->where('name','like','%' . $name . '%');
        }
        if ($types) {
            $food->where('types','like','%' . $types . '%');
        }
        if ($price_form) {
            $food->where('price_form','like','%' . $price_form . '%');
        }
        if ($price_to) {
            $food->where('price_to','like','>=' , $price_to);
        }
        if ($price_form) {
            $price_form->where('price_form','<=', $price_form);
        }
        if ($rate_form) {
            $food->where('rate_form','>=',$rate_form );
        }
        if ($rate_to) {
            $food->where('rate_to','<=', $rate_to);
        }

        return ResponseFormatter::success(
            $food->paginate($limit), 'Data list produk berhasil diambil');
    }
}
