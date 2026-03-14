import React from 'react';
import { Container } from 'react-bootstrap';
import Header from './Header';
import Footer from './Footer';
import Dashboard from './Dashboard';

function Layout() {
    const isLoggedIn = !!localStorage.getItem('token');

    return (
        <div className="d-flex flex-column min-vh-100">
            <Header />
            <main className="flex-grow-1">
                <Container className="py-5">
                    {isLoggedIn ? (
                        <Dashboard />
                    ) : (
                        <div className="row justify-content-center">
                            <div className="col-md-8">
                                <div className="alert alert-info text-center">
                                    <h4>Chào mừng đến với Hệ Thống Thi Trắc Nghiệm</h4>
                                    <p>Vui lòng đăng nhập hoặc đăng ký để tiếp tục.</p>
                                </div>
                                <div className="card shadow-sm">
                                    <div className="card-body text-center">
                                        <h5 className="card-title">Các Tính Năng Chính</h5>
                                        <ul className="list-unstyled">
                                            <li>✓ Thi trắc nghiệm trực tuyến</li>
                                            <li>✓ Xem kết quả tức thì</li>
                                            <li>✓ Thống kê và báo cáo chi tiết</li>
                                            <li>✓ Quản lý các kỳ thi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                </Container>
            </main>
            <Footer />
        </div>
    );
}

export default Layout;
