//import React
import React, {useCallback,useEffect, useState} from 'react';

//import layout
import Layout from '../../../../Layouts/Vendor';

//import Link
import { Link,usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Modal from 'react-bootstrap/Modal';
import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import axios from 'axios';
import {Alert} from "react-bootstrap";

export default function Index({ errors, session,contractrate, default_selected_hotel_room, hotel_rooms, vendor }) {
    const { url } = usePage();

    const [loadDates, setLoadDates] = useState([])
    
    const [isLoading, setIsLoading] = useState(true);
    const [showModel, setShowModal] = useState(false)
    const [activeHotelRoom, setActiveHotelRoom] = useState(default_selected_hotel_room.room_id)
    const [activeContractRoom, setActiveContractRoom] = useState(default_selected_hotel_room.contract_id)
    const [activeStart, setActiveStart] = useState(null)
    const [activeEnd, setActiveEnd] = useState(null)
    const [alert, setAlert] = useState({
        show: false,
        type: '',
        message: ''
    })

    // Define state for handleStore
    const [date, setDate] = useState('')
    const [startDate, setStartDate] = useState('')

    const [night,setNight] = useState('')
    
    const [roombooking,setRoombooking] = useState('')
    const [totalroomallow,setTotalallow] = useState(0)
    
    const [totalnobooked,setTotalallowBooked] = useState(0)
    const [vailableroom,setAvailableroom] = useState(0)
    const [addAllow, setAddAllow] = useState(0);
    const [minAllow, setMinAllow] = useState(0);
    const [endDate, setEndDate] = useState('')
    const [price, setPrice] = useState(0)
    const [allow, setAllow] = useState('')
    const [defaultallow, setDefaultAllow] = useState('')
    const [active, setActive] = useState(true)
    const [nocheckin, setNoCheckin] = useState(false)
    const [nocheckout, setNoCheckout] = useState(false)

    const handleDatesRender = (arg) => {
        setActiveStart(arg.start)
        setActiveEnd(arg.end)
    };

    const handleAddAllowChange = (e) => {
        const newAddAllow = parseInt(e.target.value);
        setAddAllow(newAddAllow);
        
        // Menghitung jumlah allow + addAllow
        const totalx = allow + newAddAllow + roombooking;
        
        const totalallow = allow + newAddAllow;
        console.log(totalx,totalallow,roombooking)
        setTotalallow(totalx);
        setTotalallowBooked(totalallow);
        setDefaultAllow(totalallow);
    }

    const handleMinAllowChange = (e) => {
        const newAddAllow = parseInt(e.target.value);
        setMinAllow(newAddAllow);
        // Menghitung jumlah allow + addAllow
        let totalx = allow - newAddAllow + roombooking;
        
        let totalallow = allow - newAddAllow;
          // Mengganti nilai negatif dengan 0
        totalx = Math.max(totalx, 0);
        totalallow = Math.max(totalallow, 0);

        console.log(totalx,totalallow,roombooking)
        setTotalallow(totalx);
        setTotalallowBooked(totalallow);
    }

    const handleOpenModal = (arg) => {
        // console.log(arg,">>>>>ARG");

        let startDateStr = arg.event.start.toLocaleDateString('en-GB');

        // Split tanggal menjadi bagian-bagian
        const startDateParts = startDateStr.split('/');
        
        // Buat format tanggal 'yyyy-mm-dd'
        const formattedStartDate = `${startDateParts[2]}-${startDateParts[1]}-${startDateParts[0]}`;

        setDate(formattedStartDate);
        setStartDate(arg.event.startStr);

        let endDateStr = arg.event.endStr || new Date(arg.event.startStr).getTime() + 86400 * 1000;

        // Konversi endDateStr ke format tanggal yang diinginkan
        const endDateTimestamp = new Date(endDateStr).getTime() - 86400 * 1000;
        const endDate2 = new Date(endDateTimestamp).toLocaleDateString('en-GB');

        // Split tanggal menjadi bagian-bagian
        const parts = endDate2.split('/');

        // Buat format tanggal 'yyyy-mm-dd'
        const formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;

        setEndDate(formattedDate);
        setPrice(arg.event.extendedProps.price);
        setAllow(arg.event.allow);
        setDefaultAllow(arg.event.allow);
        setNight(arg.event.extendedProps.night);
        setAvailableroom(arg.event.extendedProps.roomavailable);
        setRoombooking(arg.event.extendedProps.roombooking);
        if(arg.event.extendedProps.nocheckout != 0){
            setNoCheckout(true);
        }else{
            setNoCheckout(false);
        }

        if(arg.event.extendedProps.nocheckin != 0){
            setNoCheckin(true);
        }else{
            setNoCheckin(false);
        }


        setShowModal(true);
    };

    const handleCloseModal = () => {
        setDate('')
        setStartDate('')
        setPrice(0)
        setShowModal(false)
        setTotalallowBooked(0);
        setTotalallow(0);
        setAddAllow(0);
        setMinAllow(0);
    }
    useEffect(() => {
        // Anda dapat menambahkan logika tambahan jika diperlukan
        // Contoh: Memuat data dari server

        // Misalnya, ini adalah simulasi pengambilan data yang memakan waktu
        setTimeout(() => {
            setIsLoading(false); // Langkah 2: Setel isLoading menjadi false setelah halaman selesai dimuat
        }, 900); // Menggunakan setTimeout untuk simulasi saja (2 detik).

        // Jika Anda ingin melakukan pengambilan data dari server, Anda dapat melakukannya di sini dan kemudian mengatur isLoading menjadi false setelah data berhasil dimuat.
    }, []); // Kosongkan array dependencies untuk menjalankan efek ini hanya sekali saat komponen dimuat.

    useEffect(() => {
        // Fungsi yang ingin Anda jalankan saat halaman di-reload
        const fetchData = async () => {
          try {
            const response = await axios.get(`/room/surcharge/${activeHotelRoom}/load-dates`, {
              params: {
                'start': activeStart,
                'end': activeEnd
              }
            });

            setActiveContractRoom(activeContractRoom);
            setActiveHotelRoom(activeHotelRoom);
            setLoadDates(response.data);
          } catch (error) {
            // Tangani kesalahan jika permintaan gagal
            console.error('Error fetching data:', error);
          }
        };

        fetchData();
      }, [activeHotelRoom, activeStart, activeEnd]);

    const handleNavRoomTypeSelect = useCallback(async (hotel_room_id,contract_id) => {
        try {
            const response = await axios.get(`/room/surcharge/${hotel_room_id}/load-dates`,{
                params: {
                    'start': activeStart,
                    'end': activeEnd
                }
            })
            setActiveHotelRoom(hotel_room_id)
            setLoadDates(response.data)
        } catch (error) {
            setAlert({
                show: true,
                type: 'danger',
                message: error
            })
        }
    }, [activeStart,activeEnd]);

    const handleNavContractSelect = useCallback(async (hotel_room_id,contract_id) => {
        try {
            const response = await axios.get(`/room/surcharge/${hotel_room_id}/${contract_id}/load-dates`,{
                params: {
                    'start': activeStart,
                    'end': activeEnd
                }
            })
            setActiveContractRoom(contract_id);
            sethotel_room_id(hotel_room_id)
            setLoadDates(response.data)
        } catch (error) {
            setAlert({
                show: true,
                type: 'danger',
                message: error
            })
        }
    }, [activeStart,activeEnd]);

    const handleStore = async (e) => {
        e.preventDefault()
        const allow2 = totalroomallow > defaultallow ? totalroomallow : defaultallow;
        Inertia.post('/room/surcharge/store', {
            vendor_id: vendor.id,
            room_hotel_id: activeHotelRoom,
            start_date: startDate,
            end_date: endDate,
            price: price,
            active: active,
            nocheckin: nocheckin,
            nocheckout: nocheckout,
            room_allow: allow2,
            night : night
        }, {
            onSuccess: () => {
                handleNavRoomTypeSelect(activeHotelRoom)
                // handleNavContractSelect(activeContractRoom)
                setAddAllow(0);
                setMinAllow(0);
                setDefaultAllow(0);
                handleCloseModal()
             
            },
            onError: (errors) => {
                handleCloseModal()
                setAlert({
                    show: true,
                    type: 'danger',
                    message: errors.create
                })
            }
        })
    }
      
      // Di bagian yang melakukan navigasi
      const handleLinkClick = async (vendorId) => {
        try {
          // Lakukan permintaan jaringan dengan fetch
          const response = await fetch(`/room/surcharge/destroy/${vendorId}/${activeHotelRoom}/${startDate}`, {
            method: 'GET',
            // Tambahkan header jika diperlukan
          });
         
          // Lakukan sesuatu dengan respons jika diperlukan
          if (response.ok) {
            setTotalallowBooked(0);
            setAddAllow(0);
            setMinAllow(0);
            handleNavRoomTypeSelect(activeHotelRoom);
            handleCloseModal()
          } else {
            // Handle kesalahan jika respons tidak OK
            console.error('Gagal melakukan permintaan.');
          }
      
        } catch (error) {
          console.error(error);
        }
      };

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
                                    <>
                                     <div className="container">
                    {alert.show && (
                        <Alert variant={alert.type} onClose={() => setShowAlert(false)} dismissible>
                            {alert.message}
                        </Alert>
                    )}

                    <div className="mb-3">
                        <div className="panel-body">
                            <div className="filter-div d-flex justify-content-between">
                                <h2 className="title-bar no-border-bottom">
                                Rate Calendar
                                </h2>
                                <div className="col-right">
                                    {hotel_rooms.length > 0 && (
                                        <span className="count-string">
                                           Showing 1 - {hotel_rooms.length} of {hotel_rooms.length} rooms
                                       </span>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="user-panel">
                        <div className="panel-title"><strong>Calendar</strong></div>
                        <div className="panel-body no-padding" style={{ background: "#f4f6f8", padding: "0px 15px" }}>
                            <div className="row">
                                <div className="col-md-3" style={{ borderRight: "1px solid #dee2e6" }}>
                                    <p className='font-weight-700'>Room Type</p>
                                    <ul className="nav nav-tabs flex-column vertical-nav">
                                        {hotel_rooms.length > 0 && (
                                            <>
                                                {hotel_rooms.map((item) =>
                                                    (
                                                        <li key={item.id} className="nav-item event-name" onClick={() => handleNavRoomTypeSelect(item.id,item.contract_id)}>
                                                            <a className={`nav-link ${activeHotelRoom == item.id ? "active" : ""}`}>
                                                                {item.ratedesc}
                                                            </a>
                                                        </li>
                                                    )
                                                )}
                                            </>
                                        )}
                                    </ul>
                                   <hr />
                                   <Link href='/room/surcharge/surchargeallroom' className='btn btn-primary'>Surcharge</Link>
                                </div>
                                <div className="col-md-9" style={{ background: "white", padding: "15px"}}>
                                   <FullCalendar
                                            plugins={[ dayGridPlugin ]}
                                            initialView="dayGridMonth"
                                            datesSet={handleDatesRender}
                                            events={loadDates}
                                            eventClick={handleOpenModal}
                                            // rerenderDelay={1000} // Sesuaikan waktu penundaan sesuai kebutuhan Anda

                                        />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <Modal show={showModel} onHide={handleCloseModal}>
                    <Modal.Header>
                        <Modal.Title>
                            Rate Calendar <br />
                            {roombooking != 0 &&
                            <>
                            <span className='badge badge-danger'>room booked : {roombooking}</span>
                            </> }
                        </Modal.Title>
                        <Link
                        href="#"
                        onClick={(e) => {
                            e.preventDefault();
                            handleLinkClick(vendor.id); 
                        }}
                        className='btn btn-datatable btn-icon btn-transparent-dark mr-2'
                        >
                        <svg
                                    xmlns="http://www.w3.org/1000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-refresh-cw"
                                >
                                    <polyline points="23 4 23 10 17 10"></polyline>
                                    <polyline points="1 20 1 14 7 14"></polyline>
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                </svg>
                        </Link>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="container">
                            <form onSubmit={handleStore}>

                                <div className="mb-3">
                                    <label className="form-label fw-bold">Start Date</label>
                                    <input type="date" className="form-control" value={date} readOnly={true} />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label fw-bold">End Date</label>
                                    <input type="date" onChange={(e) =>setEndDate(e.target.value)} className="form-control" value={endDate} />
                                </div>

                                <div className="mb-3">
                                    <label className="form-label fw-bold">Rate</label>
                                    <input id="price" type="number" className="form-control" value={price} onChange={(e) => setPrice(e.target.value)} />
                                </div>
                                {errors.price && (
                                    <div className="alert alert-danger">
                                        {errors.price}
                                    </div>
                                )}
                                <div className="row">
                                    <div className="col mb-3">
                                        <label className="form-label fw-bold">+ / - Room</label>
                                        <input id="addallow" type="number" className="form-control" defaultValue={0} onChange={handleAddAllowChange} 
                                        disabled={minAllow !== 0}
                                        />
                                    </div>
                                    {/* <div className="col mb-3">
                                        <label className="form-label fw-bold">-Min Room</label>
                                        <input id="minallow" type="number" className="form-control" defaultValue={0} onChange={handleMinAllowChange} 
                                         disabled={addAllow !== 0} // Disable input jika nilai addAllow tidak sama dengan 0
                                        />
                                    </div> */}
                                    <div className="col">
                                        <div className="mb-3">
                                            <label className="form-label fw-bold">total</label>
                                            <input readOnly id="allow" type="number" className="form-control" value={totalnobooked ? totalnobooked : 0} onChange={(e) => setAllow(e.target.value)} />
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    
                                    <div className="col">
                                        <div className="mb-3">
                                            <label className="form-label fw-bold text-success" style={{fontWeight:700}}>Room Allotment</label>
                                            <input readOnly id="allow" type="number" className="form-control" value={allow ? allow : 0} onChange={(e) => setAllow(e.target.value)} />
                                        </div>
                                    </div>
                                </div>
                                {errors.allow && (
                                    <div className="alert alert-danger">
                                        {errors.allow}
                                    </div>
                                )}
                                
                                 
                                <div className="mb-3">
                                    <label className="form-label fw-bold">Min Stay</label>
                                    <input id="allow" type="number" className="form-control" value={night} onChange={(e) => setNight(e.target.value)} />
                                </div>

                                <div className="mb-3">
                                    <label className="form-label fw-bold">Status</label>
                                    {/* <p><input id="active" type="checkbox" defaultChecked={active} onChange={(e) => setActive(e.target.checked)} /> Available for booking?</p> */}
                                </div>
                                {/* {errors.price && (
                                    <div className="alert alert-danger">
                                        {errors.price}
                                    </div>
                                )} */}
                                <div className="d-flex justify-content-between">
                                    <div className="mb-3">
                                        {/* <label className="form-label fw-bold">No Checkin</label> */}
                                        <p><input id="active" type="checkbox" defaultChecked={nocheckin} onChange={(e) => setNoCheckin(e.target.checked)} /> no Check-in?</p>
                                    </div>
                                    {errors.price && (
                                        <div className="alert alert-danger">
                                            {errors.price}
                                        </div>
                                    )}

                                    <div className="mb-3">
                                        {/* <label className="form-label fw-bold">No Checkout</label> */}
                                        <p><input id="active" type="checkbox" defaultChecked={nocheckout} onChange={(e) => setNoCheckout(e.target.checked)} /> no Check-out?</p>
                                    </div>
                                    {errors.price && (
                                        <div className="alert alert-danger">
                                            {errors.price}
                                        </div>
                                    )}

                                </div>

                                <div className="mb-3">
                                    <button className="btn btn-primary" type="submit">
                                        <i className="fa fa-save mr-1"></i> Save
                                    </button>
                                    <a href="#" className="btn btn-secondary ml-2" onClick={handleCloseModal}>
                                        Close
                                    </a>
                                </div>
                            </form>
                        </div>
                    </Modal.Body>
                </Modal>
                                    </>
                                )}
                
            </Layout>
        </>
    )
}
