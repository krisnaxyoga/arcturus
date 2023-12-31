import React, { useState, useEffect } from "react";
//import layout
import Layout from "../../Layouts/Transport";

import axios from "axios";
import { Link, usePage } from "@inertiajs/inertia-react";
function Index({token, user}) {
    const { url } = usePage();
    const [data, setData] = useState([]);
    const domain = window.location.origin;
    const tokenFromLocalStorage = localStorage.getItem("token");
    const idFromLocalStorage = localStorage.getItem("id");
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

    const fetchData = async () => {
        try {
            const response = await axios.get(
                `${domain}/api/reportordertransport`,
                {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${tokenFromLocalStorage}`,
                    },
                }
            );

            setData(response.data.data); // Mengatur data yang diterima dari API ke state
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
        }
    };
    useEffect(() => {
        // if (!localStorage.getItem('token')) {
        //   window.location.href = '/auth/transport'; // Redirect to login page if no token
        //   return;
        // }

        // Check if tokens match

        // console.log(tokenFromLocalStorage,">>>>INI ADA DI LOCALSTORAGE");
        // console.log(token.token,">>>>>INI ADA DI SISTEM");
        // const tokenFromFunction = token;
        if (tokenFromLocalStorage != token) {
            window.location.href = "/auth/transport"; // Redirect to login page if tokens don't match
        }

        fetchData();
        // If token exists, call API to verify and potentially fetch user data
        // ...
    }, []);
    return (
        <>
            <Layout token={token} user={user} page={url}>
                <div>
                    <div className="row">
                        <div className="col-lg-12">
                            <div className="card bg-primary mb-4">
                                <div
                                    className="card-body d-flex justify-content-between"
                                >
                                  <div>
                                     <h1 style={{fontWeight:'700'}} className="card-title text-light fw-bold">
                                        Dashboard Transport
                                    </h1>
                                    <p className="card-text text-light">
                                        Create Your Product Dashboard in Minutes
                                    </p>
                                    <Link href={`/transport/addpackage/${tokenFromLocalStorage}/${idFromLocalStorage}`} className="btn btn-secondary">
                                        Create Package
                                    </Link>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                      <div className="col-lg-12">
                        <h3>Booking History</h3>
                        {data ? (
                            data.map((post, index) => (
                                <>
                                    <div key={index}>
                                        <div
                                            className="card mb-3"
                                            style={{ borderRadius: "24px" }}
                                        >
                                            <dv className="card-header">
                                            <p className="text-dark"
                                                                style={{
                                                                    fontWeight:
                                                                        "700",
                                                                }}
                                                            >
                                                                {post.typecar}
                                                            </p>
                                            </dv>
                                            <div className="card-body">
                                                <div className="d-flex justify-content-between">
                                                    <span className="row justify-content-between">
                                                        <span className="col-lg-6">
                                                            {post.booking_status ==
                                                            "paid" ? (
                                                                <>
                                                                    <p className="badge badge-success">
                                                                        {
                                                                            post.booking_status
                                                                        }
                                                                    </p>
                                                                </>
                                                            ) : (
                                                                <>
                                                                    <p className="badge badge-danger">
                                                                        {
                                                                            post.booking_status
                                                                        }
                                                                    </p>
                                                                </>
                                                            )}

                                                            <p>
                                                                Guest : <br />
                                                                {
                                                                    post.guest_name
                                                                }
                                                            </p>

                                                            <p>
                                                                {
                                                                    post.destination
                                                                }
                                                            </p>
                                                            <p>
                                                                {
                                                                    formatRupiah(post.total_price_nomarkup)
                                                                }
                                                            </p>
                                                            {/* <p>
                                                                {
                                                                    post.number_police
                                                                }
                                                            </p> */}
                                                        </span>
                                                        <span className="col-lg-6">
                                                            <p className="m-0"> Pickup date :  </p>
                                                            <p> {
                                                                    formatDate(post.pickup_date)
                                                                }</p>
                                                            <p className="m-0"> Pickup time :

                                                            </p>
                                                            <p>{
                                                                    post.time_pickup
                                                                }</p>
                                                        </span>
                                                    </span>
                                                    {post.booking_status ==
                                                            "paid" ? (
                                                                <>
                                                                    <span>
                                                                        <div className="card mb-2">
                                                                            <a
                                                                                href={`/transport/bookinghistoryshow/${post.id}/${tokenFromLocalStorage}/${idFromLocalStorage}`}
                                                                                className="btn btn-warning"
                                                                            >
                                                                                <i className="fa fa-eye"></i>
                                                                            </a>
                                                                        </div>
                                                                    </span>
                                                                </>
                                                            ) : (
                                                                <>

                                                                </>
                                                            )}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </>
                            ))
                        ) : (
                            <p>Loading...</p>
                        )}
                      </div>
                    </div>
                </div>
            </Layout>
        </>
    );
}

export default Index;
