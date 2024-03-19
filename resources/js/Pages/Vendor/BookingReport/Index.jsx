//import React
import React,{ useState,useEffect } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';
import Pagination from '../../../Components/Pagination';
import Bookings from '../BookingReport/Bookings';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props,data,vendor }) {
    const { url } = usePage();
    const [filteredBookings, setFilteredBookings] = useState(data);
    const [currentPage, setCurrentPage] = useState(1)
    const [postsPerPage, setPostsPerPage] = useState(10)
    const [selectedStatus, setSelectedStatus] = useState('all'); // Status default untuk menampilkan semua
    const [startDate, setStartDate] = useState('');
    const [endDate, setEndDate] = useState('');

    const indexOfLastPost = currentPage * postsPerPage;
    const indexOfFirstPost = indexOfLastPost - postsPerPage;
    const currentPosts = filteredBookings.slice(indexOfFirstPost, indexOfLastPost);

    const paginate = pageNum => setCurrentPage(pageNum);

    const nextPage = () => setCurrentPage(currentPage + 1);

    const prevPage = () => setCurrentPage(currentPage - 1);

    useEffect(() => {
        // Filter bookings berdasarkan status pembayaran
        let filtered = data;
        if (selectedStatus !== 'all') {
            filtered = filtered.filter(booking => booking.booking_status === selectedStatus);
        }

        if (startDate && endDate) {
            filtered = filtered.filter(booking => new Date(booking.booking_date) >= new Date(startDate) && new Date(booking.booking_date) <= new Date(endDate));
        }
        
        // Urutkan berdasarkan tanggal pembuatan, yang terbaru dulu
        const sortedBookings = filtered.sort((a, b) => new Date(b.booking_date) - new Date(a.booking_date));

        setFilteredBookings(sortedBookings);
    }, [data, selectedStatus, startDate, endDate]);


     // Handle perubahan pada select status pembayaran
     const handleStatusChange = event => {
        console.log(event.target.value);
        setSelectedStatus(event.target.value);
    };
        // Handle perubahan pada input tanggal
        const handleStartDateChange = event => {
            setStartDate(event.target.value);
        };

        const handleEndDateChange = event => {
            setEndDate(event.target.value);
        };
     // Handle filter tanggal end-date jika tidak dipilih
     const handleEndDateBlur = () => {
        if (!endDate && startDate) {
            const nextDay = new Date(new Date(startDate).getTime() + 86400000); // Menambahkan 1 hari ke start-date
            setEndDate(nextDay.toISOString().split('T')[0]);
        }
    };
  return (
    <>
    <Layout page={url} vendor={vendor}>
        <div className="container">
            <div className="row">
                <div className="col-lg-12">
                    <div className="card">
                        <div className="card-header">
                            <h1 className='text-center'>Booking Report</h1>
                            <div className="row justify-content-center">
                                <div className="col-lg-3">
                                     {/* Select untuk memilih status pembayaran */}
                                    <select className='form-control' value={selectedStatus} onChange={handleStatusChange}>
                                        <option value="all">All</option>
                                        <option value="paid">Paid</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div className="col-lg-3">
                                    <input type="date" className='form-control' value={startDate} onChange={handleStartDateChange} />
                                </div>
                                <div className="col-lg-3">
                                    <input type="date" className='form-control' value={endDate} onChange={handleEndDateChange} onBlur={handleEndDateBlur} />
                    
                                </div>
                            </div>
                           
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
                                            <th>Nights</th>
                                            <th>Total Room</th>
                                            <th>Total Guest</th>
                                            <th>Guest Name</th>
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
