import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Rates({ rates }) {

    return (
        <tbody>
            {rates.map((item) => (
                <>
                    <tr key={item.id}>
                        <td>{item.ratecode}</td>
                        <td>{item.codedesc}</td>
                        <td>{item.stayperiod_begin}</td>
                        <td>{item.stayperiod_end}</td>
                        <td>{item.booking_begin}</td>
                        <td>{item.booking_end}</td>
                        <td>{item.min_stay}</td>
                        <td>{item.distribute.join(', ')}</td>
                        <td>
                            <Link href={`/room/contract/edit/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
                                <i className="fa fa-edit"></i>
                            </Link>
                            <Link href={`/room/contract/destroy/${item.id}`} className='btn btn-datatable btn-icon btn-transparent-dark mr-2'>
                                <i className="fa fa-trash"></i>
                            </Link>
                        </td>
                    </tr>
                </>
            ))}
        </tbody>
    )
}
