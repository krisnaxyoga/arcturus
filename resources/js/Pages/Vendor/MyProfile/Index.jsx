//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
// import axios from 'axios';

import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';

export default function Index({ session,data,country,vendor,markup,banner,property }) {
//  console.log(data,">>>>>>>data user");
 const { url } = usePage();
 const [selectcountry,setCountry] = useState('');
 const [type_property,setProperty] = useState('');
 const [busisnessname,setBusisness] = useState('');
 const [email,setEmail] = useState('');
 const [firstname,setFirstName] = useState('');
 const [lastname,setLasttName] = useState('');
 const [phone,setPhone] = useState('');
 const [logo,setLogo] = useState(null);
 const [address,setAddress] = useState('');
 const [address2,setAddress2] = useState('');
 const [city,setCity] = useState('');
 const [state,setState] = useState('');
 const [description,setDesc] = useState('');
 const [highlight,setHight] = useState('');

 const [taxValue, setTaxValue] = useState('');
 const [bankname, setBankName] = useState('');
 const [bankaccount, setBankAccount] = useState('');
 const [bankaddress,setBankAddress] = useState('');
 const [accountnumber, setAccountNumber] = useState('');
 const [swifcode, setSwifCode] = useState('');

 const[emailreservation, setEmailReservation] = useState('');

 const [imgbanner,setBanner] = useState(null);
 const [title,setTitle] = useState('');
 const [descbanner,setDescbanner] = useState('');


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
                    alert('File size must not exceed 5 MB!, otherwise contact admin');
                    e.target.value = ''; // Mengosongkan input file
                } else {
                    setLogo(file);
                }
            }
        };

        const handleBanner = (e) => {
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
                    alert('File size must not exceed 5 MB!, otherwise contact admin');
                    e.target.value = ''; // Mengosongkan input file
                } else {
                    setBanner(file);
                }
            }
        };
            

        // Tampilkan gambar pratinjau jika gambar ada
        const logoPreview = logo ? URL.createObjectURL(logo) : null;
        const bannerPreview = imgbanner ? URL.createObjectURL(imgbanner) : null;

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('country', selectcountry ? selectcountry : data[0].country);
        formData.append('busisnessname', busisnessname ? busisnessname : data[0].vendor_name);
        formData.append('email', email ? email : data[0].users.email);
        formData.append('firstname',firstname ? firstname : data[0].users.first_name);
        formData.append('lastname', lastname ? lastname : data[0].users.last_name);
        formData.append('phone', phone ? phone : data[0].phone);
        formData.append('logo', logo ? logo : data[0].logo_img);
        formData.append('address', address ? address : data[0].address_line1);
        formData.append('address2', address2 ? address2 : data[0].address_line2);
        formData.append('city', city ? city : data[0].city);
        formData.append('state', state ? state : data[0].state);
        // formData.append('description', description ? description : data[0].description);
        formData.append('highlight', highlight ? highlight : data[0].highlight);
        formData.append('type_property', type_property ? type_property : data[0].type_property);

        formData.append('tax', taxValue ? taxValue : markup.tax);
        formData.append('bank', bankname ? bankname : data[0].bank_name);
        formData.append('bankaccount', bankaccount ? bankaccount : data[0].bank_account);
        formData.append('swifcode', swifcode ? swifcode : data[0].swif_code);
        formData.append('bankaddress', bankaddress ? bankaddress : data[0].bank_address);
        formData.append('accountnumber', accountnumber ? accountnumber : data[0].account_number);
        formData.append('email_reservation',emailreservation ? emailreservation : data[0].email_reservation);

        Inertia.post('/myprofile/update', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }

    const storeBanner = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('title', title);
        // formData.append('description', descbanner);
        formData.append('banner', imgbanner);

        Inertia.post('/myprofile/slider/store', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah

            },
        });

    }

  return (
    <>
    <Layout page={url} vendor={vendor}>
        <div className="container">
            <h1>Settings</h1>
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
                                                    <p style={{fontWeight:"bold"}}>Personal Information</p>
                                                    <div>
                                                        <label for="busisnessname" className="form-label">Hotel Name</label>
                                                        <div className="input-group mb-3">
                                                        <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-building'></i></span>
                                                            <input defaultValue={data[0].vendor_name} onChange={(e) => setBusisness(e.target.value)} type="text" className="form-control" placeholder="Business name" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                                        </div>
                                                    </div>
                                                <div>
                                                <label for="Email" className="form-label">Email Sales</label>
                                                    <div className="input-group mb-3">
                                                    <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-envelope" aria-hidden="true"></i></span>
                                                        <input defaultValue={data[0].users.email} onChange={(e)=>setEmail(e.target.value)} type="email" inputMode="email" className="form-control" placeholder="E-mail" aria-label="email" aria-describedby="basic-addon1"/>
                                                    </div>
                                                    <label for="Email" className="form-label">Email Reservation</label>
                                                <div className="input-group mb-3">
                                                <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-envelope" aria-hidden="true"></i></span>
                                                    <input defaultValue={data[0].email_reservation} onChange={(e)=>setEmailReservation(e.target.value)} type="email" inputMode="email" className="form-control" placeholder="E-mail" aria-label="email" aria-describedby="basic-addon1"/>
                                                </div>
                                                </div>


                                                    <div className="d-flex">
                                                        <div className='mr-2'>
                                                            <label for="Firstname" className="form-label">First name</label>
                                                            <div className="input-group mb-3 mr-2">
                                                            <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-user'></i></span>
                                                                <input defaultValue={data[0].users.first_name} onChange={(e) =>setFirstName(e.target.value)} type="text" className="form-control" placeholder="First Name" aria-label="first name" aria-describedby="basic-addon1"/>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label for="Lastname" className="form-label">Last name</label>
                                                            <div className="input-group mb-3">
                                                            <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-user'></i></span>
                                                                <input defaultValue={data[0].users.last_name} onChange={(e)=>setLasttName(e.target.value)} type="text" className="form-control" placeholder="Last name" aria-label="lastname" aria-describedby="basic-addon1"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="Lastname" className="form-label">Phone Number <span className='text-danger'>*</span></label>
                                                        <div className="input-group mb-3">
                                                            <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-phone'></i></span>
                                                            <input required defaultValue={data[0].phone} type="text" onChange={(e)=>setPhone(e.target.value)} className="form-control" placeholder="Phone Number" aria-label="Username" aria-describedby="basic-addon1"/>
                                                        </div>
                                                    </div>
                                                    {/* <div>
                                                        <label for="Lastname" className="form-label">Birthday</label>
                                                        <div className="input-group mb-3">
                                                        <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-birthday-cake" aria-hidden="true"></i></span>
                                                            <input type="date" className="form-control" placeholder="Birthday" aria-label="Username" aria-describedby="basic-addon1"/>
                                                        </div>
                                                    </div> */}
                                                    
                                                    <div className="mb-3">
                                                        <label for="formFile" className="form-label">Logo</label>
                                                        <input className="form-control" onChange={handleFileChange} type="file" id="formFile"/>
                                                    </div>
                                                    <div className="mb-3">
                                                    {logoPreview ? (
                                                        <>
                                                        {logoPreview && <img src={logoPreview} alt="Logo Preview" style={{width:'100px'}}/>}
                                                        </>
                                                    ):(
                                                        <>
                                                        <img style={{width:"100px"}} src={data[0].logo_img||'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Image_not_available.png/640px-Image_not_available.png'} alt="" />
                                                        </>
                                                    )}
                                                    </div>


                                                </div>
                                                <div className="col-lg-6">
                                                    <p style={{fontWeight:"bold"}}>Location Information</p>
                                                    <div>
                                                        <label for="Lastname" className="form-label">Address Line 1</label>
                                                        <div className="input-group mb-3">
                                                        <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-location-arrow" aria-hidden="true"></i></span>
                                                            <input onChange={(e)=>setAddress(e.target.value)} defaultValue={data[0].address_line1} type="text" className="form-control" placeholder="Address Line 1" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="Lastname" className="form-label">Address Line 2</label>
                                                        <div className="input-group mb-3">
                                                        <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-location-arrow" aria-hidden="true"></i></span>
                                                            <input onChange={(e)=>setAddress2(e.target.value)} defaultValue={data && data[0].address_line2 == null ? data[0].address_line2 : ''} type="text" className="form-control" placeholder="Address Line 2" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="Lastname" className="form-label">City </label>
                                                        <div className="input-group mb-3">
                                                        <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-street-view'></i></span>
                                                            <input onChange={(e)=>setCity(e.target.value)} defaultValue={data[0].city} type="text" className="form-control" placeholder="City" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                                            {/* <p style={{ fontSize:'11px' }} className='text-danger'>admin@arcturus.my.id</p> */}

                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="Lastname" className="form-label">State </label>
                                                        <div className="input-group mb-3">
                                                        <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-map-signs'></i></span>
                                                            <input onChange={(e)=>setState(e.target.value)} defaultValue={data[0].state} type="text" className="form-control" placeholder="State" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                                            {/* <p style={{ fontSize:'11px' }} className='text-danger'>if you want to change country, you must send an email to admin@arcturus.my.id</p> */}

                                                        </div>
                                                    </div>
                                                    <div className="mb-3">
                                                        <label htmlFor="">Property Type</label>
                                                         <select required onChange={(e)=>setProperty(e.target.value)} className="form-control" aria-label="Default select example">
                                                            {Object.keys(property).map(key => (
                                                                <option key={key} selected={property[key] === data[0].type_property} value={property[key]}>{property[key]}</option>
                                                            ))}
                                                        </select>
                                                    </div>
                                                    <div className='mb-3'>
                                                        <label for="Lastname" className="form-label">Country <span className='text-danger'>*</span></label>
                                                        <input readOnly type="text" value={data[0].country} className='form-control'/>
                                                        <p style={{ fontSize:'11px' }} className='text-danger'>admin@arcturus.my.id</p>
                                                            {/* <p style={{ fontSize:'11px' }} className='text-danger'>if you want to change country, you must send an email to admin@arcturus.my.id</p> */}

                                                        {/* <select required onChange={(e)=>setCountry(e.target.value)} className="form-control" aria-label="Default select example">
                                                            {Object.keys(country).map(key => (
                                                                <option key={key} selected={country[key]===data[0].country} value={country[key]}>{country[key]}</option>
                                                            ))}
                                                        </select> */}
                                                    </div>
                                                    <div className="mb-3">
                                                        <div className="row">
                                                            <div className="col-lg-12">
                                                                <div htmlFor="" className='text-info d-flex'><p>* all rates are inclusive of </p>
                                                                    {/* <input style={{width: '3rem'}} defaultValue={markup && markup.tax} type="text" className='form-control' placeholder='...%' onChange={(e)=>setTaxValue(e.target.value)} />  */}
                                                                    {/* <span className='ml-2 text-warning'>% </span> */}
                                                            <p>&nbsp;any goverment tax & service charge</p></div>

                                                            </div>
                                                            </div>
                                                        {/* <div className="row justify-content-center">

                                                            <div className="col-lg-4 d-flex">
                                                                <label htmlFor="" className='mr-2'>tax</label>
                                                                <input style={{width: '3rem'}} defaultValue={markup && markup.tax} type="text" className='form-control' placeholder='...%' onChange={(e)=>setTaxValue(e.target.value)} /> <span className='ml-2 text-warning'>%</span>
                                                            </div>
                                                        </div> */}
                                                    </div>
                                                    {/* <div>
                                                        <label for="Lastname" className="form-label">Zip Code</label>
                                                        <div className="input-group mb-3">
                                                        <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-map-pin'></i></span>
                                                            <input type="text" className="form-control" placeholder="Zip code" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                                        </div>
                                                    </div> */}

                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="col-lg-12">
                                                    <div className="mb-3">
                                                        <label htmlFor="">Highlight</label>
                                                        <textarea onChange={(e)=>setHight(e.target.value)} className='form-control' cols="30" rows="10">{data[0].highlight}</textarea>
                                                    </div>
                                                    {/* <div className="mb-3">
                                                        <label htmlFor="">Description</label>
                                                        <textarea onChange={(e)=>setDesc(e.target.value)} className='form-control' cols="30" rows="10">{data[0].description}</textarea>
                                                    </div> */}
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
                                <Tab eventKey="credit" title="Bank account">
                                        <div className="card mb-4">
                                            <div className="card-header">
                                                <h2>Bank Account</h2>
                                            </div>
                                            <div className="card-body">
                                                <form onSubmit={storePost}>
                                                    <div className="row">

                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="">Account Name</label>
                                                                <input defaultValue={data[0].bank_account} type="text" className='form-control' onChange={(e) => setBankAccount(e.target.value)}/>
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="">Bank Name</label>
                                                                <input defaultValue={data[0].bank_name} type="text" className='form-control' onChange={(e) => setBankName(e.target.value)}/>
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="">Account Number</label>
                                                                <input defaultValue={data[0].account_number} type="text" className='form-control' onChange={(e) => setAccountNumber(e.target.value)}/>
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="">Bank Address</label>
                                                                <input defaultValue={data[0].bank_address} type="text" className='form-control' onChange={(e) => setBankAddress(e.target.value)}/>
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="">Swift code</label>
                                                                <input defaultValue={data[0].swif_code} type="text" className='form-control' onChange={(e) => setSwifCode(e.target.value)}/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="row">
                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <button className='btn btn-primary' type='submit'> <i className='fa fa-save'></i> save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                </Tab>
                                <Tab eventKey="slider" title="Banner">
                                        <div className="row">
                                            <div className="col-lg-6">
                                                <div className="card mb-3">
                                                    <div className="card-body">
                                                        <form onSubmit={storeBanner}>
                                                            <div className="row">
                                                                <div className="col-lg-12">
                                                                    <div className="mb-3">
                                                                        <label htmlFor="">title</label>
                                                                        <input onChange={(e)=>setTitle(e.target.value)} type="text" className='form-control' />
                                                                    </div>
                                                                    <div className="mb-3">
                                                                    {bannerPreview && <img className='mb-3' src={bannerPreview} alt="Banner Preview" style={{width:'200px'}}/>}
                                                                        <label htmlFor="">image</label>
                                                                        <input onChange={handleBanner} type="file" className='form-control' />
                                                                    </div>
                                                                </div>
                                                                {/* <div className="col-lg-6">
                                                                    <div className="mb-3">
                                                                        <label htmlFor="">description</label>
                                                                        <input onChange={(e)=>setDescbanner(e.target.value)} type="text" className='form-control' />
                                                                    </div>
                                                                </div> */}
                                                            </div>
                                                            <div className="row">
                                                                <div className="col-lg-12">
                                                                    <div className="mb-3">
                                                                        <button className='btn btn-primary' type='submit'><i className='fa fa-save'></i> save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col-lg-6">
                                                <div className="card">
                                                    <div className="card-body">
                                                        <div className="table-responsive">
                                                            <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>image</th>
                                                                        <th>title</th>
                                                                        {/* <th>description</th> */}
                                                                        <th>action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    {banner.map((item)=>(
                                                                        <>
                                                                        <tr key={item.id}>
                                                                            <td><img src={item.image} alt={item.image} style={{ width:'200px' }} /></td>
                                                                            <td>{item.title}</td>
                                                                            {/* <td>{item.description}</td> */}
                                                                            <td>
                                                                            <Link className='btn btn-datatable btn-icon btn-transparent-dark mr-2' href={`/myprofile/slider/delete/${item.id}`}>
                                                                                <i className='fa fa-trash'></i>
                                                                            </Link>
                                                                            </td>
                                                                        </tr>
                                                                        </>
                                                                    ))}
                                                                </tbody>
                                                            </table>
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
