//import React
import React,{ useState,useEffect } from 'react';

//import layout
import Layout from '../../../Layouts/Vendor';
import Pagination from '../../../Components/Pagination';
import Bookings from '../BookingReport/Bookings';

import ExcelJS from 'exceljs';
import { saveAs } from 'file-saver';
//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

export default function Index({ props,data,vendor }) {
    const { url } = usePage();
    const [filteredBookings, setFilteredBookings] = useState(data);
    const [currentPage, setCurrentPage] = useState(1)
    const [postsPerPage, setPostsPerPage] = useState(10)
    const [selectedStatus, setSelectedStatus] = useState('all'); // Status default untuk menampilkan semua
    const [startDate, setStartDate] = useState('');
    const [endDate, setEndDate] = useState('');
    const [startDate2, setStartDate2] = useState('');
    const [endDate2, setEndDate2] = useState('');

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
            filtered = filtered.filter(booking => new Date(booking.checkout_date) >= new Date(startDate) && new Date(booking.checkout_date) <= new Date(endDate));
            // filtered = filtered.filter(booking => new Date(booking.booking_date) >= new Date(startDate) && new Date(booking.booking_date) <= new Date(endDate));
        }

        if (startDate2 && endDate2) {
            // filtered = filtered.filter(booking => new Date(booking.checkout_date) >= new Date(startDate) && new Date(booking.checkout_date) <= new Date(endDate));
            filtered = filtered.filter(booking => new Date(booking.booking_date) >= new Date(startDate2) && new Date(booking.booking_date) <= new Date(endDate2));
        }
        
        // Urutkan berdasarkan tanggal pembuatan, yang terbaru dulu
        const sortedBookings = filtered.sort((a, b) => new Date(b.booking_date) - new Date(a.booking_date));

        setFilteredBookings(sortedBookings);
    }, [data, selectedStatus, startDate2, endDate2, startDate, endDate]);

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
      }

      const formatDate = (dateString) => {
        const parts = dateString.split('-'); // Memecah tanggal berdasarkan tanda "-"
        if (parts.length === 3) {
          const [year, month, day] = parts;
          return `${day}/${month}/${year}`; // Mengganti urutan tanggal
        }
        return dateString; // Kembalikan jika tidak dapat memproses tanggal
      };

     // Handle perubahan pada select status pembayaran
     const handleStatusChange = event => {
        console.log(event.target.value);
        setSelectedStatus(event.target.value);
    };
        // Handle perubahan pada input tanggal
        const handleStartDateChange = event => {
            setStartDate(event.target.value);
        };

          // Handle perubahan pada input tanggal
          const handleStartDateChange2 = event => {
            setStartDate2(event.target.value);
        };

        const handleEndDateChange = event => {
            setEndDate(event.target.value);
        };

        const handleEndDateChange2 = event => {
            setEndDate2(event.target.value);
        };
     // Handle filter tanggal end-date jika tidak dipilih
     const handleEndDateBlur = () => {
        if (!endDate && startDate) {
            const nextDay = new Date(new Date(startDate).getTime() + 86400000); // Menambahkan 1 hari ke start-date
            setEndDate(nextDay.toISOString().split('T')[0]);
        }
    };

    const handleEndDateBlur2 = () => {
        if (!endDate2 && startDate2) {
            const nextDay = new Date(new Date(startDate2).getTime() + 86400000); // Menambahkan 1 hari ke start-date
            setEndDate2(nextDay.toISOString().split('T')[0]);
        }
    };

   
    const handleExportToExcel = () => {
        // Membuat workbook baru
        const workbook = new ExcelJS.Workbook();
    
        // Menambahkan worksheet baru
        const worksheet = workbook.addWorksheet('Booking Report');

    
         // Menambahkan header
        worksheet.addRow(['No', 'Agent Name', 'Booking Date', 'Checkin Date', 'Checkout Date', 'Nights', 'Total Room', 'Total Guest', 'Guest Name', 'Total', 'Status']);

        // Menambahkan data
        filteredBookings.forEach((item, index) => {
            worksheet.addRow([
                index + 1,
                `${item.users.first_name} ${item.users.last_name}`,
                formatDate(item.booking_date),
                formatDate(item.checkin_date),
                formatDate(item.checkout_date),
                item.night,
                item.total_room,
                item.total_guests,
                `${item.first_name} ${item.last_name}`,
                formatRupiah(item.pricenomarkup),
                item.booking_status
            ]);
        });
        
        // Menulis workbook ke buffer Excel
        workbook.xlsx.writeBuffer().then(buffer => {
          // Mengonversi buffer ke objek blob
          const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
          // Menyimpan blob sebagai file Excel
          saveAs(blob, 'booking_report.xlsx');
        });
      };
      
      const handleExportToPDF = () => {
        const doc = new jsPDF();
        doc.text("Booking Report", 10, 10);
    
        // Menambahkan data dalam tabel
        let yPos = 20; // Posisi awal Y
        const headers = ["No", "Agent Name", "Booking Date", "Checkin Date", "Checkout Date", "Nights", "Total Room", "Total Guest", "Guest Name", "Total", "Status"];
        const tableData = filteredBookings.map((item, index) => [
            index + 1,
            `${item.users.first_name} ${item.users.last_name}`,
            formatDate(item.booking_date),
            formatDate(item.checkin_date),
            formatDate(item.checkout_date),
            item.night,
            item.total_room,
            item.total_guests,
            `${item.first_name} ${item.last_name}`,
            formatRupiah(item.pricenomarkup),
            item.booking_status
        ]);
    
        doc.autoTable({
            startY: yPos,
            head: [headers],
            body: tableData,
        });
    
        // Simpan sebagai file PDF
        doc.save("booking_report.pdf");
    };
    
  return (
    <>
    <Layout page={url} vendor={vendor}>
        <div className="container">
            <div className="row">
                <div className="col-lg-12">
                    <div className="card mb-2">
                        <div className="card-body">
                            
                        <h2>Stay</h2>
                            <div className="row">
                                <div className="col-lg-3"> 
                                    <input type="date" className='form-control' value={startDate} onChange={handleStartDateChange} />
                                </div>
                                <div className="col-lg-3">
                                    <input type="date" className='form-control' value={endDate} onChange={handleEndDateChange} onBlur={handleEndDateBlur} />
                    
                                </div>
                                <div className="col-lg-3">
                                    <button type='button' className='btn btn-success mr-1' onClick={handleExportToExcel}>excel</button>
                                    <button type='button' className='btn btn-danger' onClick={handleExportToPDF}>pdf</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="card mb-2">
                        <div className="card-body">
                            <h2>Made on</h2>
                        <div className="row">
                                <div className="col-lg-3"> 
                                    <input type="date" className='form-control' value={startDate2} onChange={handleStartDateChange2} />
                                </div>
                                <div className="col-lg-3">
                                    <input type="date" className='form-control' value={endDate2} onChange={handleEndDateChange2} onBlur={handleEndDateBlur2} />
                    
                                </div>
                                <div className="col-lg-3">
                                    <button type='button' className='btn btn-success mr-1' onClick={handleExportToExcel}>excel</button>
                                    <button type='button' className='btn btn-danger' onClick={handleExportToPDF}>pdf</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
