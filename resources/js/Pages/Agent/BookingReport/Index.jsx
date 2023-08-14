//import React
import React, { useState } from 'react';

//import layout
import Layout from '../../../Layouts/Agent';
import Pagination from '../../../Components/Pagination';
import Bookings from '../BookingReport/Bookings';


//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props, session, data, agent }) {
    const { url } = usePage();

    const [currentPage, setCurrentPage] = useState(1)
    const [postsPerPage, setPostsPerPage] = useState(10)

    const indexOfLastPost = currentPage * postsPerPage;
    const indexOfFirstPost = indexOfLastPost - postsPerPage;
    const currentPosts = data.slice(indexOfFirstPost, indexOfLastPost);

    const paginate = pageNum => setCurrentPage(pageNum);

    const nextPage = () => setCurrentPage(currentPage + 1);

    const prevPage = () => setCurrentPage(currentPage - 1);

    return (
        <>
            <Layout agent={agent} page={url}>
                <div className="container">
                    <div className="row">
                        <div className="col-lg-12">
                            <div className="card">
                                <div className="card-header">
                                    <h2>Booking Report</h2>
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
                                                </tr>
                                            </thead>
                                            <Bookings bookings={currentPosts} />
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
