//import React
import React, { useState } from "react";

//import layout
import Layout from "../../../Layouts/Vendor";

//import Link
import { Link, usePage } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";

export default function Create({ props, vendor, session,country }) {
    const { url } = usePage();
    const [firstname, setFirstName] = useState("");
    const [email, setEmail] = useState("");
    const [phone, setPhone] = useState("");
    const [selectcountry,setCountry] = useState('');

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append("country",selectcountry);
        formData.append("firstname", firstname);
        formData.append("email", email);
        formData.append("phone", phone);
        Inertia.post("/vendor-profile/propertystore", formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    };
    return (
        <>
            <Layout page={url} vendor={vendor}>
                <div className="container">
                    <h1>Create Property</h1>
                    <div className="row">
                        <div className="col-lg-6">
                            <form onSubmit={storePost}>
                                <div className="card">
                                    <div className="card-body">
                                        {session.success && (
                                            <div className="alert alert-success border-0 shadow-sm rounded-3">
                                                {session.success}
                                            </div>
                                        )}
                                        <div className="row">
                                            <div className="col-lg-12">
                                                <div className="mb-3">
                                                    <label htmlFor="firstname">
                                                        Hotel Name
                                                    </label>
                                                    <input
                                                        onChange={(e) =>
                                                            setFirstName(
                                                                e.target.value
                                                            )
                                                        }
                                                        type="text"
                                                        className="form-control"
                                                        name="firstname"
                                                    />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="phone">
                                                        Phone
                                                    </label>
                                                    <input
                                                        onChange={(e) =>
                                                            setPhone(
                                                                e.target.value
                                                            )
                                                        }
                                                        type="text"
                                                        className="form-control"
                                                        name="phone"
                                                    />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="email">
                                                        Email
                                                    </label>
                                                    <input
                                                        inputMode="email"
                                                        onChange={(e) =>
                                                            setEmail(
                                                                e.target.value
                                                            )
                                                        }
                                                        type="text"
                                                        className="form-control"
                                                        name="email"
                                                    />
                                                </div>
                                                <div className="mb-3">
                                                    <label htmlFor="">
                                                        country
                                                    </label>
                                                    <select
                                                        required
                                                        onChange={(e) =>
                                                            setCountry(
                                                                e.target.value
                                                            )
                                                        }
                                                        className="form-control"
                                                        aria-label="Default select example"
                                                    >
                                                        <option value="">-select country-</option>
                                                        {Object.keys(
                                                            country
                                                        ).map((key) => (
                                                            <option
                                                                key={key}
                                                               
                                                                value={
                                                                    country[key]
                                                                }
                                                            >
                                                                {country[key]}
                                                            </option>
                                                        ))}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div className="row justify-content-between">
                                            {" "}
                                            {/* Use justify-content-between to move the buttons to both ends */}
                                            <div className="col-lg-auto">
                                                <button
                                                    type="submit"
                                                    className="btn btn-primary"
                                                >
                                                    <i className="fa fa-save"></i>{" "}
                                                    Save
                                                </button>
                                            </div>
                                            <div className="col-lg-auto">
                                                <Link
                                                    href={`/vendor-profile/property`}
                                                    className="btn btn-danger"
                                                >
                                                    Cancel
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    );
}
