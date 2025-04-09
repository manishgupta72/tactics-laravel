import React from 'react';

export default function Dashboard({ products }) {
    return (
        <div className="container mt-4">
            <h1 className="mb-4 text-center">Product List</h1>
            <table className="table table-bordered table-striped table-hover">
                <thead className="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Short Description</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Discounted Price</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th>Updated On</th>
                    </tr>
                </thead>
                <tbody>
                    {products.map((product) => (
                        <tr key={product.id}>
                            <td>{product.id}</td>
                            <td>{product.product_name}</td>
                            <td>{product.product_s_description}</td>
                            <td>{product.product_description}</td>
                            <td>{product.product_price}</td>
                            <td>{product.product_d_price}</td>
                            <td>{product.status}</td>
                            <td>{product.addedon}</td>
                            <td>{product.updatedon}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
