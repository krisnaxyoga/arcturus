import React, { useState, useEffect } from "react";
import Layout from "../../../Layouts/Transport";
import { Link, usePage } from "@inertiajs/inertia-react";
import axios from "axios";

export default function Index({ token, user }) {
    const { url } = usePage();
    const [data, setData] = useState([]);
    const tokenFromLocalStorage = localStorage.getItem("token");
    const domain = window.location.origin;
    const idFromLocalStorage = localStorage.getItem("id");

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
    }

    useEffect(() => {

        fetchData(); // Memanggil fungsi untuk melakukan panggilan API saat komponen dimuat
    }, [tokenFromLocalStorage]); // Menambahkan tokenFromLocalStorage ke dalam dependencies useEffect

    const fetchData = async () => {
        try {
            const response = await axios.get(
                `${domain}/api/packagecar`,
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

        // If token exists, call API to verify and potentially fetch user data
        // ...
    }, []);


    const handleDelete = async (id) => {
      try {
          const response = await axios.get(
              `${domain}/api/packagecar/destroy/${id}`,
              {
                  headers: {
                      Authorization: `Bearer ${token}`,
                  },
              }
          );

          fetchData();
          console.log("Response:", response.data);
          // Lakukan sesuatu dengan response jika perlu
      } catch (error) {
          console.error("Terjadi kesalahan:", error);
          // Tangani kesalahan jika DELETE gagal
      }
  };


    return (
        <Layout token={token} user={user} page={url}>
            <div className="container">
              <Link href={`/transport/addpackageform/${token}/${idFromLocalStorage}`} className="btn btn-primary mb-3">Add</Link>
                <div className="row">
                    <div className="col-lg-6">
                        {data ? (
                            data.map((post, index) => (
                                <>
                                    <div key={index}>
                                        <div className="card mb-3" style={{borderRadius:'24px'}}>
                                            <div className="card-body">
                                                <div className="d-flex justify-content-between">
                                                    <span>
                                                      <p style={{fontWeight:'700'}}>{post.type_car}</p>
                                                      <p>{post.set ? post.set : 0}/seat</p>
                                                      <p>{post.transportdestination.destination}</p>
                                                      <p>{formatRupiah(post.price)}</p>
                                                      {/* <p>{post.number_police}</p> */}
                                                    </span>
                                                    <span>
                                                      <div className="card mb-2">
                                                            <a href={`/transport/editpackageform/${tokenFromLocalStorage}/${post.id}/${idFromLocalStorage}`} className="btn btn-warning">
                                                                <i className="fa fa-edit"></i>
                                                            </a>
                                                      </div>
                                                        <div className="card">
                                                            <button  onClick={() => handleDelete(post.id)} className="btn btn-danger">
                                                              <i className="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </span>
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
    );
}
