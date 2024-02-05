//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Pagination from '../../../Components/Pagination';
import Extrabed from '../ExtraBedPrice/Extrabed';

export default function Index({ props,data,vendor,room }) {
    const { url } = usePage();
    const [currentPage, setCurrentPage] = useState(1)
    const [postsPerPage, setPostsPerPage] = useState(10)

    const indexOfLastPost = currentPage * postsPerPage;
    const indexOfFirstPost = indexOfLastPost - postsPerPage;
    const currentPosts = room.slice(indexOfFirstPost, indexOfLastPost);

    const paginate = pageNum => setCurrentPage(pageNum);

    const nextPage = () => setCurrentPage(currentPage + 1);

    const prevPage = () => setCurrentPage(currentPage - 1);

  return (
    <>
    <Layout page={url} vendor={vendor}>
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
                            <Extrabed extrabed={currentPosts} />
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
