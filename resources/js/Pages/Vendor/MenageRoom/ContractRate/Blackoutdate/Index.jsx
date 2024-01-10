import React, { useState }  from 'react'
//import React
import { useEffect } from "react";
//import layout
import Layout from '../../../../../Layouts/Vendor';

import { Link, usePage } from '@inertiajs/inertia-react';

import { Inertia } from "@inertiajs/inertia";

function Index({vendor,data,session,contractid}) {
    const { url } = usePage();

    const [startdate, setStartDate] = useState('');
    const [enddate, setEndDate] = useState('');
    const [code, setCode] = useState('');
    const [editData, setEditData] = useState(null); // Menyimpan data yang akan diedit

    const formatDate = (dateString) => {
        const parts = dateString.split('-'); // Memecah tanggal berdasarkan tanda "-"
        if (parts.length === 3) {
          const [year, month, day] = parts;
          return `${day}/${month}/${year}`; // Mengganti urutan tanggal
        }
        return dateString; // Kembalikan jika tidak dapat memproses tanggal
      };

      const handleEdit = (item) => {
        // Ketika tombol "Edit" diklik, isi editData dengan data yang akan diedit
        setEditData(item);
        setCode(item.code);
        setStartDate(item.stayperiod_start); // Isi nilai awal Start Date
        setEndDate(item.stayperiod_end); // Isi nilai awal End Date
        setSurcharge(item.surcharge_price); // Isi nilai awal Surcharge
      };

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('start_date', startdate);
        formData.append('end_date', enddate);
        formData.append('contractid', contractid);
        formData.append('code', code);
        Inertia.post('/room/contract/blackoutcontractstore', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
                window.location.reload();
            },
        });
    }

      const handleStartDateChange = (e) => {
        const newStartDate = e.target.value;
        setStartDate(newStartDate);
    
        // Jika Anda ingin mengatur nilai awal "End Date" berdasarkan "Start Date", Anda bisa melakukannya di sini
        // Contoh: Mengatur "End Date" berdasarkan "Start Date" + 1 hari
        const startDate = new Date(newStartDate);
        const nextDay = new Date(startDate);
        nextDay.setDate(startDate.getDate() + 1);
    
        const year = nextDay.getFullYear();
        let month = nextDay.getMonth() + 1;
        if (month < 10) {
          month = `0${month}`;
        }
        let day = nextDay.getDate();
        if (day < 10) {
          day = `0${day}`;
        }
    
        const formattedEndDate = `${year}-${month}-${day}`;

        // console.log(formattedEndDate);
        setEndDate(formattedEndDate);
      };

  return (
    <Layout page={`/room/contract/index`} vendor={vendor}>
         <div className="container">
                {session.success && (
                        <div className={`alert ${session.success === 'Data saved!' ? 'alert-danger' : 'alert-success'} border-0 shadow-sm rounded-3`}>
                        {session.success}
                    </div>
                )}
                    <h1>Blackout Date</h1>
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
                                                        defaultValue={startdate}
                                                        onChange={handleStartDateChange}
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
                                                        defaultValue={enddate}
                                                        onChange={(e) =>setEndDate(e.target.value)}
                                                    />
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
                                                    <Link href={`/room/contract/edit/${contractid}`} className="btn btn-danger ">Back</Link>
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
                                                    <th>start date</th>
                                                    <th>end date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {data.map((item, index) => (
                                                    <>
                                                        <tr key={index}>
                                                            <td>{index+1}</td>
                                                            <td>{formatDate(item.stayperiod_start)}</td>
                                                            <td>{formatDate(item.stayperiod_end)}</td>
                                                            <td>
                                                            <button className="btn btn-datatable btn-icon btn-transparent-dark mr-2" onClick={() => handleEdit(item)}>
                                                            <i className='fa fa-edit'></i>
                                                            </button>
                                                                <Link className='btn btn-datatable btn-icon btn-transparent-dark mr-2'
                                                                onClick={() => {
                                                                  if (window.confirm('Are you sure you want to delete this?')) {
                                                                    // Lanjutkan dengan menghapus jika pengguna menekan OK pada konfirmasi
                                                                    window.location.href = `/room/contract/blackoutdestroy/${item.code}`;
                                                                  }
                                                                }}>
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
  )
}

export default Index