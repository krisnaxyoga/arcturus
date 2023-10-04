import React from 'react'
import { Link } from '@inertiajs/inertia-react';

export default function Bookings({ TransfFromAdmin }) {
    const formatDate = (dateString) => {
        const options = {
          year: 'numeric',
          month: 'numeric',
          day: 'numeric',
          hour: 'numeric',
          minute: 'numeric',
          second: 'numeric',
        };
        const formattedDate = new Date(dateString).toLocaleDateString('en-US', options);
        return formattedDate;
      };
    return (
        <tbody>
            {TransfFromAdmin.map((item, index) => (
                <>
                    <tr key={item.id+index+1}>
                    <td>{formatDate(item.created_at)}</td>
                        <td><a href={item.image} target="_blank" className='btn btn-success'><i className='fa fa-eye'></i></a> </td>
                       
                    </tr>
                </>
            ))}
        </tbody>
    )
}
