import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Bars({ bars }) {

    return (
        <tbody>
            {bars.map((item, index) => (
                <>
                    <tr key={index}>
                        <td>{item.barroom.barcode}</td>
                        <td>{item.barroom.bardesc}</td>
                        <td>{item.barroom.begindate}</td>
                        <td>{item.barroom.enddate}</td>
                        <td>{item.room.ratecode}</td>
                        <td>{item.room.ratedesc}</td>
                        <td>{item.price}</td>
                        <td>
                            <Link href={`/room/barcode/edit/${item.barroom.id}`} className='btn btn-warning'>
                                <i className="fa fa-edit"></i>
                            </Link>
                            <Link href={`/room/barcode/destroy/${item.barroom.id}`} className='btn btn-danger'>
                                <i className="fa fa-trash"></i>
                            </Link>
                        </td>
                    </tr>
                </>
            ))}
        </tbody>
    )
}
