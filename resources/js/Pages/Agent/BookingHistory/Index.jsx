//import React
import React, { useState } from 'react';

//import layout
import Layout from '../../../Layouts/Agent';
import Pagination from '../../../Components/Pagination';
import Bookings from '../BookingHistory/Bookings';


//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props, session, data, agent, transport }) {
    const { url } = usePage();

    const [currentPage, setCurrentPage] = useState(1)
    const [postsPerPage, setPostsPerPage] = useState(10)

    const indexOfLastPost = currentPage * postsPerPage;
    const indexOfFirstPost = indexOfLastPost - postsPerPage;
    const currentPosts = data.slice(indexOfFirstPost, indexOfLastPost);

    const paginate = pageNum => setCurrentPage(pageNum);

    const nextPage = () => setCurrentPage(currentPage + 1);

    const prevPage = () => setCurrentPage(currentPage - 1);

    console.log(transport,">>>TRANSPORT")
    return (
        <>
            <Layout agent={agent} page={url}>
                <div className="container">
                    {session.success && (
                        <div className="alert alert-success border-0 shadow-sm rounded-3">
                            {session.success}
                        </div>
                    )}
                    <div className="row">
                        <div className="col-lg-12">
                            <div className="card">
                                <div className="card-header">
                                    <h2>Booking History</h2>
                                </div>
                                <div className="card-body">
                                    <div className="table-responsive">
                                        <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Hotel Name</th>
                                                    <th>Booking Date</th>
                                                    <th>Checkin Date</th>
                                                    <th>Checkout Date</th>
                                                    <th>Nights</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Confirmation letter</th>
                                                </tr>
                                            </thead>
                                            <Bookings bookings={currentPosts} transports={transport}/>
                                        </table>
                                        <Pagination postsPerPage={postsPerPage} totalPosts={data.length} paginate={paginate} nextPage={nextPage} prevPage={prevPage} crntPage={currentPage} />
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
