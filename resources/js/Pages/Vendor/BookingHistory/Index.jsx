//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';
import Pagination from '../../../Components/Pagination';
import Bookings from '../BookingHistory/Bookings';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props,data }) {
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
    <Layout page={url}>
        <div className="container">
            <div className="row">
                <div className="col-lg-12">
                    <div className="card">
                        <div className="card-header">
                            <h1>Booking History</h1>
                        </div>
                        <div className="card-body">
                            <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Agent Name</th>
                                            <th>Booking Date</th>
                                            <th>Checkin Date</th>
                                            <th>Checkout Date</th>
                                            <th>Durations(nights)</th>
                                            <th>Total Room</th>
                                            <th>Total Guest</th>
                                            <th>Guest Name</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Action</th>
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