import React, { useState, useEffect } from "react";
import Layout from "../../../Layouts/Transport";
import { Link, usePage } from "@inertiajs/inertia-react";
import axios from "axios";

export default function Edit({token, user, iddata}) {
    const { url } = usePage();
    const domain = window.location.origin;
    const [formData, setFormData] = useState({
        type_car: "",
        destination: "",
        price: "",
        number_police: "",
        change_policy: "",
        cancellation_policy:"",
    });

    const [destination, setDestenition] = useState([]);
    const tokenFromLocalStorage = localStorage.getItem("token");
    // const tokenFromLocalStorage = localStorage.getItem("token");
    // console.log(iddata,">>>ID DATA");
    const fetchData = async () => {
        try {
            const response = await axios.get(
                `${domain}/api/destination`,
                {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${tokenFromLocalStorage}`,
                    },
                }
            );

            setDestenition(response.data.data); // Mengatur data yang diterima dari API ke state
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
        }
    };

    useEffect(() => {
        const fetchPackageCar = async () => {
            try {
                const response = await axios.get(
                    `${domain}/api/packagecar/show/${iddata}`,
                    {
                        headers: {
                            Authorization: `Bearer ${tokenFromLocalStorage}`,
                        },
                    }
                );

                const { data } = response.data;

                setFormData({
                    type_car: data.type_car || "",
                    destination: data.destination || "",
                    price: data.price || "",
                    // number_police: data.number_police || "",
                    change_policy: data.change_policy,
                    cancellation_policy: data.cancellation_policy,

                });

            } catch (error) {
                console.error("Terjadi kesalahan:", error);
            }
        };
        fetchData();
        fetchPackageCar();
    }, []);

    const handleSubmit = async (event) => {
        event.preventDefault();

        try {
            const response = await axios.post(
                `${domain}/api/packagecar/update/${iddata}`,
                formData,
                {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization:
                            `Bearer ${token}`, // Ganti dengan token yang valid
                    },
                }
            );

            window.location.href = `/transport/addpackage/${token}/${user.id}`;
            console.log("Response:", response.data);
            // Lakukan sesuatu dengan response jika perlu
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
            // Tangani kesalahan jika POST gagal
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

    const handleChange = (event) => {
        const { name, value } = event.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    // console.log(formData);

    return (
        <Layout token={token} user={user} page={`/transport/addpackage/${token}/${user.id}`}>
            <div className="container">
                <div className="row">
                    <div className="col-lg-6">
                        <div className="card">
                            <div className="card-body">
                                <form onSubmit={handleSubmit}>
                                    <div className="form-group mb-3">
                                        <label>Type Car:</label>
                                        <input
                                        className="form-control"
                                            type="text"
                                            name="type_car"
                                            value={formData.type_car}
                                            onChange={handleChange}
                                        />
                                    </div>
                                    <div className="form-group mb-3">
                                        <label>Destination:</label>
                                        <select className="form-control" name="destination" id="" onChange={handleChange}>
                                        <option value="">-select-</option>
                                        {destination ? (
                                            destination.map((post, index) => (
                                                <>
                                                <option key={index} selected={formData.destination == post.id} value={post.id}>{post.destination}</option>
                                                </>
                                            ))
                                        ):(
                                            <>
                                            <option value="">tidak ada data</option>
                                            </>
                                        )}
                                        </select>
                                    </div>
                                    <div className="form-group mb-3">
                                        <label>Price:</label>
                                        <input
                                        className="form-control"
                                            type="number"
                                            name="price"
                                            value={formData.price}
                                            onChange={handleChange}
                                        />
                                    </div>
                                    {/* <div className="form-group mb-3">
                                        <label>Number Police:</label>
                                        <input
                                        className="form-control"
                                            type="text"
                                            name="number_police"
                                            value={formData.number_police}
                                            onChange={handleChange}
                                        />
                                    </div> */}
                                    <div className="form-group mb-3">

                                    <label htmlFor="">Change Policy</label>
                                        <textarea className="form-control" onChange={handleChange} value={formData.change_policy} name="change_policy" id="" cols="30" rows="10">
                                            {formData.change_policy}
                                        </textarea>
                                    </div>
                                    <div className="form-group mb-3">
                                    <label htmlFor="">cancellation Policy</label>
                                        <textarea name="cancellation_policy" onChange={handleChange} value={formData.cancellation_policy} className="form-control" id="" cols="30" rows="10">
                                            {formData.cancellation_policy}
                                        </textarea>
                                    </div>
                                    <div className="d-flex justify-content-between">
                                       <button type="submit" className="btn btn-primary">Save</button>
                                       <Link href={`/transport/addpackage/${token}/${user.id}`} className="btn btn-danger">Back</Link>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}

