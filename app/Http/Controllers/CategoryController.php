<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Company;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\PayUService\Exception;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = $this->filter($request)->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Filter function
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $query = Category::where('company_id', session('company_id'));
        if ($request->name)
            $query->where('name', 'like', $request->name.'%');
        if ($request->type)
            $query->where('type', 'like', $request->type);
        if (isset($request->enabled))
            $query->where('enabled', $request->enabled);
        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = [
            'expense' => 'Expenses',
            'income' => 'Incomes',
            'item' => 'Items',
            'other' => 'Others'
        ];
        return view('categories.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:categories,name',
            'type' => 'required',
            'color' => 'required',
            'enabled' => 'required',
        ]);
        $data = $request->only(['name','type','color','enabled']);
        $data['company_id'] = session('company_id');
        Category::create($data);
        return redirect()->route('category.index')->with('success', trans('category Create Successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $types = [
            'expense' => 'Expenses',
            'income' => 'Incomes',
            'item' => 'Items',
            'other' => 'Others'
        ];
        return view('categories.edit', compact('category','types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:categories,name,'.$category->id,
            'type' => 'required',
            'color' => 'required',
            'enabled' => 'required',
        ]);
        $data = $request->only(['name', 'type', 'color', 'enabled']);
        $category->update($data);
        return redirect()->route('category.index')->with('success', trans('Category Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('success', trans('Category Deleted Successfully'));
    }
}
