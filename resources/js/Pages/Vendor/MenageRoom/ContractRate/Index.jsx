//import React
import React,{ useState, useEffect } from 'react';

//import layout
import Layout from '../../../../Layouts/Vendor';
import Pagination from '../../../../Components/Pagination';
import Rates from '../../../Vendor/MenageRoom/ContractRate/Rates';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

import Modal from 'react-bootstrap/Modal';

export default function Index({ session,data,roomtype,form,barroom,surcharge,black,vendor }) {
    const { url } = usePage();
    const [isLoading, setIsLoading] = useState(true);
    const [currentPage, setCurrentPage] = useState(1);
    const [postsPerPage, setPostsPerPage] = useState(10);
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
        // Anda dapat menambahkan logika tambahan jika diperlukan
        // Contoh: Memuat data dari server

        // Misalnya, ini adalah simulasi pengambilan data yang memakan waktu
        setTimeout(() => {
            setIsLoading(false); // Langkah 2: Setel isLoading menjadi false setelah halaman selesai dimuat
        }, 1000); // Menggunakan setTimeout untuk simulasi saja (2 detik).

        // Jika Anda ingin melakukan pengambilan data dari server, Anda dapat melakukannya di sini dan kemudian mengatur isLoading menjadi false setelah data berhasil dimuat.
    }, []); // Kosongkan array dependencies untuk menjalankan efek ini hanya sekali saat komponen dimuat.


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
                // Lakukan aksi setelah gamBARberhasil diunggah
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
                // Lakukan aksi setelah gamBARberhasil diunggah
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
                // Lakukan aksi setelah gamBARberhasil diunggah
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
                // Lakukan aksi setelah gamBARberhasil diunggah
            },
        });
    }

    const indexOfLastPost = currentPage * postsPerPage;
    const indexOfFirstPost = indexOfLastPost - postsPerPage;
    const currentPosts = data.slice(indexOfFirstPost, indexOfLastPost);

    const paginate = pageNum => setCurrentPage(pageNum);

    const nextPage = () => setCurrentPage(currentPage + 1);

    const prevPage = () => setCurrentPage(currentPage - 1);

    const [introDisabled, setIntroDisabled] = useState(false);
    let intro = null;
    useEffect(() => {
        // Memeriksa status "tidak ingin ditampilkan lagi" dari penyimpanan lokal
        const isDisabled = localStorage.getItem('introDisabled');
        if (isDisabled === 'true' || form != 'add') {
          setIntroDisabled(true);
        } else {
          // Inisialisasi Intro.js jika tidak dinonaktifkan
          intro = introJs();
          intro.setOptions({
            steps: [
              {
                element: '.intro-step-1',
                intro: 'step 1',
              },
              {
                element: '.intro-step-2',
                intro: 'step 2',
              }, 
              {
                element: '.intro-step-3',
                intro: 'step 3',
              },
              {
                element: '.intro-step-4',
                intro: 'step 4',
              },
              {
                element: '.intro-step-5',
                intro: 'step 5',
              },
              {
                element: '.intro-step-6',
                intro: 'step 6',
              },
              // Tambahkan langkah-langkah sesuai kebutuhan Anda
            ],
          });
    
          // Mulai tutorial saat komponen di-mount
          intro.start();
        }
      }, []);
    
      // Fungsi untuk menonaktifkan tampilan tutorial
      function disableIntro() {
        // Menyimpan status "tidak ingin ditampilkan lagi" di penyimpanan lokal
        localStorage.setItem('introDisabled', 'true');
        setIntroDisabled(true);
    
        // Keluar dari tutorial jika sedang berjalan
        if (intro) {
          intro.exit();
        }
      }
    
      // Event handler saat tutorial selesai
      useEffect(() => {
        if (intro) {
          intro.oncomplete(() => {
            disableIntro();
          });
    
          intro.onexit(() => {
            disableIntro();
          });
        }
      }, [intro]);

    return (
        <>
        <Layout page={url} vendor={vendor}>
            {isLoading ? (
                <div className="container">
                    <div className="loading-container">
                        <div className="loading-spinner"></div>
                        <div className="loading-text">Loading...</div>
                    </div>
                </div>// Langkah 3: Tampilkan pesan "Loading..." saat isLoading true
            ) : (
                // Tampilan halaman Anda yang sebenarnya
                <div className="container">
                <div className="row">
                    {form === 'add' ? (
                        <>
                            <div className="col-lg-11">
                                <form onSubmit={storePostprice}>
                                    <div className="card">
                                        <div className="card-body">
                                            {session.success && (
                                                <div className={`alert ${session.success === 'Please first fill in your Room Type on the room types menu.' ? 'alert-danger' : 'alert-success'} border-0 shadow-sm rounded-3`}>
                                                    {session.success}
                                                </div>
                                            )}
                                            <div className="row">
                                                <div className="col-lg-12">
                                                    <div className="row">
                                                        <div className="col-lg-6">
                                                            <div className="mb-3 intro-step-1">
                                                                <label htmlFor="" className='fw-bold'>Bar code</label>
                                                                <input onChange={(e) => setBarcode(e.target.value)} type="text" className='form-control' />
                                                            </div>

                                                            <div className="mb-3 intro-step-2">
                                                                <label htmlFor="" className='fw-bold'>Begin Stay date</label>
                                                                <input  value={begin} onChange={(e) => setBegin(e.target.value)} type="date" className='form-control' />
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-6">
                                                            <div className="mb-3">
                                                                <label htmlFor="" className='fw-bold'>Bar description</label>
                                                                <input onChange={(e) => setBarDesc(e.target.value)} type="text" className='form-control form-control-solid' placeholder='HOTEL BEST AVAILABLE RATE'/>
                                                            </div>
                                                            <div className="mb-3 intro-step-3">
                                                                <label htmlFor="" className='fw-bold'>End Stay date</label>
                                                                <input value={end} onChange={(e) => setEnd(e.target.value)} type="date" className='form-control' />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="row">
                                                        <div className="col-lg-12">
                                                            <div className="card intro-step-4">
                                                                <div className="card-body">
                                                                    <div className="table-responsive">
                                                                        <table id="dataTable" width="100%" cellSpacing="0">
                                                                            <thead>
                                                                            <tr>
                                                                                {/* <th>room type code</th> */}
                                                                                <th>room type</th>
                                                                                <th>Bar rate</th>
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
                                                    <button type="submit" className="btn btn-primary intro-step-5">
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
                                                 <div className={`alert ${session.success === 'Please first fill in your Room Type on the room types menu.' ? 'alert-danger' : 'alert-success'} border-0 shadow-sm rounded-3`}>
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
                                                                <label htmlFor="" className='fw-bold'>Begin Stay date</label>
                                                                <input defaultValue={barroom.begindate} onChange={(e) => setBegin(e.target.value)} type="date" className='form-control' />
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-6">
                                                            <div className="mb-3">
                                                                <label htmlFor="" className='fw-bold'>Bar description</label>
                                                                <input defaultValue={barroom.bardesc} onChange={(e) => setBarDesc(e.target.value)} type="text" className='form-control form-control-solid' />
                                                            </div>
                                                            <div className="mb-3">
                                                                <label htmlFor="" className='fw-bold'>End Stay date</label>
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
                                                                                <th>Room Type</th>
                                                                                <th>Bar Rate</th>
                                                                                <th>Action</th>
                                                                                {/* <th>child price</th> */}
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            {roomtype.length > 0 && (
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
                                                                                                {/* <Link href={`/room/barcodeprice/destroy/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
                                                                                                    <i className='fa fa-trash'></i>
                                                                                                </Link> */}
                                                                                            </td>
                                                                                        </tr>
                                                                                    ))
                                                                                    }
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
                                                        <i className="fa fa-save mr-1"></i> Save
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
                                                <label htmlFor="" className='form-label'>Bar Rate</label>
                                                <input className='form-control' type="number" defaultValue={modalData?.price} onChange={(e) => setPrice(e.target.value)} />
                                            </div>
                                            <div className="mb-3">
                                                <button className="btn btn-primary" type='submit'>
                                                    <i className='fa fa-save'></i>&nbsp;Save
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

                    <div className="col-lg-11 mt-3">
                        <div className="card">
                            <div className="card-header">
                                <h2>Contract Rate</h2>
                            </div>
                            <div className="card-body">
                                <Link href="/room/contract/create" className="btn btn-primary mb-2 intro-step-6">Add</Link>
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
            </div>
            )}
            
        </Layout>
        </>
    )
}
