<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PetRequest;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /**
     * Display a listing of the pets.
     */
    public function index(): JsonResponse
    {
        return response()->json(Pet::where('user_id', Auth::id())->get());
    }

    /**
     * Store a newly created pet in storage.
     */
    public function store(PetRequest $request): JsonResponse
    {
        $data = $request->validated();

        $pet = Pet::create(array_merge($data, ['user_id' => Auth::id()]));

        return response()->json([
            'status' => '201',
            'pet' => $pet,
        ], 201);
    }

    /**
     * Display the specified pet.
     */
    public function show(Pet $pet): JsonResponse
    {
        if ($pet->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'message' => 'Không tìm thấy người dùng'
            ], 403);
        }
        return response()->json([
            'status' => 200,
            'pet' => $pet
        ]);
    }

    /**
     * Update the specified pet in storage.
     */
    public function update(PetRequest $request, Pet $pet): JsonResponse
    {
        if ($pet->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'message' => 'Không tìm thấy người dùng'
            ], 403);
        }

        $data = $request->validated();
        $pet->update($data);

        return response()->json([
            'status' => 200,
            'pet' => $pet
        ]);
    }

    /**
     * Remove the specified pet from storage.
     */
    public function destroy(Pet $pet): JsonResponse
    {
        if ($pet->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'message' => 'Không tìm thấy người dùng'
            ], 403);
        }

        $pet->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Xóa thú cưng thành công'
        ]);
    }
}
