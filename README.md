# he-thong-thi-trac-nghiem-client
Dự án này sử dụng `BASE_API` từ tệp `.env` để kết nối với service API.

---

# Laravel Client Setup Guide

## 1. Clone repository

```bash
git clone <repository-url>
cd <project-folder>
```

## 2. Install PHP dependencies (Composer)

```bash
composer install
```

## 3. Install Node dependencies

```bash
npm install
```

## 4. Create environment file

Sao chép tệp .env.example sang tệp .env

```bash
cp .env.example .env
```

## 5. Configure API Server

Project client sẽ gọi API từ project service. Mở file .env và cấu hình địa chỉ API:

```bash
BASE_API=http://127.0.0.1:8000/api
```

Đảm bảo project service đang chạy trước khi chạy client.

## 6. Generate application key

```bash
php artisan key:generate
```

## 7. Start the development client

```bash
php artisan serve --port=8001
```

Default URL: http://127.0.0.1:8001
