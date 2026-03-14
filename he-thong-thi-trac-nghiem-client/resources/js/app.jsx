import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import 'bootstrap/dist/css/bootstrap.min.css';
import './app.css';
import Layout from './components/Layout';
import Login from './components/Login';
import Register from './components/Register';

function App() {
    return (
        <Router>
            <Routes>
                <Route path="/login" element={<Login />} />
                <Route path="/register" element={<Register />} />
                <Route path="/*" element={<Layout />} />
            </Routes>
        </Router>
    );
}

ReactDOM.createRoot(document.getElementById('app')).render(<App />);
