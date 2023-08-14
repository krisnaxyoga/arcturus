import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Bookings({ bookings }) {

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
      }

    console.log(bookings, ">>>>>>>>>>> DATA BOOKINGS >>>>>>>>>>>>>>>>>")
    return (
        <tbody>
            {bookings.map((item, index) => (
                <>
                    <tr key={index}>
                        <td>{index+1}</td>
                        <td>{item.users.first_name} {item.users.last_name}</td>
                        <td>{item.booking_date}</td>
                        <td>{item.checkin_date}</td>
                        <td>{item.checkout_date}</td>
                        {/* <td>{item.night} /night</td>
                        <td>{item.total_room} /room</td> */}
                        <td>{item.total_guests}</td>
                        <td>{item.first_name} {item.last_name}</td>
                        <td>{formatRupiah(item.price)}</td>
                        <td>{item.booking_status}</td>
                    </tr>
                </>
            ))}
        </tbody>
    )
}