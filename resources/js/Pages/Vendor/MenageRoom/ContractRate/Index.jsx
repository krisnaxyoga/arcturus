//import React
import React,{ useState, useEffect } from 'react';

//import layout
import Layout from '../../../../Layouts/Vendor';
import Pagination from '../../../../Components/Pagination';
import Rates from '../../../Vendor/MenageRoom/ContractRate/Rates';


//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';
import Modal from 'react-bootstrap/Modal';

export default function Index({ session,data,roomtype,form,barroom,surcharge,black }) {
    const { url } = usePage();
    // console.log(roomtype,">>romtype");

    const [currentPage, setCurrentPage] = useState(1)
    const [postsPerPage, setPostsPerPage] = useState(10)
    const [barcode, setBarcode] = useState('');
    const [bardesc, setBarDesc] = useState('');
    const [begin, setBegin] = useState('');
    const [end, setEnd] = useState('');

    const [price, setPrice] = useState();
    const [dataValues, setDataValues] = useState([]);

    const [show, setShow] = useState(false);
    const [modalData, setModalData] = useState();

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
      }

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
    const buttonSendValue = (param) => {
        setModalData(param);
        // console.log(param, "param");
        setShow(true);
    }
    const handleClose = () => {
        setShow(false);
    }

    const handleAdultValueChangeEdit = (e, index, field) => {
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
    const storePostprice = async (e) => {
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

    const storePostPriceEdit = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('barcode', barcode ? barcode : barroom.barcode);

        formData.append('bardesc', bardesc ? bardesc : barroom.bardesc);

        formData.append('begin', begin ? begin : barroom.begindate);
        formData.append('end', end ? end : barroom.enddate);

        dataValues ? dataValues : data.forEach((adp, index) => {
            formData.append(`price[${index}][price]`, adp.price);
            formData.append(`price[${index}][room_id]`, adp.room_id);
            formData.append(`price[${index}][id]`, adp.id);
        });

        Inertia.post(`/room/barcode/update/${barroom.id}`, formData, {
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
                // alert('data saved!');
                window.location.reload();
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }

    const indexOfLastPost = currentPage * postsPerPage;
    const indexOfFirstPost = indexOfLastPost - postsPerPage;
    const currentPosts = data.slice(indexOfFirstPost, indexOfLastPost);

    const paginate = pageNum => setCurrentPage(pageNum);

    const nextPage = () => setCurrentPage(currentPage + 1);

    const prevPage = () => setCurrentPage(currentPage - 1);

    return (
        <>
        <Layout page={url}>
            <div className="container">
                <div className="row">
                    <div className="col-lg-12">
                    <Tabs
                                defaultActiveKey="home"
                                id="fill-tab-example"
                                className="mb-3"
                                fill
                            >
                        <Tab eventKey="home" title="Bar Info">

                        {form === 'add' ? (
                                <>
                                <div className="col-lg-11">
                                    <form onSubmit={storePostprice}>
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
                                                                    <input  value={begin} onChange={(e) => setBegin(e.target.value)} type="date" className='form-control' />
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
                                                                                        {/* <th>room type code</th> */}
                                                                                        <th>room type</th>
                                                                                        <th>rate price</th>
                                                                                        {/* <th>child price</th> */}
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    {roomtype.map((item, index) => (
                                                                                        <>
                                                                                            <tr key={index}>
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
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                </>
                            ):(
                                <>
                                <div className="col-lg-11">
                                    <form onSubmit={storePostPriceEdit}>
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
                                                                    <input defaultValue={barroom.barcode} onChange={(e) => setBarcode(e.target.value)} type="text" className='form-control' />
                                                                </div>

                                                                <div className="mb-3">
                                                                    <label htmlFor="" className='fw-bold'>Begin Sell date</label>
                                                                    <input defaultValue={barroom.begindate} onChange={(e) => setBegin(e.target.value)} type="date" className='form-control' />
                                                                </div>
                                                            </div>
                                                            <div className="col-lg-6">
                                                                <div className="mb-3">
                                                                    <label htmlFor="" className='fw-bold'>Bar description</label>
                                                                    <input defaultValue={barroom.bardesc} onChange={(e) => setBarDesc(e.target.value)} type="text" className='form-control' />
                                                                </div>
                                                                <div className="mb-3">
                                                                    <label htmlFor="" className='fw-bold'>End Sell date</label>
                                                                    <input defaultValue={barroom.enddate} onChange={(e) => setEnd(e.target.value)} type="date" className='form-control' />
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
                                                                                        <th>room type</th>
                                                                                        <th>rate price</th>
                                                                                        <th>action</th>
                                                                                        {/* <th>child price</th> */}
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    {roomtype.length === 0 ? (
                                                                                        <>

                                                                                        </>
                                                                                    ):(
                                                                                        <>
                                                                                        {roomtype.map((item, index) => (
                                                                                                    <tr key={index}>
                                                                                                        <td>{item.room.ratedesc}</td>
                                                                                                        <td>
                                                                                                            <ul>
                                                                                                                    <span>{formatRupiah(item.price)}</span>

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
                                                                                            ))}
                                                                                        </>
                                                                                    )}


                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div className="mb-3">
                                                                            <Link href={`/room/barcodeprice/cekroom/${barroom.id}`} className='btn btn-success'><i className='fa fa-plus'></i> add room</Link>
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
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
                                </>
                            )}

                        </Tab>
                        <Tab eventKey="contract" title="contract">
                            <div className="row mb-4">
                                <div className="col-lg-12">
                                    <div className="card">
                                        <div className="card-header">
                                            <h2>Contract Rate</h2>
                                        </div>
                                        {session.success && (
                                            <div className="alert alert-success border-0 shadow-sm rounded-3">
                                                {session.success}
                                            </div>
                                        )}
                                        <div className="card-body">
                                        <Link href="/room/contract/create" className="btn btn-primary mb-2">add</Link>
                                            <div className="table-responsive">
                                                    <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>Rate code</th>
                                                                <th>Rate description</th>
                                                                <th>Begin Stay</th>
                                                                <th>End Stay</th>
                                                                <th>Begin Booking</th>
                                                                <th>End Booking</th>
                                                                <th>Min Stay</th>
                                                                <th>Market</th>
                                                                <th>action</th>
                                                            </tr>
                                                        </thead>
                                                        <Rates rates={currentPosts} />
                                                    </table>
                                                    <Pagination postsPerPage={postsPerPage} totalPosts={data.length} paginate={paginate} nextPage={nextPage} prevPage={prevPage} crntPage={currentPage}/>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-lg-6">
                                    <div className="card">
                                        <div className="card-header">
                                            <div className="d-flex justify-content-between">
                                                <h2>Blackout Dates</h2>
                                                <div>
                                                    <Link href='/room/markup/addblack' className='btn btn-primary'> <i className='fa fa-plus'></i> add</Link>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="card-body" style={{ height:'15rem',overflow:'auto' }}>
                                            <div className="table-responsive">
                                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>start date</th>
                                                            <th>End date</th>
                                                            <th>action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {black.map((item, index) => (
                                                            <>
                                                                <tr key={index}>
                                                                    <td>{item.start_date}</td>
                                                                    <td>{item.end_date}</td>
                                                                    <td>
                                                                        <Link href={`/room/markup/editblack/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
                                                                            <i className='fa fa-edit'></i>
                                                                        </Link>
                                                                        <Link href={`/room/markup/destroyblack/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
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
                                <div className="col-lg-6">
                                    <div className="card">
                                        <div className="card-header">
                                            <div className="d-flex justify-content-between">
                                                <h2>Surcharge Dates</h2>
                                                <div>
                                                    <Link href='/room/markup/addsurchage' className='btn btn-primary'> <i className='fa fa-plus'></i> add</Link>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="card-body" style={{ height:'15rem',overflow:'auto' }}>
                                            <div className="table-responsive">
                                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>price</th>
                                                            <th>start date</th>
                                                            <th>End date</th>
                                                            <th>action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {surcharge.map((item, index) => (
                                                            <>
                                                                <tr key={index}>
                                                                    <td>{formatRupiah(item.surcharge_block_price)}</td>
                                                                    <td>{item.start_date}</td>
                                                                    <td>{item.end_date}</td>
                                                                    <td>
                                                                        <Link href={`/room/markup/editsurchage/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
                                                                            <i className='fa fa-edit'></i>
                                                                        </Link>
                                                                        <Link href={`/room/markup/destroysurchage/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
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
