import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Attributes({ rooms }) {
    return (
        <tbody>
            {rooms.map((item, index) => (
                <>
                    <tr key={index}>
                        <td>{item.id}</td>
                        <td>{item.ratecode}</td>
                        <td>{item.ratedesc}</td>
                        <td>{item.room_allow}</td>
                        <td>
                            <ul>
                                {Array.isArray(item.attribute) ? (
                                    item.attribute.map((attr, attrIndex) => (
                                        <li key={attrIndex}>{attr}</li>
                                    ))
                                ) : (
                                    <li>{item.attribute}</li>
                                )}
                            </ul>
                        </td>
                        <td>{item.adults}</td>
    
                        <td>
                            <Link className='btn btn-warning' href={`/room/edit/${item.id}`}>
                                <i className='fa fa-edit'></i>
                            </Link>
                            <Link className='btn btn-danger mx-2' href={`/room/destroy/${item.id}`}>
                                <i className='fa fa-trash'></i>
                            </Link>
                        </td>
                    </tr>
                </>
            ))}
        </tbody>
    )
}
