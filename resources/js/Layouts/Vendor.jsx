//import React
import React, { useState, useEffect } from 'react';
//import Link
import {Link, usePage} from '@inertiajs/inertia-react';
import Dropdown from 'react-bootstrap/Dropdown';
import Button from 'react-bootstrap/Button';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import Popover from 'react-bootstrap/Popover';
import '../../css/app.css';

const decrypt = (encryptedValue) => {
    return atob(encryptedValue);
};

function Layout({ children, page, vendor }) {

    const [show, setShow] = useState(false);
    const [isOpen, setIsOpen] = useState(false);
    const [isOpen2, setIsOpen2] = useState(false);

    // const [positionmaster, setPostionmaster] = useState('')
    const logOut = (e) => {
        localStorage.clear();
        window.location.href = `/logout`;
    }

    // Mendapatkan nilai 'encryptedPosition' dari localStorage
    const encryptedPosition = localStorage.getItem('encryptedPosition');
    // Mendapatkan nilai 'encryptedIsSuperAdmin' dari localStorage
    const encryptedIsSuperAdmin = localStorage.getItem('encryptedIsSuperAdmin');

    // Mendekripsi nilai jika encrypted data ada di localStorage
    const position = decrypt(encryptedPosition);
    const is_super_admin = decrypt(encryptedIsSuperAdmin);

    // setPostionmaster(position);
    const handleShow = () => {
        setShow(!show);
    };

    const toggleAccordion2 = () => {
        setIsOpen2(!isOpen2);
    };

    useEffect(() => {

        if(page === '/myprofile'){
            setIsOpen2(true);
        }else if(page === '/room/attribute'){
            setIsOpen2(true);
        }else if(page === '/room/extrabed'){
            setIsOpen2(true);
        }else if(page === '/room/index'){
            setIsOpen2(true);
        }else if(page === '/room/contract/index') {
            setIsOpen2(true);
        }else if(page === '/room/surcharge/index') {
            setIsOpen2(true);
        }
        else{
            setIsOpen2(false);
        }

    }, [page]);

    return (
        <>
            <nav className="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
                <a className="navbar-brand h-100 text-truncate" href="/">
                    {/* <img className="img-fluid" src="/images/undraw_Pic_profile_re_7g2h.png"/> */}
                    {vendor.vendor_name}
                </a>
                <Button variant="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" onClick={handleShow}>
                    <i className="fa fa-bars" aria-hidden="true"></i>
                </Button>
                <ul className="navbar-nav align-items-center ml-auto">
                    <li className="nav-item no-caret mr-3">{vendor.users.first_name} {vendor.users.last_name}</li>
                    <li className="nav-item no-caret mr-2 dropdown-user">
                        <Dropdown>
                            <Dropdown.Toggle className='p-0 rounded-circle' variant="light" id="dropdown-basic">
                                <img className="img-fluid p-0" style={{ maxHeight: '50px', maxWidth: '50px' }} src="/images/undraw_Pic_profile_re_7g2h.png" />

                            </Dropdown.Toggle>
                            <Dropdown.Menu style={{ transform: "none!important" }}>
                                <Dropdown.Item onClick={logOut}>
                                    logout
                                </Dropdown.Item>
                                {/* <Dropdown.Item href="#/action-3">Something else</Dropdown.Item> */}
                            </Dropdown.Menu>
                        </Dropdown>
                    </li>
                </ul>
            </nav>
            <div id="layoutSidenav" className="section position-relative">
                <div style={show ? { transform: 'translateX(0)' } : {}} id="layoutSidenav_nav">
                    <nav className="sidenav shadow-right sidenav-light">
                        <div className="sidenav-menu">
                            <div className="nav accordion" id="accordionSidenav">
                                {(is_super_admin === 'true') && (
                                    <>
                                        <div className="sidenav-menu-heading">Super Admin</div>
                                        <a href='/redirect-admin?page=hotel' className='nav-link'>
                                            <div className="nav-link-icon"><i className="fa fa-home" aria-hidden="true"></i></div>
                                            Admin Dashboard
                                        </a>
                                    </>
                                )}

                                <div className="sidenav-menu-heading">Main</div>
                                {/* <a href="/" className='nav-link'>
                                    <div className="nav-link-icon"><i className="fa fa-home" aria-hidden="true"></i></div>
                                    Home
                                </a>
                                <Link className={`nav-link ${page === '/vendordashboard' ? 'active' : ''}`} href="/vendordashboard">
                                    <div className="nav-link-icon"><i className="fa fa-adjust" aria-hidden="true"></i></div>
                                    Dashboard
                                </Link>
                                {/* <Link className={`nav-link ${page === '/bookinghistory' ? 'active' : ''}`} href="/bookinghistory">
                                    <div className="nav-link-icon"><i className="fa fa-clock" aria-hidden="true"></i></div>
                                    Booking History
                                </Link> */}
                                <div className="nav-item">
                                    <a href="#" className='nav-link' onClick={toggleAccordion2}>
                                        <div className="nav-link-icon"><i className="fa fa-cogs" aria-hidden="true"></i></div>
                                        My Hotel
                                    </a>
                                    {isOpen2 && <div className="bg-white py-2 px-4 colapse-inner rounded">
                                        <Link className={`nav-link ${page === '/myprofile' ? 'active' : ''}`} href="/myprofile">Hotel Info</Link>

                                        {/* <Link className={`nav-link ${page === '/room/attribute' ? 'active' : ''}`} href="/room/attribute">Amenities</Link> */}
                                        <Link className={`nav-link ${page === '/room/index' ? 'active' : ''}`} href="/room/index">Room Types</Link>
                                        {/* <Link className={`nav-link ${page === '/room/extrabed' ? 'active' : ''}`} href="/room/extrabed">Extrabed (Under Construction)</Link> */}
                                        <Link className={`nav-link ${page === '/room/contract/index' ? 'active' : ''}`} href="/room/contract/index">Rates</Link>
                                        <Link className={`nav-link ${page === '/room/surcharge/index' ? 'active' : ''}`} href="/room/surcharge/index">Rate Calendar</Link>
                                    </div>}
                                </div>
                                <Link className={`nav-link ${page === '/bookingreport' ? 'active' : ''}`} href="/bookingreport">
                                    <div className="nav-link-icon">  <i className="fas fa-fw fa-chart-area"></i></div>
                                    Booking Report
                                </Link>
                                {(vendor.users.position === 'master' || position === 'master') && (
                                    <>
                                     <Link className={`nav-link ${page === '/vendor-profile/property' ? 'active' : ''}`} href="/vendor-profile/property">
                                        <div className="nav-link-icon"><i className="fa fa-building" aria-hidden="true"></i></div>
                                        Property
                                    </Link>
                                    </>
                                )}
                                <Link className={`nav-link ${page === '/vendor-profile/changepassword' ? 'active' : ''}`} href="/vendor-profile/changepassword">
                                    <div className="nav-link-icon">  <i className="fas fa-fw fa-key"></i></div>
                                    password
                                </Link>
                            </div>
                        </div>
                        <div className="sidenav-footer">
                            <div className="sidenav-footer-content">
                                <div className="sidenav-footer-subtitle">Logged in as: {vendor.users.first_name} {vendor.users.last_name}</div>

                                <div className="sidenav-footer-title"></div>
                            </div>
                        </div>
                    </nav>
                </div>
                <div id="layoutSidenav_content">
                    <main className="container mt-5">
                        {children}
                    </main>
                    {/* <a href="{{ route('influencer.formvipcontent')}}" className="float" target="_blank">
                <img className="dropdown-user-img my-float" src="/images/plus.svg" />
            </a> */}
                    <footer className="footer mt-auto footer-light">
                        <div className="container-fluid">
                            <div className="row">
                                <div className="col-md-12 small">Copyright &#xA9; {vendor.vendor_name} </div>
                            </div>
                        </div>
                    </footer>
                    <nav className="navbar navbar-dark bg-white navbar-expand d-lg-none d-xl-none fixed-bottom">
                        <ul className="navbar-nav nav-justified w-100">
                            <li className="nav-item">
                                <Link className={`nav-link ${page === '/vendordashboard' ? 'active' : ''}`} href="/vendordashboard">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="dark" className="bi bi-house-door" viewBox="0 0 16 16">
                                        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z" />
                                    </svg>
                                </Link>
                            </li>
                            {/* seacrh */}
                            {/* <li className="nav-item">
                    <a href="#" className="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="dark" className="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                    </a>
                </li> */}
                            <li className="nav-item">
                                {['top'].map((placement) => (
                                    <OverlayTrigger
                                        trigger="click"
                                        key={placement}
                                        placement={placement}
                                        overlay={
                                            <Popover id={`popover-positioned-${placement}`}>
                                                {/* <Popover.Header as="h3">{`Popover ${placement}`}</Popover.Header> */}
                                                <Popover.Body>
                                                    <Link className={`nav-link text-dark ${page === '/room/index' ? 'active' : ''}`} href="/room/index">
                                                        ROOM TYPES
                                                    </Link>
                                                    <Link className={`nav-link text-dark ${page === '/room/contract/index' ? 'active' : ''}`} href="/room/contract/index">
                                                        RATES
                                                    </Link>
                                                </Popover.Body>
                                            </Popover>
                                        }
                                    >
                                        <Button variant='light' className='rounded-5'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="light" className="bi bi-plus-circle" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                            </svg>
                                        </Button>
                                    </OverlayTrigger>
                                ))}
                            </li>
                            {/* notif */}
                            {/* <li className="nav-item">
                    <a href="#" className="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="dark" className="bi bi-bell" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                        </svg>
                    </a>
                </li> */}
                            <li className="nav-item">
                                <Link className={`nav-link ${page === '/myprofile' ? 'active' : ''}`} href="/myprofile">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="dark" className="bi bi-person" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                    </svg>
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>


        </>
    )

}

export default Layout
