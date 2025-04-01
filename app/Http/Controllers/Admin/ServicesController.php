<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('id', 'asc')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(ServiceRequest $request)
    {
        Service::create($request->validated());

        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được tạo.');
    }

    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được cập nhật.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được xóa.');
    }
}
