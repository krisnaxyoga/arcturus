//import React
import React, { useState } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
// import axios from 'axios';

import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';

export default function Detail({ session, data, agent }) {
    console.log(data, ">>>>>>>data country >>>>>>>>");
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
    }
    const { url } = usePage();

    const handlePrintPDF = () => {
        // Simpan halaman dalam mode print
        const printContent = document.getElementById('print-card');
        if (printContent) {
          const originalContents = document.body.innerHTML;
          const printableContents = printContent.innerHTML;
          document.body.innerHTML = printableContents;
          window.print();
          document.body.innerHTML = originalContents;
        }
        // window.print();
      };
    

    return (
        <>
            <Layout page={url}>
                <div className="container">
                    <h1>Booking Detail #{data.id}</h1>
                    <hr />
                    <div className="row">
                        <div className="col-lg-12">
                            <div className="card mb-3" id="print-card">
                                <div className="card-body">
                                    <div className="row">
                                        <div className="col-lg-6">
                                        <img className="img-fluid" src={data.vendor.logo_img} alt="Logo hotel" width="100px" height="auto" />
                                            <h2>BOOKING STATUS</h2>
                                            {data.booking_status === 'paid' ? (
                                                <>
                                                 <div className='btn btn-outline-success mb-3'>
                                                <p style={{fontSize:'16px'}}>{data.booking_status}</p>
                                            </div>
                                                </>
                                            ):(
                                                <>
                                                <div className='btn btn-outline-danger mb-3'>
                                                    <p style={{fontSize:'16px'}}>{data.booking_status}</p>
                                                </div>
                                                </>
                                            )}
                                            <h4>HOTEL NAME</h4>
                                           <p className='mb-3'>{data.vendor.vendor_name}</p>

                                           <h4>BOOKING DATE</h4>
                                           <p className='mb-3'>{data.booking_date}</p>

                                           <h4>NIGHT</h4>
                                           <p className='mb-3'>{data.night} / Night</p>
                                           
                                           <h4>TOTAL GUEST</h4>
                                           <p className='mb-3'>{data.total_guests} / Person</p>

                                           <h4>CHECKIN and CHECKOUT</h4>
                                            <p className='mb-3'> {data.checkin_date} - {data.checkout_date}</p>
                                        </div>
                                        <div className="col-lg-6">
                                           
                                            <h4>GUEST NAME</h4>
                                           <p className='mb-3'>{data.first_name} {data.last_name}</p>
                                           <div className='mb-3'>
                                                <h4 for="email" className="form-h4">Email</h4>
                                                <p>{data.email}</p>
                                            </div>
                                            <div className='mb-3'>
                                                <h4 for="phone" className="form-h4">Phone</h4>
                                                <p>{data.phone}</p>
                                            </div>
                                            
                                            <div className='mb-3'>
                                                <h4 for="country" className="form-h4">Country</h4>
                                                <p>{data.country}</p>
                                            </div>
                                            <div className='mb-3'>
                                                <h4 for="request" className="form-h4">Special Request</h4>
                                                <p>{data.special_request}</p>
                                            </div>
                                            <h4 className='text-dark'>TOTAL PAYMENT</h4>
                                           <p className='mb-3 text-success'>{formatRupiah(data.price)}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button className="btn btn-primary" onClick={handlePrintPDF}><i className='fa fa-file-pdf'></i> Print as PDF</button>
                            <button onClick={() => history.back()} className="btn btn-danger ml-2">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    )
}
