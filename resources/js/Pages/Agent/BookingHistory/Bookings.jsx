import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Bookings({ bookings, transports }) {

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
    }

    console.log(bookings, ">>>>>>>>>>> DATA BOOKINGS >>>>>>>>>>>>>>>>>")
    return (
        <tbody>
{bookings.map((booking, index) => {
    const transportsForBooking = transports || []; // Inisialisasi jika transports bernilai undefined
    const totalPrice = (transportsForBooking.find(trans => trans.booking_id == booking.id) || {}).total_price || 0;
    const finalPrice = parseInt(booking.price) + parseInt(totalPrice);;

    return (
        <tr key={`booking_${index}`}>
            <td>{index + 1}</td>
            <td>{booking.vendor.vendor_name} {booking.id}</td>
            <td>{booking.booking_date}</td>
            <td>{booking.checkin_date}</td>
            <td>{booking.checkout_date}</td>
            <td>{booking.night}</td>
            <td>
                <span key={`price_${index}`}>
                    {formatRupiah(finalPrice)}
                </span>
            </td>
            <td>{booking.booking_status ? booking.booking_status : 'unpaid'}</td>
            <td>
                <Link className='btn btn-outline-warning' href={`/agent/bookinghistory/detail/${booking.id}`} title='Details'>
                    <i className="fa fa-file"></i>
                </Link>&nbsp;
                {booking.booking_status === 'unpaid' && (
                    <a href={`/paymentbookingpage/${booking.id}`} title='Pay' className='btn btn-outline-danger'>
                        <span style={{ fontSize: '12px', fontWeight: '700' }}>pay</span>
                    </a>
                )}
            </td>
        </tr>
    );
})}

</tbody>

    )
}
