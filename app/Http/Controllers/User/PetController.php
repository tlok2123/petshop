<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PetRequest;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class PetController extends Controller
{
    public function index(): JsonResponse
    {
        $pets = Pet::where('user_id', Auth::id())->get();
        return Helper::apiResponse(200, 'Lấy danh sách thú cưng thành công', ['pets' => $pets]);
    }

    public function store(PetRequest $request): JsonResponse
    {
        $data = $request->validated();
        $pet = Pet::create(array_merge($data, ['user_id' => Auth::id()]));
        return Helper::apiResponse(201, 'Thêm thú cưng thành công', ['pet' => $pet]);
    }

    public function show(Pet $pet): JsonResponse
    {
        if ($pet->user_id !== Auth::id()) {
            return Helper::apiResponse(403, 'Không tìm thấy người dùng');
        }
        return Helper::apiResponse(200, 'Lấy thông tin thú cưng thành công', ['pet' => $pet]);
    }

    public function update(PetRequest $request, Pet $pet): JsonResponse
    {
        if ($pet->user_id !== Auth::id()) {
            return Helper::apiResponse(403, 'Không tìm thấy người dùng');
        }
        $data = $request->validated();
        $pet->update($data);
        return Helper::apiResponse(200, 'Cập nhật thú cưng thành công', ['pet' => $pet]);
    }

    public function destroy(Pet $pet): JsonResponse
    {
        if ($pet->user_id !== Auth::id()) {
            return Helper::apiResponse(403, 'Không tìm thấy người dùng');
        }
        $pet->delete();
        return Helper::apiResponse(200, 'Xóa thú cưng thành công');
    }
}
