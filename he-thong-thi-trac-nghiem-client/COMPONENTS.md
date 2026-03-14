# 🧩 Components Reference Guide

## Overview
This document describes each React component used in the application.

---

## 📌 Header Component

**File:** `resources/js/components/Header.jsx`

**Purpose:** Display navigation bar and user authentication status

### Features
- Responsive navbar
- Navigate between pages
- Show user name when logged in
- Logout button
- Mobile menu toggle

### Props
None

### State
```javascript
expanded: boolean  // Mobile menu open/close
```

### Key Functions
- `handleLogout()` - Clear localStorage and redirect to login
- Uses React Router `<Link>` for navigation
- Checks `localStorage.getItem('token')` for auth status

### Usage
```jsx
import Header from './components/Header';

<Header />
```

### Styling
- Bootstrap Navbar component
- Primary blue background color
- White text

---

## 📌 Footer Component

**File:** `resources/js/components/Footer.jsx`

**Purpose:** Display footer with contact info and links

### Features
- 3-column layout (About, Links, Contact)
- Current year display
- Responsive grid
- Dark background

### Props
None

### State
None (uses current year)

### Usage
```jsx
import Footer from './components/Footer';

<Footer />
```

### Content Sections
1. **About** - Brief description
2. **Quick Links** - Navigation links
3. **Contact Info** - Email, phone, address

---

## 📌 Layout Component

**File:** `resources/js/components/Layout.jsx`

**Purpose:** Main layout wrapper for authenticated pages

### Features
- Wraps Header and Footer
- Shows Dashboard when logged in
- Shows Welcome page when not logged in
- Min-height 100vh layout

### Props
None

### State
None

### Conditional Rendering
```javascript
if (localStorage.getItem('token')) {
  // Show Dashboard
} else {
  // Show Welcome message
}
```

### Usage
```jsx
<Route path="/*" element={<Layout />} />
```

---

## 📌 Login Component

**File:** `resources/js/components/Login.jsx`

**Purpose:** Handle user login with form validation

### Form Fields
| Field | Type | Validation | Required |
|-------|------|-----------|----------|
| email | text | Email format | Yes |
| password | password | Min 6 chars | Yes |
| remember me | checkbox | - | No |

### Features
- Real-time validation
- Loading spinner during submission
- Error message display
- Success message
- Link to register page
- Forgot password link

### Props
None

### State
```javascript
formData: {
  email: string,
  password: string
}
errors: object          // Field errors
loading: boolean        // Loading state
generalError: string    // General error message
successMessage: string  // Success message
```

### Validation Rules
- **Email:** Must be valid email format
- **Password:** Minimum 6 characters

### Functions
- `handleChange()` - Update form field
- `validateForm()` - Validate all fields
- `handleSubmit()` - Submit form and authenticate
- `handleChange()` - Clear errors on input

### Usage
```jsx
<Route path="/login" element={<Login />} />
```

### API Integration
```javascript
POST /api/login
{
  email: "user@example.com",
  password: "password123"
}

Response:
{
  token: "jwt-token",
  user: {
    id: 1,
    name: "User Name",
    email: "user@example.com"
  }
}
```

---

## 📌 Register Component

**File:** `resources/js/components/Register.jsx`

**Purpose:** Handle user registration with validation

### Form Fields
| Field | Type | Validation | Required |
|-------|------|-----------|----------|
| fullName | text | 3+ chars | Yes |
| studentId | text | 8+ digits | Yes |
| email | text | Valid email | Yes |
| password | password | 6+ chars, uppercase, number | Yes |
| confirmPassword | password | Matches password | Yes |
| agreeTerms | checkbox | Must check | Yes |

### Features
- Comprehensive field validation
- Password strength requirements
- Confirm password matching
- Terms agreement checkbox
- Loading spinner
- Error handling
- Success message
- Smart error clearing

### Props
None

### State
```javascript
formData: {
  fullName: string,
  studentId: string,
  email: string,
  password: string,
  confirmPassword: string,
  agreeTerms: boolean
}
errors: object          // Field-specific errors
loading: boolean        // Submission loading state
generalError: string    // General error
successMessage: string  // Success message
```

### Validation Rules
- **Full Name:** Min 3 characters
- **Student ID:** 8+ digits only
- **Email:** Valid email format
- **Password:**
  - Minimum 6 characters
  - At least 1 uppercase letter
  - At least 1 number
- **Confirm Password:** Must match password
- **Terms:** Must be checked

### Functions
- `handleChange()` - Update form or checkbox
- `validateForm()` - Validate all fields
- `handleSubmit()` - Process registration

### Usage
```jsx
<Route path="/register" element={<Register />} />
```

### API Integration
```javascript
POST /api/register
{
  fullName: "Student Name",
  studentId: "12345678",
  email: "student@example.com",
  password: "Password123",
  confirmPassword: "Password123"
}

Response:
{
  token: "jwt-token",
  user: {
    id: 1,
    name: "Student Name",
    email: "student@example.com",
    studentId: "12345678"
  }
}
```

---

## 📌 Dashboard Component

**File:** `resources/js/components/Dashboard.jsx`

**Purpose:** Display user dashboard with stats and activity

### Features
- Welcome message
- Stats cards (4 columns)
- Recent activity table
- Quick action buttons
- Responsive grid layout

### Props
None

### State
None

### Display Data
```javascript
{
  userName: from localStorage,
  studentId: from localStorage,
  stats: {
    upcomingExams: 3,
    completedExams: 5,
    averageScore: 8.5
  }
}
```

### Card Components
1. **Upcoming Exams** - Count of exams
2. **Test Results** - Completed exams count
3. **Average Score** - Overall average
4. **Profile** - Edit profile button

### Table Columns
| Column | Data | Type |
|--------|------|------|
| Kỳ Thi | Test name | text |
| Ngày | Test date | date |
| Kết Quả | Score | number |
| Trạng Thái | Status | badge |

### Usage
```jsx
{isLoggedIn ? <Dashboard /> : <WelcomeMessage />}
```

---

## 📌 App Component

**File:** `resources/js/app.jsx`

**Purpose:** Root component with routing

### Features
- React Router setup
- Route definitions
- Bootstrap CSS import
- Global CSS import

### Routes
```javascript
POST /login         → <Login />
POST /register      → <Register />
/*                  → <Layout />
```

### Bootstrap Integration
```javascript
import 'bootstrap/dist/css/bootstrap.min.css';
```

### Usage
```jsx
ReactDOM.createRoot(document.getElementById('app')).render(<App />);
```

---

## 🔄 Component Hierarchy

```
App
├── Router
│   └── Routes
│       ├── /login → Login
│       ├── /register → Register
│       └── /* → Layout
│           ├── Header
│           ├── Main Content
│           │   ├── Dashboard (if logged in)
│           │   └── Welcome (if not logged in)
│           └── Footer
```

---

## 🎨 Common Bootstrap Classes Used

- `.container` - Max-width container
- `.row` / `.col` - Grid layout
- `.card` - Card component
- `.form-control` - Input fields
- `.btn` - Buttons
- `.alert` - Alert messages
- `.badge` - Status badges
- `.table` - Data tables
- `.spinner-border` - Loading spinner
- `.text-center` - Center text
- `.fw-bold` - Bold text
- `.text-muted` - Gray text
- `.mb-*` / `.mt-*` - Margins
- `.p-*` - Padding

---

## 🔐 Local Storage Usage

Components use localStorage for:

```javascript
localStorage.setItem('token', jwtToken)              // Auth token
localStorage.setItem('userName', 'User Name')       // User name
localStorage.setItem('userEmail', 'email@ex.com')   // Email
localStorage.setItem('studentId', '12345678')       // Student ID

// Check if logged in
const isLoggedIn = !!localStorage.getItem('token');
```

---

## 💡 Best Practices

1. **Always validate** form input on client AND server
2. **Clear errors** when user starts typing
3. **Show loading states** during API calls
4. **Use semantic HTML** for accessibility
5. **Check auth status** before showing protected content
6. **Clear storage on logout** to prevent data leaks
7. **Use consistent naming** for form fields

---

## 🐛 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Form not validating | Check field names match validation rules |
| Navigation not working | Ensure routes are defined in App.jsx |
| Styles not loading | Verify Bootstrap CSS import in app.jsx |
| localStorage undefined | Check if token key name is consistent |
| Component not updating | Check state updates after form submission |

---

**Last Updated:** March 2026
