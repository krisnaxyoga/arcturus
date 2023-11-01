//import React
import React, { useState } from "react";

//import layout
import Layout from "../../../../Layouts/Vendor";

//import Link
import { Link, usePage } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";

export default function SurchargeAllRoom({ session, vendor, surchargeallroom }) {
    const { url } = usePage();
    const [startdate, setStartDate] = useState('');
    const [enddate, setEndDate] = useState('');
    const [surchargeprice, setSurcharge] = useState('');

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
      }

    const formatDate = (dateString) => {
        const parts = dateString.split('-'); // Memecah tanggal berdasarkan tanda "-"
        if (parts.length === 3) {
          const [year, month, day] = parts;
          return `${day}/${month}/${year}`; // Mengganti urutan tanggal
        }
        return dateString; // Kembalikan jika tidak dapat memproses tanggal
      };

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('start_date', startdate);
        formData.append('end_date', enddate);
        formData.append('surchargeprice', surchargeprice);
        Inertia.post('/room/surcharge/surchargeallroomstore', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
                window.location.reload();
            },
        });
    }

    return (
        <>
            <Layout page={`/room/surcharge/index`} vendor={vendor}>
                <div className="container">
                {session.success && (
                        <div className={`alert ${session.success === 'Data saved!' ? 'alert-danger' : 'alert-success'} border-0 shadow-sm rounded-3`}>
                        {session.success}
                    </div>
                )}
                    <h1>Surcharge Rate</h1>
                    <div className="row">
                        <div className="col-lg-6">
                            <div className="card mb-3">
                                <div className="card-body">
                                    <form onSubmit={storePost}>
                                        <div className="row">
                                            <div className="col-lg-6">
                                                <div className="mb-3 form-group">
                                                    <label htmlFor="">
                                                        Start Date
                                                    </label>
                                                    <input
                                                        type="date"
                                                        className="form-control"
                                                        onChange={(e) =>setStartDate(e.target.value)}
                                                    />
                                                </div>
                                            </div>
                                            <div className="col-lg-6">
                                                <div className="mb-3 form-group">
                                                    <label htmlFor="">
                                                        End Date
                                                    </label>
                                                    <input
                                                        type="date"
                                                        className="form-control"
                                                        onChange={(e) =>setEndDate(e.target.value)}
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col">
                                                <div className="mb-3 form-group">
                                                    <label htmlFor="">Surcharge</label>
                                                    <input type="number" className="form-control" onChange={(e) =>setSurcharge(e.target.value)}/>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row justify-content-between">
                                            <div className="col">
                                                <div className="mb-3">
                                                    <button className="btn btn-primary" type="submit">save</button>
                                                </div>
                                            </div>
                                            <div className="col">
                                                <div className="mb-3  d-flex justify-content-end">
                                                    <Link href="/room/surcharge/index" className="btn btn-danger ">Cancel</Link>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-lg-6">
                            <div className="card">
                                <div className="card-body">
                                <div className="table-responsive">
                                        <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>surcharge</th>
                                                    <th>start date</th>
                                                    <th>end date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {surchargeallroom.map((item, index) => (
                                                    <>
                                                        <tr key={index}>
                                                            <td>{index+1}</td>
                                                            <td>{formatRupiah(item.surcharge_price)}</td>
                                                            <td>{formatDate(item.stayperiod_start)}</td>
                                                            <td>{formatDate(item.stayperiod_end)}</td>
                                                            <td>
                                                                <Link className='btn btn-datatable btn-icon btn-transparent-dark mr-2' href={`/room/surcharge/surchargeallroomdestroy/${item.code}`}>
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
    );
}
