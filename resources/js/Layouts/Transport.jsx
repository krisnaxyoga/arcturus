//import React
import React, { useState,useEffect } from 'react';
//import Link
import { Link } from '@inertiajs/inertia-react';
import Dropdown from 'react-bootstrap/Dropdown';
import Button from 'react-bootstrap/Button';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import Popover from 'react-bootstrap/Popover';
import axios from 'axios';

function Transport({ children, page, user, token }) {

    const currentYear = new Date().getFullYear();
    const domain = window.location.origin;
    const tokenFromLocalStorage = localStorage.getItem('token');
    const idFromLocalStorage = localStorage.getItem('id');
    
    const [data, setData] = useState([]);

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
    }

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.get(`${domain}/api/userlogin/${idFromLocalStorage}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${tokenFromLocalStorage}`,
                    },
                });

                console.log(response.status,">>response")
                setData(response.data.data); // Mengatur data yang diterima dari API ke state
            } catch (error) {
                if(error.response.data.error == 'Unauthorized'){
                    localStorage.removeItem("token");
                    localStorage.removeItem("id");
        
                    //redirect halaman login
                    window.location.href = `/auth/transport`;
                }
                console.error('Terjadi kesalahan:', error);
            }
        };

        fetchData(); // Memanggil fungsi untuk melakukan panggilan API saat komponen dimuat

    }, [tokenFromLocalStorage]); // Menambahkan tokenFromLocalStorage ke dalam dependencies useEffect

    
   
    const logoutHanlder = async () => {

        //set axios header dengan type Authorization + Bearer token
        axios.defaults.headers.common['Authorization'] = `Bearer ${tokenFromLocalStorage}`
        //fetch Rest API
        await axios.post(`${domain}/api/login/transport/logout`)
        .then(() => {

            //remove token from localStorage
            localStorage.removeItem("token");
            localStorage.removeItem("id");

            //redirect halaman login
            window.location.href = `/auth/transport`;
        });
    };

    const [show, setShow] = useState(false);

    const handleShow = () => {
        setShow(!show);
    };

    const [isOpen, setIsOpen] = useState(false);

    const toggleAccordion = () => {
        setIsOpen(!isOpen);
    };

    console.log(data);
  return (
    <>
    <nav className="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
        <a className="navbar-brand h-100 text-truncate" href="#">
            {data.company_name}
        </a>
        <Button variant="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" onClick={handleShow}>
         <i className="fa fa-bars" aria-hidden="true"></i>
        </Button>
        <ul className="navbar-nav align-items-center ml-auto">
            <li className="nav-item no-caret mr-3">{data.company_name}</li>
            <li className="nav-item no-caret mr-2 dropdown-user">
            
                <Dropdown>
                    <Dropdown.Toggle className='p-0 rounded-circle' variant="light" id="dropdown-basic">
                    <img className="img-fluid p-0" style={{ maxHeight: '50px', maxWidth: '50px' }} src="/images/undraw_Pic_profile_re_7g2h.png" />

                    </Dropdown.Toggle>
                    <Dropdown.Menu style={{ transform: "none!important" }}>
                        <Dropdown.Item style={{fontWeight:'700'}} onClick={logoutHanlder}>
                           <i className="fa fa-power-off"></i>&nbsp;logout
                        </Dropdown.Item>
                    </Dropdown.Menu>
                </Dropdown>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav" className="section position-relative">
        <div style={show ? { transform: 'translateX(0)' } : {}} id="layoutSidenav_nav">
            {/* <div id="layoutSidenav" className="section position-relative"> */}
                {/* <div id="layoutSidenav_nav"> */}
                    <nav className="sidenav shadow-right sidenav-light">
                        <div className="sidenav-menu">
                            <div className="nav accordion" id="accordionSidenav">
                                <div className="sidenav-menu-heading">Main</div>
                                <Link href={`/transport/dashboard/${tokenFromLocalStorage}/${idFromLocalStorage}`} className={`nav-link ${page === `/transport/dashboard/${tokenFromLocalStorage}/${idFromLocalStorage}` ? 'active' : ''}`}>
                                    <div className="nav-link-icon"><i className="fa fa-home" aria-hidden="true"></i></div>
                                    Home
                                </Link>
                                <Link className={`nav-link ${page === `/transport/addpackage/${tokenFromLocalStorage}/${idFromLocalStorage}` ? 'active' : ''}`} href={`/transport/addpackage/${tokenFromLocalStorage}/${idFromLocalStorage}`}>
                                    <div className="nav-link-icon"><i className="fa fa-adjust" aria-hidden="true"></i></div>
                                    Package
                                </Link>
                                <Link className={`nav-link ${page === `/transport/bookinghistory/${tokenFromLocalStorage}/${idFromLocalStorage}` ? 'active' : ''}`} href={`/transport/bookinghistory/${tokenFromLocalStorage}/${idFromLocalStorage}`}>
                                    <div className="nav-link-icon"><i className="fa fa-clock" aria-hidden="true"></i></div>
                                    Booking History
                                </Link>
                                <Link className={`nav-link intro-step-2 ${page === `/profile/transport/${tokenFromLocalStorage}/${idFromLocalStorage}` ? 'active' : ''}`} href={`/profile/transport/${tokenFromLocalStorage}/${idFromLocalStorage}`}>
                                    <div className="nav-link-icon"><i className="fa fa-cogs" aria-hidden="true"></i></div>
                                    My Profile
                                </Link>
                                {/* <Link className={`nav-link ${page === '/agent-enquiryreport' ? 'active' : ''}`} href="/agent-enquiryreport">
                                    <div className="nav-link-icon"><i className="fa fa-book" aria-hidden="true"></i></div>
                                    Enquiry Report
                                </Link> */}
                            </div>
                        </div>
                        <div className="sidenav-footer">
                            <div className="sidenav-footer-content">
                                <div className="sidenav-footer-subtitle">Logged in as: {data.company_name} </div>

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
                                <div className="col-md-12 small">Copyright &#xA9; {currentYear} &nbsp;

                                {data.company_name}

                                </div>
                            </div>
                        </div>
                    </footer>
                    <nav className="navbootom navbar navbar-dark bg-white navbar-expand d-lg-none d-xl-none fixed-bottom">
                        <ul className="navbar-nav nav-justified w-100">
                            <li className="nav-item">
                                <Link href={`/transport/dashboard/${tokenFromLocalStorage}/${idFromLocalStorage}`} className={`nav-link ${page === `/transport/dashboard/${tokenFromLocalStorage}/${idFromLocalStorage}` ? 'activebottom' : ''}`}>
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0 0 48 48">
                                <linearGradient id="jv689zNUBazMNK6AOyXtga_wFfu6zXx15Yk_gr1" x1="6" x2="42" y1="41" y2="41" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#c8d3de"></stop><stop offset="1" stop-color="#c8d3de"></stop></linearGradient><path fill="url(#jv689zNUBazMNK6AOyXtga_wFfu6zXx15Yk_gr1)" d="M42,39H6v2c0,1.105,0.895,2,2,2h32c1.105,0,2-0.895,2-2V39z"></path><linearGradient id="jv689zNUBazMNK6AOyXtgb_wFfu6zXx15Yk_gr2" x1="14.095" x2="31.385" y1="10.338" y2="43.787" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#fcfcfc"></stop><stop offset=".495" stop-color="#f4f4f4"></stop><stop offset=".946" stop-color="#e8e8e8"></stop><stop offset="1" stop-color="#e8e8e8"></stop></linearGradient><path fill="url(#jv689zNUBazMNK6AOyXtgb_wFfu6zXx15Yk_gr2)" d="M42,39H6V20L24,3l18,17V39z"></path><path fill="#de490d" d="M13,25h10c0.552,0,1,0.448,1,1v17H12V26C12,25.448,12.448,25,13,25z"></path><path d="M24,4c-0.474,0-0.948,0.168-1.326,0.503l-5.359,4.811L6,20v5.39L24,9.428L42,25.39V20L30.685,9.314	l-5.359-4.811C24.948,4.168,24.474,4,24,4z" opacity=".05"></path><path d="M24,3c-0.474,0-0.948,0.167-1.326,0.5l-5.359,4.784L6,18.909v5.359L24,8.397l18,15.871v-5.359	L30.685,8.284L25.326,3.5C24.948,3.167,24.474,3,24,3z" opacity=".07"></path><linearGradient id="jv689zNUBazMNK6AOyXtgc_wFfu6zXx15Yk_gr3" x1="24" x2="24" y1="1.684" y2="23.696" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#d43a02"></stop><stop offset="1" stop-color="#b9360c"></stop></linearGradient><path fill="url(#jv689zNUBazMNK6AOyXtgc_wFfu6zXx15Yk_gr3)" d="M44.495,19.507L25.326,2.503C24.948,2.168,24.474,2,24,2s-0.948,0.168-1.326,0.503	L3.505,19.507c-0.42,0.374-0.449,1.02-0.064,1.43l1.636,1.745c0.369,0.394,0.984,0.424,1.39,0.067L24,7.428L41.533,22.75	c0.405,0.356,1.021,0.327,1.39-0.067l1.636-1.745C44.944,20.527,44.915,19.881,44.495,19.507z"></path><linearGradient id="jv689zNUBazMNK6AOyXtgd_wFfu6zXx15Yk_gr4" x1="28.05" x2="35.614" y1="25.05" y2="32.614" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#33bef0"></stop><stop offset="1" stop-color="#0a85d9"></stop></linearGradient><path fill="url(#jv689zNUBazMNK6AOyXtgd_wFfu6zXx15Yk_gr4)" d="M29,25h6c0.552,0,1,0.448,1,1v6c0,0.552-0.448,1-1,1h-6c-0.552,0-1-0.448-1-1v-6	C28,25.448,28.448,25,29,25z"></path>
                                </svg>
                                <p className='text-dark m-0' style={{fontSize:'10px'}}>home</p>
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
                                <Link className={`nav-link ${page === `/transport/addpackage/${tokenFromLocalStorage}/${idFromLocalStorage}` ? 'activebottom' : ''}`} href={`/transport/addpackage/${tokenFromLocalStorage}/${idFromLocalStorage}`}>
                                
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0 0 48 48">
                                <linearGradient id="dyoR47AMqzPbkc_5POASHa_aWZy3jlAFSa9_gr1" x1="9.858" x2="38.142" y1="-27.858" y2="-56.142" gradientTransform="matrix(1 0 0 -1 0 -18)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#9dffce"></stop><stop offset="1" stop-color="#50d18d"></stop></linearGradient><path fill="url(#dyoR47AMqzPbkc_5POASHa_aWZy3jlAFSa9_gr1)" d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z"></path><path d="M34,21h-7v-7c0-1.105-0.895-2-2-2h-2c-1.105,0-2,0.895-2,2v7h-7	c-1.105,0-2,0.895-2,2v2c0,1.105,0.895,2,2,2h7v7c0,1.105,0.895,2,2,2h2c1.105,0,2-0.895,2-2v-7h7c1.105,0,2-0.895,2-2v-2	C36,21.895,35.105,21,34,21z" opacity=".05"></path><path d="M34,21.5h-7.5V14c0-0.828-0.672-1.5-1.5-1.5h-2	c-0.828,0-1.5,0.672-1.5,1.5v7.5H14c-0.828,0-1.5,0.672-1.5,1.5v2c0,0.828,0.672,1.5,1.5,1.5h7.5V34c0,0.828,0.672,1.5,1.5,1.5h2	c0.828,0,1.5-0.672,1.5-1.5v-7.5H34c0.828,0,1.5-0.672,1.5-1.5v-2C35.5,22.172,34.828,21.5,34,21.5z" opacity=".07"></path><linearGradient id="dyoR47AMqzPbkc_5POASHb_aWZy3jlAFSa9_gr2" x1="22" x2="26" y1="24" y2="24" gradientUnits="userSpaceOnUse"><stop offset=".824" stop-color="#135d36"></stop><stop offset=".931" stop-color="#125933"></stop><stop offset="1" stop-color="#11522f"></stop></linearGradient><path fill="url(#dyoR47AMqzPbkc_5POASHb_aWZy3jlAFSa9_gr2)" d="M23,13h2c0.552,0,1,0.448,1,1v20c0,0.552-0.448,1-1,1h-2c-0.552,0-1-0.448-1-1V14	C22,13.448,22.448,13,23,13z"></path><linearGradient id="dyoR47AMqzPbkc_5POASHc_aWZy3jlAFSa9_gr3" x1="13" x2="35" y1="24" y2="24" gradientUnits="userSpaceOnUse"><stop offset=".824" stop-color="#135d36"></stop><stop offset=".931" stop-color="#125933"></stop><stop offset="1" stop-color="#11522f"></stop></linearGradient><path fill="url(#dyoR47AMqzPbkc_5POASHc_aWZy3jlAFSa9_gr3)" d="M35,23v2c0,0.552-0.448,1-1,1H14c-0.552,0-1-0.448-1-1v-2c0-0.552,0.448-1,1-1h20	C34.552,22,35,22.448,35,23z"></path>
                                </svg>
                                <p className='text-dark m-0' style={{fontSize:'10px'}}>package</p>
                                </Link>
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
                                <Link className={`nav-link ${page === `/profile/transport/${tokenFromLocalStorage}/${idFromLocalStorage}` ? 'activebottom' : ''}`} href={`/profile/transport/${tokenFromLocalStorage}/${idFromLocalStorage}`}>
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0 0 48 48">
<linearGradient id="L4rKfs~Qrm~k0Pk8MRsoza_s5NUIabJrb4C_gr1" x1="32.012" x2="15.881" y1="32.012" y2="15.881" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#fff"></stop><stop offset=".242" stop-color="#f2f2f2"></stop><stop offset="1" stop-color="#ccc"></stop></linearGradient><circle cx="24" cy="24" r="11.5" fill="url(#L4rKfs~Qrm~k0Pk8MRsoza_s5NUIabJrb4C_gr1)"></circle><linearGradient id="L4rKfs~Qrm~k0Pk8MRsozb_s5NUIabJrb4C_gr2" x1="17.45" x2="28.94" y1="17.45" y2="28.94" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#0d61a9"></stop><stop offset=".363" stop-color="#0e5fa4"></stop><stop offset=".78" stop-color="#135796"></stop><stop offset="1" stop-color="#16528c"></stop></linearGradient><circle cx="24" cy="24" r="7" fill="url(#L4rKfs~Qrm~k0Pk8MRsozb_s5NUIabJrb4C_gr2)"></circle><linearGradient id="L4rKfs~Qrm~k0Pk8MRsozc_s5NUIabJrb4C_gr3" x1="5.326" x2="38.082" y1="5.344" y2="38.099" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#889097"></stop><stop offset=".331" stop-color="#848c94"></stop><stop offset=".669" stop-color="#78828b"></stop><stop offset="1" stop-color="#64717c"></stop></linearGradient><path fill="url(#L4rKfs~Qrm~k0Pk8MRsozc_s5NUIabJrb4C_gr3)" d="M43.407,19.243c-2.389-0.029-4.702-1.274-5.983-3.493c-1.233-2.136-1.208-4.649-0.162-6.693 c-2.125-1.887-4.642-3.339-7.43-4.188C28.577,6.756,26.435,8,24,8s-4.577-1.244-5.831-3.131c-2.788,0.849-5.305,2.301-7.43,4.188 c1.046,2.044,1.071,4.557-0.162,6.693c-1.281,2.219-3.594,3.464-5.983,3.493C4.22,20.77,4,22.358,4,24 c0,1.284,0.133,2.535,0.364,3.752c2.469-0.051,4.891,1.208,6.213,3.498c1.368,2.37,1.187,5.204-0.22,7.345 c2.082,1.947,4.573,3.456,7.34,4.375C18.827,40.624,21.221,39,24,39s5.173,1.624,6.303,3.971c2.767-0.919,5.258-2.428,7.34-4.375 c-1.407-2.141-1.588-4.975-0.22-7.345c1.322-2.29,3.743-3.549,6.213-3.498C43.867,26.535,44,25.284,44,24 C44,22.358,43.78,20.77,43.407,19.243z M24,34.5c-5.799,0-10.5-4.701-10.5-10.5c0-5.799,4.701-10.5,10.5-10.5S34.5,18.201,34.5,24 C34.5,29.799,29.799,34.5,24,34.5z"></path>
</svg>
                                <p className='text-dark m-0' style={{fontSize:'10px'}}>profile</p>
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        {/* </div> */}
    {/* </div> */}
</>
  )
}

export default Transport