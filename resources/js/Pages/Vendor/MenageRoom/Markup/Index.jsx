//import React
import React, { useState } from 'react';

//import layout
import Layout from '../../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';


export default function Index({ black, session, data, surcharge }) {
    const { url } = usePage();
    const [showConfirmation, setShowConfirmation] = useState(false);

    const handleDelete = () => {
        // Logika penghapusan data
        // ...

        // Tutup dialog konfirmasi setelah penghapusan selesai
        setShowConfirmation(false);
    };

    const handleCancel = () => {
        // Batalkan penghapusan dan tutup dialog konfirmasi
        setShowConfirmation(false);
    };



    return (
        <>
            <Layout page={url}>
                <div className="container">
                    {(!data || Object.keys(data).length === 0) ? (
                        <>
                            <Link href="/room/markup/create" className="btn btn-primary mb-2"> <i className="fa fa-plus"></i> add pricing setup</Link>
                        </>
                    ) : (
                        <>
                        </>
                    )}

                    <div className="row">
                        <div className="col-lg-12 mb-3">
                            <div className="card">
                                <div className="card-header">
                                    <h2>Mark-Up</h2>
                                </div>
                                {showConfirmation && (
                                    <ConfirmationDialog
                                        message="Anda yakin ingin menghapus data?"
                                        onConfirm={handleDelete}
                                        onCancel={handleCancel}
                                    />
                                )}
                                <div className="card-body">
                                    <div className="table-responsive">
                                        <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Min MarkUp</th>
                                                    <th>Service percentace include</th>
                                                    <th>tax</th>
                                                    <th>action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {data.map((item, index) => (
                                                    <>
                                                        <tr key={index}>
                                                            <td>{item.markup_price}</td>
                                                            <td>{item.service}%</td>
                                                            <td>{item.tax}%</td>
                                                            <td>
                                                                <Link href={`/room/markup/edit/${item.id}`} className='btn btn-warning'>
                                                                    <i className='fa fa-edit'></i>
                                                                </Link>
                                                            </td>
                                                        </tr>
                                                    </>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-6">
                            <div className="card">
                                <div className="card-header">
                                    <div className="d-flex justify-content-between">
                                        <h2>Blackout Dates</h2>
                                        <div>
                                            <Link href='/room/markup/addblack' className='btn btn-primary'> <i className='fa fa-plus'></i> add</Link>
                                        </div>
                                    </div>
                                </div>
                                <div className="card-body">
                                    <div className="table-responsive">
                                        <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                            <thead>
                                                <tr>
                                                    <th>start date</th>
                                                    <th>End date</th>
                                                    <th>action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {black.map((item, index) => (
                                                    <>
                                                        <tr key={index}>
                                                            <td>{item.start_date}</td>
                                                            <td>{item.end_date}</td>
                                                            <td>
                                                                <Link href={`/room/markup/editblack/${item.id}`} className='btn btn-warning mx-2'>
                                                                    <i className='fa fa-edit'></i>
                                                                </Link>
                                                                <Link href={`/room/markup/destroyblack/${item.id}`} className='btn btn-danger'>
                                                                    <i className='fa fa-trash'></i>
                                                                </Link>
                                                            </td>
                                                        </tr>
                                                    </>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-6">
                            <div className="card">
                                <div className="card-header">
                                    <div className="d-flex justify-content-between">
                                        <h2>Surcharge Dates</h2>
                                        <div>
                                            <Link href='/room/markup/addsurchage' className='btn btn-primary'> <i className='fa fa-plus'></i> add</Link>
                                        </div>
                                    </div>
                                </div>
                                <div className="card-body">
                                    <div className="table-responsive">
                                        <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                            <thead>
                                                <tr>
                                                    <th>price</th>
                                                    <th>start date</th>
                                                    <th>End date</th>
                                                    <th>action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {surcharge.map((item, index) => (
                                                    <>
                                                        <tr key={index}>
                                                            <td>{item.surcharge_black_price}</td>
                                                            <td>{item.start_date}</td>
                                                            <td>{item.end_date}</td>
                                                            <td>
                                                                <Link href={`/room/markup/editsurchage/${item.id}`} className='btn btn-warning mx-2'>
                                                                    <i className='fa fa-edit'></i>
                                                                </Link>
                                                                <Link href={`/room/markup/destroysurchage/${item.id}`} className='btn btn-danger'>
                                                                    <i className='fa fa-trash'></i>
                                                                </Link>
                                                            </td>
                                                        </tr>
                                                    </>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    )
}
