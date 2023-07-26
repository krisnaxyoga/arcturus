//import React
import React,{ useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../../Layouts/Vendor';

//import Link
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function PriceAgentRoom({ props,session,data }) {

    const [price, setPrice] = useState();

    const [taxChecked, setTaxChecked] = useState(false);
    const [taxValue, setTaxValue] = useState('');
    const [serviceChecked, setServiceChecked] = useState(false);
    const [serviceValue, setServiceValue] = useState('');

    // useEffect(() => {
    //     // Mendapatkan nilai dari database
    //     const taxValue = data.tax; // Lakukan operasi untuk mendapatkan nilai dari database
    //     const serviceValue = data.service;
    //     // Mengubah nilai menjadi bentuk boolean
    //     setServiceChecked(serviceValue !== 0);
    //     setTaxChecked(taxValue !== 0);
    //   }, []);

    // const handleTaxChange = (e) => {
    // setTaxChecked(e.target.checked);
    // if (e.target.checked) {
    //     setTaxValue(e.target.value);
    // } else {
    //     setTaxValue(0);
    // }
    // };
    // const handleServiceChange = (e) => {
    //     setServiceChecked(e.target.checked);
    //     if (e.target.checked) {
    //         setServiceValue(e.target.value);
    //     } else {
    //         setServiceValue(0);
    //     }
    //     };
    console.log(taxValue,">>>>>>>>value");
    console.log(serviceValue,">>>>>>>>>>>>>service");

    // const handleChangestart = (e, index) => {
    //     const updatedAdult = [...startdate];
    //     updatedAdult[index].value = e.target.value;
    //     setStartdate(updatedAdult);
    //   };


    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('service', serviceValue !== '' ? serviceValue : data.service);
        formData.append('tax', taxValue !== '' ? taxValue : data.tax);
        formData.append('markup', price || data.markup_price);

        Inertia.post(`/room/markup/update/${data.id}`, formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
              },
        });
    }
    // if (data.length > 0) {
    //     const dprice = data[0].price;
    //     setPrice(dprice);
    //   }
  return (
    <>
    <Layout>
        <div className="container">
            <div className="row">
                <h1>Pricing set up</h1>
                <div className="col-lg-11">
                    <form onSubmit={storePost}>
                        <div className="card">
                            <div className="card-body">
                            {session.success && (
                                <div className="alert alert-success border-0 shadow-sm rounded-3">
                                    {session.success}
                                </div>
                            )}
                                <div className="row">
                                    <div className="col-lg-12">
                                        <div className="row">
                                            <div className="col-lg-6">
                                                <div className="mb-3">
                                                    <label htmlFor="" className='fw-bold'>min mark-up</label>
                                                    <input defaultValue={data.markup_price} onChange={(e)=>setPrice(e.target.value)} type="number" className='form-control'/>
                                                </div>
                                            </div>
                                            <div className="col-lg-6">
                                                    <div className="mb-3">
                                                            <div className='ml-5 mt-4'>
                                                                <label htmlFor="" className='fw-bold'>
                                                                <input type="text"
                                                                className='form-control'
                                                                onChange={(e)=>setTaxValue(e.target.value)}
                                                                value={data.tax}/>% tax
                                                                </label>
                                                            </div>

                                                            <div className='ml-5'>
                                                                <label htmlFor="" className='fw-bold'>
                                                                <input type="text"
                                                                className='form-control'
                                                                onChange={(e)=>setServiceValue(e.target.value)}
                                                                value={data.service}/>% service
                                                                </label>
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <button type='submit' className='btn btn-primary'>
                                <i className='fa fa-save'></i>
                                    save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Layout>
    </>
  )
}
