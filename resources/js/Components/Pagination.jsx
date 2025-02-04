import React, { Component } from 'react'

export default function Pagination({ postsPerPage, totalPosts, paginate, nextPage, prevPage, crntPage }) {

    const pageNumbers = [];

    for (let i = 1; i <= Math.ceil(totalPosts / postsPerPage); i++) {
        pageNumbers.push(i);
    }

    return (
        <nav>
            <ul className="pagination justify-content-center">
                <li className="page-item">
                    <button disabled={crntPage === 1} className="page-link" href="#" onClick={() => prevPage()}>Previous</button>
                </li>
                {pageNumbers.map(num => (
                    <li className="page-item" key={num}>
                        <a onClick={() => paginate(num)} href="#" className="page-link">{num}</a>
                    </li>
                ))}
                <li className="page-item">
                    <button disabled={crntPage === pageNumbers.length} className="page-link" href="#" onClick={() => nextPage()}>Next</button>
                </li>
            </ul>
        </nav>
    )
}
