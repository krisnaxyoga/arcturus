//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../Layouts/Agent';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props, data }) {
    const { url } = usePage();

  return (
    <>
    <Layout agent={data} page={url}>
        <div className="container">
            <div className="row">
                <h1>Enquiry Report</h1>
                <div className="col-lg-12">
                    <div className="card">
                        <div className="card-body">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
    </>
  )
}