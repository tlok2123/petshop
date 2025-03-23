# Cài đặt Project Petshop Laravel 12

## 1. Cấu hình `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=petshop
DB_USERNAME=root
DB_PASSWORD=

APP_USER_URL="URL Front-End"
APP_URL="URL hiện tại chạy project"
```

## 2. Chạy các file cần thiết

### Chạy migrations để tự động thiết lập database
```sh
php artisan migrate
```

### Chạy lệnh sau để sinh JWT Secret key
```sh
php artisan jwt:secret
```

### Chạy lệnh sau để mở port
```sh
php artisan serve --host=0.0.0.0 --port=8000
```

## 3. Kiểm thử với Front-end hoặc Postman

Chúc các bạn thành công! Có khó khăn hãy liên hệ:

**Telegram:** [@lok2123](https://t.me/lok2123)

