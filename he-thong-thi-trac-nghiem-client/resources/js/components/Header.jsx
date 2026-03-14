import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { Navbar, Nav, Container, Button } from 'react-bootstrap';

function Header() {
    const navigate = useNavigate();
    const [expanded, setExpanded] = useState(false);
    const isLoggedIn = !!localStorage.getItem('token');
    const userName = localStorage.getItem('userName');

    const handleLogout = () => {
        localStorage.removeItem('token');
        localStorage.removeItem('userName');
        navigate('/login');
    };

    return (
        <header>
            <Navbar bg="primary" expand="lg" sticky="top" expanded={expanded} onToggle={() => setExpanded(!expanded)}>
                <Container>
                    <Navbar.Brand as={Link} to="/" className="text-white fw-bold">
                        📝 Hệ Thống Thi Trắc Nghiệm
                    </Navbar.Brand>
                    <Navbar.Toggle aria-controls="basic-navbar-nav" />
                    <Navbar.Collapse id="basic-navbar-nav">
                        <Nav className="ms-auto">
                            {isLoggedIn ? (
                                <>
                                    <Nav.Link as={Link} to="/dashboard" className="text-white">
                                        Bảng Điều Khiển
                                    </Nav.Link>
                                    <Nav.Link as={Link} to="/exams" className="text-white">
                                        Các Kỳ Thi
                                    </Nav.Link>
                                    <Nav.Link as={Link} to="/results" className="text-white">
                                        Kết Quả
                                    </Nav.Link>
                                    <Nav.Link className="text-white">
                                        Xin chào, {userName}
                                    </Nav.Link>
                                    <Button
                                        variant="outline-light"
                                        onClick={handleLogout}
                                        className="ms-2"
                                    >
                                        Đăng Xuất
                                    </Button>
                                </>
                            ) : (
                                <>
                                    <Nav.Link as={Link} to="/login" className="text-white">
                                        Đăng Nhập
                                    </Nav.Link>
                                    <Nav.Link as={Link} to="/register" className="text-white ms-2">
                                        <Button variant="light" size="sm">
                                            Đăng Ký
                                        </Button>
                                    </Nav.Link>
                                </>
                            )}
                        </Nav>
                    </Navbar.Collapse>
                </Container>
            </Navbar>
        </header>
    );
}

export default Header;
