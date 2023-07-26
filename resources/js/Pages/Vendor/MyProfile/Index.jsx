//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
// import axios from 'axios';

export default function Index({ session,data,country }) {
 console.log(data,">>>>>>>data user");
 const { url } = usePage();
 const [selectcountry,setCountry] = useState('');
 const [busisnessname,setBusisness] = useState('');
 const [email,setEmail] = useState('');
 const [firstname,setFirstName] = useState('');
 const [lastname,setLasttName] = useState('');
 const [phone,setPhone] = useState('');
 const [logo,setlogo] = useState(null);
 const [address,setAddress] = useState('');
 const [address2,setAddress2] = useState('');
 const [city,setCity] = useState('');
 const [state,setState] = useState('');


 const handleFileChange = (e) => {
    setlogo(e.target.files[0]);
  };

  const storePost = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('country', selectcountry ? selectcountry : data[0].country);
    formData.append('busisnessname', busisnessname ? busisnessname : data[0].vendor_name);
    formData.append('email', email ? email : data[0].users.email);
    formData.append('firstname',firstname ? firstname : data[0].first_name);
    formData.append('lastname', lastname ? lastname : data[0].last_name);
    formData.append('phone', phone ? phone : data[0].phone);
    formData.append('logo', logo ? logo : data[0].logo_img);
    formData.append('address', address ? address : data[0].address_line1);
    formData.append('address2', address2 ? address2 : data[0].address_line2);
    formData.append('city', city ? city : data[0].city);
    formData.append('state', state ? state : data[0].state);

    Inertia.post('/myprofile/update', formData, {
        onSuccess: () => {
            // Lakukan aksi setelah gambar berhasil diunggah
          },
    });
}
    // axios.get('/api/country')
    //     .then(response => {
    //     // Proses data yang diterima
    //     const data = response.data;
    //     console.log(data);
    //     setCountry(data);
    //     })
    //     .catch(error => {
    //     // Tangani kesalahan
    //     console.error(error);
    //     });
  return (
    <>
    <Layout page={url}>
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
                <div className="card">
                    <div className="card-body">
                       <form onSubmit={storePost}>
                        <div className="row">
                            <div className="col-lg-6">
                                <p style={{fontWeight:"bold"}}>Personal Information</p>
                                <div>
                                    <label for="busisnessname" className="form-label">Business name</label>
                                    <div className="input-group mb-3">
                                    <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-building'></i></span>
                                        <input defaultValue={data[0].vendor_name} onChange={(e) => setBusisness(e.target.value)} type="text" className="form-control" placeholder="Business name" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                    </div>
                                </div>
                               <div>
                               <label for="Email" className="form-label">Email</label>
                                <div className="input-group mb-3">
                                <span className="input-group-text rounded-0" id="basic-addon1"><i className="fa fa-envelope" aria-hidden="true"></i></span>
                                    <input defaultValue={data[0].users.email} onChange={(e)=>setEmail(e.target.value)} type="email" className="form-control" placeholder="E-mail" aria-label="email" aria-describedby="basic-addon1"/>
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
                                    <label for="Lastname" className="form-label">Phone Number</label>
                                    <div className="input-group mb-3">
                                        <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-phone'></i></span>
                                        <input defaultValue={data[0].phone} type="text" onChange={(e)=>setPhone(e.target.value)} className="form-control" placeholder="Phone Number" aria-label="Username" aria-describedby="basic-addon1"/>
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
                                <img style={{width:"100px"}} src={data[0].logo_img} alt="" />
                                </div>
                                <div className="mb-3">
                                <label for="formFile" className="form-label">Logo</label>
                                <input className="form-control" onChange={handleFileChange} type="file" id="formFile"/>
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
                                        <input onChange={(e)=>setAddress2(e.target.value)} defaultValue={data[0].address_line2} type="text" className="form-control" placeholder="Address Line 2" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                    </div>
                                </div>
                                <div>
                                    <label for="Lastname" className="form-label">City <span className='text-danger'>*</span></label>
                                    <div className="input-group mb-3">
                                    <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-street-view'></i></span>
                                        <input onChange={(e)=>setCity(e.target.value)} defaultValue={data[0].city} type="text" className="form-control" placeholder="City" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                    </div>
                                </div>
                                <div>
                                    <label for="Lastname" className="form-label">State <span className='text-danger'>*</span></label>
                                    <div className="input-group mb-3">
                                    <span className="input-group-text rounded-0" id="basic-addon1"><i className='fa fa-map-signs'></i></span>
                                        <input onChange={(e)=>setState(e.target.value)} defaultValue={data[0].state} type="text" className="form-control" placeholder="State" aria-label="busisnessname" aria-describedby="basic-addon1"/>
                                    </div>
                                </div>
                                <div className='mb-3'>
                                    <label for="Lastname" className="form-label">Country <span className='text-danger'>*</span></label>
                                    <select onChange={(e)=>setCountry(e.target.value)} className="form-control" aria-label="Default select example">
                                        {Object.keys(country).map(key => (
                                              <option key={key} selected={country[key]===data[0].country} value={country[key]}>{country[key]}</option>
                                        ))}
                                    </select>
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
                        <hr />
                        <button type='submit' className='btn btn-primary'>
                        <i className='fa fa-save'></i>
                            save 
                        </button>
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