import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Extrabed({ extrabed }) {

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
console.log(extrabed,">>>>EXTRABED")
    return (
        <tbody>
            {extrabed.map((item, index) => (
                <>
                    <tr key={item.id+index+1}>
                        <td>{index+1}</td>
                        <td>{item.ratedesc}</td>
                        <td>{item.extrabedprice ? item.extrabedprice.priceextrabed : 0}</td>
                        <td>
                            <Link className='btn btn-datatable btn-icon btn-transparent-dark mr-2' href={`/bookinghistory/detail/${item.id}`} title='Details'>
                            <i className="fas fa-edit"></i>
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
