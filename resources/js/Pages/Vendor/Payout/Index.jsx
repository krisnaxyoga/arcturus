//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props,data,session }) {
    const { url } = usePage();
    
    const [bankname, setBankName] = useState('');
    const [bankaccount, setBankAccount] = useState('');
    const [swifcode, setSwifCode] = useState('');

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('bank', bankname ? bankname : data.bank_name);
        formData.append('bankaccount', bankaccount ? bankaccount : data.bank_account);
        formData.append('swifcode', swifcode ? swifcode : data.swif_code);

        Inertia.post('/payouts/store', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    }

  return (
    <>
    <Layout page={url}>
        <div className="container">
            <div className="row">
                <h1>Payouts</h1>
                <div className="col-lg-12">
                {session.success && (
                                <div className="alert alert-success border-0 shadow-sm rounded-3">
                                    {session.success}
                                </div>
                            )}
                    <div className="card">
                        <div className="card-body">
                            <form onSubmit={storePost}>
                                <div className="row">
                                    <div className="col-lg-4">
                                        <div className="mb-3">
                                            <label htmlFor="">Bank Name</label>
                                            <input defaultValue={data.bank_name} type="text" className='form-control' onChange={(e) => setBankName(e.target.value)}/>
                                        </div>
                                    </div>
                                    <div className="col-lg-4">
                                        <div className="mb-3">
                                            <label htmlFor="">Bank account</label>
                                            <input defaultValue={data.bank_account} type="text" className='form-control' onChange={(e) => setBankAccount(e.target.value)}/>
                                        </div>
                                    </div>
                                    <div className="col-lg-4">
                                        <div className="mb-3">
                                            <label htmlFor="">Swif code</label>
                                            <input defaultValue={data.swif_code} type="text" className='form-control' onChange={(e) => setSwifCode(e.target.value)}/>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-lg-4">
                                        <div className="mb-3">
                                            <button className='btn btn-primary' type='submit'> <i className='fa fa-save'></i> save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
    </>
  )
}