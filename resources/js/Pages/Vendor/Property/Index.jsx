//import React
import React, { useState } from "react";

//import layout
import Layout from "../../../Layouts/Vendor";

//import Link
import { Link, usePage } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";

const decrypt = (encryptedValue) => {
    return atob(encryptedValue);
};

export default function Index({ props, vendor, data }) {
    const { url } = usePage();

    // Mendapatkan nilai 'encryptedIsSuperAdmin' dari localStorage
    const encryptedIsSuperAdmin = localStorage.getItem('encryptedIsSuperAdmin');

    // Mendekripsi nilai jika encrypted data ada di localStorage
    const is_super_admin = decrypt(encryptedIsSuperAdmin);

    return (
        <>
            <Layout page={url} vendor={vendor}>
                <div className="container">
                    <div className="row">
                        <h1>Property</h1>
                        <div className="col-lg-12">
                            <div className="card">
                                <div className="card-body">
                                    <Link
                                        href="/vendor-profile/propertycreate"
                                        className="btn btn-primary mb-2"
                                    >
                                        add
                                    </Link>
                                    <table
                                        className="table table-bordered"
                                        id="dataTable"
                                        width="100%"
                                        cellSpacing="0"
                                    >
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Hotel Name</th>
                                                <th>email</th>

                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {data.map((item, index) => (
                                                <>
                                                    <tr key={index}>
                                                        <td>{index + 1}</td>
                                                        <td>
                                                            {item.vendors.vendor_name}
                                                        </td>
                                                        <td>
                                                            {item.vendors.email}
                                                        </td>
                                                        <td>
                                                            <Link
                                                                className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                                                href={`/vendor-profile/loginproperty/${item.id}?is_super_admin=${is_super_admin === 'true' ? 'true' : 'false'}`}
                                                            >
                                                                <i className="fa fa-key"></i>
                                                            </Link>
                                                        </td>
                                                    </tr>
                                                </>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    );
}
