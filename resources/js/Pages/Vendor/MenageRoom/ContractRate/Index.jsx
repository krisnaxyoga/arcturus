//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../../Layouts/Vendor';
import Pagination from '../../../../Components/Pagination';
import Rates from '../../../Vendor/MenageRoom/ContractRate/Rates';


//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props,session,data }) {
    const { url } = usePage();
    console.log(data,">>>DATA");

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
                        <h2>Contract Rate</h2>
                    </div>
                    <div className="card-body">
                    <Link href="/room/contract/create" className="btn btn-primary mb-2">add</Link>
                           <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                        <tr>
                                            <th>Rate code</th>
                                            <th>Rate description</th>
                                            <th>Begin Stay</th>
                                            <th>End Stay</th>
                                            <th>Begin Booking</th>
                                            <th>End Booking</th>
                                            <th>Min Stay</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <Rates rates={currentPosts} />
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
