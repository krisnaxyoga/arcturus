import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Bookings({ bookings }) {

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
      }

      const formatDate = (dateString) => {
        const parts = dateString.split('-'); // Memecah tanggal berdasarkan tanda "-"
        if (parts.length === 3) {
          const [year, month, day] = parts;
          return `${day}/${month}/${year}`; // Mengganti urutan tanggal
        }
        return dateString; // Kembalikan jika tidak dapat memproses tanggal
      };

    return (
        <tbody>
            {bookings.map((item, index) => (
                <>
                    <tr key={index}>
                        <td>{index+1}</td>
                        <td>{item.users.first_name} {item.users.last_name}</td>
                        <td>{formatDate(item.booking_date)}</td>
                        <td>{formatDate(item.checkin_date)}</td>
                        <td>{formatDate(item.checkout_date)}</td>
                        {/* <td>{item.night} /night</td>
                        <td>{item.total_room} /room</td> */}
                        <td>{item.total_guests}</td>
                        <td>{item.first_name} {item.last_name}</td>
                        <td>{formatRupiah(item.pricenomarkup)}</td>
                        <td>{item.booking_status}</td>
                    </tr>
                </>
            ))}
        </tbody>
    )
}