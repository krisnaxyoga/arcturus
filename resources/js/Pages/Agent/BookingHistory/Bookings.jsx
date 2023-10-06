import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Bookings({ bookings }) {

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
    }

    console.log(bookings, ">>>>>>>>>>> DATA BOOKINGS >>>>>>>>>>>>>>>>>")
    return (
        <tbody>
            {bookings.map((item, index) => (
                <>
                    <tr key={index}>
                        <td>{index+1}</td>
                        <td>{item.vendor.vendor_name}</td>
                        <td>{item.booking_date}</td>
                        <td>{item.checkin_date}</td>
                        <td>{item.checkout_date}</td>
                        <td>{item.night}</td>
                        <td>{formatRupiah(item.price)}</td>
                        <td>{item.booking_status ? item.booking_status : 'unpaid'}</td>
                        <td>
                            <Link className='btn btn-outline-warning' href={`/agent/bookinghistory/detail/${item.id}`} title='Details'>
                                <i className="fas fa-info-circle"></i>
                            </Link>&nbsp;
                            {/* <Link className='btn btn-outline-success' href={`/agent/bookinghistory/invoice/${item.id}`} title='Invoice'>
                                <i className="fas fa-print"></i>
                            </Link> */}
                        </td>
                    </tr>
                </>
            ))}
        </tbody>
    )
}