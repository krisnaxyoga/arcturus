//import React
import React,{ useState } from 'react';

//import layout
import Layout from '../../../../Layouts/Vendor';
import Pagination from '../../../../Components/Pagination';
import Bars from '../../../Vendor/MenageRoom/BarRoom/Bars';

//import Link
import { Link , usePage} from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props,session,data }) {
    const { url } = usePage();
    console.log(data,">>>DATa");

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
                        <h2>Bar Information</h2>
                    </div>
                    <div className="card-body">
                    <Link href="/room/barcode/create" className="btn btn-primary mb-2">add</Link>
                           <div className="table-responsive">
                                <table className="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                        <tr>
                                            <th>Bar code</th>
                                            <th>Bar code description</th>
                                            <th>Begin sell date</th>
                                            <th>End sell date</th>
                                            <th>room code</th>
                                            <th>room type</th>
                                            <th>price</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <Bars bars={currentPosts} />
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
