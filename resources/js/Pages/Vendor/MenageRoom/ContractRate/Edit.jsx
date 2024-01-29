//import React
import React, { useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../../Layouts/Vendor';
import axios from 'axios';
import { Button, Form, Modal } from 'react-bootstrap';

//import Link
import { CKEditor } from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';

export default function PriceAgentRoom({ country, session, data, markup, bardata, contract, contractprice, advancepurchase,vendor,contpriceone }) {

    const [ratecode, setRateCode] = useState('');
    const [ratedesc, setRateDesc] = useState('');
    const [minstay, setMinStay] = useState('');
    const [beginsell, setBeginSell] = useState('');
    const [endsell, setEndSell] = useState('');
    const [begindate, setBeginDate] = useState('');
    const [enddate, setEndDate] = useState('');
    const [cancelPolicy, setCancellationPolicy] = useState('');
    const [depositPolicy, setDepositPolicy] = useState('');
    const [benefitPolicy, setBenefitPolicy] = useState('');

    const [otherpolicy, setOtherPolicy] = useState('');
    const [minPrice, setMinPrice] = useState('');
    const [sellingPrice, setSellingPrice] = useState('');
    const [advanceprice, setAdvPrice] = useState('');

    const [modalData, setModalData] = useState()

    const [showModal, setShowModal] = useState(false);

    const [showModalMarkup, setShowModalMarkup] = useState(false);

    const [showModalTable, setShowModalTable] = useState(false);
    const [updatedRates, setUpdatedRates] = useState(contractprice);
    const [updatedRatesADV, setUpdatedRatesAdv] = useState(advanceprice);
    const [updatedadvStatus, setUpdatedAdvStatus] = useState(advancepurchase);

    const [showModalAdvance, setShowModalAdvance] = useState(false);

    const [percentage, setPercentage] = useState(contract.percentage);

    // advance purchase variable
    const [day, setDay] = useState('');
    const [price, setPrice] = useState('');
    const [beginselladvance, setBeginselladvance] = useState('');
    const [endselladvance, setEndselladvance] = useState('');
    const [isLoading, setIsLoading] = useState(true);

    // Buat fungsi untuk mengambil data advanceprice
    const fetchAdvancePrice = async (contractId) => {
        try {
        const response = await axios.get(`/contract/getadvanceprice/${contractId}`);
        const advancepriceData = response.data.advanceprice;
        setAdvPrice(advancepriceData);
        // Lakukan sesuatu dengan data advancepriceData, misalnya, perbarui state
        // Misalnya, setAdvancePrice(advancepriceData) jika Anda menggunakan React Hooks.
        // Atau this.setState({ advanceprice: advancepriceData }) jika Anda menggunakan kelas komponen.
        } catch (error) {
        console.error('Gagal mengambil data advanceprice:', error);
        }
    };
    
    // Panggil fungsi fetchAdvancePrice saat diperlukan
    const contractId = contract.id; // Gantilah dengan ID kontrak yang sesuai
    fetchAdvancePrice(contractId);
    
    useEffect(() => {
        // Anda dapat menambahkan logika tambahan jika diperlukan
        // Contoh: Memuat data dari server

        // Misalnya, ini adalah simulasi pengambilan data yang memakan waktu
        setTimeout(() => {
            setIsLoading(false); // Langkah 2: Setel isLoading menjadi false setelah halaman selesai dimuat
        }, 900); // Menggunakan setTimeout untuk simulasi saja (2 detik).

        // Jika Anda ingin melakukan pengambilan data dari server, Anda dapat melakukannya di sini dan kemudian mengatur isLoading menjadi false setelah data berhasil dimuat.
    }, []); // Kosongkan array dependencies untuk menjalankan efek ini hanya sekali saat komponen dimuat.


    const handleDayChange = (e, beginsell, endsell, day) => {
        const inputDay = parseInt(e.target.value, 10);
        setDay(inputDay);

        // Calculate beginsell and endsell based on input day
        if (beginsell !== '' && !isNaN(beginsell)) {
          const startDate = new Date(beginsell);
          const newEndsell = new Date(endsell);
          newEndsell.setDate(startDate.getDate() + inputDay);

          setEndselladvance(newEndsell.toISOString().slice(0, 10));
        }
      };

  const [advPercentage, setAdvPercentage] = useState('');
  const [advPercentageFinal, setAdvPercentageFinal] = useState('');

  const [advId, setAdvId] = useState('');

const handlePercentageAdv = (e,itemId) => {

    setAdvId(itemId);
    const percentageAdv = parseFloat(e.target.value); // Ubah ke tipe angka desimal
    setAdvPercentage(percentageAdv);

    const percentageFinal = 1 - (percentageAdv / 100);
    setAdvPercentageFinal(percentageFinal);

    // console.log(advPercentageFinal);
}

    const [checkboxes, setCheckboxes] = useState({
        sunday: false,
        monday: false,
        tuesday: false,
        wednesday: false,
        thursday: false,
        friday: false,
        saturday: false,
    });
// console.log(contpriceone,"one");
    // const [checkboxItems, setCheckboxItems] = useState(contract.pick_day.map((item) => ({ label: item, checked: false })));


    const [selectedDistribute, setSelectedDistribute] = useState(contract.distribute || []);
    const [selectedExclude, setSelectedExclude] = useState(contract.except || []);

    useEffect(() => {
        setSelectedDistribute(contract.distribute || []);
        setSelectedExclude(contract.except || []);
    }, [contract.distribute, contract.except]);
    
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
      }
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

    const buttonSendValueAdvance = (item) => {
        setModalData(item);
        setShowModalAdvance(true);
    };

    const handleCloseModalTable = () => {
        setShowModalTable(false);
    };

    const buttonSendValue = (item) => {
        setModalData(item);

        const sellprice = Number(item.recom_price); //+ Number(markup[0]?.markup_price)

        setSellingPrice(sellprice)

        setShowModal(true)
    };


    const handleCloseModal = () => {
        setShowModal(false);
    };

    const handleCloseModalAdvance = () => {
        setShowModalAdvance(false);
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

    const handleRateMinChange = (e) => {
        const value = e.target.value;

        // if (markup[0].markup_price == 0) {
            const selling = Number(value) + Number(modalData?.barprice.price) - ((modalData?.barprice.price * 0.8) + 15000);
            setSellingPrice(selling);
        // } else {
        //     const selling = Number(value) + Number(markup[0].markup_price);
        //     setSellingPrice(selling);
        // }

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

    const handleBenefitPolicyChange = (event, editor) => {
        setBenefitPolicy(editor.getData());
    };

    const handleOthertPolicyChange = (event, editor) => {
        setOtherPolicy(editor.getData());
    };

    const storePost = async (e) => {
        e.preventDefault();

        
        setIsLoading(true);

        const formData = new FormData();

        formData.append('ratecode', ratecode ? ratecode : contract.ratecode);

        formData.append('codedesc', ratedesc ? ratedesc : contract.codedesc);
        formData.append('min_stay', minstay ? minstay : contract.min_stay);

        formData.append('stayperiod_begin', beginsell ? beginsell : contract.stayperiod_begin);
        formData.append('stayperiod_end', endsell ? endsell : contract.stayperiod_end);
        formData.append('booking_begin', begindate ? begindate : contract.booking_begin);
        formData.append('booking_end', enddate ? enddate : contract.booking_end);


        const days = checkboxes ? checkboxes : contract.pick_day;
        const trueDays = Object.keys(days).filter((day) => days[day] == true);

        formData.append('pick_day', trueDays);

        formData.append('cencellation_policy', cancelPolicy ? cancelPolicy : contract.cencellation_policy);
        formData.append('deposit_policy', depositPolicy ? depositPolicy : contract.deposit_policy);
        formData.append('benefit_policy', benefitPolicy ? benefitPolicy : contract.benefit_policy);
        formData.append('other_policy', otherpolicy ? otherpolicy : contract.other_policy);

        formData.append('percentage',percentage ? percentage:contract.percentage);

        formData.append('distribute', selectedDistribute);
        formData.append('except', selectedExclude);

        Inertia.post(`/room/contract/update/${contract.id}`, formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
                setIsLoading(false);
            },
        });
    }

    const storeAdvance = async (e,itemId,itemDay,itemPercentage) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append('day', day ? day : itemDay);
        formData.append('percentage', advPercentage ? advPercentage : itemPercentage);
        // formData.append('beginsell',beginselladvance ? beginselladvance : itemBeginsell);
        // formData.append('endsell',endselladvance ? endselladvance : itemEndsell);
        setIsLoading(true);

        Inertia.post(`/contract/advancepurchase/${itemId}`, formData, {
            onSuccess: () => {
                window.location.reload();
            },
        });
    }

        // Fungsi untuk mengubah status dan mengirim permintaan ke server
        const toggleStatus = async (id, currentStatus) => {
            try {
            // Kirim permintaan ke server untuk mengubah status
            const response = await fetch(`/room/contract/contract_price_is_active/${id}/${currentStatus == 1 ? 0 : 1}`, {
                method: 'GET', // Gantilah dengan metode HTTP yang sesuai
                // Tambahkan header jika diperlukan
            });

            if (response.ok) {
                // Jika berhasil, perbarui status secara lokal
                const updatedRatesCopy = [...updatedRates];
                const rateIndex = updatedRatesCopy.findIndex((rate) => rate.id == id);
                updatedRatesCopy[rateIndex].is_active = currentStatus == 1 ? 0 : 1;
                setUpdatedRates(updatedRatesCopy);
            } else {
                // Handle kesalahan jika ada
                console.error('Gagal mengubah status.');
            }
            } catch (error) {
            console.error('Terjadi kesalahan:', error);
            }
        };

         // Fungsi untuk mengubah status dan mengirim permintaan ke server
         const toggleStatusAdv = async (id, currentStatus) => {
            try {
            // Kirim permintaan ke server untuk mengubah status
            const response = await fetch(`/room/contract/adv_price_is_active/${id}/${currentStatus == 1 ? 0 : 1}`, {
                method: 'GET', // Gantilah dengan metode HTTP yang sesuai
                // Tambahkan header jika diperlukan
            });

            if (response.ok) {
                // Jika berhasil, perbarui status secara lokal
                const updatedRatesCopy = [...updatedRatesADV];
                const rateIndex = updatedRatesCopy.findIndex((rate) => rate.id == id);
                updatedRatesCopy[rateIndex].is_active = currentStatus == 1 ? 0 : 1;
                setUpdatedRatesAdv(updatedRatesCopy);
            } else {
                // Handle kesalahan jika ada
                // console.error('Gagal mengubah status.');
            }
            } catch (error) {
            // console.error('Terjadi kesalahan:', error);
            }
        };
        const toggleStatusAdvmaster = async (id, currentStatus) => {
            try {
                const scrollPosition = window.scrollY;
                setIsLoading(true);

                // Kirim permintaan ke server untuk mengubah status
                const response = await fetch(`/advance/updateadvancetstatus/${id}/${currentStatus == 2 ? 1 : 2}`, {
                method: 'GET',
                // Tambahkan header jika diperlukan
                });
                setIsLoading(false);
                 // Atur kembali posisi scroll setelah perubahan
                window.scrollTo({ top: scrollPosition, behavior: 'auto' });

                if (response.ok) {
                // Simpan ID yang sedang aktif
                const updatedRatesCopy = [...updatedadvStatus];
                const rateIndex = updatedRatesCopy.findIndex((rate) => rate.id == id);
                updatedRatesCopy[rateIndex].is_active = currentStatus == 2 ? 1 : 2;
                setUpdatedAdvStatus(updatedRatesCopy);

                // Ganti URL tanpa me-refresh halaman
                const newURL = window.location.href.replace(/#.*/, '') + `#adv-${id}`;
                window.history.pushState({}, '', newURL);
                } else {
                // Handle kesalahan jika ada
                console.error('Gagal mengubah status.');
                }
            } catch (error) {
                console.error('Terjadi kesalahan:', error);
            }
        };

    return (
        <>
            <Layout page='/room/contract/index' vendor={vendor}>
            {isLoading ? (
                <div className="container">
                    <div className="loading-container">
                        <div className="loading-spinner"></div>
                        <div className="loading-text">Loading...</div>
                    </div>
                </div>// Langkah 3: Tampilkan pesan "Loading..." saat isLoading true
            ) : (
                // Tampilan halaman Anda yang sebenarnya
                <>
                <div className="container">
                    <div className="row">
                        <h1>Contract Rate</h1>
                        {(!contract || Object.keys(contract).length == 0) || (!bardata || Object.keys(bardata).length == 0) ? (
                            <div className="alert alert-danger border-0 shadow-sm rounded-3">
                                <p>Please check again the information bar and your mark up price is it complete?</p>
                            </div>
                        ) : (
                            <>
                                <div className="col-lg-12">
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
                                                                        {/* <p htmlFor="" className='fs-6 fst-italic'> this contract only can be changed once a month and every 1st day of the month</p> */}

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
                                                                            <label htmlFor="" className='fw-bold'>Contract description</label>
                                                                            <input defaultValue={contract.codedesc} onChange={(e) => setRateDesc(e.target.value)} type="text" className='form-control' />
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-4">
                                                                        <div className="mb-3">
                                                                        {
                                                                            contract.rolerate == 1 ?
                                                                                <>
                                                                                    <label htmlFor="" className='fw-bold'>Minimum stay</label>
                                                                                    <input defaultValue={contract.min_stay} readOnly onChange={(e) => setMinStay(e.target.value)} type="number" className='form-control' />
                                                                                </> :
                                                                                <>
                                                                                    <label htmlFor="" className='fw-bold'>Minimum stay</label>
                                                                                    <input defaultValue={contract.min_stay} onChange={(e) => setMinStay(e.target.value)} type="number" className='form-control' />
                                                                                </>
                                                                        }
                                                                      </div>
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                                <div className="row">
                                                                    <div className="col-lg-6">
                                                                        <label htmlFor="" className='font-weight-bolder'>STAY PERIODS</label>

                                                                        <div className="row">
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>Begin Stay date</label>
                                                                                    <input style={{backgroundColor: '#e3e6ec'}} defaultValue={contract.stayperiod_begin} onChange={(e) => setBeginSell(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>End Stay date</label>
                                                                                    <input style={{backgroundColor: '#e3e6ec'}} defaultValue={contract.stayperiod_end} onChange={(e) => setEndSell(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr />
                                                                        <label htmlFor="" className='font-weight-bolder'>BOOKING PERIODS</label>

                                                                        <div className="row">
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>Begin Sell date</label>
                                                                                    <input defaultValue={contract.booking_begin} onChange={(e) => setBeginDate(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                            <div className="col-lg-6">
                                                                                <div className="mb-3">
                                                                                    <label htmlFor="" className='fw-bold'>End Sell date</label>
                                                                                    <input defaultValue={contract.booking_end} onChange={(e) => setEndDate(e.target.value)} type="date" className='form-control' />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div className="mb-3">
                                                                            <div className="d-flex">
                                                                            <input style={{width: '4rem'}} onChange={(e) => setPercentage(e.target.value)} type="text" defaultValue={contract.percentage} className='form-control'/> <p style={{marginTop: '8px',marginLeft: '7px'}}>
                                                                               {contract.rolerate == 1 ? (
                                                                                <>
                                                                                % off BAR
                                                                                </>
                                                                               ):(
                                                                                <>
                                                                                % discount of contract rate
                                                                                </>
                                                                               )}
                                                                            </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-lg-6">
                                                                        <div className="mb-5">

                                                                        {contract.rolerate == 1 ? (
                                                                                            <>
                                                                                             <label style={{ marginTop:'2rem' }} htmlFor="">Market</label>
                                                                                            <input type="text" value={'WORLDWIDE'} className='form-control' readOnly/>
                                                                                            </>
                                                                                        ):(
                                                                                            <>
                                                                                             <label htmlFor="">Market</label>
                                                                                             {vendor.marketcountry ? (
                                                                                                <>
                                                                                                <select
                                                                                                    name=""
                                                                                                    id=""
                                                                                                    className="form-control"
                                                                                                    onChange={handleSelectDistribute}
                                                                                                    multiple
                                                                                                >
                                                                                                    {/* <option value="WORLDWIDE">WORLDWIDE</option>
                                                                                                    <option value="WORLDWIDE">WORLDWIDE</option>
                                                                                                    <option value="Asia">Asia</option>
                                                                                                    <option value="Pasific">Pasific</option>
                                                                                                    <option value="Europe">Europe</option>
                                                                                                    <option value="Middle East">Middle East</option>
                                                                                                    <option value="Russia and CIS">Russia and CIS</option>
                                                                                                    <option value="Africa">Africa</option> */}
                                                                                                    {Object.keys(vendor.marketcountry).map((key) => (
                                                                                                    <option key={key} value={vendor.marketcountry[key]}>
                                                                                                        {vendor.marketcountry[key]}
                                                                                                    </option>
                                                                                                    ))}
                                                                                                </select>
                                                                                                <p className="mt-2">
                                                                                                    Selected Values:{" "}
                                                                                                    <span className="text-secondary">
                                                                                                    {selectedDistribute.map((value) => (
                                                                                                        <span key={value}>
                                                                                                            <span  onClick={() => handleRemoveSelected(value)} className="btn badge badge-success text-light mx-1">
                                                                                                            {value} <span className="mx-1 badge badge-danger">x</span>
                                                                                                            </span>
                                                                                                        </span>
                                                                                                    ))}
                                                                                                    </span>
                                                                                                </p>
                                                                                                </>
                                                                                             ):(
                                                                                                <>
                                                                                                 <br />
                                                                                                    <div className='mt-2'>
                                                                                                        <p className='badge badge-danger'>please input country data in your profile</p>
                                                                                                        <br />
                                                                                                        <Link href='/myprofile' className='btn btn-primary'>Input</Link>
                                                                                                    </div>
                                                                                                </>
                                                                                             )}
                                                                                                

                                                                                            </>
                                                                                        )}

                                                                            </div>

                                                                        {/* <label htmlFor="pickdays" className='fw-bold'>Pick valid day</label>
                                                                        <label className="form-label mx-5">
                                                                                <input
                                                                                    type="checkbox"
                                                                                    className='form-check-input'
                                                                                    name="WORLDWIDE"
                                                                                    checked={Object.values(checkboxes).every(val => val)}
                                                                                    onChange={handleCheckboxChangeAll}
                                                                                />
                                                                                All
                                                                            </label>
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

                                                                        </div> */}

                                                                        {/* <div className="mb-3">
                                                                            <label htmlFor="">exclude</label>
                                                                            <select name="" id="" className='form-control' onChange={handleSelectExclude} multiple>

                                                                                {Object.keys(country).map(key => (
                                                                                    <option key={key} value={country[key]}>{country[key]}</option>
                                                                                ))}
                                                                            </select>
                                                                            <p className='mt-2'>Selected Values: <span className='text-secondary'>{selectedExclude.join(', ')}</span></p>

                                                                        </div> */}

                                                                    </div>
                                                                </div>
                                                            </Tab>
                                                            <Tab eventKey="profile" title="Policy">
                                                                <div className="row">
                                                                <div className="col-lg-12">
                                                                        <div className="mb-3">
                                                                            <label className="form-label fw-bold">Benefit Policy</label>
                                                                              <CKEditor
                                                                                editor={ClassicEditor}
                                                                                data={contract.benefit_policy}
                                                                                onReady={editor => {
                                                                                    // You can store the "editor" and use when it is needed.
                                                                                    console.log('Editor is ready to use!', editor);
                                                                                }}
                                                                                onChange={handleBenefitPolicyChange}
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
                                                                            <label className="form-label fw-bold">Cancellation Policy</label>
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
                                                                    <div className="col-lg-12">
                                                                        <div className="mb-3">
                                                                            <label className="form-label fw-bold">Other Conditions</label>
                                                                              <CKEditor
                                                                                editor={ClassicEditor}
                                                                                data={contract.other_policy}
                                                                                onReady={editor => {
                                                                                    // You can store the "editor" and use when it is needed.
                                                                                    console.log('Editor is ready to use!', editor);
                                                                                }}
                                                                                onChange={handleOthertPolicyChange}
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
                                                                                        {contract.rolerate == 1 ? (
                                                                                            <>
                                                                                            <th>bar</th>
                                                                                            </>
                                                                                        ):(
                                                                                            <>
                                                                                            {/* <th>contract</th> */}
                                                                                            </>
                                                                                        )}

                                                                                        <th>contract rate</th>
                                                                                        {/* <th>min mark-up</th>
                                                                                        <th>selling rate</th> */}
                                                                                        <th>Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    {contractprice.map((item, index) => (
                                                                                        <>
                                                                                            <tr key={index}>
                                                                                                {contract.rolerate == 1 ? (
                                                                                                    <>
                                                                                                    <td>{item.room.ratedesc}</td>
                                                                                                    <td>
                                                                                                        {formatRupiah(item.barprice.price)}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                    {contract.percentage == percentage
                                                                                                        ? formatRupiah(item.recom_price)
                                                                                                        : formatRupiah(item.barprice.price * ((100 - percentage) / 100))}

                                                                                                    </td>
                                                                                                    </>

                                                                                                ):(
                                                                                                    <>
                                                                                                     <td>{item.room.ratedesc}</td>
                                                                                                    {/* <td>{formatRupiah(contpriceone[index].recom_price)}</td> */}
                                                                                                    <td>
                                                                                                    {contract.percentage == percentage
                                                                                                        ? formatRupiah(item.recom_price)
                                                                                                        : formatRupiah(contpriceone[index][0].recom_price * ((100 - percentage) / 100))}

                                                                                                    </td>
                                                                                                    </>
                                                                                                )}

                                                                                                <td>
                                                                                                    <a href='#' className='btn btn-datatable btn-icon btn-transparent-dark mr-2' onClick={() => buttonSendValue(item)}>
                                                                                                        <i className='fa fa-edit'></i>
                                                                                                    </a>
                                                                                                    {/* <a href={`/room/contract/destroycontractprice/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
                                                                                                        <i className='fa fa-trash'></i>
                                                                                                    </a> */}
                                                                                                      <a
                                                                                                        href="#"
                                                                                                        className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                                                                        onClick={(e) => {
                                                                                                            e.preventDefault(); // Mencegah perilaku default dari tautan
                                                                                                            toggleStatus(item.id, item.is_active);
                                                                                                        }}
                                                                                                        >
                                                                                                        {item.is_active == 1 ? (
                                                                                                            <span className="text-success">
                                                                                                            <i className="fa fa-circle" aria-hidden="true"></i>on
                                                                                                            </span>
                                                                                                        ) : (
                                                                                                            <span className="text-danger">
                                                                                                            <i className="fa fa-circle" aria-hidden="true"></i>off
                                                                                                            </span>
                                                                                                        )}
                                                                                                        </a>
                                                                                                    {/* <a href={`/room/contract/contract_price_is_active/${item.id}/${item.is_active == 1 ? 0 : 1}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>{item.is_active == 1 ? <><span className='text-success'><i class="fa fa-toggle-on" aria-hidden="true"></i>on </span></> : <><span className='text-danger'><i class="fa fa-toggle-off" aria-hidden="true"></i>off</span></>} </a> */}
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
                                                                {/* <Form.Group controlId="formRate">
                                                                    <Form.Label>Bar Price</Form.Label>
                                                                    <Form.Control
                                                                        type="text"
                                                                        name="rate"
                                                                        data-price={modalData?.reprice}
                                                                        defaultValue={modalData?.barprice.price}
                                                                        disabled
                                                                    />
                                                                </Form.Group> */}
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
                                                                <a href={`/room/contract/updatecontractprice/${modalData?.id}/${minPrice ? minPrice : modalData?.recom_price}/${sellingPrice}`} className='btn btn-primary mt-4'>
                                                                    Save Changes
                                                                </a>
                                                                <Button className='ml-1 mt-4' variant="secondary" onClick={handleCloseModal}>
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
                                                                            (contractItem) => contractItem.room_id == item.room.id
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
                                                        <div className="ml-2 mb-3">
                                                        <Link href={`/room/contract/blackoutcontract/${contract.id}`} className='btn btn-secondary'>Blackout</Link>
                                                        </div>
                                                        </div>

                                                        {/* <Link href={`/room/promo/index/${contract.id}`} className='btn btn-warning'>Promo Price</Link> */}
                                                    </div>
                                                    <div className="col-lg-auto">
                                                        <Link href={'/room/contract/index'} className="btn btn-danger">
                                                            Cancel
                                                        </Link>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div className="col-lg-12">
                                {advancepurchase.map((item, index) => (
                                    <>
                                    <div className="row" key={index}>
                                        <div className="col-lg-12">
                                            <form onSubmit={(e) => storeAdvance(e, item.id,item.day,item.percentage,item.beginsell,item.endsell)}>
                                            <div className="card mt-3">
                                                <div className="card-header">
                                                    <div className="d-flex justify-content-between">
                                                        <h2>
                                                             ADVANCE PURCHASE
                                                        </h2>
                                                        <span className='d-flex'>
                                                            <span className='d-flex'>
                                                                <input onChange={(e) => handlePercentageAdv(e, item.id)} type="text" className='form-control' style={{width:'5rem'}} defaultValue={item.percentage}/>
                                                                <p className='text-dark' style={{marginTop: '8px',marginLeft: '7px'}}> %</p>
                                                            </span>
                                                            <span className='d-flex'>
                                                                <input onChange={(e) => handleDayChange(e, item.beginsell, item.endsell, item.day)} type="number" className='form-control' style={{width:'5rem'}} defaultValue={item.day}/>
                                                                <p className='text-dark' style={{marginTop: '8px',marginLeft: '7px'}}> DAYS</p>
                                                            </span>
                                                        </span>
                                                    </div>

                                                </div>
                                                <div className="card-body">
                                                    {item.is_active == 1 ? (
                                                        <>
                                                        <div className="d-flex mb-4">
                                                        <p className='mr-2'>status : </p>    <a
                                                        href="#"
                                                        className="btn btn-success"
                                                        onClick={(e) => {
                                                            e.preventDefault(); // Mencegah perilaku default dari tautan
                                                            toggleStatusAdvmaster(item.id, item.is_active); // Ganti dengan fungsi toggleStatus yang sesuai
                                                            // window.history.pushState({}, '', `#adv-${item.id}`); // Menggunakan History API
                                                        }}
                                                        >on</a>
                                                       

                                                        </div>
                                                        <div className="row mb-3">
                                                        <div className="col-lg-6">
                                                            <label htmlFor="">Begin Stay Date</label>
                                                            <input readOnly type="date" defaultValue={beginselladvance ||item.beginsell} onChange={(e) => setBeginselladvance(e.target.value)} className='form-control form-control-solid'/>
                                                        </div>
                                                        <div className="col-lg-6">
                                                            <label htmlFor="">End Stay Date</label>
                                                            <input readOnly type="date" defaultValue={endselladvance || item.endsell} onChange={(e) => setEndselladvance(e.target.value)} className='form-control form-control-solid'/>
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
                                                                                        <th>contract</th>
                                                                                        <th>Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                {advanceprice.filter(
                                                                                    (advancepriceItem) =>
                                                                                        advancepriceItem.advance_id == item.id
                                                                                    )
                                                                                    .map((advancepriceItem, index) => (
                                                                                    <tr key={index}>
                                                                                        <td>{advancepriceItem.room.ratedesc}</td>
                                                                                        {/* <td>
                                                                                        {(1-(item.percentage/100))}
                                                                                        </td> */}
                                                                                        <td>
                                                                                            {advPercentageFinal !== '' && advId == advancepriceItem.advance_id ? formatRupiah(((advancepriceItem.price/(1-(item.percentage/100))) * advPercentageFinal)) : formatRupiah(advancepriceItem.price)}
                                                                                        </td>
                                                                                        <td>
                                                                                        <a
                                                                                            href="#"
                                                                                            className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                                                            onClick={() => buttonSendValueAdvance(advancepriceItem)}
                                                                                        >
                                                                                            <i className="fa fa-edit"></i>
                                                                                        </a>
                                                                                        <a
                                                                                                        href="#"
                                                                                                        className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                                                                        onClick={(e) => {
                                                                                                            e.preventDefault(); // Mencegah perilaku default dari tautan
                                                                                                            toggleStatusAdv(advancepriceItem.id, advancepriceItem.is_active);
                                                                                                        }}
                                                                                                        >
                                                                                                        {advancepriceItem.is_active == 1? (
                                                                                                            <span className="text-success">
                                                                                                            <i className="fa fa-circle" aria-hidden="true"></i>on
                                                                                                            </span>
                                                                                                        ) : (
                                                                                                            <span className="text-danger">
                                                                                                            <i className="fa fa-circle" aria-hidden="true"></i>off
                                                                                                            </span>
                                                                                                        )}
                                                                                                        </a>
                                                                                         {/* <a
                                                                                            href={`/contract/destroyadvanceprice/${advancepriceItem.id}`}
                                                                                            className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                                                        >
                                                                                            <i className="fa fa-trash"></i>
                                                                                        </a> */}
                                                                                        </td>
                                                                                    </tr>
                                                                                    ))}

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <hr />
                                                    <div className="my-3">
                                                        <button className='btn btn-primary' type='submit'> <i className='fa fa-save'></i> save</button>
                                                    </div>
                                                         </>
                                                    ):(
                                                        <>
                                                        <div className="d-flex">
                                                        <p>status : </p>
                                                        
                                                        <a
                                                        href="#"
                                                        className="btn btn-danger"
                                                        onClick={(e) => {
                                                            e.preventDefault(); // Mencegah perilaku default dari tautan
                                                            toggleStatusAdvmaster(item.id, item.is_active); // Ganti dengan fungsi toggleStatus yang sesuai
                                                           
                                                        }}
                                                        >off</a>

                                                        </div>
                                                         </>
                                                    )}
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    </>
                                ))}
                                </div>
                            </>
                        )}
                    </div>
                </div>
                <Modal show={showModalAdvance} onHide={handleCloseModalAdvance}>
                    <Modal.Header>
                        <Modal.Title>Edit Advance Price Rate</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <Form>

                            <Form.Group controlId="formName">
                                <Form.Label>Room Type Name</Form.Label>
                                <Form.Control
                                    type="text"
                                    name="name"
                                    defaultValue={modalData?.room?.ratedesc}
                                    disabled
                                />
                            </Form.Group>
                            <Form.Group controlId="formRateMin">
                                <Form.Label>Rate Minimum</Form.Label>
                                <Form.Control
                                    type="text"
                                    name="rate_min"
                                    data-id={modalData?.id}
                                    defaultValue={modalData?.price}
                                    onChange={(e) =>setPrice(e.target.value)}
                                />
                            </Form.Group>

                            <a href={`/room/contract/updateadvancetprice/${modalData?.id}/${price ? price : modalData?.price}`} className='btn btn-primary mt-4'>
                                Save Changes
                            </a>
                            <Button className='ml-1 mt-4' variant="secondary" onClick={handleCloseModalAdvance}>
                                Close
                            </Button>
                        </Form>
                    </Modal.Body>

                </Modal>
                </>
            )}
                
            </Layout>
        </>
    )
}
