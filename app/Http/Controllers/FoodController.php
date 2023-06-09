<?php

namespace App\Http\Controllers;

use App\Models\food;
use Illuminate\Http\Request;
use App\Http\Requests\FoodRequest;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $food = food::paginate(10);

        return view('food.index',[
            'food' => $food
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('food.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FoodRequest $request)
    {
        $data = $request->all();

        $data['picturespath'] = $request->file('picturespath')->store('assets/food','public');

        food::create($data);

        return redirect()->route('food.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(food $food)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(food $food)
    {
        return view('food.edit',[
            'item' => $food
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FoodRequest $request, food $food)
    {
        $data = $request->all();

        if($request->file('picturespath'))
        {
            $data['picturespath'] = $request->file('picturespath')->store('assets/food', 'public');
        }

        $food->update($data);

        return redirect()->route('food.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(food $food)
    {
        $food->delete();

        return redirect()->route('food.index');
    }
}
