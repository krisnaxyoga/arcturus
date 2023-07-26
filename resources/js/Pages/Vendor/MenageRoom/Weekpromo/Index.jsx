//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../../Layouts/Vendor';

//import Link
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props,session,data }) {
    return (
        <>
        <Layout>
            <div className="container">
                <div className="row">
                    <div className="col-lg-12">
                    <div className="card">
                    <div className="card-header">
                        <h2>Advance Purchese Promotion</h2>
                    </div>
                    <div className="card-body">
                        <Link href="/room/promotion/adv/create" className="btn btn-primary mb-2">add</Link>
                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                        <tr>
                                            <th>Adv code</th>
                                            <th>price promo</th>
                                            <th>min night</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((item,index)=>(
                                            <>
                                             <tr key={index}>
                                                <td>{item.code}</td>
                                                <td>{item.price_promo}</td>
                                                <td>{item.minight}</td>
                                                <td>
                                                    
                                                </td>
                                             </tr>
                                            </>
                                        ))}
                                        {/* @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $item->first_name }}</td>
                                            <td>{{ $item->last_name }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->address }}</td>
                                            <td><a href="{{route('dashboard.agent.edit',$item->id)}}" className="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i data-feather="edit"></i></a>
                                            
                                                <form className="d-inline" action="" method="POST" onSubmit="return confirm('Apakah anda yakin akan menghapus data ini?');">
                                                    @csrf
                                                    @method('delete')
        
                                                    <button type="submit" className="btn btn-datatable btn-icon btn-transparent-dark mr-2">
                                                        <i data-feather="trash-2"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        
                                        </tr>
                                        @endforeach */}
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