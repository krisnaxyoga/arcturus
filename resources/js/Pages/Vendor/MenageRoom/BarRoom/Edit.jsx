//import React
import React, { useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../../Layouts/Vendor';

//import Link
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Modal from 'react-bootstrap/Modal';

export default function PriceAgentRoom({ props, session, data }) {

    const [barcode, setBarcode] = useState('');
    const [bardesc, setBarDesc] = useState('');
    const [begin, setBegin] = useState('');
    const [end, setEnd] = useState('');
    const [show, setShow] = useState(false);
    const [price, setPrice] = useState();

    const [modalData, setModalData] = useState()

    const [dataValues, setDataValues] = useState([]);


    const buttonSendValue = (param) => {
        setModalData(param);
        console.log(param, "param");
        setShow(true);
    }
    const handleClose = () => {
        setShow(false);
    }

    const handleAdultValueChange = (e, index, field) => {
        const { value } = e.target;
        const dataId = e.target.dataset.id;
        const dataPrice = e.target.dataset.price;
        // Salin array dataValues ke variabel newDataValues
        const newDataValues = [...dataValues];

        // Update nilai yang diubah dengan nilai baru
        newDataValues[index] = {
            ...newDataValues[index],
            [field]: value,
            ['id']: dataPrice,
            ['room_id']: dataId
        };

        //   setPriceRoomType(newDataValues)
        // Simpan nilai yang diubah ke dalam state
        setDataValues(newDataValues);
    };
    console.log(dataValues, ">>>NILAI VALUE ")

    const [roomprice, setPriceRoomType] = useState(data.map((item) => ({
        price: item.price,
        id: item.id,
        room_id: item.room_id

    })));

    console.log(roomprice, ">>>>>ROOM PRICE")

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('barcode', barcode ? barcode : data[0].barroom.barcode);

        formData.append('bardesc', bardesc ? bardesc : data[0].barroom.bardesc);

        formData.append('begin', begin ? begin : data[0].barroom.begindate);
        formData.append('end', end ? end : data[0].barroom.enddate);

        dataValues ? dataValues : data.forEach((adp, index) => {
            formData.append(`price[${index}][price]`, adp.price);
            formData.append(`price[${index}][room_id]`, adp.room_id);
            formData.append(`price[${index}][id]`, adp.id);
        });

        Inertia.post(`/room/barcode/update/${data[0].bar_id}`, formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }

    const updatePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('price', price ? price : modalData?.price);

        Inertia.post(`/room/barcodeprice/update/${modalData?.id}`, formData, {
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
                                                            <input defaultValue={data[0].barroom.barcode} onChange={(e) => setBarcode(e.target.value)} type="text" className='form-control' />
                                                        </div>

                                                        <div className="mb-3">
                                                            <label htmlFor="" className='fw-bold'>Begin Sell date</label>
                                                            <input defaultValue={data[0].barroom.begindate} onChange={(e) => setBegin(e.target.value)} type="date" className='form-control' />
                                                        </div>
                                                    </div>
                                                    <div className="col-lg-6">
                                                        <div className="mb-3">
                                                            <label htmlFor="" className='fw-bold'>Bar decription</label>
                                                            <input defaultValue={data[0].barroom.bardesc} onChange={(e) => setBarDesc(e.target.value)} type="text" className='form-control' />
                                                        </div>
                                                        <div className="mb-3">
                                                            <label htmlFor="" className='fw-bold'>End Sell date</label>
                                                            <input defaultValue={data[0].barroom.enddate} onChange={(e) => setEnd(e.target.value)} type="date" className='form-control' />
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
                                                                                <th>action</th>
                                                                                {/* <th>child price</th> */}
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            {data.map((item, index) => (
                                                                                <>
                                                                                    <tr key={index}>

                                                                                        <td>{item.room.ratecode}</td>
                                                                                        <td>{item.room.ratedesc}</td>
                                                                                        <td>
                                                                                            <ul>
                                                                                                <input
                                                                                                    type="number"
                                                                                                    defaultValue={item.price}
                                                                                                    placeholder='price...'
                                                                                                    className='form-control'
                                                                                                    data-id={item.room_id}
                                                                                                    data-price={item.id}
                                                                                                    onChange={(e) =>
                                                                                                        handleAdultValueChange(e, index, 'price')}
                                                                                                    readOnly
                                                                                                />

                                                                                            </ul>
                                                                                        </td>
                                                                                        <td>
                                                                                            <a href='#' className='btn btn-datatable btn-icon btn-transparent-dark mr-2' onClick={() => buttonSendValue(item)}>
                                                                                                <i className='fa fa-edit'></i>
                                                                                            </a>
                                                                                            <Link href={`/room/barcodeprice/destroy/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
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
                                                                    <Link href={`/room/barcodeprice/cekroom/${data[0].barroom.id}`} className='btn btn-success'><i className='fa fa-plus'></i> add room</Link>
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
                                                <button type="submit" className="btn btn-primary" onSubmit={storePost}>
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
                <Modal show={show} onHide={handleClose}>
                    <Modal.Header>
                        <Modal.Title>{modalData?.room?.ratecode}</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="container">
                            <form onSubmit={updatePost}>
                                <div className="mb-3">
                                    <label htmlFor="" className='form-label'>Rate Price</label>
                                    <input className='form-control' type="number" defaultValue={modalData?.price} onChange={(e) => setPrice(e.target.value)} />
                                </div>
                                <div className="mb-3">
                                    <button className="btn btn-primary" type='submit'>
                                        <i className='fa fa-save'></i> save
                                    </button>
                                    <a href='#' className='mx-2 btn btn-secondary' onClick={handleClose}>
                                        Close
                                    </a>
                                </div>
                            </form>
                        </div>
                    </Modal.Body>
                </Modal>
            </Layout>
        </>
    )
}
