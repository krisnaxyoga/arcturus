//import React
import React, { useState } from 'react';

//import layout
import Layout from '../../../Layouts/Agent';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';


export default function Invoice({ session, data, agent, gateway, roombooking, setting }) {
    console.log(data, ">>>>>>>data >>>>>>>>");

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
    }

    const [bookingStatus, setBookingStatus] = useState('unpaid')
    const [paymentMethod, setPaymentMethod] = useState('')
    const [trxID, setTrxID] = useState('')
    const [totalPaid, setTotalPaid] = useState(0)

    if (gateway) {
        setPaymentMethod(gateway.payment_method)
        setTrxID(gateway.trx_id)
        setTotalPaid(gateway.total_transaction)
    }

    if (data.booking_status) {
        setBookingStatus(data.booking_status)
    }


    const { url } = usePage();

    const handlePrintPDF = () => {
        // Simpan halaman dalam mode print
        window.print();
    };


    return (
        <>
            <Layout page={url} agent={agent}>
                <div className="container">
                    <h1>Invoice #{data.id}</h1>
                    <hr />
                    <div className="row">
                        <div className="col-lg-12">
                            {session.success && (
                                <div className="alert alert-success border-0 shadow-sm rounded-3">
                                    {session.success}
                                </div>
                            )}
                            <div className="card">
                                <div className="card-header" >
                                    <div style={{ display: 'flex', justifyContent: 'space-between' }}>

                                        {/* LOGO */}
                                        <a className="navbar-brand h-100 text-truncate" href="#">
                                            <img className="img-fluid" src={data.vendor.logo_img} alt="Logo" width="100px" height="auto" />
                                        </a>

                                        {/* Kolom kanan */}
                                        <div>
                                            <div><h1>INVOICE</h1></div>
                                            <div>Invoice# : {data.id}</div>
                                            <div>Created : {data.booking_date}</div>

                                        </div>
                                    </div>
                                    <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: "5px" }}>
                                        <div>
                                            {setting.company_name}
                                            <br />
                                            {setting.address_line1} {setting.address_line2}
                                            <br />
                                            Phone: {setting.telephone} Email: {setting.email}
                                        </div>
                                        <div style={{ marginRight: '25px' }}>
                                            <div>Amount due:</div>
                                            <div>{formatRupiah(data.price + data.vendor_service_fee_amount)}</div>
                                        </div>
                                    </div>
                                </div>
                                {/* <hr /> */}
                                <div className="card-body">
                                    <form>
                                        <div className="row">
                                            <div className="col-lg-6">
                                                <h2>Billing to:</h2>
                                                System Admin<br />
                                                {setting.url_website}<br />
                                                {setting.city}, {setting.state}, {setting.country}
                                            </div>
                                            <div className="col-lg-6"></div>
                                        </div>
                                        <hr />
                                        <div className='row'>
                                            <div className="col-lg-12">
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="bookno" className="form-label">Booking Number</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>#{data.id}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="bookstatus" className="form-label">Booking Status</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>{bookingStatus}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="paymethod" className="form-label">Payment Method</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>{paymentMethod}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="hotel" className="form-label">Hotel Name</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>{data.vendor.vendor_name}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="address" className="form-label">Address</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>{data.vendor.city}, {data.vendor.state}, {data.vendor.country}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="checkin" className="form-label">Check-In</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>{data.checkin_date}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="checkout" className="form-label">Check-Out</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>{data.checkout_date}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="night" className="form-label">Nights</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>{data.night}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="adult" className="form-label">Adults</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>{data.total_guests}</p>
                                                    </div>
                                                </div>
                                                <hr />
                                                <p>Pricing</p>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="roomtypes" className="form-label">Room Types</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>{roombooking.room.ratedesc ? roombooking.room.ratedesc : ''} * {roombooking.total_room} : {formatRupiah(data.price)}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p>Service fee : {formatRupiah(data.vendor_service_fee_amount)}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="totals" className="form-label">Total</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p style={{ fontWeight: 'bold' }}>{formatRupiah(data.price + data.vendor_service_fee_amount)}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="paid" className="form-label">Paid</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p style={{ fontWeight: 'bold' }}>{formatRupiah(totalPaid)}</p>
                                                    </div>
                                                </div>
                                                <div className='row'>
                                                    <div className="col-md-6">
                                                        <label for="paid" className="form-label">Remain</label>
                                                    </div>
                                                    <div className="col-md-6">
                                                        <p style={{ fontWeight: 'bold' }}>{formatRupiah(data.price + data.vendor_service_fee_amount)}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div className="row justify-content-between"> {/* Use justify-content-between to move the buttons to both ends */}
                                            <div className="col-lg-auto">
                                                <button className="btn btn-primary" onClick={handlePrintPDF}><i className='fa fa-file-pdf'></i> Print as PDF</button>
                                            </div>
                                            <div className="col-lg-auto">
                                                <Link href="/agent/bookinghistory" className="btn btn-warning mb-2">Back to List</Link>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </Layout>
        </>
    )
}
