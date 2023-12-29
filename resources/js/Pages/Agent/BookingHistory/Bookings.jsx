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
        // Menyaring transports berdasarkan booking_id yang sama dengan id pada booking
        const filteredTransports = Array.isArray(transports) ? transports.filter(transport => transport.booking_id == booking.id) : [];

        // Menghitung total harga dari transports yang sesuai dengan booking
        const totalPrice = filteredTransports.reduce((acc, transport) => acc + transport.total_price, 0);

        // Menggabungkan item.price dari booking dengan total_price dari transports (jika ada)
        const totalBookingPrice = booking.price + totalPrice;

        return (
            <tr key={`booking_${index}`}>
                <td>{index + 1}</td>
                <td>{booking.vendor.vendor_name} {booking.id}</td>
                <td>{booking.booking_date}</td>
                <td>{booking.checkin_date}</td>
                <td>{booking.checkout_date}</td>
                <td>{booking.night}</td>
                <td>{formatRupiah(totalBookingPrice)}</td>
                <td>{booking.booking_status ? booking.booking_status : 'unpaid'}</td>
                <td>
                    <Link className='btn btn-outline-warning' href={`/agent/bookinghistory/detail/${booking.id}`} title='Details'>
                        <i className="fa fa-file"></i>
                    </Link>&nbsp;
                    {booking.booking_status === 'unpaid' && <a href={`/paymentbookingpage/${booking.id}`} title='Pay' className='btn btn-outline-danger'> <span style={{ fontSize: '12px', fontWeight: '700' }}>pay</span> </a>}
                </td>
            </tr>
        );
    })}
</tbody>

    )
}