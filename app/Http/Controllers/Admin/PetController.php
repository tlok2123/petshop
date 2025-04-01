<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PetRequest;
use App\Models\Pet;
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

    public function store(PetRequest $request)
    {
        Pet::create($request->validated());

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

    public function update(PetRequest $request, Pet $pet)
    {
        $pet->update($request->validated());

        return redirect()->route('admin.pets.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return redirect()->route('admin.pets.index')->with('success', 'Xóa thành công');
    }
}
