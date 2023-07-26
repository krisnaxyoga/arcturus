//import React
import React, { useState } from "react";

//import layout
import Layout from "../../../../Layouts/Vendor";

//import Link
import { Link } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";

export default function AdvPromotion({ props, session, room }) {
    const [inputs, setInputs] = useState([""]);
    const [counter, setCounter] = useState(1);

    const [advcode, setAdv] = useState("");
    const [ratecode, setRagecode] = useState("");

    const [night, setNight] = useState("");

    const handleTambahInput = () => {
        setInputs([...inputs, ""]);
        setCounter(counter + 1);
    };
    const handleInputChange = (index, value) => {
        const newInputs = [...inputs];
        newInputs[index] = value;
        // const pricerekomen = newInputs[index] * 0.8;
        // setAprice(pricerekomen);
        setInputs(newInputs);
    };
    const handleHapusInput = (index) => {
        const newInputs = [...inputs];
        newInputs.splice(index, 1);
        setInputs(newInputs);
    };

    const storePost = async (e) => {
        e.preventDefault();

        const adult_price = {
            price: inputs,
        };

        console.log(adult_price,">>>>adult price")
        const formData = new FormData();
        formData.append('code', advcode);
        formData.append('room_id', ratecode);

        adult_price.price.forEach((adp, index) => {
            formData.append(`price[${index}]`, adp);
        });

        formData.append('night', night);

        Inertia.post('/room/promotion/adv/store', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
              },
        });
    }
    return (
        <>
            <Layout>
                <div className="container">
                    <div className="row">
                        <h1>Advance Purchese Promo</h1>
                        <div className="col-lg-6">
                            <div className="card">
                                <div className="card-body">
                                    <form onSubmit={storePost}>
                                        <div className="d-flex justify-content-lg-between">
                                            <div class="mb-3">
                                                <label
                                                    for="exampleFormControlInput1"
                                                    className="form-label"
                                                >
                                                    adv code
                                                </label>
                                                <input
                                                    onChange={(e) =>
                                                        setAdv(e.target.value)
                                                    }
                                                    type="text"
                                                    className="form-control"
                                                    id="exampleFormControlInput1"
                                                    placeholder="Advance Purchese Promo code"
                                                />
                                            </div>
                                            <p>from</p>
                                            <div class="mb-3">
                                                <label
                                                    for="exampleFormControlInput1"
                                                    className="form-label"
                                                >
                                                    Rate Code
                                                </label>
                                                <select
                                                    className="form-control"
                                                    onChange={(e) =>
                                                        setRagecode(
                                                            e.target.value
                                                        )
                                                    }
                                                    name=""
                                                    id=""
                                                >
                                                    {room.map((item, index) => (
                                                        <>
                                                            <option
                                                                value={item.id}
                                                                key={index}
                                                            >
                                                                {item.ratecode}
                                                            </option>
                                                        </>
                                                    ))}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label
                                                for="exampleFormControlInput1"
                                                className="form-label"
                                            >
                                                Niminum Night
                                            </label>
                                            <input
                                                onChange={(e) =>
                                                    setNight(e.target.value)
                                                }
                                                type="number"
                                                className="form-control"
                                                id="exampleFormControlInput1"
                                                placeholder="night"
                                            />
                                        </div>
                                        <div className="row">
                                            {inputs.map((input, index) => (
                                                <>
                                                    <div className="col-lg-3">
                                                        <p className="my-2">
                                                            {30 * (index + 1)}{" "}
                                                            Days
                                                        </p>
                                                    </div>
                                                    <div className="col-lg-9">
                                                        <div className="d-flex">
                                                            <input
                                                                className="mb-1 form-control"
                                                                key={index}
                                                                value={input}
                                                                onChange={(e) =>
                                                                    handleInputChange(index,e.target.value)
                                                                }
                                                                placeholder={`${ratecode}`}
                                                            />
                                                            <button
                                                                type="button"
                                                                className="btn btn-danger mx-2 mb-2"
                                                                onClick={() =>
                                                                    handleHapusInput(
                                                                        index
                                                                    )
                                                                }
                                                            >
                                                                <i className="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </>
                                            ))}
                                        </div>

                                        <div className="mt-2">
                                            <button
                                                className="btn btn-primary"
                                                type="button"
                                                onClick={handleTambahInput}
                                            >
                                                + Add Configuration
                                            </button>
                                        </div>
                                        <hr />
                                        <button className="btn btn-success">
                                            save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    );
}
