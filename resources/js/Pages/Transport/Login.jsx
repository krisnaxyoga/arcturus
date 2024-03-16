import React, { useState, useEffect }  from 'react';
import axios from 'axios';

export default function Login() {
 //define state
 const [email, setEmail] = useState("");
 const [password, setPassword] = useState("");

 //define state validation
 const [validation, setValidation] = useState([]);

  //id
  const id = localStorage.getItem("id");
  const token = localStorage.getItem('token');

  //hook useEffect
  useEffect(() => {

    //check token

    if(localStorage.getItem('token')) {
        window.location.href = `/transport/dashboard/${token}/${id}`;
    }
}, []);

const loginHandler = async (e) => {
    e.preventDefault();
    
    //initialize formData
    const formData = new FormData();

    //append data to formData
    formData.append('email', email);
    formData.append('password', password);
    const domain = window.location.origin;

    //send data to server
    await axios.post(`${domain}/api/login/transport`, formData)
    .then((response) => {

        //set token on localStorage
        localStorage.setItem('token', response.data.token);
        
        localStorage.setItem('id', response.data.user.id);

        //redirect to dashboard
        window.location.href = `/transport/dashboard/${response.data.token}/${response.data.user.id}`;
    })
    .catch((error) => {

        //assign error to state "validation"
        setValidation(error.response.data);
    })
};
  return (
    <>
    <div className="container" style={{marginTop:'15rem'}}>
        <div className="row justify-content-center">
            <div className="col-lg-4">
                <div className="card">
                    <div className="card-header">
                       <h3 className='text-center' style={{fontWeight:'700'}}>
                       Welcome to Arcturus Transport!
                       </h3>
                    </div>
                    <div className="card-body">
                    {
                                validation.message && (
                                    <div className="alert alert-danger">
                                        {validation.message}
                                    </div>
                                )
                            }
                        <form onSubmit={loginHandler}>
                            <div className="form-group mb-3">
                                <label htmlFor="email">Email</label>
                                <input className='form-control' type="text" placeholder='input your email...' value={email} onChange={(e) => setEmail(e.target.value)}/>
                            </div>
                            {
                                    validation.email && (
                                        <div className="alert alert-danger">
                                            {validation.email[0]}
                                        </div>
                                    )
                                }
                            <div className="form-group mb-3">
                                <label htmlFor="password">Password</label>
                                <input type="password" className="form-control" value={password} onChange={(e) => setPassword(e.target.value)}/>
                            </div>
                            {
                                    validation.password && (
                                        <div className="alert alert-danger">
                                            {validation.password[0]}
                                        </div>
                                    )
                                }
                            <div className="mb-3">
                                <button type='submit' className='btn btn-primary mb-2'>login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </>
  )
}
