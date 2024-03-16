import React from 'react'
import { Link } from '@inertiajs/inertia-react';

import Modal from 'react-bootstrap/Modal';
export default function Extrabed({ extrabed }) {

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
console.log(extrabed,">>>>EXTRABED")
    return (
        <>
        <tbody>
            {extrabed.map((item, index) => (
                <>
                    <tr key={item.id+index+1}>
                        <td>{index+1}</td>
                        <td>{item.ratedesc}</td>
                        <td>{item.extrabedprice ? item.extrabedprice.priceextrabed : 0}</td>
                        <td>
                        <a href='#' className='btn btn-datatable btn-icon btn-transparent-dark mr-2' onClick={() => buttonSendValue(item)}>
                            <i className='fa fa-edit'></i>
                        </a>
                            {/* <Link className='btn btn-outline-success' href={`/agent/bookinghistory/invoice/${item.id}`} title='Invoice'>
                            <i className="fas fa-print"></i>
                            </Link> */}
                        </td>
                    </tr>
                </>
            ))}
        </tbody>
        <Modal show={show} onHide={handleClose}>
                <Modal.Header>
                    <Modal.Title>{modalData?.room?.ratecode}</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div className="container">
                        <form onSubmit={updatePost}>
                            <div className="mb-3">
                                <label htmlFor="" className='form-label'>Rate Price</label>
                                <input className='form-control' type="number" defaultValue={modalData?.price} onChange={(e) => setPrice(e.target.value)} />
                            </div>
                            <div className="mb-3">
                                <button className="btn btn-primary" type='submit'>
                                    <i className='fa fa-save'></i> save
                                </button>
                                <a href='#' className='mx-2 btn btn-secondary' onClick={handleClose}>
                                    Close
                                </a>
                            </div>
                        </form>
                    </div>
                </Modal.Body>
            </Modal>
        </>
        
    )
}
