//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props,data,session }) {
    const { url } = usePage();
    
   

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
                </div>
            </div>
        </div>
    </Layout>
    </>
  )
}