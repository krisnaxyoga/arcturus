//import React
import React, { useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../../../Layouts/Vendor';



export default function PricePromo({ props, session, data, markup, bardata }) {
    const [startdate, setStartdate] = useState([{ start: '', end: '', price: '', day: 0, id: 1 }]);
    const [counter, setCounter] = useState(1);

    const [advcode, setAdv] = useState("");
    const [ratecode, setRagecode] = useState("");

    const [night, setNight] = useState("");

    console.log(data);
    const handleChangestart = (e, index, field) => {
        const newDate = [...startdate];
        newDate[index][field] = e.target.value;
        setStartdate(newDate);
        const startDate = new Date(newDate[index].start);
        const endDate = new Date(newDate[index].end);

        if (!isNaN(startDate) && !isNaN(endDate)) {
            const selisihMilidetik = endDate - startDate;
            const selisihHari = selisihMilidetik / (1000 * 60 * 60 * 24);
            newDate[index].day = selisihHari;
            setStartdate(newDate);
        }
    };
    const handleAddInputstart = () => {
        const newDate = [...startdate];
        const newId = startdate.length + 1; // Menentukan id baru untuk objek yang ditambahkan
        newDate.push({ start: '', end: '', price: '', day: 0, id: newId });
        setStartdate(newDate);
        // setStartdate([...startdate, { value: '', id: startdate.length + 1 }]);
    };

    const handleRemoveInputstart = (index) => {
        const updatedAdult = [...startdate];
        updatedAdult.splice(index, 1);
        setStartdate(updatedAdult);
    };

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('code', advcode);

        startdate.forEach((adp, index) => {
            formData.append(`promoprice[${index}][day]`, adp.day);
            formData.append(`promoprice[${index}][price]`, adp.price);
            formData.append(`promoprice[${index}][start]`, adp.start);
            formData.append(`promoprice[${index}][end]`, adp.end);
            formData.append(`promoprice[${index}][id]`, adp.id);
        });

        Inertia.post(`/room/promo/store/${data[0].contractrate.id}`, formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }
    return (
        <>
            <Layout>
                <div className="container">
                    <div className="row">
                        <div className="col-lg-12">
                            <div className="card">
                                <div className="card-body">
                                    <form onSubmit={storePost}>
                                        <div className="row">
                                            <div className="col-lg-4">
                                                <div className="mb-3">
                                                    <label htmlFor="">contract rate code</label>
                                                    <input type="text" className='form-control' defaultValue={data[0].contractrate.ratecode} readOnly />
                                                </div>
                                            </div>
                                            <div className="col-lg-4">
                                                <div className="mb-3">
                                                    <label htmlFor="">description code</label>
                                                    <input type="text" className='form-control' defaultValue={data[0].contractrate.codedesc} readOnly />
                                                </div>
                                            </div>
                                            <div className="col-lg-4">
                                                <div className="mb-3">
                                                    <label htmlFor="">promotion code</label>
                                                    <input type="text" className='form-control' defaultValue={advcode} onChange={(e) => setAdv(e.target.value)} />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="container">
                                                {startdate.map((preference, index) => (
                                                    <div key={preference.id} className='row'>
                                                        <div className="col-lg-3">
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
                                                        <div className="col-lg-3">
                                                            <div className="mb-3">
                                                                <label htmlFor="">end date</label>
                                                                <input
                                                                    type="date"
                                                                    className='form-control'
                                                                    value={preference.end}
                                                                    onChange={(e) => handleChangestart(e, index, 'end')}
                                                                />
                                                            </div>

                                                        </div>
                                                        <div className="col-lg-1">
                                                            <div className="mb-3">
                                                                <label htmlFor="">day</label>
                                                                <input type="text" value={preference.day} readOnly className='form-control' />
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-3">
                                                            <div className="mb-3">
                                                                <label htmlFor="">price</label>
                                                                <input
                                                                    type="number"
                                                                    className='form-control'
                                                                    value={preference.price}
                                                                    onChange={(e) => handleChangestart(e, index, 'price')}
                                                                />
                                                            </div>

                                                        </div>
                                                        <div className="col-lg-2">
                                                            <button type="button" style={{ marginTop: "35px" }} className='btn btn-danger' onClick={() => handleRemoveInputstart(index)}>
                                                                <i className='fa fa-trash'></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>

                                        </div>

                                        <div className="mt-2">
                                            <button type="button" className='btn btn-primary' onClick={handleAddInputstart}>
                                                <i className="fa fa-plus"></i> Add Promo
                                            </button>
                                        </div>
                                        <hr />
                                        <div className="row justify-content-between"> {/* Use justify-content-between to move the buttons to both ends */}
                                            <div className="col-lg-auto">
                                                <button type="submit" className="btn btn-primary">
                                                    <i className="fa fa-save"></i> Save
                                                </button>
                                            </div>
                                            <div className="col-lg-auto">
                                                <button onClick={() => history.back()} className="btn btn-danger">
                                                    Cancel
                                                </button>
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