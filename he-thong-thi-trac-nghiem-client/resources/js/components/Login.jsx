import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { Container, Row, Col, Form, Button, Alert, Spinner } from 'react-bootstrap';
import Header from './Header';
import Footer from './Footer';
import axios from 'axios';

function Login() {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        email: '',
        password: '',
    });
    const [errors, setErrors] = useState({});
    const [loading, setLoading] = useState(false);
    const [generalError, setGeneralError] = useState('');
    const [successMessage, setSuccessMessage] = useState('');

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
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

        if (!formData.email.trim()) {
            newErrors.email = 'Email không được để trống';
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
            newErrors.email = 'Email không hợp lệ';
        }

        if (!formData.password) {
            newErrors.password = 'Mật khẩu không được để trống';
        } else if (formData.password.length < 6) {
            newErrors.password = 'Mật khẩu phải có ít nhất 6 ký tự';
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
                    if (formData.email && formData.password.length >= 6) {
                        resolve({
                            data: {
                                token: 'demo-token-123',
                                user: {
                                    id: 1,
                                    name: 'Sinh Viên',
                                    email: formData.email
                                }
                            }
                        });
                    } else {
                        throw new Error('Email hoặc mật khẩu không chính xác');
                    }
                }, 1000);
            });

            // Store token and user info
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('userName', response.data.user.name);
            localStorage.setItem('userEmail', response.data.user.email);

            setSuccessMessage('Đăng nhập thành công! Đang chuyển hướng...');
            setTimeout(() => {
                navigate('/');
            }, 1500);
        } catch (error) {
            setGeneralError(error.message || 'Đăng nhập thất bại. Vui lòng thử lại.');
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
                        <Col md={6} lg={5}>
                            <div className="card shadow-lg">
                                <div className="card-body p-5">
                                    <h2 className="card-title text-center mb-4 text-primary fw-bold">
                                        Đăng Nhập
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
                                                placeholder="Nhập mật khẩu của bạn"
                                                value={formData.password}
                                                onChange={handleChange}
                                                isInvalid={!!errors.password}
                                                disabled={loading}
                                            />
                                            <Form.Control.Feedback type="invalid">
                                                {errors.password}
                                            </Form.Control.Feedback>
                                        </Form.Group>

                                        <div className="mb-3">
                                            <Form.Check
                                                type="checkbox"
                                                label="Nhớ tôi"
                                                disabled={loading}
                                            />
                                        </div>

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
                                                    Đang đăng nhập...
                                                </>
                                            ) : (
                                                'Đăng Nhập'
                                            )}
                                        </Button>
                                    </Form>

                                    <hr />

                                    <p className="text-center mb-0">
                                        Chưa có tài khoản?{' '}
                                        <Link to="/register" className="text-decoration-none fw-bold">
                                            Đăng ký tại đây
                                        </Link>
                                    </p>
                                    <p className="text-center text-muted small mt-3">
                                        <Link to="#" className="text-decoration-none text-muted">
                                            Quên mật khẩu?
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

export default Login;
