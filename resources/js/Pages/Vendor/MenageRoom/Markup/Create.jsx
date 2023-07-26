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

    const [startdate, setStartdate] = useState([{ start: '',end:'', id: 1 }]);
    const [sucharge, setSucharge] = useState([{ start: '',end:'',price:'', id: 1 }]);
    const [taxChecked, setTaxChecked] = useState(false);
    const [taxValue, setTaxValue] = useState('');
    const [serviceChecked, setServiceChecked] = useState(false);
    const [serviceValue, setServiceValue] = useState('');


    // const handleChangestart = (e, index) => {
    //     const updatedAdult = [...startdate];
    //     updatedAdult[index].value = e.target.value;
    //     setStartdate(updatedAdult);
    //   };

    console.log(startdate,">>>>>start date");


    console.log(sucharge,">>>>>start date suchar");
      const handleChangestart = (e, index, field) => {
        const newDate = [...startdate];
        newDate[index][field] = e.target.value;
        setStartdate(newDate);
      };
      const handleAddInputstart = () => {
        const newDate = [...startdate];
        const newId = startdate.length + 1; // Menentukan id baru untuk objek yang ditambahkan
        newDate.push({ start: '', end: '', id: newId });
        setStartdate(newDate);
        // setStartdate([...startdate, { value: '', id: startdate.length + 1 }]);
      };

      const handleRemoveInputstart = (index) => {
        const updatedAdult = [...startdate];
        updatedAdult.splice(index, 1);
        setStartdate(updatedAdult);
      };

      //sucharge function

      const handleChangeSuchar = (e, index, field) => {
        const newDate = [...sucharge];
        newDate[index][field] = e.target.value;
        setSucharge(newDate);
      };
      const handleAddInputSuchar = () => {
        const newDate = [...sucharge];
        const newId = sucharge.length + 1; // Menentukan id baru untuk objek yang ditambahkan
        newDate.push({ start: '', end: '',price:'', id: newId });
        setSucharge(newDate);
      };

      const handleRemoveInputSuchar = (index) => {
        const updatedAdult = [...sucharge];
        updatedAdult.splice(index, 1);
        setSucharge(updatedAdult);
      };


    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('service', serviceValue);

        formData.append('tax', taxValue);

        formData.append('markup', price);

        startdate.forEach((adp, index) => {
            formData.append(`blockout[${index}][start]`, adp.start);
            formData.append(`blockout[${index}][end]`, adp.end);
            formData.append(`blockout[${index}][id]`, adp.id);
        });
        sucharge.forEach((adp, index) => {
            formData.append(`surcharge[${index}][start]`, adp.start);
            formData.append(`surcharge[${index}][end]`, adp.end);
            formData.append(`surcharge[${index}][price]`, adp.price);
            formData.append(`surcharge[${index}][id]`, adp.id);
        });
        Inertia.post('/room/agent/price', formData, {
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
                                                    <input onChange={(e)=>setPrice(e.target.value)} type="number" className='form-control'/>
                                                </div>
                                            </div>
                                            <div className="col-lg-6">
                                                    <div className="mb-3">
                                                            <div className='ml-5 mt-4'>
                                                                <label htmlFor="" className='fw-bold'>
                                                                <input type="text"
                                                                className='form-control'
                                                                onChange={(e)=>setTaxValue(e.target.value)}
                                                                defaultValue={data.tax}/>% tax
                                                                </label>
                                                            </div>

                                                            <div className='ml-5'>
                                                                <label htmlFor="" className='fw-bold'>
                                                                <input type="text"
                                                                className='form-control'
                                                                onChange={(e)=>setServiceValue(e.target.value)}
                                                                defaultValue={data.service}/>% service
                                                                </label>
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div className="mb-3 ">
                                            <label htmlFor="" style={{ fontWeight:"bold" }}>Blackout Dates</label>
                                        {startdate.map((preference, index) => (
                                            <div key={preference.id} className='row'>
                                                <div className="col-lg-4">
                                                    <div className="mb-3">
                                                    <label htmlFor="">start date</label>
                                                        <input
                                                            type="date"
                                                            className='form-control'
                                                            value={preference.start}
                                                            onChange={(e) => handleChangestart(e, index, 'start')}
                                                        />
                                                    </div>
                                                    </div>
                                                    <div className="col-lg-4">
                                                        <div className="mb-3">
                                                            <label htmlFor="">end date</label>
                                                            <input
                                                                type="date"
                                                                className='form-control'
                                                                value={preference.end}
                                                                onChange={(e) => handleChangestart(e, index,'end')}
                                                            />
                                                        </div>

                                                </div>
                                                <div className="col-lg-2">
                                                    <button type="button" style={{ marginTop:"35px" }} className='btn btn-danger' onClick={() => handleRemoveInputstart(index)}>
                                                        <i className='fa fa-trash'></i>
                                                    </button>
                                                </div>
                                            </div>
                                            ))}
                                            <button type="button" className='btn btn-primary' onClick={handleAddInputstart}>
                                                <i className="fa fa-plus"></i> Add Preference
                                                </button>
                                        </div>
                                        <div className="mb-3 ">
                                            <label htmlFor="" style={{ fontWeight:"bold" }}>Sucharges</label>
                                        {sucharge.map((preference, index) => (
                                            <div key={preference.id} className='row'>
                                                <div className="col-lg-4">
                                                    <div className="mb-3">
                                                <label htmlFor="">start date</label>
                                                        <input
                                                            type="date"
                                                            className='form-control'
                                                            value={preference.start}
                                                            onChange={(e) => handleChangeSuchar(e, index, 'start')}
                                                        />
                                                        </div>
                                                        </div>
                                                        <div className="col-lg-4">
                                                        <div className="mb-3">
                                                        <label htmlFor="">end date</label>
                                                        <input
                                                            type="date"
                                                            className='form-control'
                                                            value={preference.end}
                                                            onChange={(e) => handleChangeSuchar(e, index,'end')}
                                                        />
                                                        </div>
                                                        </div>
                                                        <div className="col-lg-3">
                                                        <div className="mb-3">
                                                        <label htmlFor="">price</label>
                                                        <input
                                                            type="number"
                                                            className='form-control'
                                                            value={preference.price}
                                                            onChange={(e) => handleChangeSuchar(e, index,'price')}
                                                        />
                                                        </div>
                                                        </div>
                                                        <div className="col-lg-1">
                                                            <button style={{ marginTop:"35px" }} type="button" className='btn btn-danger' onClick={() => handleRemoveInputSuchar(index)}>
                                                            <i className='fa fa-trash'></i>
                                                            </button>
                                                        </div>
                                            </div>
                                            ))}
                                            <button type="button" className='btn btn-primary' onClick={handleAddInputSuchar}>
                                                <i className="fa fa-plus"></i> Add Preference
                                                </button>
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
