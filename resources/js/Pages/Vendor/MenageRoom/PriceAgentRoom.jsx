//import React
import React,{ useState } from 'react';
import { useEffect } from "react";
//import layout
import Layout from '../../../Layouts/Vendor';

//import Link
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function PriceAgentRoom({ props,session,data }) {

    const [price, setPrice] = useState();
    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('price', price);
        Inertia.post('/room/agent/price', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
              },
        });
    }
    // if (data.length > 0) {
    //     const dprice = data[0].price;
    //     setPrice(dprice);
    //   }
  return (
    <>
    <Layout>
        <div className="container">
            <div className="row">
                <h1>Pricing set up</h1>
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
                                    <div class="mb-3">
                                            <label for="exampleFormControlInput1" className="form-label">Price</label>
                                            {data == "" ? (
                                                <>
                                                <input defaultValue={price} onChange={(e) => setPrice(e.target.value)}  type="number" className="form-control" id="exampleFormControlInput1" placeholder="Price Agent"/>

                                                </>
                                            ) : (
                                                <>
                                                    {data.map((item) => (
                                                        <input defaultValue={item.price} onChange={(e) => setPrice(e.target.value)}  type="number" className="form-control" id="exampleFormControlInput1" placeholder="Price Agent"/>
                                                    ))}
                                                </>
                                            )}

                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <button type='submit' className='btn btn-primary'>
                                <i className='fa fa-save'></i>
                                    save
                                </button>
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
