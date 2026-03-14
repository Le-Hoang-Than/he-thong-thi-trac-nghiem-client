# Hệ Thống Thi Trắc Nghiệm - Frontend Documentation

## 📋 Mục Lục
- [Giới Thiệu](#giới-thiệu)
- [Cấu Trúc Project](#cấu-trúc-project)
- [Cài Đặt](#cài-đặt)
- [Chạy Project](#chạy-project)
- [Build Production](#build-production)
- [Các Component Chính](#các-component-chính)
- [Routing](#routing)
- [API Integration](#api-integration)

## 🎯 Giới Thiệu

Frontend của hệ thống thi trắc nghiệm được xây dựng bằng:
- **React 18** - UI Framework
- **React Router** - Routing
- **Bootstrap 5** - UI Components & Styling
- **Vite** - Build Tool
- **Laravel** - Backend Server

## 📁 Cấu Trúc Project

```
resources/
├── js/
│   ├── app.jsx                 # Entry point React
│   ├── bootstrap.js            # Bootstrap configuration
│   └── components/
│       ├── Header.jsx          # Header navigation
│       ├── Footer.jsx          # Footer component
│       ├── Layout.jsx          # Main layout wrapper
│       ├── Login.jsx           # Login form
│       ├── Register.jsx        # Register form
│       └── Dashboard.jsx       # User dashboard
├── css/
│   └── app.css                 # Global styles
└── views/
    └── app.blade.php           # Laravel blade template (React root)
```

## 🚀 Cài Đặt

### 1. Cài đặt dependencies

```bash
cd he-thong-thi-trac-nghiem-client
npm install
```

### 2. Tạo file .env (nếu cần)

```bash
cp .env.example .env
```

## ▶️ Chạy Project

### Chế độ Development

```bash
npm run dev
```

Ứng dụng sẽ chạy tại `http://localhost:5173`

### Chế độ Production

```bash
npm run build
```

Build files sẽ được output vào `public/` folder.

## 🏗️ Các Component Chính

### Header Component
- Navigation bar với links
- Responsive menu
- Hiển thị trạng thái đăng nhập
- Nút Đăng xuất

**Props:** None
**State:** `expanded` - để quản lý mobile menu

### Footer Component
- Thông tin liên hệ
- Links nhanh
- Copyright

**Props:** None
**State:** None

### Layout Component
- Wrapper cho toàn bộ ứng dụng
- Hiển thị Dashboard nếu đã đăng nhập
- Hiển thị Welcome page nếu chưa đăng nhập

**Props:** None
**State:** None

### Login Component
- Form đăng nhập
- Validation fields
- Loading state
- Error handling

**Form Fields:**
- Email
- Password

### Register Component
- Form đăng ký
- Validation fields
- Password strength check
- Terms agreement

**Form Fields:**
- Full Name
- Student ID
- Email
- Password
- Confirm Password
- Terms Agreement

### Dashboard Component
- Stats cards (Exams, Results, Average Score)
- Recent activity table
- Links to other sections

## 🛣️ Routing

```
/              - Home/Dashboard (protected)
/login         - Login page
/register      - Register page
/dashboard     - User dashboard
/exams         - List of exams
/results       - Exam results
/profile       - User profile
```

## 🔌 API Integration

### Authentication API Endpoints

```
POST /api/register    - Register new user
POST /api/login       - User login
POST /api/logout      - User logout
```

### Example API Call

```javascript
const response = await axios.post('/api/login', {
  email: 'user@example.com',
  password: 'password123'
});

// Store token
localStorage.setItem('token', response.data.token);
```

## 🔐 Local Storage Keys

```
token        - JWT token
userName     - User full name
userEmail    - User email
studentId    - Student ID
```

## 🎨 Styling

- **Framework:** Bootstrap 5
- **Custom CSS:** `resources/css/app.css`
- **Theme Colors:**
  - Primary: #0d6efd (Blue)
  - Secondary: #6c757d (Gray)
  - Success: #198754 (Green)
  - Danger: #dc3545 (Red)

## 📱 Responsive Design

- Mobile-first approach
- Breakpoints: xs, sm (576px), md (768px), lg (992px), xl (1200px)
- Bootstrap grid system

## 🔧 Development Tips

### Adding New Components

1. Create component file in `resources/js/components/`
2. Import in relevant parent component
3. Use React Router for page navigation
4. Use localStorage for auth state

### Adding New Routes

Edit `resources/js/app.jsx` in the Routes section:

```javascript
<Route path="/new-page" element={<NewComponent />} />
```

### Adding Styles

Add to `resources/css/app.css` or use Bootstrap classes in JSX.

## 🚨 Common Issues & Solutions

### Issue: "Cannot find module 'react'"
**Solution:** Run `npm install`

### Issue: Form validation not working
**Solution:** Check that form field names match validation rules in component

### Issue: Token expires
**Solution:** Implement refresh token logic in axios interceptors

## 📚 Dependencies

- react: ^18.2.0
- react-dom: ^18.2.0
- react-router-dom: ^6.20.0
- bootstrap: ^5.3.0
- axios: ^1.11.0
- @vitejs/plugin-react: ^4.2.0
- vite: ^7.0.7

## 🔗 Useful Links

- [React Documentation](https://react.dev)
- [React Router Documentation](https://reactrouter.com)
- [Bootstrap Documentation](https://getbootstrap.com)
- [Vite Documentation](https://vitejs.dev)

## 👨‍💻 Development Workflow

1. Create feature branch: `git checkout -b feature/feature-name`
2. Make changes
3. Test locally: `npm run dev`
4. Build: `npm run build`
5. Commit and push
6. Create pull request

## 📝 Notes

- Form validation is client-side only. Always validate on server-side as well.
- Authentication tokens are stored in localStorage (not secure for sensitive data)
- Consider using httpOnly cookies for production
- Implement CSRF protection for API calls

---

**Last Updated:** March 2026
**Version:** 1.0.0
