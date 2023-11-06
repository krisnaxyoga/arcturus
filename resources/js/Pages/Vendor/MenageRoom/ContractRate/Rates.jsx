import React,{ useState } from "react";
import { Link } from "@inertiajs/inertia-react";

export default function Rates({ rates }) {
    const formatDate = (dateString) => {
        const parts = dateString.split('-'); // Memecah tanggal berdasarkan tanda "-"
        if (parts.length === 3) {
          const [year, month, day] = parts;
          return `${day}/${month}/${year}`; // Mengganti urutan tanggal
        }
        return dateString; // Kembalikan jika tidak dapat memproses tanggal
      };

      const [updatedRates, setUpdatedRates] = useState(rates);

        // Fungsi untuk mengubah status dan mengirim permintaan ke server
        const toggleStatus = async (id, currentStatus) => {
            try {
                // Kirim permintaan ke server untuk mengubah status
                const response = await fetch(`/room/contract/contractrate_is_active/${id}/${currentStatus == 1 ? 0 : 1}`, {
                    method: 'GET', // Gantilah dengan metode HTTP yang sesuai
                    // Tambahkan header jika diperlukan
                });

                if (response.ok) {
                    // Jika berhasil, perbarui status secara lokal
                    const updatedRatesCopy = [...updatedRates];
                    const rateIndex = updatedRatesCopy.findIndex((rate) => rate.id == id);
                    updatedRatesCopy[rateIndex].is_active = currentStatus == 1 ? 0 : 1;
                    setUpdatedRates(updatedRatesCopy);
                } else {
                    // Handle kesalahan jika ada
                    console.error('Gagal mengubah status.');
                }
            } catch (error) {
                console.error('Terjadi kesalahan:', error);
            }
        };

    return (
        <tbody>
            {rates.map((item) => (
                <>
                    <tr className={item.rolerate == 1 && "bg-light"} key={item.id}>
                        {/* <td>{item.ratecode}</td>
                        <td>{item.codedesc}</td> */}
                        <td>{formatDate(item.stayperiod_begin)}</td>
                        <td>{formatDate(item.stayperiod_end)}</td>
                        <td>{formatDate(item.booking_begin)}</td>
                        <td>{formatDate(item.booking_end)}</td>
                        <td>{item.min_stay}</td>
                        <td>{item.distribute.join(", ")}</td>
                        <td>
                            <Link
                                href={`/room/contract/sync_advance_purchase/${item.id}`}
                                data-toggle="tooltip"
                                data-placement="top"
                                title="refresh advance purchase rate"
                                className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                            >
                                <svg
                                    xmlns="http://www.w3.org/1000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-refresh-cw"
                                >
                                    <polyline points="23 4 23 10 17 10"></polyline>
                                    <polyline points="1 20 1 14 7 14"></polyline>
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                </svg>
                            </Link>
                            <Link
                                href={`/room/contract/edit/${item.id}`}
                                className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                            >
                                <i className="fa fa-edit"></i>
                            </Link>
                            {item.is_active != undefined ? (
                                <a
                                    href="#"
                                    className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                    onClick={(e) => {
                                    e.preventDefault();
                                    toggleStatus(item.id, item.is_active);
                                    }}
                                >
                                    {item.is_active == 1 ? (
                                    <span className="text-success">
                                        <i className="fa fa-circle" aria-hidden="true"></i>on
                                    </span>
                                    ) : (
                                    <span className="text-danger">
                                        <i className="fa fa-circle" aria-hidden="true"></i>off
                                    </span>
                                    )}
                                </a>
                                ) : (
                                <span>Missing or invalid data</span>
                                )}
                                {item.rolerate == 2 && <>
                                    <Link
                                        href="#"
                                        className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                        onClick={() => {
                                            if (window.confirm('Are you sure you want to delete this?')) {
                                              // Lanjutkan dengan menghapus jika pengguna menekan OK pada konfirmasi
                                              window.location.href = `/room/surcharge/surchargeallroomdestroy/${item.code}`;
                                            }
                                          }}
                                    >
                                        <i className="fa fa-trash"></i>
                                    </Link>
                                </>}
                           
                        </td>
                    </tr>
                </>
            ))}
        </tbody>
    );
}
