//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Pagination from '../../../Components/Pagination';
import Bookings from '../BookingHistory/Bookings';

export default function Index({ props }) {
    const { url } = usePage();

  return (
    <>
    <Layout page={url}>
        <div className="container">
            <div className="card">
                <div className="card-body">
                    <div className="table-responsive">
                        <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Room type</th>
                                    <th>Extrabed Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            {/* <Bookings bookings={currentPosts} /> */}
                        </table>
                        <Pagination postsPerPage={postsPerPage} totalPosts={data.length} paginate={paginate} nextPage={nextPage} prevPage={prevPage} crntPage={currentPage} />
                    </div>
                </div>
            </div>
        </div>
    </Layout>
    </>
  )
}
