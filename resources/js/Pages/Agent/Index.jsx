//import React
import React, { useState,useEffect } from 'react';

//import layout
import Layout from '../../Layouts/Agent';

import Pagination from '../../Components/Pagination';
import Bookings from '../Agent/BookingHistory/Bookings';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import Carousel from 'react-bootstrap/Carousel';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ totalroom, data,booking,success,pending,getbooking,transport }) {
  const { url } = usePage();

  function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
}
  const [currentPage, setCurrentPage] = useState(1)
  const [postsPerPage, setPostsPerPage] = useState(10)

  const indexOfLastPost = currentPage * postsPerPage;
  const indexOfFirstPost = indexOfLastPost - postsPerPage;
  const currentPosts = getbooking.slice(indexOfFirstPost, indexOfLastPost);

  const paginate = pageNum => setCurrentPage(pageNum);

  const nextPage = () => setCurrentPage(currentPage + 1);

  const prevPage = () => setCurrentPage(currentPage - 1);


  return (
    <>
      <Layout page={url} agent={data}>
        {/* <!-- Content Wrapper --> */}
        <div id="content-wrapper" className="d-flex flex-column">
          {/* <!-- Main Content --> */}
          <div id="content">
            {/* <!-- Begin Page Content --> */}
            <div className="container-fluid">
              {/* <!-- Page Heading --> */}
              <div className="d-sm-flex align-items-center justify-content-center mb-4">
                <h1 className="h3 mb-0 text-gray-800">Agent Dashboard</h1>
              </div>

              {/* <!-- Content Row --> */}
              <div className="row">
                {/* <!-- Earnings (Monthly) Card Example --> */}
                <div className="col-xl-3 col-md-6 mb-4">
                  <div className="card border-left-primary shadow h-100 py-2">
                    <div className="card-body">
                      <div className="row no-gutters align-items-center">
                        <div className="col mr-2">
                          <div className="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total revenue</div>
                          <div className="h5 mb-0 font-weight-bold text-gray-800">{formatRupiah(booking)}</div>
                        </div>
                        <div className="col-auto">
                          <i className="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                {/* <!-- Earnings (Monthly) Card Example --> */}
                <div className="col-xl-3 col-md-6 mb-4">
                  <div className="card border-left-success shadow h-100 py-2">
                    <div className="card-body">
                      <div className="row no-gutters align-items-center">
                        <div className="col mr-2">
                          <div className="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Booking paid</div>
                          <div className="h5 mb-0 font-weight-bold text-gray-800">{success}</div>
                        </div>
                        <div className="col-auto">
                          <i className="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                {/* <!-- Earnings (Monthly) Card Example --> */}


                {/* <!-- Pending Requests Card Example --> */}
                <div className="col-xl-3 col-md-6 mb-4">
                  <div className="card border-left-warning shadow h-100 py-2">
                    <div className="card-body">
                      <div className="row no-gutters align-items-center">
                        <div className="col mr-2">
                          <div className="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Booking unpaid</div>
                          <div className="h5 mb-0 font-weight-bold text-gray-800">{pending}</div>
                        </div>
                        <div className="col-auto">
                          <i className="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="col-xl-3 col-md-6 mb-4">
                  <div className="card border-left-secondary shadow h-100 py-2">
                    <div className="card-body">
                      <div className="row no-gutters align-items-center">
                        <div className="col mr-2">
                          <div className="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Total Room Night</div>
                          <div className="h5 mb-0 font-weight-bold text-gray-800">{totalroom}</div>
                        </div>
                        <div className="col-auto">
                          <i className="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div className="row">
                <div className="col-lg-12">
                  <div className="card">
                    <div className="card-header">
                    <h2>Today's Booking</h2>
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
              {/* <!-- Content Row --> */}

            </div>
            {/* <!-- End of Begin Page Content --> */}
          </div>
          {/* <!-- End of Main Content --> */}
        </div>
        {/* <!-- End of Content Wrapper --> */}

      </Layout>
    </>
  )
}
