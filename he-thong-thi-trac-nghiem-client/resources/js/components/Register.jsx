import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { Container, Row, Col, Form, Button, Alert, Spinner } from 'react-bootstrap';
import Header from './Header';
import Footer from './Footer';
import axios from 'axios';

function Register() {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        fullName: '',
        studentId: '',
        email: '',
        password: '',
        confirmPassword: '',
        agreeTerms: false,
    });
    const [errors, setErrors] = useState({});
    const [loading, setLoading] = useState(false);
    const [generalError, setGeneralError] = useState('');
    const [successMessage, setSuccessMessage] = useState('');

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: type === 'checkbox' ? checked : value
        }));
        // Clear error for this field
        if (errors[name]) {
            setErrors(prev => ({
                ...prev,
                [name]: ''
            }));
        }
    };

    const validateForm = () => {
        const newErrors = {};

        if (!formData.fullName.trim()) {
            newErrors.fullName = 'Họ và tên không được để trống';
        } else if (formData.fullName.length < 3) {
            newErrors.fullName = 'Họ và tên phải có ít nhất 3 ký tự';
        }

        if (!formData.studentId.trim()) {
            newErrors.studentId = 'Mã số sinh viên không được để trống';
        } else if (!/^\d{8,}$/.test(formData.studentId)) {
            newErrors.studentId = 'Mã số sinh viên không hợp lệ (phải là số)';
        }

        if (!formData.email.trim()) {
            newErrors.email = 'Email không được để trống';
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
            newErrors.email = 'Email không hợp lệ';
        }

        if (!formData.password) {
            newErrors.password = 'Mật khẩu không được để trống';
        } else if (formData.password.length < 6) {
            newErrors.password = 'Mật khẩu phải có ít nhất 6 ký tự';
        } else if (!/[A-Z]/.test(formData.password)) {
            newErrors.password = 'Mật khẩu phải chứa ít nhất một ký tự in hoa';
        } else if (!/[0-9]/.test(formData.password)) {
            newErrors.password = 'Mật khẩu phải chứa ít nhất một chữ số';
        }

        if (!formData.confirmPassword) {
            newErrors.confirmPassword = 'Vui lòng xác nhận mật khẩu';
        } else if (formData.password !== formData.confirmPassword) {
            newErrors.confirmPassword = 'Mật khẩu không khớp';
        }

        if (!formData.agreeTerms) {
            newErrors.agreeTerms = 'Bạn phải đồng ý với điều khoản sử dụng';
        }

        return newErrors;
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setGeneralError('');
        setSuccessMessage('');

        const newErrors = validateForm();
        if (Object.keys(newErrors).length > 0) {
            setErrors(newErrors);
            return;
        }

        setLoading(true);

        try {
            // Demo: Simulate API call
            // In real application, replace with actual API endpoint
            const response = await new Promise((resolve) => {
                setTimeout(() => {
                    resolve({
                        data: {
                            token: 'demo-token-' + Date.now(),
                            user: {
                                id: Math.random() * 1000,
                                name: formData.fullName,
                                email: formData.email,
                                studentId: formData.studentId
                            }
                        }
                    });
                }, 1500);
            });

            // Store token and user info
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('userName', response.data.user.name);
            localStorage.setItem('userEmail', response.data.user.email);
            localStorage.setItem('studentId', response.data.user.studentId);

            setSuccessMessage('Đăng ký thành công! Đang chuyển hướng...');
            setTimeout(() => {
                navigate('/');
            }, 1500);
        } catch (error) {
            setGeneralError(error.message || 'Đăng ký thất bại. Vui lòng thử lại.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="d-flex flex-column min-vh-100">
            <Header />
            <main className="flex-grow-1">
                <Container className="py-5">
                    <Row className="justify-content-center">
                        <Col md={7} lg={6}>
                            <div className="card shadow-lg">
                                <div className="card-body p-5">
                                    <h2 className="card-title text-center mb-4 text-primary fw-bold">
                                        Đăng Ký Tài Khoản
                                    </h2>

                                    {generalError && (
                                        <Alert variant="danger" onClose={() => setGeneralError('')} dismissible>
                                            {generalError}
                                        </Alert>
                                    )}

                                    {successMessage && (
                                        <Alert variant="success">{successMessage}</Alert>
                                    )}

                                    <Form onSubmit={handleSubmit}>
                                        <Form.Group className="mb-3">
                                            <Form.Label className="fw-bold">Họ và Tên</Form.Label>
                                            <Form.Control
                                                type="text"
                                                name="fullName"
                                                placeholder="Nhập họ và tên đầy đủ"
                                                value={formData.fullName}
                                                onChange={handleChange}
                                                isInvalid={!!errors.fullName}
                                                disabled={loading}
                                            />
                                            <Form.Control.Feedback type="invalid">
                                                {errors.fullName}
                                            </Form.Control.Feedback>
                                        </Form.Group>

                                        <Form.Group className="mb-3">
                                            <Form.Label className="fw-bold">Mã Số Sinh Viên</Form.Label>
                                            <Form.Control
                                                type="text"
                                                name="studentId"
                                                placeholder="Nhập mã số sinh viên"
                                                value={formData.studentId}
                                                onChange={handleChange}
                                                isInvalid={!!errors.studentId}
                                                disabled={loading}
                                            />
                                            <Form.Control.Feedback type="invalid">
                                                {errors.studentId}
                                            </Form.Control.Feedback>
                                        </Form.Group>

                                        <Form.Group className="mb-3">
                                            <Form.Label className="fw-bold">Địa Chỉ Email</Form.Label>
                                            <Form.Control
                                                type="email"
                                                name="email"
                                                placeholder="Nhập email của bạn"
                                                value={formData.email}
                                                onChange={handleChange}
                                                isInvalid={!!errors.email}
                                                disabled={loading}
                                            />
                                            <Form.Control.Feedback type="invalid">
                                                {errors.email}
                                            </Form.Control.Feedback>
                                        </Form.Group>

                                        <Form.Group className="mb-3">
                                            <Form.Label className="fw-bold">Mật Khẩu</Form.Label>
                                            <Form.Control
                                                type="password"
                                                name="password"
                                                placeholder="Nhập mật khẩu (ít nhất 6 ký tự, 1 chữ hoa, 1 chữ số)"
                                                value={formData.password}
                                                onChange={handleChange}
                                                isInvalid={!!errors.password}
                                                disabled={loading}
                                            />
                                            <Form.Control.Feedback type="invalid">
                                                {errors.password}
                                            </Form.Control.Feedback>
                                        </Form.Group>

                                        <Form.Group className="mb-3">
                                            <Form.Label className="fw-bold">Xác Nhận Mật Khẩu</Form.Label>
                                            <Form.Control
                                                type="password"
                                                name="confirmPassword"
                                                placeholder="Nhập lại mật khẩu"
                                                value={formData.confirmPassword}
                                                onChange={handleChange}
                                                isInvalid={!!errors.confirmPassword}
                                                disabled={loading}
                                            />
                                            <Form.Control.Feedback type="invalid">
                                                {errors.confirmPassword}
                                            </Form.Control.Feedback>
                                        </Form.Group>

                                        <Form.Group className="mb-3">
                                            <Form.Check
                                                type="checkbox"
                                                name="agreeTerms"
                                                label={
                                                    <>
                                                        Tôi đồng ý với{' '}
                                                        <Link to="#" className="text-decoration-none">
                                                            điều khoản sử dụng
                                                        </Link>
                                                        {' '}và{' '}
                                                        <Link to="#" className="text-decoration-none">
                                                            chính sách bảo mật
                                                        </Link>
                                                    </>
                                                }
                                                checked={formData.agreeTerms}
                                                onChange={handleChange}
                                                disabled={loading}
                                                isInvalid={!!errors.agreeTerms}
                                            />
                                            <Form.Control.Feedback type="invalid" style={{ display: errors.agreeTerms ? 'block' : 'none' }}>
                                                {errors.agreeTerms}
                                            </Form.Control.Feedback>
                                        </Form.Group>

                                        <Button
                                            variant="primary"
                                            type="submit"
                                            className="w-100 fw-bold"
                                            disabled={loading}
                                        >
                                            {loading ? (
                                                <>
                                                    <Spinner
                                                        as="span"
                                                        animation="border"
                                                        size="sm"
                                                        role="status"
                                                        aria-hidden="true"
                                                        className="me-2"
                                                    />
                                                    Đang đăng ký...
                                                </>
                                            ) : (
                                                'Đăng Ký'
                                            )}
                                        </Button>
                                    </Form>

                                    <hr />

                                    <p className="text-center mb-0">
                                        Đã có tài khoản?{' '}
                                        <Link to="/login" className="text-decoration-none fw-bold">
                                            Đăng nhập tại đây
                                        </Link>
                                    </p>
                                </div>
                            </div>
                        </Col>
                    </Row>
                </Container>
            </main>
            <Footer />
        </div>
    );
}

export default Register;
