import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Bookings({ TransfFromAdmin }) {

    return (
        <tbody>
            {TransfFromAdmin.map((item, index) => (
                <>
                    <tr key={item.id+index+1}>
                        <td>{index+1}</td>
                        <td><Link href={item.image}>{item.image}</Link> </td>
                    </tr>
                </>
            ))}
        </tbody>
    )
}
