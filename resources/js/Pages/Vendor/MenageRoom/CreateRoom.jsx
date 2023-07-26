//import React
import React, { useState, useEffect } from 'react';
//import layout
import Layout from '../../../Layouts/Vendor';
import Form from 'react-bootstrap/Form';
//import Link
import { CKEditor } from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';


export default function Index({ session, props, attr,roomtype }) {

    const [inputs, setInputs] = useState(['']); // State untuk menyimpan nilai-nilai input
    const [maxadult, setAdult] = useState([{ value: '', id: 1 }]);
    const [maxchild, setChild] = useState([{ value: '', id: 1 }]);
    const [roomtypeid, setRoomType] = useState('');
    const [near, setNear] = useState('');
    const [ratecode, setRagecode] = useState('');
    const [ratedesc, setRateDesc] = useState('');
    const [roomname, setRoomName] = useState('');
    const [image, setImage] = useState(null);
    const [allowed, setAllowed] = useState('');
    const [images, setImages] = useState([]);
    const [adult, setMaxAdult] = useState('');
    const [child, setMaxChild] = useState('');
    const [service, setService] = useState('');
    const [vidio, setVidio] = useState('');
    const [content, setDescription] = useState('');
    const [extrabed, setExtrabed] = useState('');
    const [infant, setInfant] = useState('');
    const [baby_cot, setBebyCot] = useState('');
    const [max_benefit, setBenefit] = useState('');
    const [bedroom, setBedroom] = useState('');
    const [size, setSize] = useState('');
   

    const [selectedValues, setSelectedValues] = useState([]);


    console.log(maxchild, "hasill")
    //adult function add max
    const handleChangeadult = (e, index) => {
        const updatedAdult = [...maxadult];
        updatedAdult[index].value = e.target.value;
        setAdult(updatedAdult);
    };
    const handleAddInputAdult = () => {
        setAdult([...maxadult, { value: '', id: maxadult.length + 1 }]);
    };

    const handleRemoveInputAdult = (index) => {
        const updatedAdult = [...maxadult];
        updatedAdult.splice(index, 1);
        setAdult(updatedAdult);
    };

    //input child
    const handleChangechild = (e, index) => {
        const updatedchild = [...maxchild];
        updatedchild[index].value = e.target.value;
        setChild(updatedchild);
    };
    const handleAddInputchild = () => {
        setChild([...maxchild, { value: '', id: maxchild.length + 1 }]);
    };

    const handleRemoveInputchild = (index) => {
        const updatedchild = [...maxchild];
        updatedchild.splice(index, 1);
        setChild(updatedchild);
    };

    const [checkboxItems, setCheckboxItems] = useState(attr.map((item) => ({ label: item.name, checked: false })));

    console.log(selectedValues, "<<<<xwhhwhw");
    const handleCheckboxChange = (index) => {
        const updatedCheckboxItems = [...checkboxItems];
        updatedCheckboxItems[index].checked = !updatedCheckboxItems[index].checked;
        setCheckboxItems(updatedCheckboxItems);

        setSelectedValues((prevSelectedValues) => {
            if (updatedCheckboxItems[index].checked) {
                return [...prevSelectedValues, updatedCheckboxItems[index].label];
            } else {
                return prevSelectedValues.filter((value) => value !== updatedCheckboxItems[index].label);
            }
        });
    };


    const handleDescChange = (event, editor) => {
        setDescription(editor.getData());
    };

    const handleFacitiChange = (event, editor) => {
        setFacilities(editor.getData());
    };

    const handleServiceChange = (event, editor) => {
        setService(editor.getData());
    };

    const handleNearChange = (event, editor) => {
        setNear(editor.getData());
    };

    const handleFileChange = (e) => {
        setImage(e.target.files[0]);
        console.log(image, ">>>>>>>>>>>image");
    };
    const handleImageUpload = (e) => {
        const selectedImages = Array.from(e.target.files);
        setImages(selectedImages);
    };

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        // formData.append('price', price);
        formData.append('ratecode', ratecode);
        formData.append('ratedesc', ratedesc);
        formData.append('roomname', roomname);
        // formData.append('facilities', selectedValues);
        selectedValues.forEach((adp, index) => {
            formData.append(`facilities[${index}]`, adp);
        });

        formData.append('near', near);
        formData.append('service', service);
        // formData.append('extrabed',extrabed);
        maxadult.forEach((adp, index) => {
            formData.append(`maxadult[${index}][value]`, adp.value);
            formData.append(`maxadult[${index}][id]`, adp.id);
        });

        maxchild.forEach((adp, index) => {
            formData.append(`maxchild[${index}][value]`, adp.value);
            formData.append(`maxchild[${index}][id]`, adp.id);
        });

        formData.append('size', size);
        formData.append('roomtypeid', roomtypeid);
        formData.append('adult', adult);
        formData.append('child', child);
        formData.append('bedroom', bedroom);
        formData.append('extra_bed', extrabed);
        formData.append('infant', infant);
        formData.append('baby_cot', baby_cot);
        formData.append('max_benefit', max_benefit);

        formData.append('allowed', allowed);
        images.forEach((image, index) => {
            formData.append(`gallery[${index}]`, image);
        });

        formData.append('feature_image', image);
        // formData.append('video', vidio);
        formData.append('content', content);
        // formData.append('week_sale', selectedValues);

        Inertia.post('/room/store', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }

    return (
        <>
            <Layout page={'/room/index'}>
                <div className="container">
                    <div className="row">
                        <h1>Create Room Type</h1>
                        {session.success && (
                            <div className="alert alert-success border-0 shadow-sm rounded-3">
                                {session.success}
                            </div>
                        )}
                        <div className="col-lg-12">
                            <Tabs
                                defaultActiveKey="home"
                                id="fill-tab-example"
                                className="mb-3"
                                fill
                            >
                                <Tab eventKey="home" title="Room Type">
                                    <div className="col-lg-12">
                                        <form onSubmit={storePost}>
                                            <div className="card">
                                                <div className="card-body">
                                                    <div className="row">
                                                        <div className="col-lg-6">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Room Name</label>
                                                                <input required onChange={(e) => setRoomName(e.target.value)} type="text" className="form-control" id="exampleFormControlInput1" placeholder="room name" />
                                                            </div>
                                                            <div className="d-flex">
                                                                <div className="mb-3 ">
                                                                    <label htmlFor="">Room Type</label>
                                                                    <select className='form-control' name="" id="" onChange={(e) => setRoomType(e.target.value)}>
                                                                       <option value="">-select room type-</option>
                                                                        {roomtype.map((item) => (
                                                                        <>
                                                                        <option key={item.id} value={item.id}>{item.name}</option>
                                                                        </>
                                                                        ))}
                                                                    </select>
                                                                </div>
                                                                <div className="mb-3 mx-2">
                                                                    <label htmlFor="exampleFormControlInput1" className="form-label">Room Type Code</label>
                                                                    <input required onChange={(e) => setRagecode(e.target.value)} type="text" className="form-control" id="exampleFormControlInput1" placeholder="Rate Code" />
                                                                </div>
                                                            </div>
                                                            
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Room Type Description</label>
                                                                <input required onChange={(e) => setRateDesc(e.target.value)} type="text" className="form-control" id="exampleFormControlInput1" placeholder="Rate Description" />
                                                            </div>

                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Feature Image</label>
                                                                <input onChange={handleFileChange} type="file" className="form-control" id="exampleFormControlInput1" placeholder="Feature Image" />
                                                            </div>
                                                            <div className="row">
                                                                {/* <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Max Infant</label>
                                                                <input onChange={(e) => setInfant(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Max Infant"/>
                                                            </div>
                                                        </div> */}
                                                                {/* <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Total Bedroom</label>
                                                                <input onChange={(e) => setBedroom(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Total Bedroom"/>
                                                            </div>
                                                        </div> */}
                                                                {/* <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Size</label>
                                                                <input onChange={(e) => setSize(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Total Bedroom"/>
                                                            </div>
                                                        </div> */}
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-6">
                                                            {/* <div className="mb-3">
                                                        <label htmlFor="exampleFormControlInput1" className="form-label">Price</label>
                                                        <input onChange={(e) => handlePrice(e.target.value)}  type="text" className="form-control" id="exampleFormControlInput1" placeholder="Price"/>
                                                    </div> */}
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Room allotment</label>
                                                                <input required onChange={(e) => setAllowed(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Room allotment" />
                                                            </div>
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Gallery</label>
                                                                <input onChange={handleImageUpload} type="file" multiple className="form-control" id="exampleFormControlInput1" placeholder="Feature Image" />
                                                            </div>

                                                            {/* <div className="mb-3">
                                                        <label htmlFor="exampleFormControlInput1" className="form-label">Configuration adult</label>
                                                        <br />
                                                        {adultprice &&(
                                                                <label htmlFor="" className='text-secondary' style={{fontSize:"12px"}}>recommendation price {adultprice}</label>
                                                            )}
                                                        <div className="row justify-content-center">

                                                        {inputs.map((input, index) => (
                                                            <>
                                                            <div className="col-lg-3">
                                                                <p className='my-2'>{index + 1} adult</p>
                                                            </div>
                                                        <div className="col-lg-9">
                                                            <div className="d-flex">
                                                                <input
                                                                    className='mb-1 form-control'
                                                                    key={index}
                                                                    value={input}
                                                                    onChange={(e) => handleInputChange(index, e.target.value)}
                                                                    placeholder={`${ratecode}`}
                                                                />
                                                                <button type="button" className='btn btn-danger mx-2 mb-2' onClick={() => handleHapusInput(index)}>
                                                                    <i className='fa fa-trash'></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                            </>
                                                        ))}
                                                    </div>
                                                    </div>
                                                <button className='btn btn-primary' type="button" onClick={handleTambahInput}>
                                                    + Add Configuration
                                                </button> */}

                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">max Extra bed</label>
                                                                <input required onChange={(e) => setExtrabed(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Max Extra Bed" />
                                                            </div>
                                                            <div className="row">
                                                                <div className="col-lg-6">
                                                                    <div className="mb-3 ">
                                                                        <label htmlFor="exampleFormControlInput1" className="form-label">Max Person</label>
                                                                        <input required onChange={(e) => setMaxAdult(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="max adult" />
                                                                    </div>
                                                                </div>
                                                                <div className="col-lg-6">
                                                                    <div className="mb-3">
                                                                        <label htmlFor="exampleFormControlInput1" className="form-label">Size</label>
                                                                        <input required onChange={(e) => setSize(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Total Bedroom" />
                                                                    </div>
                                                                </div>
                                                                {/* <div className="col-lg-4">
                                                        <div className="mb-3">
                                                            <label htmlFor="exampleFormControlInput1" className="form-label">Max Child</label>
                                                            <input onChange={(e) => setMaxChild(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Max Child"/>
                                                        </div>
                                                    </div>
                                                    <div className="col-lg-4">
                                                        <div className="mb-3">
                                                            <label htmlFor="exampleFormControlInput1" className="form-label">max Beby Cot</label>
                                                            <input onChange={(e) => setBebyCot(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Max Bebt Cot"/>
                                                        </div>
                                                    </div> */}
                                                            </div>
                                                            {/* <div className="mb-3 ">
                                                    <label htmlFor="exampleFormControlInput1" className="form-label">Max Adult</label>
                                                    <input onChange={(e) => setMaxAdult(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="max adult"/>
                                                 */}
                                                            {/* {maxadult.map((preference, index) => (
                                                    <div key={preference.id} className='row'>
                                                    <div className="col-lg-6">
                                                                <p className='my-2'>adult</p>
                                                        </div>
                                                        <div className="col-lg-6">
                                                            <div className="d-flex">
                                                                <input
                                                                    type="number"
                                                                    className='form-control'
                                                                    value={preference.value}
                                                                    onChange={(e) => handleChangeadult(e, index)}
                                                                />
                                                                <button type="button" className='btn btn-danger' onClick={() => handleRemoveInputAdult(index)}>
                                                                <i className='fa fa-trash'></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    ))}
                                                    <button type="button" className='btn btn-primary' onClick={handleAddInputAdult}>
                                                        <i className="fa fa-plus"></i> Add Preference
                                                        </button> */}
                                                            {/* </div> */}
                                                            {/* <div className="mb-3">
                                                    <label htmlFor="exampleFormControlInput1" className="form-label">Max Child</label>
                                                    <input onChange={(e) => setMaxChild(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Max Child"/>
                                                 */}
                                                            {/* {maxchild.map((preference, index) => (
                                                    <div key={preference.id} className='row'>

                                                    <div className="col-lg-6">
                                                        child :
                                                        </div>
                                                        <div className="col-lg-6">
                                                            <div className="d-flex">
                                                            <input
                                                                type="number"
                                                                value={preference.value}
                                                                className='form-control'
                                                                onChange={(e) => handleChangechild(e, index)}
                                                            />
                                                                <button type="button" className='btn btn-danger' onClick={() => handleRemoveInputchild(index)}>
                                                                <i className='fa fa-trash'></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    ))} */}
                                                            {/* <button type="button" className='btn btn-primary' onClick={handleAddInputchild}>
                                                        <i className="fa fa-plus"></i> Add Preference
                                                    </button> */}
                                                            {/* </div> */}
                                                            {/* <div className="mb-3">
                                                        <label htmlFor="exampleFormControlInput1" className="form-label">max Benefit(3rd person)</label>
                                                        <input onChange={(e) => setBenefit(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Max Benefit (3rd person)"/>
                                                    </div> */}
                                                        </div>
                                                        <div className="row">
                                                            <div className="col-lg-12 mx-3">
                                                                <label htmlFor="">Facilities</label>
                                                                <div className="d-flex flex-wrap">
                                                                    {checkboxItems.map((item, index) => (
                                                                        <div key={index} className="mb-3">
                                                                            <label className="form-label mx-3">
                                                                                <input
                                                                                    className='form-check-input'
                                                                                    type="checkbox"
                                                                                    checked={item.checked}
                                                                                    onChange={() => handleCheckboxChange(index)}
                                                                                />
                                                                                {item.label}</label>
                                                                        </div>
                                                                    ))}
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
                                                                <Link href="/room/index" className="btn btn-danger">
                                                                    Cancel
                                                                </Link>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </Tab>
                                <Tab eventKey="profile" title="Description">
                                    <div className="row">
                                        <div className="col-lg-12">
                                            <form onSubmit={storePost}>
                                                <div className="card">
                                                    <div className="card-body">
                                                        <div className="mb-3">
                                                            <label className="form-label fw-bold">Description</label>
                                                            {/* <textarea className="form-control" value={description} onChange={(e) => setDescription(e.target.value)} placeholder="Masukkan Judul Post" rows={4}></textarea> */}
                                                            <CKEditor
                                                                editor={ClassicEditor}
                                                                data=""
                                                                // value={description}
                                                                // nChange={(e) => setDescription(e.target.value)}
                                                                onReady={editor => {
                                                                    // You can store the "editor" and use when it is needed.
                                                                    console.log('Editor is ready to use!', editor);
                                                                }}
                                                                onChange={handleDescChange}
                                                                onBlur={(event, editor) => {
                                                                    console.log('Blur.', editor);
                                                                }}
                                                                onFocus={(event, editor) => {
                                                                    console.log('Focus.', editor);
                                                                }}
                                                            />
                                                        </div>

                                                        <div className="mb-3">
                                                            <label htmlFor="">Service Information</label>
                                                            <CKEditor
                                                                editor={ClassicEditor}
                                                                data=""
                                                                // value={description}
                                                                // nChange={(e) => setDescription(e.target.value)}
                                                                onReady={editor => {
                                                                    // You can store the "editor" and use when it is needed.
                                                                    console.log('Editor is ready to use!', editor);
                                                                }}
                                                                onChange={handleServiceChange}
                                                                onBlur={(event, editor) => {
                                                                    console.log('Blur.', editor);
                                                                }}
                                                                onFocus={(event, editor) => {
                                                                    console.log('Focus.', editor);
                                                                }}
                                                            />
                                                        </div>
                                                        <div className="mb-3">
                                                            <label htmlFor="">Hotel Nearby</label>
                                                            <CKEditor
                                                                editor={ClassicEditor}
                                                                data=""
                                                                // value={description}
                                                                // nChange={(e) => setDescription(e.target.value)}
                                                                onReady={editor => {
                                                                    // You can store the "editor" and use when it is needed.
                                                                    console.log('Editor is ready to use!', editor);
                                                                }}
                                                                onChange={handleNearChange}
                                                                onBlur={(event, editor) => {
                                                                    console.log('Blur.', editor);
                                                                }}
                                                                onFocus={(event, editor) => {
                                                                    console.log('Focus.', editor);
                                                                }}
                                                            />
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
                                </Tab>
                                {/* <Tab eventKey="longer-tab" title="Loooonger Tab">
                            Tab content for Loooonger Tab
                        </Tab>
                        <Tab eventKey="contact" title="Contact">
                            Tab content for Contact
                        </Tab> */}
                            </Tabs>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    )
}
