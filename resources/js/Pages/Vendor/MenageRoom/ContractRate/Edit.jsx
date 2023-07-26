//import React
import React, { useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../../Layouts/Vendor';

import { Button, Form, Modal } from 'react-bootstrap';

//import Link
import { CKEditor } from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';

export default function PriceAgentRoom({ country, session, data, markup, bardata, contract, contractprice }) {

    const [ratecode, setRateCode] = useState('');
    const [ratedesc, setRateDesc] = useState('');
    const [minstay, setMinStay] = useState('');
    const [beginsell, setBeginSell] = useState(bardata[0].begindate);
    const [endsell, setEndSell] = useState(bardata[0].enddate);
    const [begindate, setBeginDate] = useState('');
    const [enddate, setEndDate] = useState('');
    const [cancelPolicy, setCancellationPolicy] = useState('');
    const [depositPolicy, setDepositPolicy] = useState('');

    const [minPrice, setMinPrice] = useState('');
    const [sellingPrice, setSellingPrice] = useState('');

    const [dataValues, setDataValues] = useState([]);

    const [selectedValues, setSelectedValues] = useState([]);

    const [modalData, setModalData] = useState()

    const [showModal, setShowModal] = useState(false);

    const [showModalTable, setShowModalTable] = useState(false);


    const [checkboxes, setCheckboxes] = useState({
        sunday: false,
        monday: false,
        tuesday: false,
        wednesday: false,
        thursday: false,
        friday: false,
        saturday: false,
    });

    // const [checkboxItems, setCheckboxItems] = useState(contract.pick_day.map((item) => ({ label: item, checked: false })));


    const [selectedDistribute, setSelectedDistribute] = useState(contract.distribute || []);
    const [selectedExclude, setSelectedExclude] = useState(contract.except || []);

    useEffect(() => {
        setSelectedDistribute(contract.distribute || []);
        setSelectedExclude(contract.except || []);
    }, [contract.distribute, contract.except]);

    console.log(selectedDistribute, ">>>>>>>>>>>>> ISI distribute >>>>>>>>>>>>>>>>>>>>>>")

    const handleSelectDistribute = (event) => {
        const selectedDistributes = event.target.value;
        const isSelected = selectedDistribute.includes(selectedDistributes);

        if (isSelected) {
            setSelectedDistribute((prevSelectedValues) => prevSelectedValues.filter((value) => value !== selectedDistributes));
        } else {
            setSelectedDistribute((prevSelectedValues) => [...prevSelectedValues, selectedDistributes]);
        }
    };

    const handleSelectExclude = (event) => {
        const selectedExcludes = event.target.value;
        const isSelected = selectedExclude.includes(selectedExcludes);

        if (isSelected) {
            setSelectedExclude((prevSelectedValues) => prevSelectedValues.filter((value) => value !== selectedExcludes));
        } else {
            setSelectedExclude((prevSelectedValues) => [...prevSelectedValues, selectedExcludes]);
        }
    };

    useEffect(() => {
        // Ubah nilai checkboxes berdasarkan data dari Laravel
        const updatedCheckboxes = { ...checkboxes };
        contract.pick_day.forEach((item) => {
            if (updatedCheckboxes.hasOwnProperty(item)) {
                updatedCheckboxes[item] = true;
            }
        });

        setCheckboxes(updatedCheckboxes);
    }, [contract.pick_day]);

    const buttonSendValueTable = (item) => {
        setShowModalTable(true);
    };
    const handleCloseModalTable = () => {
        setShowModalTable(false);
    };

    const buttonSendValue = (item) => {
        setModalData(item);

        const sellprice = Number(item.recom_price) + Number(markup[0]?.markup_price)

        setSellingPrice(sellprice)


        setShowModal(true);
    };

    const handleCloseModal = () => {
        setShowModal(false);
    };



    const handleCheckboxChange = (event) => {
        const { name, checked } = event.target;
        setCheckboxes((prevState) => ({
            ...prevState,
            [name]: checked
        }));

        // Menambahkan atau menghapus nilai dari array tergantung pada checkbox yang dicentang atau tidak dicentang
        setSelectedValues((prevValues) => {
            if (checked) {
                return [...prevValues, name];
            } else {
                return prevValues.filter((value) => value !== name);
            }
        });
    };

    console.log(checkboxes, ">>>>>>>>>>>> ISI ARRAY SEKARANG>>>>>>>>>>>>>")


    const handleRateMinChange = (e) => {
        const value = e.target.value;
        const dataId = e.target.dataset.id;
        const dataPrice = e.target.dataset.price;
        const dataRateCode = e.target.dataset.ratecode;
        const dataRateDesc = e.target.dataset.ratedesc;


        if (markup[0].markup_price === 0) {
            const selling = Number(value) + Number(modalData?.barprice.price) - ((modalData?.barprice.price * 0.8) + 15000);
            setSellingPrice(selling);
        } else {
            const selling = Number(value) + Number(markup[0].markup_price);
            setSellingPrice(selling);
        }

        setMinPrice(value)



    }
    const handleCheckboxChangeAll = (event) => {
        const { checked } = event.target;
        if (checked) {
            const allDays = {
                sunday: true,
                monday: true,
                tuesday: true,
                wednesday: true,
                thursday: true,
                friday: true,
                saturday: true
            };
            setCheckboxes(allDays);
        } else {
            setCheckboxes({
                sunday: false,
                monday: false,
                tuesday: false,
                wednesday: false,
                thursday: false,
                friday: false,
                saturday: false
            });
        }
    };


    const handleCancelPolicyChange = (event, editor) => {
        setCancellationPolicy(editor.getData());
    };

    const handleDepositPolicyChange = (event, editor) => {
        setDepositPolicy(editor.getData());
    };


    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('ratecode', ratecode ? ratecode : contract.ratecode);

        formData.append('codedesc', ratedesc ? ratedesc : contract.codedesc);
        formData.append('min_stay', minstay ? minstay : contract.min_stay);

        formData.append('stayperiod_begin', beginsell ? beginsell : contract.stayperiod_begin);
        formData.append('stayperiod_end', endsell ? endsell : contract.stayperiod_end);
        formData.append('booking_begin', begindate ? begindate : contract.booking_begin);
        formData.append('booking_end', enddate ? enddate : contract.booking_end);


        const days = checkboxes ? checkboxes : contract.pick_day;
        const trueDays = Object.keys(days).filter((day) => days[day] === true);

        formData.append('pick_day', trueDays);

        formData.append('cencellation_policy', cancelPolicy ? cancelPolicy : contract.cencellation_policy);
        formData.append('deposit_policy', depositPolicy ? depositPolicy : contract.deposit_policy);

        //console.log(adp, ">>>>>>>>>>>>> ISI PICK DAYS >>>>>>>>>>>>>>>>")

        // selectedDistribute.forEach((adp, index) => {
        //     formData.append(`price[${index}][price]`, adp.price);
        //     formData.append(`price[${index}][room_id]`, adp.room_id);
        // });
        formData.append('distribute', selectedDistribute);
        formData.append('except', selectedExclude);

        Inertia.post(`/room/contract/update/${contract.id}`, formData, {
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
                        <h1>Contract Rate</h1>
                        {(!contract || Object.keys(contract).length === 0) || (!markup || Object.keys(markup).length === 0) || (!bardata || Object.keys(bardata).length === 0) ? (
                            <div className="alert alert-danger border-0 shadow-sm rounded-3">
                                <p>Please check again the information bar and your mark up price is it complete?</p>
                            </div>
                        ) : (
                            <>
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
                                                        <Tabs
                                                            defaultActiveKey="home"
                                                            id="fill-tab-example"
                                                            className="mb-3"
                                                            fill
                                                        >
                                                            <Tab eventKey="home" title="Rate Code">
                                                                <div className="row">
                                                                    <div className="col-lg-6">
                                                                        <p htmlFor="" className='fs-6 fst-italic'> this contract only can be changed once a month and every 1st day of the month</p>

                                                                    </div>
                                                                </div>
                                                                <div className="row">
                                                                    <div className="col-lg-4">
                                                                        <div className="mb-3">
                                                                            <label htmlFor="" className='fw-bold'>Contract Rate Code</label>
                                                                            <input defaultValue={contract.ratecode} onChange={(e) => setRateCode(e.target.value)} type="text" className='form-control' />
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-4">

                                                                        <div className="mb-3">
                                                                            <label htmlFor="" className='fw-bold'>Contract decription</label>
                                                                            <input defaultValue={contract.codedesc} onChange={(e) => setRateDesc(e.target.value)} type="text" className='form-control' />
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-4">
                                                                        <div className="mb-3">
                                                                            <label htmlFor="" className='fw-bold'>Minimum stay</label>
                                                                            <input defaultValue={contract.min_stay} onChange={(e) => setMinStay(e.target.value)} type="number" className='form-control' />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                                <div className="row">
                                                                    <div className="col-lg-6">
                                                                        <label htmlFor="" className='fw-bold'>STAY PERIODS</label>
                                                                        <hr />
                                                                        <div className="row">
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>Begin Sell date</label>
                                                                                    <input readOnly defaultValue={bardata[0].begindate} onChange={(e) => setBeginSell(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>End Sell date</label>
                                                                                    <input readOnly defaultValue={bardata[0].enddate} onChange={(e) => setEndSell(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-6">
                                                                        <label htmlFor="" className='fw-bold'>BOOKING PERIODS</label>
                                                                        <hr />
                                                                        <div className="row">
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>Start date</label>
                                                                                    <input defaultValue={contract.booking_begin} onChange={(e) => setBeginDate(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>End date</label>
                                                                                    <input defaultValue={contract.booking_end} onChange={(e) => setEndDate(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div className="row">
                                                                    <div className="col-lg-6">
                                                                        <label htmlFor="pickdays" className='fw-bold'>Pick valid day</label>
                                                                        <div className="mb-3">
                                                                            <label className="form-label mx-3">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="sunday"
                                                                                    checked={checkboxes.sunday}
                                                                                    onChange={handleCheckboxChange}
                                                                                />
                                                                                Sun
                                                                            </label>
                                                                            <label className="form-label mx-3">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="monday"
                                                                                    checked={checkboxes.monday}
                                                                                    onChange={handleCheckboxChange}
                                                                                />
                                                                                Mon
                                                                            </label>
                                                                            <label className="form-label mx-3">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="tuesday"
                                                                                    checked={checkboxes.tuesday}
                                                                                    onChange={handleCheckboxChange}
                                                                                />
                                                                                Tue
                                                                            </label>
                                                                            <label className="form-label mx-3">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="wednesday"
                                                                                    checked={checkboxes.wednesday}
                                                                                    onChange={handleCheckboxChange}
                                                                                />
                                                                                Wed
                                                                            </label>
                                                                            <label className="form-label mx-3">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="thursday"
                                                                                    checked={checkboxes.thursday}
                                                                                    onChange={handleCheckboxChange}
                                                                                />
                                                                                Thurs
                                                                            </label>
                                                                            <label className="form-label mx-3">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="friday"
                                                                                    checked={checkboxes.friday}
                                                                                    onChange={handleCheckboxChange}
                                                                                />
                                                                                Fri
                                                                            </label>
                                                                            <label className="form-label mx-3">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="saturday"
                                                                                    checked={checkboxes.saturday}
                                                                                    onChange={handleCheckboxChange}
                                                                                />
                                                                                Sat
                                                                            </label>
                                                                            <label className="form-label mx-3">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="all"
                                                                                    checked={Object.values(checkboxes).every(val => val)}
                                                                                    onChange={handleCheckboxChangeAll}
                                                                                />
                                                                                All
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-6">
                                                                        <div className="row">
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="">distribute</label>
                                                                                    <select name="" id="" className='form-control' onChange={handleSelectDistribute} multiple>
                                                                                        <option value="all">all</option>
                                                                                        {Object.keys(country).map(key => (
                                                                                            <option key={key} value={country[key]}>{country[key]}</option>
                                                                                        ))}
                                                                                    </select>
                                                                                    <p className='mt-2'>Selected Values: <span className='text-secondary'>{selectedDistribute.join(', ')}</span></p>
                                                                                </div>
                                                                            </div>
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="">exclude</label>
                                                                                    <select name="" id="" className='form-control' onChange={handleSelectExclude} multiple>

                                                                                        {Object.keys(country).map(key => (
                                                                                            <option key={key} value={country[key]}>{country[key]}</option>
                                                                                        ))}
                                                                                    </select>
                                                                                    <p className='mt-2'>Selected Values: <span className='text-secondary'>{selectedExclude.join(', ')}</span></p>

                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </Tab>
                                                            <Tab eventKey="profile" title="Policy">
                                                                <div className="row">
                                                                    <div className="col-lg-12">
                                                                        <div className="mb-3">
                                                                            <label className="form-label fw-bold">Cancellation Policy</label>
                                                                            {/* <textarea className="form-control" value={description} onChange={(e) => setDescription(e.target.value)} placeholder="Masukkan Judul Post" rows={4}></textarea> */}
                                                                            <CKEditor
                                                                                editor={ClassicEditor}
                                                                                data={contract.cencellation_policy}
                                                                                onReady={editor => {
                                                                                    // You can store the "editor" and use when it is needed.
                                                                                    console.log('Editor is ready to use!', editor);
                                                                                }}
                                                                                onChange={handleCancelPolicyChange}
                                                                                onBlur={(event, editor) => {
                                                                                    console.log('Blur.', editor);
                                                                                }}
                                                                                onFocus={(event, editor) => {
                                                                                    console.log('Focus.', editor);
                                                                                }}
                                                                            />
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-12">
                                                                        <div className="mb-3">
                                                                            <label className="form-label fw-bold">Deposit Policy</label>
                                                                            {/* <textarea className="form-control" value={description} onChange={(e) => setDescription(e.target.value)} placeholder="Masukkan Judul Post" rows={4}></textarea> */}
                                                                            <CKEditor
                                                                                editor={ClassicEditor}
                                                                                data={contract.deposit_policy}
                                                                                onReady={editor => {
                                                                                    // You can store the "editor" and use when it is needed.
                                                                                    console.log('Editor is ready to use!', editor);
                                                                                }}
                                                                                onChange={handleDepositPolicyChange}
                                                                                onBlur={(event, editor) => {
                                                                                    console.log('Blur.', editor);
                                                                                }}
                                                                                onFocus={(event, editor) => {
                                                                                    console.log('Focus.', editor);
                                                                                }}
                                                                            />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </Tab>
                                                        </Tabs>
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
                                                                                        <th>rate recomendation</th>
                                                                                        <th>min mark-up</th>
                                                                                        <th>agent selling rate</th>
                                                                                        <th>Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    {contractprice.map((item, index) => (
                                                                                        <>
                                                                                            <tr key={index}>

                                                                                                <td>{item.room.ratecode}</td>
                                                                                                <td>{item.room.ratedesc}</td>
                                                                                                <td>
                                                                                                    {item.barprice.price}
                                                                                                </td>
                                                                                                <td>
                                                                                                    {item.recom_price}
                                                                                                    <a href='#' className='btn btn-datatable btn-icon btn-transparent-dark mr-2' onClick={() => buttonSendValue(item)}>
                                                                                                        <i className='fa fa-edit'></i>
                                                                                                    </a>
                                                                                                </td>
                                                                                                <td>
                                                                                                    {markup[0].markup_price === 0 ? (
                                                                                                        item.barprice.price - (item.recom_price + 15000)
                                                                                                    ) : (
                                                                                                        markup[0].markup_price
                                                                                                    )}
                                                                                                </td>

                                                                                                <td >
                                                                                                    {markup[0].markup_price === 0 ? (
                                                                                                        item.recom_price + (item.barprice.price - (item.recom_price + 15000))
                                                                                                    ) : (
                                                                                                        item.recom_price + markup[0].markup_price
                                                                                                    )
                                                                                                    }
                                                                                                </td>
                                                                                                <td>
                                                                                                    <a href={`/room/contract/destroycontractprice/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
                                                                                                        <i className='fa fa-trash'></i>
                                                                                                    </a>
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

                                                    <Modal show={showModal} onHide={handleCloseModal}>
                                                        <Modal.Header>
                                                            <Modal.Title>Edit Selling Rate</Modal.Title>
                                                        </Modal.Header>
                                                        <Modal.Body>
                                                            <Form>
                                                                <Form.Group controlId="formCode">
                                                                    <Form.Label>Room Type Code</Form.Label>
                                                                    <Form.Control
                                                                        type="text"
                                                                        name="code"
                                                                        defaultValue={modalData?.room?.ratecode}
                                                                        disabled
                                                                    />
                                                                </Form.Group>
                                                                <Form.Group controlId="formName">
                                                                    <Form.Label>Room Type Name</Form.Label>
                                                                    <Form.Control
                                                                        type="text"
                                                                        name="name"
                                                                        defaultValue={modalData?.room?.ratedesc}
                                                                        disabled
                                                                    />
                                                                </Form.Group>
                                                                <Form.Group controlId="formRate">
                                                                    <Form.Label>Rate Price</Form.Label>
                                                                    <Form.Control
                                                                        type="text"
                                                                        name="rate"
                                                                        data-price={modalData?.reprice}
                                                                        defaultValue={modalData?.barprice.price}
                                                                        disabled
                                                                    />
                                                                </Form.Group>
                                                                <Form.Group controlId="formRateMin">
                                                                    <Form.Label>Rate Minimum</Form.Label>
                                                                    <Form.Control
                                                                        type="text"
                                                                        name="rate_min"
                                                                        data-id={modalData?.id}
                                                                        defaultValue={modalData?.recom_price}
                                                                        onChange={handleRateMinChange}
                                                                    />
                                                                </Form.Group>
                                                                <Form.Group controlId="formMarkup">
                                                                    <Form.Label>Markup</Form.Label>
                                                                    <Form.Control
                                                                        type="text"
                                                                        name="markup"
                                                                        defaultValue={markup[0].markup_price === 0 ? (
                                                                            modalData?.barprice.price - ((modalData?.barprice.price * 0.8) + 15000)
                                                                        ) : (
                                                                            markup[0].markup_price
                                                                        )}
                                                                        disabled
                                                                    />
                                                                </Form.Group>
                                                                <Form.Group controlId="formSelling">
                                                                    <Form.Label>Selling</Form.Label>
                                                                    {sellingPrice > modalData?.barprice.price ? (
                                                                        <>
                                                                            <p className='text-danger'>the selling price exceeds the price on the website</p>
                                                                        </>
                                                                    ) : (
                                                                        <>
                                                                        </>
                                                                    )}
                                                                    <Form.Control
                                                                        type="text"
                                                                        name="selling"
                                                                        value={sellingPrice}
                                                                        disabled
                                                                    />
                                                                </Form.Group>
                                                                <a href={`/room/contract/updatecontractprice/${modalData?.id}/${minPrice ? minPrice : modalData?.recom_price}/${sellingPrice}`} className='btn btn-primary mt-4'>
                                                                    Save Changes
                                                                </a>
                                                                <Button className='mt-4' variant="secondary" onClick={handleCloseModal}>
                                                                    Close
                                                                </Button>
                                                            </Form>
                                                        </Modal.Body>

                                                    </Modal>
                                                    <Modal show={showModalTable} onHide={handleCloseModalTable}>
                                                        <Modal.Header>
                                                            <Modal.Title>Edit Select RoomType</Modal.Title>
                                                        </Modal.Header>
                                                        <Modal.Body>
                                                            <table id="dataTable" width="100%" cellSpacing="0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>room type code</th>
                                                                        <th>room type description</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    {data.map((item) => {
                                                                        const roomExistsInContract = contractprice.some(
                                                                            (contractItem) => contractItem.room_id === item.room.id
                                                                        );

                                                                        return (
                                                                            <tr key={item.id}>
                                                                                <td>{item.room.ratecode}</td>
                                                                                <td>{item.room.ratedesc}</td>
                                                                                <td>
                                                                                    {roomExistsInContract ? null : (
                                                                                        <>
                                                                                            <a
                                                                                                href={`/room/contract/addcontractprice/${item.id}/${contract.id}`}
                                                                                                className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                                                            >
                                                                                                <i className="fa fa-check"></i>
                                                                                            </a>
                                                                                        </>

                                                                                    )}
                                                                                </td>
                                                                            </tr>
                                                                        );
                                                                    })}
                                                                </tbody>
                                                            </table>
                                                        </Modal.Body>
                                                        <Modal.Footer>
                                                            <a href={`/room/contract/addallcontractprice/${contract.id}`} className='btn btn-primary'>
                                                                <i className='fa fa-plus'></i> add all
                                                            </a>
                                                            <Button variant="secondary" onClick={handleCloseModalTable}>
                                                                Close
                                                            </Button>
                                                        </Modal.Footer>
                                                    </Modal>
                                                </div>
                                                <hr />
                                                <div className="row justify-content-between"> {/* Use justify-content-between to move the buttons to both ends */}
                                                    <div className="col-lg-auto">
                                                        <div className="d-flex">
                                                            <button type="submit" className="btn btn-primary mb-3">
                                                            <i className="fa fa-save"></i> Save
                                                        </button>
                                                        <div className="mb-3 ml-2 text-right">
                                                            <a href="#" onClick={() => buttonSendValueTable()} className='btn btn-success'><i className='fa fa-plus'></i>add price</a>
                                                        </div>
                                                        </div>
                                                        
                                                        {/* <Link href={`/room/promo/index/${contract.id}`} className='btn btn-warning'>Promo Price</Link> */}
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
                            </>
                        )}
                    </div>
                </div>
            </Layout>
        </>
    )
}
