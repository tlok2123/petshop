<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::all();
        return view('admin.pets.index', compact('pets'));
    }

    public function create()
    {
        return view('admin.pets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'health_status' => 'required|string|max:255',
        ]);

        Pet::create($request->all());

        return redirect()->route('admin.pet.index')->with('success', 'Thêm thú cưng thành công');
    }

    public function edit(Pet $pet)
    {
        return view('admin.pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'health_status' => 'required|string|max:255',
        ]);

        $pet->update($request->all());

        return redirect()->route('admin.pet.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return redirect()->route('admin.pet.index')->with('success', 'Xóa thành công');
    }
}
