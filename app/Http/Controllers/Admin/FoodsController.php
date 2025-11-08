<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Foods;
use App\Models\FoodCategory;
use Illuminate\Http\Request;

class FoodsController extends Controller
{
    public function index()
    {
        $foods = Foods::with('category')->paginate(10);
        return view('admin.foods.index', compact('foods'));
    }

    public function create()
    {
        $categories = FoodCategory::all();
        return view('admin.foods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'food_category_id' => 'required|exists:food_categories,id',
        ]);

        Foods::create($request->all());

        return redirect()->route('admin.foods.index')->with('success', 'Food item created successfully.');
    }

    public function show(Foods $food)
    {
        return view('admin.foods.show', compact('food'));
    }

    public function edit(Foods $food)
    {
        $categories = FoodCategory::all();
        return view('admin.foods.edit', compact('food', 'categories'));
    }

    public function update(Request $request, Foods $food)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'food_category_id' => 'required|exists:food_categories,id',
        ]);

        $food->update($request->all());

        return redirect()->route('admin.foods.index')->with('success', 'Food item updated successfully.');
    }

    public function destroy(Foods $food)
    {
        $food->delete();

        return redirect()->route('admin.foods.index')->with('success', 'Food item deleted successfully.');
    }
}
