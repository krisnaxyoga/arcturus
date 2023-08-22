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

export default function PriceAgentRoom({ country, session, data, markup, bardata,cont }) {

    const [ratecode, setRateCode] = useState('');
    const [ratedesc, setRateDesc] = useState('');
    const [minstay, setMinStay] = useState('');
    const [beginsell, setBeginSell] = useState('');
    const [endsell, setEndSell] = useState('');
    const [begindate, setBeginDate] = useState('');
    const [enddate, setEndDate] = useState('');
    const [cancelPolicy, setCancellationPolicy] = useState('');
    const [depositPolicy, setDepositPolicy] = useState('');

    const [minPrice, setMinPrice] = useState('');
    const [sellingPrice, setSellingPrice] = useState('');


    const [modalData, setModalData] = useState()

    const [showModal, setShowModal] = useState(false);

    const [percentage, setPercentage] = useState('');


    const [selectedDistribute, setSelectedDistribute] = useState([]);
    const [selectedExclude, setSelectedExclude] = useState([]);


    const handleSelectDistribute = (event) => {
        const selectedDistributes = event.target.value;
        const isSelected = selectedDistribute.includes(selectedDistributes);

        if (isSelected) {
            setSelectedDistribute((prevSelectedValues) => prevSelectedValues.filter((value) => value !== selectedDistributes));
          } else {
            setSelectedDistribute((prevSelectedValues) => [...prevSelectedValues, selectedDistributes]);
          }
    };

    const handleRemoveSelected = (valueToRemove) => {
        setSelectedDistribute((prevSelectedValues) =>
          prevSelectedValues.filter((value) => value !== valueToRemove)
        );
      };

    //   console.log(cont,"nilainya contract");

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
        // Mendapatkan tanggal hari ini
        const today = new Date();
        const todayFormatted = today.toISOString().split('T')[0];
        setBeginDate(todayFormatted);

        // Menghitung tanggal akhir (30 hari dari hari ini)
        const endDate = new Date(today.getTime() + 31 * 24 * 60 * 60 * 1000);
        const endDateFormatted = endDate.toISOString().split('T')[0];
        setEndDate(endDateFormatted);
      }, []);

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

    const handleCloseModal = () => {
        setShowModal(false);
    };

    const handleCancelPolicyChange = (event, editor) => {
        setCancellationPolicy(editor.getData());
    };

    const handleDepositPolicyChange = (event, editor) => {
        setDepositPolicy(editor.getData());
    };

    const [checkboxes, setCheckboxes] = useState({
        sunday: false,
        monday: false,
        tuesday: false,
        wednesday: false,
        thursday: false,
        friday: false,
        saturday: false,
    });

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

    const handleRateMinChange = (e) => {
        const value = e.target.value;

        const selling = Number(value) + Number(markup.price)

        setMinPrice(value)
        setSellingPrice(selling);


    }

    const handleUpdatePrice = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('recom_price', minPrice);
        formData.append('price', sellingPrice);
    };


    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('ratecode', ratecode);
        formData.append('codedesc', ratedesc);
        formData.append('min_stay', minstay);

        formData.append('stayperiod_begin', beginsell ? beginsell : bardata[0].begindate);
        formData.append('stayperiod_end', endsell ? endsell : bardata[0].enddate);
        formData.append('booking_begin', begindate);
        formData.append('booking_end', enddate);

        const days = checkboxes ? checkboxes : contract.pick_day;
        const trueDays = Object.keys(days).filter((day) => days[day] === true);

        formData.append('pick_day', trueDays);

        formData.append('cencellation_policy', cancelPolicy);
        formData.append('deposit_policy', depositPolicy);

        formData.append('distribute',selectedDistribute);
        formData.append('except',selectedExclude);
        formData.append('percentage',percentage);

        Inertia.post('/room/contract/store', formData, {
            onSuccess: () => {

            },
        });
    }
    return (
        <>
            <Layout page='/room/contract/index'>
                <div className="container">
                <h1>Contract Rate</h1>
                    <div className="row">
                        
                        {(!data || Object.keys(data).length === 0) || (!markup || Object.keys(markup).length === 0) || (!bardata || Object.keys(bardata).length === 0) ? (
                            <div className="col-lg-6">
                                <div className="alert alert-danger border-0 shadow-sm rounded-3">
                                    <p>Please check again the information bar and your profile hotel is it complete?</p>
                                </div>
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
                                                                    <div className="col-lg-4">
                                                                        <div className="mb-3">
                                                                            <label htmlFor="" className='fw-bold'>Contract Rate Code</label>
                                                                            <input onChange={(e) => setRateCode(e.target.value)} type="text" className='form-control' required/>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-4">

                                                                        <div className="mb-3">
                                                                            <label htmlFor="" className='fw-bold'>Contract description</label>
                                                                            <input onChange={(e) => setRateDesc(e.target.value)} type="text" className='form-control' required/>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-4">
                                                                    {cont === true ? (
                                                                        <>
                                                                          <div className="mb-3">
                                                                            <label htmlFor="" className='fw-bold'>Minimum stay</label>
                                                                            <input onChange={(e) => setMinStay(e.target.value)} type="number" className='form-control' required/>
                                                                        </div>
                                                                        </>
                                                                    ):(
                                                                        <>
                                                                          <div className="mb-3">
                                                                            <label htmlFor="" className='fw-bold'>Minimum stay</label>
                                                                            <input readOnly onChange={(e) => setMinStay(e.target.value)} type="number" className='form-control' value={1}/>
                                                                        </div>
                                                                        </>
                                                                    )}
                                                                      
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                                <div className="row">
                                                                    <div className="col-lg-6">
                                                                        <label htmlFor="" className='font-weight-bolder'>STAY PERIODS</label>
                                                                   
                                                                        <div className="row">
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>Begin Sell date</label>
                                                                                    <input style={{backgroundColor: '#e3e6ec'}} defaultValue={bardata[0] .begindate} onChange={(e) => setBeginSell(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>End Sell date</label>
                                                                                    <input style={{backgroundColor: '#e3e6ec'}} defaultValue={bardata[0].enddate} onChange={(e) => setEndSell(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr />
                                                                        <label htmlFor="" className='font-weight-bolder'>BOOKING PERIODS</label>
                                                                        <div className="row">
                                                                            <div className="col-lg-6">
                                                                                <div>
                                                                                    <label htmlFor="" className='fw-bold'>Start date</label>
                                                                                    <input value={begindate} onChange={(e) => setBeginDate(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                            <div className="col-lg-6">
                                                                                <div>
                                                                                    <label htmlFor="" className='fw-bold'>End date</label>
                                                                                    <input value={enddate} onChange={(e) => setEndDate(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div className="mb-3">
                                                                            <div className="d-flex mt-3">
                                                                            <input style={{width: '4rem'}} onChange={(e) => setPercentage(e.target.value)} type="number" defaultValue={0} className='form-control'/> <p style={{marginTop: '8px',marginLeft: '7px'}}>% off BAR</p>
                                                                            </div>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-6">
                                                                        {cont === true ? (
                                                                             <div className="mb-5">
                                                                             <label htmlFor="">Market</label>
                                                                             <select
                                                                                 name=""
                                                                                 id=""
                                                                                 className="form-control"
                                                                                 onChange={handleSelectDistribute}
                                                                                 multiple
                                                                             >
                                                                                 <option value="all">all</option>
                                                                                 {Object.keys(country).map((key) => (
                                                                                 <option key={key} value={country[key]}>
                                                                                     {country[key]}
                                                                                 </option>
                                                                                 ))}
                                                                             </select>
                                                                             <p className="mt-2">
                                                                                 Selected Values:{" "}
                                                                                 <span className="text-secondary">
                                                                                 {selectedDistribute.map((value) => (
                                                                                     <ul key={value}>
                                                                                         <li>
                                                                                         {value}{" "}
                                                                                         <button style={{borderRadius:'20px'}}
                                                                                             className="btn btn-sm btn-danger"
                                                                                             onClick={() => handleRemoveSelected(value)}
                                                                                         >
                                                                                             x
                                                                                         </button>{" "}
                                                                                         </li>
                                                                                     </ul>
                                                                                 ))}
                                                                                 </span>
                                                                             </p>
                                                                         </div>
                                                                        ) : (
                                                                            <>
                                                                            <label htmlFor="" style={{marginTop:'2rem'}}> Market</label>
                                                                            <input type="text" readOnly value={'all'} className='form-control'/>
                                                                            </>
                                                                        )
                                                                        }
                                                                   
                                                                        {/* <div className="mb-3">
                                                                            <label htmlFor="">Exclude</label>
                                                                            <select name="" id="" className='form-control' onChange={handleSelectExclude} multiple>

                                                                                {Object.keys(country).map(key => (
                                                                                        <option key={key} value={country[key]}>{country[key]}</option>
                                                                                    ))}
                                                                            </select>
                                                                            <p className='mt-2'>Selected: <span className='text-secondary'>{selectedExclude.join(', ')}</span></p>

                                                                        </div> */}
                                                                        <label htmlFor="pickdays" className='fw-bold'>valid day</label>
                                                                        <label className="form-label mx-5">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="all"
                                                                                    checked={Object.values(checkboxes).every(val => val)}
                                                                                    onChange={handleCheckboxChangeAll}
                                                                                />
                                                                                All
                                                                            </label>
                                                                        <div className="mb-1">
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

                                                                        </div>
                                                                     
                                                                    </div>
                                                                </div>
                                                                
                                                            </Tab>
                                                            <Tab eventKey="profile" title="Policy">
                                                                <div className="row">
                                                                    <div className="col-lg-12">
                                                                        <div className="mb-3">
                                                                            <label className="form-label fw-bold">Cancellation Policy</label>
                                                                            <CKEditor
                                                                                editor={ClassicEditor}
                                                                                data=""
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
                                                                            <CKEditor
                                                                                editor={ClassicEditor}
                                                                                data=""
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
                                                                                    <th>room type</th>
                                                                                        <th>bar</th>
                                                                                        <th>contract recomended</th>
                                                                                        <th>min mark-up</th>
                                                                                        <th>selling rate</th>
                                                                                        <th>Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>

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
                                                <button type='submit' className='btn btn-primary'>
                                                    <i className='fa fa-save'></i>
                                                    save
                                                </button>
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
