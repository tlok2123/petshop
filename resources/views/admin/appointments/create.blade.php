@extends('admin.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Tạo cuộc hẹn</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.appointments.store') }}" method="POST">
                            @csrf

                            {{-- Chọn khách hàng --}}
                            <div class="mb-3">
                                <label class="form-label">Khách hàng</label>
                                <select name="user_id" id="user-select" class="form-control" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Chọn thú cưng --}}
                            <div class="mb-3">
                                <label class="form-label">Thú cưng</label>
                                <select name="pet_id" id="pet-select" class="form-control" required>
                                    <option value="">-- Chọn thú cưng --</option>
                                </select>
                            </div>

                            {{-- Ngày hẹn --}}
                            <div class="mb-3">
                                <label class="form-label">Ngày hẹn</label>
                                <input type="datetime-local" name="date" class="form-control" required>
                            </div>

                            {{-- Trạng thái --}}
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="1">Đang xử lý</option>
                                    <option value="2">Đã liên hệ</option>
                                    <option value="3">Đã xác nhận</option>
                                    <option value="4">Hoàn thành</option>
                                </select>
                            </div>

                            {{-- Chọn dịch vụ --}}
                            <h4>Chọn dịch vụ</h4>
                            <div id="service-list">
                                <div class="row align-items-center service-item">
                                    <div class="col-md-4">
                                        <select name="services[0]" class="form-control service-select" required>
                                            <option value="">-- Chọn dịch vụ --</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                    {{ $service->name }} - {{ number_format($service->price) }} VNĐ
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control quantity-input" placeholder="Số lượng" min="1" value="1">
                                    </div>
                                    <div class="col-md-3">
                                        <span class="total-price">0 VNĐ</span>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-service">Xóa</button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success" id="add-service">Thêm dịch vụ</button>
                            </div>

                            <div class="text-center mt-4">
                                <h4>Tổng chi phí: <span id="total-appointment-price">0 VNĐ</span></h4>
                            </div>

                            {{-- Ghi chú --}}
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="note" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success">Tạo cuộc hẹn</button>
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
            let serviceIndex = 1;
            let userSelect = document.getElementById("user-select");
            let petSelect = document.getElementById("pet-select");

            function updateTotalPrice() {
                let total = 0;
                document.querySelectorAll(".service-item").forEach(function (item) {
                    let price = parseFloat(item.querySelector(".service-select").selectedOptions[0].dataset.price || 0);
                    let quantity = parseInt(item.querySelector(".quantity-input").value) || 1;
                    let itemTotal = price * quantity;
                    item.querySelector(".total-price").textContent = new Intl.NumberFormat().format(itemTotal) + " VNĐ";
                    total += itemTotal;
                });
                document.getElementById("total-appointment-price").textContent = new Intl.NumberFormat().format(total) + " VNĐ";
            }

            document.getElementById("add-service").addEventListener("click", function () {
                let serviceList = document.getElementById("service-list");
                let newService = document.createElement("div");
                newService.classList.add("row", "align-items-center", "service-item", "mt-2");
                newService.innerHTML = `
                    <div class="col-md-4">
                        <select name="services[\${serviceIndex}]" class="form-control service-select" required>
                            <option value="">-- Chọn dịch vụ --</option>
                            @foreach($services as $service)
                <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                    {{ $service->name }} - {{ number_format($service->price) }} VNĐ
                                </option>
                            @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control quantity-input" placeholder="Số lượng" min="1" value="1">
            </div>
            <div class="col-md-3">
                <span class="total-price">0 VNĐ</span>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-service">Xóa</button>
            </div>
`;
                serviceList.appendChild(newService);
                serviceIndex++;
            });

            document.getElementById("service-list").addEventListener("input", function (event) {
                if (event.target.classList.contains("service-select") || event.target.classList.contains("quantity-input")) {
                    updateTotalPrice();
                }
            });

            document.getElementById("service-list").addEventListener("click", function (event) {
                if (event.target.classList.contains("remove-service")) {
                    event.target.closest(".service-item").remove();
                    updateTotalPrice();
                }
            });

            function loadPets(userId) {
                fetch(`/admin/get-pets-by-user/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        petSelect.innerHTML = '<option value="">-- Chọn thú cưng --</option>';
                        data.forEach(pet => {
                            let option = document.createElement("option");
                            option.value = pet.id;
                            option.textContent = pet.name;
                            petSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error("Lỗi khi lấy danh sách thú cưng:", error));
            }

            userSelect.addEventListener("change", function () {
                let userId = this.value;
                loadPets(userId);
            });
        });
    </script>
@endsection
