@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-white text-center">
                        <h3>Cập nhật cuộc hẹn #{{ $appointment->id }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Chọn khách hàng --}}
                            <div class="mb-3">
                                <label class="form-label">Khách hàng</label>
                                <select name="user_id" class="form-control" id="user-select" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $appointment->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Chọn thú cưng --}}
                            <div class="mb-3">
                                <label class="form-label">Thú cưng</label>
                                <select name="pet_id" class="form-control" id="pet-select" required>
                                    <option value="">-- Chọn thú cưng --</option>
                                    @foreach($pets as $pet)
                                        <option value="{{ $pet->id }}" {{ $appointment->pet_id == $pet->id ? 'selected' : '' }}>
                                            {{ $pet->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Ngày hẹn --}}
                            <div class="mb-3">
                                <label class="form-label">Ngày hẹn</label>
                                <input type="datetime-local" name="date" class="form-control"
                                       value="{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d\TH:i') }}" required>
                            </div>

                            {{-- Trạng thái --}}
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $appointment->status == 1 ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="2" {{ $appointment->status == 2 ? 'selected' : '' }}>Đã liên hệ</option>
                                    <option value="3" {{ $appointment->status == 3 ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="4" {{ $appointment->status == 4 ? 'selected' : '' }}>Hoàn thành</option>
                                </select>
                            </div>

                            {{-- Chọn dịch vụ --}}
                            <h4>Chọn dịch vụ</h4>
                            <div id="service-list">
                                @foreach($appointment->services as $index => $item)
                                    <div class="row align-items-center service-item mb-2">
                                        <div class="col-md-4">
                                            <select name="services[{{ $index }}][id]" class="form-control service-select" required>
                                                <option value="">Chọn dịch vụ</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                                                            {{ $item->service_id == $service->id ? 'selected' : '' }}>
                                                        {{ $service->name }} - {{ number_format($service->price) }} VNĐ
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="services[{{ $index }}][quantity]" class="form-control quantity-input"
                                                   placeholder="Số lượng" min="1" value="{{ $item->quantity }}">
                                        </div>
                                        <div class="col-md-3">
                                            <span class="total-price">{{ number_format($item->service->price * $item->quantity) }} VNĐ</span>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-service">Xóa</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success" id="add-service">Thêm dịch vụ</button>
                            </div>

                            <div class="text-center mt-4">
                                <h4>Tổng giá trị cuộc hẹn: <span id="total-appointment-price">0 VNĐ</span></h4>
                            </div>

                            {{-- Ghi chú --}}
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="note" class="form-control" rows="3">{{ $appointment->note }}</textarea>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-warning">Cập nhật</button>
                                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let userSelect = document.getElementById("user-select");
            let petSelect = document.getElementById("pet-select");

            function loadPets(userId) {
                fetch(`/admin/get-pets-by-user/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        petSelect.innerHTML = '<option value="">-- Chọn thú cưng --</option>';
                        data.forEach(pet => {
                            petSelect.innerHTML += `<option value="${pet.id}">${pet.name}</option>`;
                        });

                        // Giữ lại thú cưng đã chọn
                        let selectedPetId = "{{ $appointment->pet_id }}";
                        if (selectedPetId) {
                            petSelect.value = selectedPetId;
                        }
                    })
                    .catch(error => console.error("Lỗi khi lấy danh sách thú cưng:", error));
            }

            // Khi chọn khách hàng, load danh sách thú cưng
            userSelect.addEventListener("change", function () {
                let userId = this.value;
                loadPets(userId);
            });

            // Khi trang tải xong, nếu có user, tự động tải thú cưng
            if (userSelect.value) {
                loadPets(userSelect.value);
            }
        });
    </script>
@endsection
