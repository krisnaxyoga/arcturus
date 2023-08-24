//import React
import React, { useState, useEffect } from 'react';
//import layout
import Layout from '../../../../Layouts/Vendor';
import Pagination from '../../../../Components/Pagination';
import Attributes from '../../../Vendor/MenageRoom/Attribute/Attributes';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props, session, data,vendor }) {
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
            <Layout page={url} vendor={vendor}>
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
                                    <h2>Amenities</h2>
                                </div>
                                <div className="card-body">
                                    <Link href="/room/attribute/create" className="btn btn-primary mb-2">add</Link>
                                    <div className="table-responsive">
                                        <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Room Facility</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <Attributes attributes={currentPosts} />
                                         </table>
                                         <Pagination postsPerPage={postsPerPage} totalPosts={data.length} paginate={paginate} nextPage={nextPage} prevPage={prevPage} crntPage={currentPage}/>
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
