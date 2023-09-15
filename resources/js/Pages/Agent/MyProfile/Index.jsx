//import React
import React, { useState } from 'react';

//import layout
import Layout from '../../../Layouts/Agent';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
// import axios from 'axios';

import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';

export default function Index({ session, data, contacts, country }) {
    console.log(data, ">>>>>>>data country >>>>>>>>");

    const { url } = usePage();

    //export default function Index({ session, data, country }) {
    //console.log(data[0].users, ">>>>>>>data users");
    const [selectcountry, setCountry] = useState('');
    const [busisnessname, setBusisness] = useState('');
    const [legalname, setLegalitas] = useState('');
    const [email, setEmail] = useState('');
    // const [firstname, setFirstName] = useState('');
    // const [lastname, setLasttName] = useState('');
    const [phone, setPhone] = useState('');
    const [logo, setlogo] = useState(null);
    const [address, setAddress] = useState('');
    const [address2, setAddress2] = useState('');
    const [city, setCity] = useState('');
    const [state, setState] = useState('');
    const [area, setArea] = useState('');
    const [location, setLocation] = useState('');
    const [latitude, setLatitude] = useState('');
    const [longitude, setLongitude] = useState('');
    const [bankname, setBankName] = useState('');
    const [bankaccount, setBankAccount] = useState('');

    const [bankaddress,setBankAddress] = useState('');
    const [accountnumber, setAccountNumber] = useState('');

    const [swifcode, setSwifCode] = useState('');
    const [limit, setCreditLimit] = useState(0);
    const [used, setCreditUsed] = useState(0);
    const [saldo, setCreditSaldo] = useState(0);

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            const fileNameParts = file.name.split('.');
            const fileExtension = fileNameParts[fileNameParts.length - 1].toLowerCase();
            const allowedExtensions = ['jpg', 'jpeg', 'png'];
            const maxFileSizeInBytes = 5 * 1024 * 1024; // 5 MB

            if (!allowedExtensions.includes(fileExtension)) {
                alert('Only image files with formats png, jpg, or jpeg are allowed!');
                e.target.value = ''; // Mengosongkan input file
            } else if (file.size > maxFileSizeInBytes) {
                alert('File size must not exceed 5 MB!');
                e.target.value = ''; // Mengosongkan input file
            } else {
                setlogo(file);
            }
        }
    };
// Tampilkan gambar pratinjau jika gambar ada
const logoPreview = logo ? URL.createObjectURL(logo) : null;

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('country', selectcountry ? selectcountry : data.vendors.country);
        formData.append('busisnessname', busisnessname ? busisnessname : data.vendors.vendor_name);
        formData.append('legalname', legalname ? legalname : data.vendors.vendor_legal_name);
        formData.append('email', email ? email : data.email);
        formData.append('phone', phone ? phone : data.phone);
        formData.append('logo', logo ? logo : data.vendors.logo_img);
        formData.append('address', address ? address : data.vendors.address_line1);
        formData.append('address2', address2 ? address2 : data.vendors.address_line2);
        formData.append('city', city ? city : data.vendors.city);
        formData.append('state', state ? state : data.vendors.state);
        formData.append('area', area ? area : data.vendors.area);
        formData.append('location', location ? location : data.vendors.location);
        formData.append('latitude', latitude ? latitude : data.vendors.map_latitude);
        formData.append('longitude', longitude ? longitude : data.vendors.map_longitude);
        formData.append('bank', bankname ? bankname : data.vendors.bank_name);
        formData.append('bankaccount', bankaccount ? bankaccount : data.vendors.bank_account);
        formData.append('swifcode', swifcode ? swifcode : data.vendors.swif_code);
        formData.append('limit', limit ? limit : data.vendors.credit_limit);
        formData.append('used', used ? used : data.vendors.credit_used);
        formData.append('saldo', saldo ? saldo : data.vendors.credit_saldo);
        formData.append('bankaddress', bankaddress ? bankaddress : data.vendors.bank_address);
        formData.append('accountnumber', accountnumber ? accountnumber : data.vendors.account_number);

        Inertia.post('/agent-profile/update', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }

    return (
        <>
            <Layout page={url} agent={data}>
                <div className="container">
                    <h1>My Profile</h1>
                    <hr />
                    <div className="row">
                        <div className="col-lg-12">
                            {session.success && (
                                <div className="alert alert-success border-0 shadow-sm rounded-3">
                                    {session.success}
                                </div>
                            )}
                            <Tabs variant="tabs" defaultActiveKey="profile"
                                id="fill-tab-example"
                                className="mb-3"
                                fill
                            >
                                <Tab eventKey="profile" title="General Information">
                                    <div className="card">
                                        <div className="card-body">
                                            <form onSubmit={storePost}>
                                                <div className="row">
                                                    <div className="col-lg-6">
                                                        {/* <p style={{ fontWeight: "bold" }}>Personal Information</p> */}
                                                        <div>
                                                            <label for="busisnessname" className="form-label">Agent name</label>
                                                            <div className="input-group mb-3">
                                                                <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-building'></i></span>
                                                                <input defaultValue={data.vendors.vendor_name} onChange={(e) => setBusisness(e.target.value)} type="text" className="form-control" placeholder="Business name" aria-label="busisnessname" aria-describedby="basic-addon1" />
                                                            </div>
                                                        </div>
                                                        {/* <div>
                                                            <label for="legalname" className="form-label">Legal name</label>
                                                            <div className="input-group mb-3">
                                                                <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-building"></i></span>
                                                                <input defaultValue={data.vendors.vendor_legal_name} onChange={(e) => setLegalitas(e.target.value)} type="text" className="form-control" placeholder="Legal name" aria-label="legalname" aria-describedby="basic-addon1" />
                                                            </div>
                                                        </div> */}

                                                        <div>
                                                            <label for="Lastname" className="form-label">Address Line 1</label>
                                                            <div className="input-group mb-3">
                                                                <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-location-arrow" aria-hidden="true"></i></span>
                                                                <input onChange={(e) => setAddress(e.target.value)} defaultValue={data.vendors.address_line1} type="text" className="form-control" placeholder="Address Line 1" aria-label="busisnessname" aria-describedby="basic-addon1" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label for="Lastname" className="form-label">Address Line 2</label>
                                                            <div className="input-group mb-3">
                                                                <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-location-arrow" aria-hidden="true"></i></span>
                                                                <input onChange={(e) => setAddress2(e.target.value)} defaultValue={data.vendors.address_line2} type="text" className="form-control" placeholder="Address Line 2" aria-label="busisnessname" aria-describedby="basic-addon1" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label for="Lastname" className="form-label">Phone Number</label>
                                                            <div className="input-group mb-3">
                                                                <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-phone'></i></span>
                                                                <input defaultValue={data.phone} type="text" onChange={(e) => setPhone(e.target.value)} className="form-control" placeholder="Phone Number" aria-label="Username" aria-describedby="basic-addon1" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label for="Email" className="form-label">Email</label>
                                                            <div className="input-group mb-3">
                                                                <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-envelope" aria-hidden="true"></i></span>
                                                                <input defaultValue={data.email} inputMode="email" onChange={(e) => setEmail(e.target.value)} type="email" className="form-control" placeholder="E-mail" aria-label="email" aria-describedby="basic-addon1" />
                                                            </div>
                                                        </div>

                                                        <div className="mb-3">
                                                            <label for="formFile" className="form-label">Logo</label>
                                                            <input className="form-control" onChange={handleFileChange} type="file" id="formFile" />
                                                        </div>
                                                        <div className="mb-3">
                                                        {logoPreview ? (
                                                                <>
                                                                {logoPreview && <img src={logoPreview} alt="Logo Preview" style={{width:'100px'}}/>}
                                                                </>
                                                            ):(
                                                                <>
                                                                <img style={{width:"100px"}} src={data.logo_img||'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Image_not_available.png/640px-Image_not_available.png'} alt="" />
                                                                </>
                                                            )}
                                                        </div>
                                                    </div>
                                                    <div className="col-lg-6">
                                                        {/* <p style={{ fontWeight: "bold" }}>Location Information</p> */}
                                                        <div className='mb-3'>
                                                            <label for="country" className="form-label">Country</label>
                                                            {/* <select onChange={(e) => setCountry(e.target.value)} className="form-control" aria-label="Default select example">
                                                                 {Object.keys(country).map(key => (
                                                                        <option key={key} selected={country[key]===data.vendors.country} value={country[key]}>{country[key]}</option>
                                                                ))}
                                                            </select> */}
                                                            <input type="text" readOnly value={data.vendors.country} className='form-control'/>
                                                            <p style={{ fontSize:'11px' }} className='text-danger'>admin@arcturus.my.id</p>
                                                        </div>
                                                        <div>
                                                            <label for="state" className="form-label">State</label>
                                                            <div className="input-group mb-3">
                                                                <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-map-signs'></i></span>
                                                                <input onChange={(e) => setState(e.target.value)} defaultValue={data.vendors.state} type="text" className="form-control" placeholder="State" aria-label="state" aria-describedby="basic-addon1" />
                                                                {/* <p style={{ fontSize:'11px' }} className='text-danger'>if you want to change country, you must send an email to admin@arcturus.my.id</p> */}

                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label for="city" className="form-label">City</label>
                                                            <div className="input-group mb-3">
                                                                <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-street-view'></i></span>
                                                                <input onChange={(e) => setCity(e.target.value)} defaultValue={data.vendors.city} type="text" className="form-control" placeholder="City" aria-label="city" aria-describedby="basic-addon1" />
                                                                {/* <p style={{ fontSize:'11px' }} className='text-danger'>if you want to change country, you must send an email to admin@arcturus.my.id</p> */}

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <button type='submit' className='btn btn-primary'>
                                                    <i className='fa fa-save'></i>
                                                    save
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </Tab>
                                <Tab eventKey="contact" title="Contact Information">
                                    <div className="row">
                                        <div className="col-lg-12">
                                            <div className="card">
                                                <div className="card-header">
                                                    <div className="d-flex justify-content-between">
                                                        <h2>Contact Lists</h2>
                                                        <div>
                                                            <Link href='/agent-profile/contact/create' className='btn btn-primary'> <i className='fa fa-plus'></i> add</Link>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="card-body">
                                                    <div className="table-responsive">
                                                        <table id="dataTable" width="100%" cellSpacing="0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Department</th>
                                                                    <th>Position</th>
                                                                    <th>Title</th>
                                                                    <th>First Name</th>
                                                                    <th>Last Name</th>
                                                                    <th>Email Address</th>
                                                                    <th>Telephone</th>
                                                                    <th>Actions</th>
                                                                    {/* <th>child price</th> */}
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {contacts.map((item, index) => (
                                                                    <>
                                                                        <tr key={index}>
                                                                            <td>{item.departement}</td>
                                                                            <td>{item.position}</td>
                                                                            <td>{item.title}</td>
                                                                            <td>{item.first_name}</td>
                                                                            <td>{item.last_name}</td>
                                                                            <td>{item.email}</td>
                                                                            <td>{item.mobile_phone}</td>
                                                                            <td>
                                                                                <Link className='btn btn-datatable btn-icon btn-transparent-dark mr-2' href={`/agent-profile/contact/edit/${item.id}`}>
                                                                                    <i className='fa fa-edit'></i>
                                                                                </Link>
                                                                                <Link href={`/agent-profile/contact/destroy/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
                                                                                    <i className='fa fa-trash'></i>
                                                                                </Link>
                                                                            </td>
                                                                        </tr>
                                                                    </>
                                                                ))}

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div className="mb-3">
                                                        {/* <Link href={`/room/barcodeprice/cekroom/${data[0].barroom.id}`} className='btn btn-success'><i className='fa fa-plus'></i> add room</Link> */}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Tab>
                                <Tab eventKey="credit" title="Bank account">
                                    <div className="row">
                                        <div className="col-lg-12">
                                            <div className="card">
                                                <div className="card-header">
                                                    <div className="d-flex justify-content-between">
                                                        <h2>Bank account</h2>
                                                    </div>
                                                </div>

                                                <form onSubmit={storePost}>
                                                <div className="card-body">
                                                    <div className="row">
                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="bank">Account Name</label>
                                                                <input defaultValue={data.vendors.bank_account} onChange={(e) => setBankAccount(e.target.value)} type="text" className='form-control' name='bankaccount' />
                                                            </div>
                                                            {/* <div className="mb-3">
                                                                <label htmlFor="limit">Credit Limit</label>
                                                                <input defaultValue={data.vendors.credit_limit} onChange={(e) => setCreditLimit(e.target.value)} type="text" className='form-control' name='limit' />
                                                            </div> */}
                                                        </div>
                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="bank">Bank Name</label>
                                                                <input defaultValue={data.vendors.bank_name} onChange={(e) => setBankName(e.target.value)} type="text" className='form-control' name='bankname' />
                                                            </div>
                                                        </div>

                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="bank">Account Number</label>
                                                                <input defaultValue={data.vendors.account_number} onChange={(e) => setAccountNumber(e.target.value)} type="text" className='form-control' name='bankname' />
                                                            </div>
                                                        </div>


                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="bank">Bank Address</label>
                                                                <input defaultValue={data.vendors.bank_address} onChange={(e) => setBankAddress(e.target.value)} type="text" className='form-control' name='bankname' />
                                                            </div>
                                                        </div>

                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="bank">Swift Code</label>
                                                                <input defaultValue={data.vendors.swif_code} onChange={(e) => setSwifCode(e.target.value)} type="text" className='form-control' name='swifcode' />
                                                            </div>
                                                            {/* <div className="mb-3">
                                                                <label htmlFor="saldo">Credit Saldo</label>
                                                                <input defaultValue={data.vendors.credit_saldo} onChange={(e) => setCreditSaldo(e.target.value)} type="text" className='form-control' name='saldo' readOnly />
                                                            </div> */}
                                                        </div>


                                                    </div>
                                                    <div className="row">
                                                        <div className="col-lg-12">
                                                            <button type='submit' className='btn btn-primary'>
                                                                <i className='fa fa-save'></i>
                                                                save
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                                </form>
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
