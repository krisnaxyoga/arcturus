//import React
import React, { useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function PriceAgentRoom({ props, session, data }) {
    const { url } = usePage();
    const [barcode, setBarcode] = useState('');
    const [bardesc, setBarDesc] = useState('');
    const [begin, setBegin] = useState('');
    const [end, setEnd] = useState('');

    const [dataValues, setDataValues] = useState([]);

    useEffect(() => {
        // Mendapatkan tanggal hari ini
        const today = new Date();
        const todayFormatted = today.toISOString().split('T')[0];
        setBegin(todayFormatted);

        // Menghitung tanggal akhir (30 hari dari hari ini)
        const endDate = new Date(today.getTime() + 366 * 24 * 60 * 60 * 1000);
        const endDateFormatted = endDate.toISOString().split('T')[0];
        setEnd(endDateFormatted);
    }, []);

    const handleAdultValueChange = (e, index, field) => {
        const { value } = e.target;
        const dataId = e.target.dataset.id;
        // Salin array dataValues ke variabel newDataValues
        const newDataValues = [...dataValues];

        // Update nilai yang diubah dengan nilai baru
        newDataValues[index] = {
            ...newDataValues[index],
            [field]: value,
            ['room_id']: dataId
        };

        // Simpan nilai yang diubah ke dalam state
        setDataValues(newDataValues);
    };
    console.log(dataValues, ">>>NILAI VALUE ")


    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('barcode', barcode);

        formData.append('bardesc', bardesc);

        formData.append('begin', begin);
        formData.append('end', end);

        dataValues.forEach((adp, index) => {
            formData.append(`price[${index}][price]`, adp.price);
            formData.append(`price[${index}][room_id]`, adp.room_id);
        });

        Inertia.post('/room/barcode/store', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }
    return (
        <>
            <Layout page={url}>
                <div className="container">
                    <div className="row">
                        <h1>Bar Information</h1>
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
                                                            <label htmlFor="" className='fw-bold'>Bar code</label>
                                                            <input onChange={(e) => setBarcode(e.target.value)} type="text" className='form-control' />
                                                        </div>

                                                        <div className="mb-3">
                                                            <label htmlFor="" className='fw-bold'>Begin Sell date</label>
                                                            <input value={begin} onChange={(e) => setBegin(e.target.value)} type="date" className='form-control' />
                                                        </div>
                                                    </div>
                                                    <div className="col-lg-6">
                                                        <div className="mb-3">
                                                            <label htmlFor="" className='fw-bold'>Bar description</label>
                                                            <input onChange={(e) => setBarDesc(e.target.value)} type="text" className='form-control' />
                                                        </div>
                                                        <div className="mb-3">
                                                            <label htmlFor="" className='fw-bold'>End Sell date</label>
                                                            <input value={end} onChange={(e) => setEnd(e.target.value)} type="date" className='form-control' />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="row">
                                                    <div className="col-lg-12">
                                                        <div className="card">
                                                            <div className="card-body">
                                                                <div className="table-responsive">
                                                                    <table id="dataTable" width="100%" cellSpacing="0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>room type code</th>
                                                                                <th>room type description</th>
                                                                                <th>rate price</th>
                                                                                {/* <th>child price</th> */}
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            {data.map((item, index) => (
                                                                                <>
                                                                                    <tr key={index}>

                                                                                        <td>{item.ratecode}</td>
                                                                                        <td>{item.ratedesc}</td>
                                                                                        <td>
                                                                                            <ul>
                                                                                                <input
                                                                                                    type="number"
                                                                                                    placeholder='price...'
                                                                                                    className='form-control'
                                                                                                    data-id={item.id}
                                                                                                    onChange={(e) =>
                                                                                                        handleAdultValueChange(e, index, 'price')} />

                                                                                            </ul>
                                                                                        </td>
                                                                                        <td>

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
                                            </div>
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
