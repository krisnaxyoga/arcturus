import React, { useState } from "react";
import Layout from "../../../Layouts/Agent";

import { Link, usePage } from "@inertiajs/inertia-react";

import { Inertia } from '@inertiajs/inertia';

function Index({ agent }) {
    const { url } = usePage();
    const [markup, setMarkup] = useState("");

    const storePost = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append("markup", markup ? markup : agent.vendors.agent_markup);
        Inertia.post("/agent/hotelmarkup/markupaddagent", formData, {
            onSuccess: () => {
                alert("data saved!");
                window.location.reload();        
                // Lakukan aksi setelah gambar berhasil diunggah
            },
        });
    };

    return (
        <Layout agent={agent} page={url}>
            <div className="container">
                <div className="row">
                    <div className="col-lg-6">
                        <div className="card mb-3">
                            <div className="card-header">
                                <h2>Markup Hotel</h2>
                            </div>
                            <div className="card-body">
                                <form onSubmit={storePost}>
                                    <div className="form-group mb-3">
                                        <label htmlFor="">markup</label>
                                        <div className="d-flex">
                                        <input style={{width:'300px'}}
                                            onChange={(e) =>
                                                setMarkup(e.target.value)
                                            }
                                            className="form-control"
                                            type="text"
                                            name="markup"
                                            id=""
                                            defaultValue={
                                                agent.vendors.agent_markup
                                            }
                                        /> 
                                        <p className="mb-0 mx-3 mt-1">%</p>
                                        </div>
                                        
                                    </div>
                                    <div className="form-group">
                                        <button
                                            className="btn btn-primary"
                                            type="submit"
                                        >
                                            save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div className="col-lg-6">
                    <div className="card">
                        <img src="https://d1kqpi55383ont.cloudfront.net/ecommerceloka/blog-images/2112231640264422gmNYu9HRJ.jpg" className="card-img-top img-fluid" alt="https://d1kqpi55383ont.cloudfront.net/ecommerceloka/blog-images/2112231640264422gmNYu9HRJ.jpg"/>
                        <div className="card-body">
                            {/* <h5 className="card-title">Public Monitor</h5> */}
                             <a href="/agent/hotelmarkup/homehotel" target="_blank" className="btn btn-primary">Customers View</a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}

export default Index;
