# 🚀 Quick Start Guide - Frontend Setup

## 📦 Prerequisites

- Node.js (v16 or higher)
- npm or yarn
- PHP 8.0+ (for Laravel backend)

## ⚡ Quick Start (3 Steps)

### Step 1: Install Dependencies

```bash
cd he-thong-thi-trac-nghiem-client
npm install
```

### Step 2: Start Development Server

```bash
npm run dev
```

Ứng dụng sẽ mở tại: `http://localhost:5173`

### Step 3: Truy Cập Ứng Dụng

- Mở browser và đi tới `http://localhost:5173`
- "Đăng Ký" hoặc "Đăng Nhập"
- Thử nghiệm các tính năng

## 🏗️ Project Structure

```
resources/
├── js/
│   ├── app.jsx              ← React entry point
│   └── components/
│       ├── Header.jsx       ← Top navigation
│       ├── Footer.jsx       ← Bottom footer
│       ├── Layout.jsx       ← Main layout
│       ├── Login.jsx        ← Login page
│       ├── Register.jsx     ← Registration page
│       └── Dashboard.jsx    ← User dashboard
├── css/
│   └── app.css              ← Global styles
└── views/
    └── app.blade.php        ← Laravel view (React root)
```

## 🎨 Features Implemented

### ✅ Layout Components
- Standard Header with navigation
- Footer with contact info
- Responsive Layout wrapper

### ✅ Authentication
- Login Form with validation
- Register Form with validation
- Form state management
- Error handling

### ✅ Styling
- Bootstrap 5 integration
- Custom CSS styling
- Responsive design
- Mobile-friendly

## 🔐 Login Credentials (Demo)

For testing, use any email and password (minimum 6 characters):

```
Email: demo@example.com
Password: Password123
```

## 📋 Available Routes

```
/              Home/Dashboard (protected after login)
/login         Login page
/register      Registration page
/dashboard     User dashboard (after login)
```

## 🛠️ Building for Production

```bash
npm run build
```

Output will be in `public/` directory.

## 🐛 Troubleshooting

### Port 5173 already in use?
```bash
npm run dev -- --port 3000
```

### Cache issues?
```bash
rm -rf node_modules
npm install
npm run dev
```

### Changes not reflecting?
- Clear browser cache (Ctrl+Shift+Delete)
- Restart dev server
- Check console for errors (F12)

## 📱 Testing Login/Register

### Test Login:
1. Go to `/login`
2. Enter any email (format: email@domain.com)
3. Enter password (min 6 chars)
4. Click "Đăng Nhập"

### Test Register:
1. Go to `/register`
2. Fill all fields:
   - Full Name (3+ chars)
   - Student ID (8+ digits)
   - Email (valid format)
   - Password (6+ chars, 1 uppercase, 1 number)
   - Confirm Password
3. Accept terms & conditions
4. Click "Đăng Ký"

## 🔗 File Locations

| File | Purpose |
|------|---------|
| `resources/js/app.jsx` | React main entry |
| `resources/js/components/Login.jsx` | Login form |
| `resources/js/components/Register.jsx` | Registration form |
| `resources/js/components/Header.jsx` | Navigation header |
| `resources/js/components/Footer.jsx` | Footer |
| `resources/css/app.css` | Global styles |
| `vite.config.js` | Vite configuration |
| `package.json` | Dependencies |

## 🎯 Next Steps

1. ✅ Frontend layout setup (DONE)
2. ⏳ Connect to backend API
3. ⏳ User authentication
4. ⏳ Exam management
5. ⏳ Results & analytics

## 📞 Support

For issues or questions:
1. Check browser console (F12)
2. Review error messages
3. Check FRONTEND.md documentation
4. Restart dev server

---

**Happy Coding!** 🎉
