//import React
import React, { useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../../Layouts/Agent';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function EditContact({ props, session, data, contact }) {

    const { url } = usePage();

    const [departement, setDepartement] = useState('');
    const [position, setPosition] = useState('');
    const [firstname, setFirstName] = useState('');
    const [lastname, setLastName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('departement', departement ? departement : contact.departement);
        formData.append('position', position ? position : contact.position);
        formData.append('firstname', firstname ? firstname : contact.first_name);
        formData.append('lastname', lastname ? lastname : contact.last_name);
        formData.append('email', email ? email : contact.email);
        formData.append('phone', phone ? phone : contact.mobile_phone);
        Inertia.post(`/agent-profile/contact/update/${contact.id}`, formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }
    // if (data.length > 0) {
    //     const dprice = data.price;
    //     setPrice(dprice);
    //   }
    return (
        <>
            <Layout page={url} agent={data}>
                <div className="container">
                        <h1>Edit Contact </h1>
                    <div className="row">
                        <div className="col-lg-6">
                            <form onSubmit={storePost}>
                                <div className="card">
                                    <div className="card-body">
                                        {session.success && (
                                            <div className="alert alert-success border-0 shadow-sm rounded-3">
                                                {session.success}
                                            </div>
                                        )}
                                        <div className="row">
                                            <div className="col-lg-12">
                                                <div className="mb-3">
                                                    <label htmlFor="departement">Departement</label>
                                                    <input defaultValue={contact.departement} onChange={(e) => setDepartement(e.target.value)} type="text" className='form-control' name='departement' />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="position">Position</label>
                                                    <input defaultValue={contact.position} onChange={(e) => setPosition(e.target.value)} type="text" className='form-control' name='position' />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="firstname">First Name</label>
                                                    <input defaultValue={contact.first_name} onChange={(e) => setFirstName(e.target.value)} type="text" className='form-control' name='firstname' />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="lastname">Last Name</label>
                                                    <input defaultValue={contact.last_name} onChange={(e) => setLastName(e.target.value)} type="text" className='form-control' name='lastname' />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="phone">Phone</label>
                                                    <input defaultValue={contact.mobile_phone} onChange={(e) => setPhone(e.target.value)} type="text" className='form-control' name='phone' />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="email">Email</label>
                                                    <input inputMode="email" defaultValue={contact.email} onChange={(e) => setEmail(e.target.value)} type="text" className='form-control' name='email' />
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div className="row justify-content-between"> {/* Use justify-content-between to move the buttons to both ends */}
                                            <div className="col-lg-auto">
                                                <button type="submit" className="btn btn-primary" onSubmit={storePost}>
                                                    <i className="fa fa-save"></i> Save
                                                </button>
                                            </div>
                                            <div className="col-lg-auto">
                                                <Link href={`/agent-profile`} className='btn btn-danger'>
                                                    Cancel
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    )
}
