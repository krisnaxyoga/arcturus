//import React
import React, {useCallback, useState} from 'react';

//import layout
import Layout from '../../../../Layouts/Vendor';

//import Link
import { usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Modal from 'react-bootstrap/Modal';
import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import axios from 'axios';
import {Alert} from "react-bootstrap";

export default function Index({ errors, session, default_selected_hotel_room, hotel_rooms, vendor }) {
    const { url } = usePage();

    const [loadDates, setLoadDates] = useState([])
    const [showModel, setShowModal] = useState(false)
    const [activeHotelRoom, setActiveHotelRoom] = useState(default_selected_hotel_room.id)
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
    const [price, setPrice] = useState(0)
    const [active, setActive] = useState(true)

    const handleDatesRender = (arg) => {
        setActiveStart(arg.start)
        setActiveEnd(arg.end)
    };

    const handleOpenModal = (arg) => {
        setDate(arg.event.start.toLocaleDateString('en-GB'))
        setStartDate(arg.event.startStr)
        setPrice(arg.event.extendedProps.price)
        setShowModal(true)
    }

    const handleCloseModal = () => {
        setDate('')
        setStartDate('')
        setPrice(0)
        setShowModal(false)
    }

    const handleNavRoomTypeSelect = useCallback(async (hotel_room_id) => {
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

    const handleStore = async (e) => {
        e.preventDefault()

        Inertia.post('/room/surcharge/store', {
            vendor_id: vendor.id,
            room_hotel_id: activeHotelRoom,
            start_date: startDate,
            end_date: startDate,
            price: price,
            active: active
        }, {
            onSuccess: () => {
                handleNavRoomTypeSelect(activeHotelRoom)
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

    return (
        <>
            <Layout page={url} vendor={vendor}>
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
                        <div className="panel-title"><strong>Surcharge</strong></div>
                        <div className="panel-body no-padding" style={{ background: "#f4f6f8", padding: "0px 15px" }}>
                            <div className="row">
                                <div className="col-md-3" style={{ borderRight: "1px solid #dee2e6" }}>
                                    <ul className="nav nav-tabs flex-column vertical-nav">
                                        {hotel_rooms.length > 0 && (
                                            <>
                                                {hotel_rooms.map((item) =>
                                                    (
                                                        <li key={item.id} className="nav-item event-name" onClick={() => handleNavRoomTypeSelect(item.id)}>
                                                            <a className={`nav-link ${activeHotelRoom === item.id ? "active" : ""}`}>
                                                                {item.room.ratedesc}
                                                            </a>
                                                        </li>
                                                    )
                                                )}
                                            </>
                                        )}
                                    </ul>
                                </div>
                                <div className="col-md-9" style={{ background: "white", padding: "15px"}}>
                                    <FullCalendar
                                        plugins={[ dayGridPlugin ]}
                                        initialView="dayGridMonth"
                                        datesSet={handleDatesRender}
                                        events={loadDates}
                                        eventClick={handleOpenModal}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <Modal show={showModel} onHide={handleCloseModal}>
                    <Modal.Header>
                        <Modal.Title>
                            Surcharge Hotel Room
                        </Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="container">
                            <form onSubmit={handleStore}>

                                <div className="mb-3">
                                    <label className="form-label fw-bold">Date</label>
                                    <input type="text" className="form-control" value={date} readOnly={true} />
                                </div>

                                <div className="mb-3">
                                    <label className="form-label fw-bold">Surcharge</label>
                                    <input id="price" type="number" className="form-control" value={price} onChange={(e) => setPrice(e.target.value)} />
                                </div>
                                {errors.price && (
                                    <div className="alert alert-danger">
                                        {errors.price}
                                    </div>
                                )}

                                <div className="mb-3">
                                    <label className="form-label fw-bold">Status</label>
                                    <p><input id="active" type="checkbox" defaultChecked={active} onChange={(e) => setActive(e.target.checked)} /> Available for booking?</p>
                                </div>
                                {errors.price && (
                                    <div className="alert alert-danger">
                                        {errors.price}
                                    </div>
                                )}

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
            </Layout>
        </>
    )
}
