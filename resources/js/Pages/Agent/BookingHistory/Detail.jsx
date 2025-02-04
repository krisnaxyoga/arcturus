import React, { useState,useEffect } from 'react';
import Layout from '../../../Layouts/Agent';
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';
import jsPDF from 'jspdf';
import axios from "axios";

export default function Detail({ session, data, agent, contract, setting, roombooking, transport }) {
    // console.log(contract, ">>>>CONTRACT");

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
    }

    const [formData, setFormData] = useState({
        time_pickup: "",
        flight_time: "",
        pickup_confirmation: "",
    });
    useEffect(() => {
        const fetchTranspot = async () => {
            try {

                setFormData({
                    time_pickup: transport.time_pickup || "",
                    flight_time: transport.flight_time || "",
                    pickup_confirmation: transport.pickup_confirmation || "",

                });

            } catch (error) {
                console.error("Terjadi kesalahan:", error);
            }
        };
        fetchTranspot();
    }, []);

    const handleSubmit = async (event) => {
        event.preventDefault();

        try {
            const response = await axios.post(
                `/agent/bookinghistory/update/${transport.booking_id}`,
                formData
            );
            const agentPhoneNumber = transport.agenttransport.mobile_phone;
            const flightTime = formData.flight_time || 'N/A';
            const pickupTime = formData.time_pickup || 'N/A';

            const pickupconfirmation = formData.pickup_confirmation || 'N/A';

            const whatsappMessage = `Hello, there's an update regarding our booking. Please check. Guest Name: ${transport.guest_name}. Destination: ${transport.destination} Flight Number: ${flightTime}. Time Pickup: ${pickupTime}. Special Request: ${pickupconfirmation}`;

            // Checking if the agent's phone number is available before redirecting to WhatsApp
            if (agentPhoneNumber) {
                const whatsappLink = `https://api.whatsapp.com/send?phone=${agentPhoneNumber}&text=${encodeURIComponent(whatsappMessage)}`;
                window.open(whatsappLink);
            } else {
                console.error("Agent's phone number is unavailable.");
                // You might want to display a message that the phone number is unavailable for WhatsApp
            }


            window.location.reload();
            // console.log("Response:", response.data);
            // Lakukan sesuatu dengan response jika perlu
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
            // Tangani kesalahan jika POST gagal
        }
    };

    const handlePrintPDF = () => {
        const printContent = document.getElementById('print-card');
        if (printContent) {
            const originalContents = document.body.innerHTML;
            const printableContents = printContent.innerHTML;
            document.body.innerHTML = printableContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    };



    const createAndDownloadPDF = () => {
        const printContent2 = document.getElementById('print-card');

        if (printContent2) {
            // Mendapatkan lebar window untuk menyesuaikan skala gambar
            const windowWidth = 1600;

            html2canvas(printContent2, {
                scale: 2,
                windowWidth: windowWidth, // Menyesuaikan lebar kanvas dengan lebar window
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });
                pdf.addImage(imgData, 'PNG', 10, 10, 180, 200); // Menambahkan gambar tangkapan layar ke PDF
                pdf.save('document.pdf'); // Simpan PDF dengan nama "document.pdf"
            });
        }
      };

    const { url } = usePage();

    const handleChange = (event) => {
        const { name, value } = event.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    console.log(formData);
    return (
        <Layout page={'/agent/bookinghistory'} agent={agent}>

          <div className="container">
                <div className="row mb-3">
                    <div className="col-lg-12">
                        <div className="container">
                            <h1>Booking Detail</h1>
                            <hr />

                            <div className="card mb-3" id="print-card">
                                <div className="card-body">
                                    <div className="page-content container">
                                        {/* <div className="page-header text-blue-d2">
                                            <h1 className="page-title text-secondary-d1">
                                                Invoice
                                                <small className="page-info">
                                                    <i className="fa fa-angle-double-right text-80"></i>
                                                    ID: {data.booking_code}
                                                </small>
                                            </h1>

                                            <div className="page-tools">
                                                <div className="action-buttons">
                                                </div>
                                            </div>
                                        </div> */}

                                        <div className="container px-0">
                                            <div className="row mt-4">
                                                <div className="col-12 col-lg-12">
                                                    <div className="row justify-content-between">
                                                        <div className="col-4">
                                                            <div className="text-center text-150">

                                                            <img src={agent.vendors.logo_img} style={{ height: "20px", width: "30px" }} />
                                                            {/* <img src={agent.vendors.logo_img} style={{ height: "20px", width: "30px" }} /> */}
                                                            <br />
                                                                <span className="text-default-d3">{agent.vendors.vendor_name}</span>
                                                            </div>
                                                        </div>
                                                        <div className="col-4">
                                                            <div className="text-center text-150">

                                                            <img src={data.vendor.logo_img} style={{ height: "20px", width: "30px" }} />
                                                            {/* <img src={agent.vendors.logo_img} style={{ height: "20px", width: "30px" }} /> */}
                                                            <br />
                                                                <span className="text-default-d3">{data.vendor.vendor_name}</span>
                                                            </div>
                                                        </div>
                                                        {/* <div className="col-4">
                                                            <div className="text-center text-150">

                                                            <img src={setting.logo_image} style={{ height: "20px", width: "30px" }} />
                                                            <img src={agent.vendors.logo_img} style={{ height: "20px", width: "30px" }} />
                                                            <br />
                                                                <span className="text-default-d3">{setting.company_name}</span>
                                                            </div>
                                                        </div> */}
                                                    </div>

                                                    <hr className="row brc-default-l1 mx-n1 mb-4"/>

                                                    <div className="row">
                                                        <div className="col-sm-6">
                                                            <div>
                                                                <span className="text-sm text-grey-m2 align-middle">Guest Name: </span>
                                                                <span className="text-600 text-110 text-blue align-middle"> {data.first_name} {data.last_name}</span>
                                                            </div>
                                                            <div className="text-grey-m2 mb-3">
                                                                <div className="my-1">
                                                                    {data.address_line1}
                                                                </div>
                                                                <div className="my-1">
                                                                {data.city}, {data.state}, {data.country}
                                                                </div>
                                                                <div className="my-1"><i className="fa fa-phone fa-flip-horizontal text-secondary"></i> <b className="text-600">{data.phone}</b></div>
                                                                <div className="my-1"><i className="fa fa-envelope fa-flip-horizontal text-secondary"></i> <b className="text-600">{data.email}</b></div>
                                                            </div>
                                                            {/* <div>
                                                                <span className="text-400 text-grey-m2 align-middle">Night: </span>
                                                                <span className="text-400 text-110 text-blue align-middle"> {data.night}</span>
                                                            </div> */}
                                                            <div>
                                                                <span className="text-400 text-grey-m2 align-middle">Check in: </span>
                                                                <span className="text-400 text-110 text-blue align-middle"> {data.checkin_date}</span>
                                                            </div>
                                                            <div>
                                                                <span className="text-400 text-grey-m2 align-middle">Check out: </span>
                                                                <span className="text-400 text-110 text-blue align-middle"> {data.checkout_date}</span>
                                                            </div>
                                                        </div>

                                                        <div className="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                                                            <hr className="d-sm-none"/>
                                                            <div className="text-grey-m2">
                                                                <div className="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                                                    Invoice
                                                                </div>

                                                                <div className="my-2"><i className="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span className="text-600 text-90">ID:</span> {data.booking_code}</div>
                                                                <div className="my-2"><i className="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span className="text-600 text-90">Agent Name:</span> {data.users.first_name} {data.users.last_name}</div>

                                                                <div className="my-2"><i className="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span className="text-600 text-90">Booking Date:</span> {data.booking_date}</div>

                                                                <div className="my-2"><i className="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span className="text-600 text-90">Status: </span>
                                                                {data.booking_status === 'paid' ? (
                                                                                                    <>
                                                                                                        <span style={{padding:'6px'}} className="badge badge-success badge-pill px-25">
                                                                                                            {data.booking_status}
                                                                                                        </span>
                                                                                                    </>
                                                                                                ) : (
                                                                                                    <>
                                                                                                        <span style={{padding:'6px'}} className="badge badge-warning badge-pill px-25">
                                                                                                            {data.booking_status}
                                                                                                        </span>
                                                                                                    </>
                                                                                                )}
                                                                </div>



                                                            </div>

                                                        </div>
                                                    </div>

                                                    {/* ... Rest of the content ... */}
                                                    <div className="mt-4 mb-4">
                                                    <table className="table table-bordered table-hover">
                                                        <thead className="bgc-default-tp1 text-white">
                                                            <tr className="justify-content-between">
                                                                <th>QTY</th>
                                                                {/* <th>Room Rate</th> */}
                                                                <th>Room Type</th>
                                                                <th>#Pax</th>
                                                                <th>#Night</th>
                                                                {/* <th>Total Amount</th> */}
                                                            </tr>
                                                        </thead>
                                                        <tbody className="text-95 text-secondary-d3">
                                                        {roombooking.map((item, index) => {

                                                        //    const recomPrice = parseInt(item.contractprice.recom_price);
                                                        //    const systemMarkup = parseInt(item.vendors.system_markup);

                                                        //    const totalPrice = recomPrice + systemMarkup;
                                                            return (
                                                                <tr key={index} className="mb-2 mb-sm-0 py-25 justify-content-between">
                                                                    <td>{item.total_room}</td>
                                                                    {/* <td>{formatRupiah(totalPrice)}</td> */}
                                                                    {/* <td>{item.room_name} {roombooking.length > 1 ? (<span className='badge badge-dark'>{item.contract_name}</span>):(<></>)} </td> */}
                                                                    <td>{item.room_name} <span className='badge badge-dark'>{item.contract_name}</span> </td>
                                                                    <td>{data.total_guests}</td>
                                                                    <td>{data.night}</td>
                                                                    {/* <td>{formatRupiah(data.price)}</td> */}
                                                                </tr>
                                                            );
                                                        })}
                                                        </tbody>
                                                    </table>

                                                    {transport ? (
                                                        <>
                                                        <h4>Transport</h4>
                                                        <table className='table table-bordered table-hover'>
                                                            <thead className="bgc-default-tp1 text-white">
                                                                <tr className="justify-content-between">
                                                                    <th>Car</th>
                                                                    {/* <th>Room Rate</th> */}
                                                                    <th>Destination</th>
                                                                    <th>Pickup Date</th>
                                                                    <th>Pickup Time</th>
                                                                    {/* <th>Total Amount</th> */}
                                                                </tr>
                                                            </thead>

                                                            <tbody className="text-95 text-secondary-d3">
                                                                <tr className="mb-2 mb-sm-0 py-25 justify-content-between">
                                                                    <td>
                                                                        {transport.typecar}
                                                                    </td>
                                                                    <td>
                                                                        {transport.destination}
                                                                    </td>
                                                                    <td>
                                                                        {transport.pickup_date}
                                                                    </td>
                                                                    <td>
                                                                        {transport.time_pickup}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        </>
                                                    ) : (
                                                        <>
                                                        </>
                                                    )}

                                                            {roombooking.length > 1 ? (
                                                                    <>
                                                                        {roombooking.map((item, index) => (
                                                                       <>
                                                                       <div className='row' key={index}>
                                                                                    <div className="col-12 mt-2">
                                                                                        <div className="d-flex">
                                                                                             <p className='m-0 text-primary' style={{fontWeight:'700'}}>{item.room_name}</p>
                                                                                        <p className='badge badge-dark mx-2 mt-1'>{item.contract_name}</p>
                                                                                        </div>
                                                                                       
                                                                                    </div>
                                                                                    <div className="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                                                                        <div>
                                                                                            <div className='m-0'>
                                                                                                <p style={{ padding: "5px 0",margin:'0px',fontSize:'18px',fontWeight:'700' }}>Benefits :</p>
                                                                                                <div className='m-0' dangerouslySetInnerHTML={{ __html: item.benefit_policy.substring(0, 1500) }}></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div>
                                                                                            <p style={{ padding: "5px 0",margin:'0px',fontSize:'18px',fontWeight:'700' }}>Other Conditions :</p>
                                                                                            {item.other_policy && (
                                                                                                <div dangerouslySetInnerHTML={{ __html: item.other_policy.substring(0, 250) }}></div>
                                                                                                )}
                                                                                        </div>
                                                                                    </div>

                                                                                    <div className="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                                                                        <div className='text-right'>
                                                                                            <p style={{ padding: "5px 0",margin:'0px',fontSize:'18px',fontWeight:'700' }}>Cancellation Policy :</p>
                                                                                            <p>
                                                                                            {item.cencellation_policy && (
                                                                                                <div dangerouslySetInnerHTML={{ __html: item.cencellation_policy.substring(0, 1250) }}></div>
                                                                                                )}
                                                                                            </p>
                                                                                        </div>

                                                                                        <div className='text-right'>
                                                                                            <p style={{ padding: "5px 0",margin:'0px',fontSize:'18px',fontWeight:'700' }}>Deposit Policy :</p>
                                                                                            <p>
                                                                                                {item.deposit_policy && (
                                                                                                    <div dangerouslySetInnerHTML={{ __html: item.deposit_policy.substring(0, 1250) }}></div>
                                                                                                )}
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                       </div>
                                                                       </>
                                                                        ))}
                                                                    </>
                                                                    ) : (
                                                                        
                                                                    <>
                                                                    {roombooking.map((item, index) => (
                                                                        <>
                                                                            <div className="row mt-3">
                                                                                <div className="col-12 mt-2">
                                                                                    <div className="d-flex">
                                                                                            <p className='m-0 text-primary' style={{fontWeight:'700'}}>{item.room_name}</p>
                                                                                    <p className='badge badge-dark mx-2 mt-1'>{item.contract_name}</p>
                                                                                    </div>
                                                                                    
                                                                                </div>
                                                                                <div className="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                                                                        <div>
                                                                                            <p style={{ padding: "5px 0",margin:'0px',fontSize:'18px',fontWeight:'700' }}>Benefits :</p>
                                                                                                <div dangerouslySetInnerHTML={{ __html: item.benefit_policy.substring(0, 250) }}></div>
                                                                                        </div>
                                                                                        <div>
                                                                                            <p style={{ padding: "5px 0",margin:'0px',fontSize:'18px',fontWeight:'700' }}>Other Conditions :</p>
                                                                                            {item.other_policy && (
                                                                                                <div dangerouslySetInnerHTML={{ __html: item.other_policy.substring(0, 250) }}></div>
                                                                                                )}
                                                                                        </div>
                                                                                        </div>

                                                                                        <div className="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">

                                                                                            {/* <div className="row my-2 align-items-center bgc-primary-l3 p-2">
                                                                                                <div className="col-7 text-right">
                                                                                                    Total Room :
                                                                                                </div>
                                                                                                <div className="col-5">
                                                                                                    <span className="text-400 text-success-d3 opacity-2">{data.total_room}</span>
                                                                                                </div>
                                                                                            </div> */}
                                                                                            <div className='text-right'>
                                                                                                <p style={{ padding: "5px 0",margin:'0px',fontSize:'18px',fontWeight:'700' }}>Cancellation Policy :</p>
                                                                                                <p>
                                                                                                {item.cencellation_policy && (
                                                                                                    <div dangerouslySetInnerHTML={{ __html: item.cencellation_policy.substring(0, 250) }}></div>
                                                                                                    )}
                                                                                                </p>
                                                                                            </div>

                                                                                            <div className='text-right'>
                                                                                                <p style={{ padding: "5px 0",margin:'0px',fontSize:'18px',fontWeight:'700' }}>Deposit Policy :</p>
                                                                                                <p>
                                                                                                    {item.deposit_policy && (
                                                                                                        <div dangerouslySetInnerHTML={{ __html: item.deposit_policy.substring(0, 250) }}></div>
                                                                                                    )}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                            </div>
                                                                        </>
                                                                    ))}
                                                                   
                                                                    </>
                                                                    )}
                                                                    <div className="row">
                                                                        <div className="col-12 col-sm-7">
                                                                            <div>
                                                                                <span className="text-400 align-middle" style={{fontSize:'16px',fontWeight:'700'}}>Special Request: </span>
                                                                                <span className="text-400 text-110 align-middle"> {data.special_request}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                        <hr />

                                                        <div className='d-flex'>
                                                        <img src={setting.logo_image} style={{ height: "20px", width: "20px" }} /> <span className="mx-2 text-secondary-d1 text-105">Thank you for your business</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="d-flex justify-content-between mt-3">

                                {data.booking_status === 'unpaid' ? (
                                            <>
                                                <a href={`/paymentbookingpage/${data.id}`} className='btn btn-warning'>pay</a>
                                            </>
                                        ) : (
                                            <>
                                            <span className='d-flex'>
                                                <button className="btn btn-primary mr-2" onClick={handlePrintPDF}>
                                                    <i className="fa fa-file-pdf"></i> Print as PDF
                                                </button>
                                                <button className="btn btn-primary" onClick={createAndDownloadPDF}>
                                                    <i className="fa fa-download"></i> download PDF
                                                </button>
                                            </span>

                                            </>
                                        )}
                                <button onClick={() => history.back()} className="btn btn-danger ml-2">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {transport ? (
                    <>
                    <div className="container">
                    <hr />
                    <h3>Confirmation Transport</h3>
                    <div className="row">
                        <div className="col-lg-8">
                            <div className="card">
                                <div className="card-body">
                                    <form onSubmit={handleSubmit}>
                                        <label htmlFor="">Pickup Time</label>
                                        <input name='time_pickup' type="time" className='form-control mb-3' value={formData.time_pickup} onChange={handleChange}/>
                                        <label htmlFor="">Flight Number</label>
                                        <input type="text" name='flight_time' className='form-control mb-3' value={formData.flight_time} onChange={handleChange}/>
                                        <label htmlFor="">Special Request:</label>
                                        <textarea name="pickup_confirmation" className='form-control mb-3' value={formData.pickup_confirmation} onChange={handleChange}>
                                            {formData.pickup_confirmation}
                                        </textarea>
                                        <button type='submit' className='btn btn-primary'>
                                            send
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    </>
                ): (
                   <>
                   </>
                )

                }
            </div>

        </Layout>
    );
}
