<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Packages;

class PackagesController extends Controller
{
    /**
     * Display a listing of the packages.
     */
    public function index()
    {
        $packages = Packages::latest()->get();
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        Packages::create($request->all());

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit($id)
    {
        $package = Packages::findOrFail($id);
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $package = Packages::findOrFail($id);
        $package->update($request->all());

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy($id)
    {
        $package = Packages::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully.');
    }

}
