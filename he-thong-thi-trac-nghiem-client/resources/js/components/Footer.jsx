import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';

function Footer() {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="bg-dark text-white py-4 mt-5">
            <Container>
                <Row>
                    <Col md={4} className="mb-4">
                        <h5>Về Chúng Tôi</h5>
                        <p className="text-muted">
                            Hệ thống thi trắc nghiệm trực tuyến cho phòng công tác sinh viên.
                        </p>
                    </Col>
                    <Col md={4} className="mb-4">
                        <h5>Liên Kết Nhanh</h5>
                        <ul className="list-unstyled">
                            <li><a href="#" className="text-muted text-decoration-none">Trang Chủ</a></li>
                            <li><a href="#" className="text-muted text-decoration-none">Các Kỳ Thi</a></li>
                            <li><a href="#" className="text-muted text-decoration-none">Hỗ Trợ</a></li>
                            <li><a href="#" className="text-muted text-decoration-none">Liên Hệ</a></li>
                        </ul>
                    </Col>
                    <Col md={4} className="mb-4">
                        <h5>Thông Tin Liên Hệ</h5>
                        <p className="text-muted">
                            📧 Email: contact@example.com<br />
                            📱 Điện Thoại: +84 (0) 123 456 789<br />
                            📍 Địa chỉ: Sinh Viên, Việt Nam
                        </p>
                    </Col>
                </Row>
                <hr className="bg-secondary" />
                <Row>
                    <Col className="text-center">
                        <p className="text-muted mb-0">
                            &copy; {currentYear} Hệ Thống Thi Trắc Nghiệm. Tất cả quyền được bảo lưu.
                        </p>
                    </Col>
                </Row>
            </Container>
        </footer>
    );
}

export default Footer;
