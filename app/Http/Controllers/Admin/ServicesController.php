<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServicesController extends Controller
{
    /**
     * Hiển thị danh sách dịch vụ.
     */
    public function index()
    {
        $services = Service::orderBy('id', 'asc')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    /**
     * Hiển thị form tạo mới dịch vụ.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Lưu dịch vụ mới vào database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'type' => 'required|integer|in:' . implode(',', [
                    Service::TYPE_CARE,
                    Service::TYPE_EXAMINATION,
                    Service::TYPE_CONSIGNMENT
                ]),
        ]);


        Service::create($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được tạo.');
    }

    /**
     * Hiển thị chi tiết một dịch vụ.
     */
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    /**
     * Hiển thị form chỉnh sửa dịch vụ.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Cập nhật dịch vụ.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'type' => 'required|integer|in:' . implode(',', [
                    Service::TYPE_CARE,
                    Service::TYPE_EXAMINATION,
                    Service::TYPE_CONSIGNMENT
                ]),
        ]);


        $service->update($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được cập nhật.');
    }

    /**
     * Xóa dịch vụ.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được xóa.');
    }
}
