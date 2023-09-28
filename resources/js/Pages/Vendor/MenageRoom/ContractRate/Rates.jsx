import React from "react";
import { Link } from "@inertiajs/inertia-react";

export default function Rates({ rates }) {
    return (
        <tbody>
            {rates.map((item) => (
                <>
                    <tr className={item.rolerate == 1 && "bg-light"} key={item.id}>
                        <td>{item.ratecode}</td>
                        <td>{item.codedesc}</td>
                        <td>{item.stayperiod_begin}</td>
                        <td>{item.stayperiod_end}</td>
                        <td>{item.booking_begin}</td>
                        <td>{item.booking_end}</td>
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
                            <Link
                                href={`/room/contract/destroy/${item.id}`}
                                className="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                            >
                                <i className="fa fa-trash"></i>
                            </Link>
                        </td>
                    </tr>
                </>
            ))}
        </tbody>
    );
}
