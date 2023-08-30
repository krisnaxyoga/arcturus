//import React
import React, { useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../../Layouts/Vendor';

//import Link
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Edit({ props, session, data,vendor }) {
    const { url } = '/room/attribute';
    const [name, setName] = useState('');
    const [desc, setDesc] = useState('');

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('name', name ? name : data.name);
        formData.append('description', desc ? desc : data.description);
        Inertia.post(`/room/attribute/update/${data.id}`, formData, {
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
            <Layout page={'/room/attribute'} vendor={vendor}>
                <div className="container">
                        <h1>Attributes </h1>
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
                                                    <label htmlFor="">name</label>
                                                    <input defaultValue={data.name} onChange={(e) => setName(e.target.value)} type="text" className='form-control' />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="">Description</label>
                                                    <textarea defaultValue={data.description} onChange={(e) => setDesc(e.target.value)} className='form-control' name="" id="" cols="30" rows="10"></textarea>
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
                                                <button onClick={() => history.back()} className="btn btn-danger">
                                                    Cancel
                                                </button>
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