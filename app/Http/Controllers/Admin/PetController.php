<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use App\Models\User;


class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with('user')->paginate(10);
        return view('admin.pets.index', compact('pets'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.pets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'health_status' => 'required|string|max:255',
            'boarding_expiry' => 'nullable|date',
        ]);

        Pet::create($request->all());

        return redirect()->route('admin.pets.index')->with('success', 'Thêm thú cưng thành công');
    }

    public function edit($id)
    {
        $pet = Pet::findOrFail($id);
        $users = User::all();
        return view('admin.pets.edit', compact('pet', 'users'));
    }

    public function show($id)
    {
        $pet = Pet::findOrFail($id);
        return view('admin.pets.show', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'health_status' => 'required|string|max:255',
            'boarding_expiry' => 'nullable|date',
        ]);

        $pet->update($request->all());

        return redirect()->route('admin.pets.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return redirect()->route('admin.pets.index')->with('success', 'Xóa thành công');
    }
}
