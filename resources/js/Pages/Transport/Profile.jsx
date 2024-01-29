import React, { useState, useEffect } from "react";

import Layout from "../../Layouts/Transport";

import Tab from "react-bootstrap/Tab";
import Tabs from "react-bootstrap/Tabs";
import axios from "axios";
import { Link, usePage } from "@inertiajs/inertia-react";
function Profile({ token, user }) {
    if (token) {
        localStorage.setItem("token", token);

        localStorage.setItem("id", user.id);
    }
    const { url } = usePage();
    const domain = window.location.origin;
    const [formData, setFormData] = useState({
        company_name: "",
        email: "",
        password: "",
        mobile_phone: "",
        address: "",
    });

    const [formDataBank, setFormDataBank] = useState({
        bank_name: "",
        bank_address: "",
        account_number: "",
        swif_code: "",
    });

    const [bank, setDataBank] = useState([]);

    const tokenFromLocalStorage = localStorage.getItem("token");
    const idFromLocalStorage = localStorage.getItem("id");

    const bankaccountlist = async () => {
        try {
            const response = await axios.get(
                `${domain}/api/bankaccountlist/${idFromLocalStorage}`,
                {
                    headers: {
                        Authorization: `Bearer ${tokenFromLocalStorage}`,
                    },
                }
            );
            setDataBank(response.data.data);
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
        }
    };

    console.log(bank, "??");

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
        bankaccountlist();
        // If token exists, call API to verify and potentially fetch user data
        // ...
    }, []);

    const handleSubmit = async (event) => {
        event.preventDefault();

        try {
            const response = await axios.post(
                `${domain}/api/profileupdate/${user.id}`,
                formData,
                {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`, // Ganti dengan token yang valid
                    },
                }
            );

            if (response) {
                alert(response.data.message);
            }
            // Lakukan sesuatu dengan response jika perlu
        } catch (error) {
            console.log(error);
            console.error("Terjadi kesalahan:", error);
            // Tangani kesalahan jika POST gagal
        }
    };

    const handleBankSubmit = async (event) => {
        event.preventDefault();

        try {
            const response = await axios.post(
                `${domain}/api/bankaccount/${idFromLocalStorage}`,
                formDataBank,
                {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`, // Ganti dengan token yang valid
                    },
                }
            );

            if (response) {
                alert(response.data.message);
            }
            // Lakukan sesuatu dengan response jika perlu
        } catch (error) {
            console.log(error);
            console.error("Terjadi kesalahan:", error);
            // Tangani kesalahan jika POST gagal
        }
    };

    const handleChange = (event) => {
        const { name, value } = event.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    const handleChangeBank = (event) => {
        const { name, value } = event.target;
        setFormDataBank({
            ...formDataBank,
            [name]: value,
        });
    };

    console.log(formDataBank, ">>>>>HALO");
    return (
        <Layout token={token} user={user} page={url}>
            <div className="row">
                <div className="col-lg-6">
                    <Tabs
                        defaultActiveKey="Profile"
                        id="fill-tab-example"
                        className="mb-3"
                        fill
                    >
                        <Tab eventKey="Profile" title="Profile">
                            <div className="card mb-3">
                                <div className="card-header">Profile</div>
                                <div className="card-body">
                                    <form onSubmit={handleSubmit}>
                                        <div className="form-group mb-3">
                                            <label htmlFor="">Name</label>
                                            <input
                                                type="text"
                                                name="company_name"
                                                className="form-control"
                                                defaultValue={user.company_name}
                                                onChange={handleChange}
                                            />
                                        </div>
                                        <div className="form-group mb-3">
                                            <label htmlFor="">Email</label>
                                            <input
                                                type="text"
                                                name="email"
                                                className="form-control"
                                                defaultValue={user.email}
                                                onChange={handleChange}
                                            />
                                        </div>
                                        <div className="form-group mb-3">
                                            <label htmlFor="">
                                                Mobile Phone
                                            </label>
                                            <input
                                                type="text"
                                                name="mobile_phone"
                                                className="form-control"
                                                defaultValue={user.mobile_phone}
                                                onChange={handleChange}
                                            />
                                        </div>
                                        <div className="form-group mb-3">
                                            <label htmlFor="">password</label>
                                            <input
                                                type="password"
                                                className="form-control"
                                                name="password"
                                                onChange={handleChange}
                                            />
                                            <p
                                                className="m-0 text-danger"
                                                style={{
                                                    fontWeight: "700",
                                                    fontSize: "10px",
                                                }}
                                            >
                                                If you want to change the
                                                password, you can fill in here,
                                                if you don't want to change the
                                                password, please leave it blank.
                                            </p>
                                        </div>
                                        <div className="form-group mb-3">
                                            <label htmlFor="">Descriptions</label>
                                            <textarea
                                                className="form-control"
                                                name="address"
                                                id=""
                                                cols="30"
                                                rows="10"
                                                onChange={handleChange}
                                            >
                                                {user.address}
                                            </textarea>
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
                        </Tab>
                        <Tab eventKey="Bank" title="Bank">
                            <div className="card">
                                <div className="card-header">Bank Account</div>
                                <div className="card-body">
                                    {bank == null ? (
                                        <>
                                            <form onSubmit={handleBankSubmit}>
                                                <div className="form-group mb-3">
                                                    <label htmlFor="">
                                                        Bank Name
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="bank_name"
                                                        className="form-control"
                                                        defaultValue={
                                                            formDataBank.bank_name
                                                        }
                                                        onChange={
                                                            handleChangeBank
                                                        }
                                                    />
                                                </div>
                                                <div className="form-group mb-3">
                                                    <label htmlFor="">
                                                        Bank Address
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="bank_address"
                                                        className="form-control"
                                                        defaultValue={
                                                            formDataBank.bank_address
                                                        }
                                                        onChange={
                                                            handleChangeBank
                                                        }
                                                    />
                                                </div>
                                                <div className="form-group mb-3">
                                                    <label htmlFor="">
                                                        Account Number
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="account_number"
                                                        className="form-control"
                                                        defaultValue={
                                                            formDataBank.account_number
                                                        }
                                                        onChange={
                                                            handleChangeBank
                                                        }
                                                    />
                                                </div>
                                                <div className="form-group mb-3">
                                                    <label htmlFor="">
                                                        Swift Code
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="swif_code"
                                                        className="form-control"
                                                        defaultValue={
                                                            formDataBank.swif_code
                                                        }
                                                        onChange={
                                                            handleChangeBank
                                                        }
                                                    />
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
                                        </>
                                    ) : (
                                        <>
                                            <form onSubmit={handleBankSubmit}>
                                                <div className="form-group mb-3">
                                                    <label htmlFor="">
                                                        Bank Name
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="bank_name"
                                                        className="form-control"
                                                        defaultValue={
                                                            bank.bank_name
                                                        }
                                                        onChange={
                                                            handleChangeBank
                                                        }
                                                    />
                                                </div>
                                                <div className="form-group mb-3">
                                                    <label htmlFor="">
                                                        Bank Address
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="bank_address"
                                                        className="form-control"
                                                        defaultValue={
                                                            bank.bank_address
                                                        }
                                                        onChange={
                                                            handleChangeBank
                                                        }
                                                    />
                                                </div>
                                                <div className="form-group mb-3">
                                                    <label htmlFor="">
                                                        Account Number
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="account_number"
                                                        className="form-control"
                                                        defaultValue={
                                                            bank.account_number
                                                        }
                                                        onChange={
                                                            handleChangeBank
                                                        }
                                                    />
                                                </div>
                                                <div className="form-group mb-3">
                                                    <label htmlFor="">
                                                        Swift Code
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="swif_code"
                                                        className="form-control"
                                                        defaultValue={
                                                            bank.swif_code
                                                        }
                                                        onChange={
                                                            handleChangeBank
                                                        }
                                                    />
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
                                        </>
                                    )}
                                </div>
                            </div>
                        </Tab>
                    </Tabs>
                </div>
            </div>
        </Layout>
    );
}

export default Profile;
