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

    const { url } = usePage();


    return (
        <>
            <Layout page={url}>
                <div className="container">
                    <h1>Booking Detail #{data.id}</h1>
                    <hr />
                    <div className="row">
                        <div className="col-lg-12">
                            <Tabs variant="tabs" defaultActiveKey="profile"
                                id="fill-tab-example"
                                className="mb-3"
                                fill
                            >
                                <Tab eventKey="profile" title="Booking Detail">
                                    <div className="card">
                                        <div className="card-body">
                                                <div className="row">
                                                    <div className="col-lg-6">
                                                        <div>
                                                            <label for="status" className="form-label">Booking Status</label>
                                                            <h3>{data.booking_status}</h3>
                                                        </div>
                                                        <div>
                                                            <label for="legalname" className="form-label">Booking Date</label>
                                                            <p>{data.booking_date}</p>
                                                        </div>

                                                        <div>
                                                            <label for="Lastname" className="form-label">Guest Name</label>
                                                            <p>{data.first_name} {data.last_name}</p>
                                                        </div>
                                                        <div>
                                                            <label for="Lastname" className="form-label">Checkin</label>
                                                            {data.chekin_date}
                                                        </div>
                                                        <div>
                                                            <label for="Lastname" className="form-label">Checkout</label>
                                                           {data.checkout_date}
                                                        </div>
                                                    </div>
                                                    <div className="col-lg-6">
                                                        <div>
                                                            <label for="Lastname" className="form-label">Nights</label>
                                                           {data.night}
                                                        </div>
                                                        <div>
                                                            <label for="adults" className="form-label">Total Guest</label>
                                                            <p>{data.total_guests}</p>
                                                        </div>
                                                        <div>
                                                            <label for="totals" className="form-label">Totals</label>
                                                            <p>{data.price}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </Tab>
                                <Tab eventKey="guest" title="Guest Information">
                                    <div className="row">
                                        <div className="col-lg-12">
                                            <div className="card">
                                                <div className="col-lg-6">
                                                    <div>
                                                        <label for="firstname" className="form-label">First Name</label>
                                                       <p>{data.first_name}</p>
                                                    </div>
                                                    <div>
                                                        <label for="lastname" className="form-label">Last Name</label>
                                                       <p>{data.last_name}</p>
                                                    </div>

                                                    <div>
                                                        <label for="email" className="form-label">Email</label>
                                                        {data.email}
                                                    </div>
                                                    <div>
                                                        <label for="phone" className="form-label">Phone</label>
                                                       <p>{data.phone}</p>
                                                    </div>
                                                    <div>
                                                        <label for="address1" className="form-label">Address Line1</label>
                                                        <p>{data.address_line1}</p>
                                                    </div>
                                                    <div>
                                                        <label for="address2" className="form-label">Address Line2</label>
                                                        <p>{data.address_line2}</p>
                                                    </div>
                                                    <div>
                                                        <label for="city" className="form-label">City</label>
                                                        <p>{data.city}</p>
                                                    </div>
                                                    <div>
                                                        <label for="state" className="form-label">State</label>
                                                        <p>{data.state}</p>
                                                    </div>
                                                    <div>
                                                        <label for="zipcode" className="form-label">Zip Code</label>
                                                        <p>{data.zip_code}</p>
                                                    </div>
                                                    <div>
                                                        <label for="country" className="form-label">Country</label>
                                                        <p>{data.country}</p>
                                                    </div>
                                                    <div>
                                                        <label for="request" className="form-label">Special Request</label>
                                                        <p>{data.special_request}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Tab>
                            </Tabs>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    )
}
