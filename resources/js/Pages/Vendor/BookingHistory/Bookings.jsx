import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Bookings({ bookings }) {

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
      }

    return (
        <tbody>
            {bookings.map((item, index) => (
                <>
                    <tr key={item.id+index+1}>
                        <td>{index+1}</td>
                        <td>{item.users.first_name} {item.users.last_name}</td>
                        <td>{item.booking_date}</td>
                        <td>{item.checkin_date}</td>
                        <td>{item.checkout_date}</td>
                        <td>{item.night}</td>
                        <td>{item.total_room}</td>
                        <td>{item.total_guests}</td>
                        <td>{item.first_name} {item.last_name}</td>
                        <td>{formatRupiah(item.pricenomarkup)}</td>
                        <td>{item.booking_status}</td>
                        <td>
                            <Link className='btn btn-outline-warning' href={`/bookinghistory/detail/${item.id}`} title='Details'>
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
