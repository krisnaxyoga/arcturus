import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Attributes({ attributes }) {

    return (
        <tbody>
            {attributes.map((item, index) => (
                <>
                    <tr key={index}>
                        <td>{item.id}</td>
                        <td>{item.name}</td>
                        <td>{item.description}</td>
                        <td>
                            <Link className='btn btn-warning' href={`/room/attribute/edit/${item.id}`}>
                                <i className='fa fa-edit'></i>
                            </Link>
                            <Link className='btn btn-danger mx-2' href={`/room/attribute/destroy/${item.id}`}>
                                <i className='fa fa-trash'></i>
                            </Link>
                        </td>
                    </tr>
                </>
            ))}
        </tbody>
    )
}
