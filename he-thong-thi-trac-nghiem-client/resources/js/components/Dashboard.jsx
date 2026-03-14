import React from 'react';
import { Container, Row, Col, Card, Button } from 'react-bootstrap';
import { Link } from 'react-router-dom';

function Dashboard() {
    const userName = localStorage.getItem('userName');
    const studentId = localStorage.getItem('studentId');

    return (
        <div>
            <div className="alert alert-info">
                <h4>Chào mừng, {userName}!</h4>
                <p className="mb-0">Mã số sinh viên: <strong>{studentId}</strong></p>
            </div>

            <Row className="mb-4">
                <Col md={6} lg={3} className="mb-3">
                    <Card className="text-center shadow-sm h-100">
                        <Card.Body>
                            <h5>📚 Các Kỳ Thi Sắp Tới</h5>
                            <p className="text-muted">3 kỳ thi</p>
                            <Button variant="primary" as={Link} to="/exams">
                                Xem Chi Tiết
                            </Button>
                        </Card.Body>
                    </Card>
                </Col>
                <Col md={6} lg={3} className="mb-3">
                    <Card className="text-center shadow-sm h-100">
                        <Card.Body>
                            <h5>📊 Kết Quả Thi</h5>
                            <p className="text-muted">5 kỳ thi đã hoàn thành</p>
                            <Button variant="info" as={Link} to="/results">
                                Xem Kết Quả
                            </Button>
                        </Card.Body>
                    </Card>
                </Col>
                <Col md={6} lg={3} className="mb-3">
                    <Card className="text-center shadow-sm h-100">
                        <Card.Body>
                            <h5>⭐ Điểm Trung Bình</h5>
                            <p className="text-muted fw-bold text-success" style={{ fontSize: '24px' }}>8.5/10</p>
                            <Button variant="success" as={Link} to="/statistics">
                                Thống Kê
                            </Button>
                        </Card.Body>
                    </Card>
                </Col>
                <Col md={6} lg={3} className="mb-3">
                    <Card className="text-center shadow-sm h-100">
                        <Card.Body>
                            <h5>👤 Hồ Sơ</h5>
                            <p className="text-muted">Cập nhật thông tin</p>
                            <Button variant="secondary" as={Link} to="/profile">
                                Chỉnh Sửa
                            </Button>
                        </Card.Body>
                    </Card>
                </Col>
            </Row>

            <Row>
                <Col>
                    <Card className="shadow-sm">
                        <Card.Header className="bg-primary text-white">
                            <Card.Title className="mb-0">Hoạt Động Gần Đây</Card.Title>
                        </Card.Header>
                        <Card.Body>
                            <table className="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kỳ Thi</th>
                                        <th>Ngày</th>
                                        <th>Kết Quả</th>
                                        <th>Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Kiến Thức Công Dân</td>
                                        <td>10/03/2026</td>
                                        <td>8.5/10</td>
                                        <td><span className="badge bg-success">Đạt</span></td>
                                    </tr>
                                    <tr>
                                        <td>Tiếng Anh Cơ Bản</td>
                                        <td>05/03/2026</td>
                                        <td>7.0/10</td>
                                        <td><span className="badge bg-info">Đạt</span></td>
                                    </tr>
                                    <tr>
                                        <td>An Toàn Giao Thông</td>
                                        <td>01/03/2026</td>
                                        <td>9.0/10</td>
                                        <td><span className="badge bg-success">Xuất Sắc</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </div>
    );
}

export default Dashboard;
