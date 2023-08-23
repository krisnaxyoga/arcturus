//import React
import React, { useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../../Layouts/Vendor';

//import Link
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function EditBlackout({ props, session, data }) {

    const [start, setStart] = useState('');
    const [end, setEnd] = useState('');

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('start', start ? start : data.start_date);
        formData.append('end', end ? end : data.end_date);
        Inertia.post(`/room/markup/updateblack/${data.id}`, formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }
    return (
        <>
            <Layout>
                <div className="container">
                    <div className="row">
                        <div className="col-lg-6">
                            <form onSubmit={storePost}>
                                <div className="card">
                                    <div className="card-header">
                                        <h1>Blackout Date</h1>
                                    </div>
                                    <div className="card-body">
                                        {session.success && (
                                            <div className="alert alert-success border-0 shadow-sm rounded-3">
                                                {session.success}
                                            </div>
                                        )}
                                        <div className="row">
                                            <div className="col-lg-12">
                                                <div className="mb-3">
                                                    <label htmlFor="">start date</label>
                                                    <input defaultValue={data.start_date} onChange={(e) => setStart(e.target.value)} type="date" className='form-control' />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="">end date</label>
                                                    <input defaultValue={data.end_date} onChange={(e) => setEnd(e.target.value)} type="date" className='form-control' />
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
                                                <Link href={`/room/contract/index`} className='btn btn-danger'>
                                                    <i className='fa-window-close'></i> Cancel
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