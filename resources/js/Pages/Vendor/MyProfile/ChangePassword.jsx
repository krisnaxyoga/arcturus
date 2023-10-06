//import React
import React, { useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function EditPassword({ props, session, data }) {

    const { url } = usePage();

    const [password, setPassword] = useState('');

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('password', password);
        Inertia.post('/vendor-profile/updatepassword', formData, {
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
            <Layout page={url} vendor={data}>
                <div className="container">
                        <h1>New Password</h1>
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
                                                    <label htmlFor="password">New Password</label>
                                                    <input onChange={(e) => setPassword(e.target.value)} type="password" className='form-control' name='new password...' />
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
                                                <Link href={`/agentdashboard`} className='btn btn-danger'>
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