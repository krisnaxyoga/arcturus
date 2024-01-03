//import React
import React,{ useState, useEffect } from 'react';

//import layout
import Layout from '../../../Layouts/Agent';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import Tooltip from 'react-bootstrap/Tooltip';

export default function Index({ props, session, agent,history,setting }) {
    const { url } = usePage();
    const [copied, setCopied] = useState(false);
    const [copied2, setCopied2] = useState(false);
    const [pleaseamount, settotalamoutnt] = useState(false);
    const [text, setText] = useState("2027999995");
    const [totaltopup, setTopup] = useState('');
    const [imgtrf,setTrf] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [totalPayment, setTotalPayment] = useState('');
    const [selectedImage, setSelectedImage] = useState(null);
    const [uniqueCode, setUniqueCode] = useState('');
    const [isButtonDisabled, setIsButtonDisabled] = useState(false);
    const [isButtonDisabledTopup, setIsButtonDisabledTopup] = useState(true);

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount).slice(0, -3);
    }
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate();
        const month = date.getMonth() + 1; // Adding 1 because months are 0-based
        const year = date.getFullYear();
    
        // Pad day and month with leading zeros if needed
        const formattedDay = day < 10 ? `0${day}` : day;
        const formattedMonth = month < 10 ? `0${month}` : month;
    
        return `${formattedDay}/${formattedMonth}/${year}`;
    }

    const handleTopupChange = (e) => {
        setTopup(e.target.value);
        // const inputValue = e.target.value;
        // // Periksa apakah input adalah angka
        // const isNumericInput = /^[0-9]*$/.test(inputValue);
    
        // if (isNumericInput) {
        //   // Tambahkan 3 angka acak di belakang angka yang dimasukkan
        //   const randomDigits = Math.floor(Math.random() * 1000);
        //   const formattedTotal = parseFloat(inputValue) + parseFloat(`0.${randomDigits}`);
        //   setTopup(inputValue);
        //   setTotalPayment(formattedTotal.toFixed(0));
        // } else {
        //   // Jika input bukan angka, biarkan input kosong
        //   setTopup('');
        //   setTotalPayment('');
        // }
      };

      const renderTooltip = (props) => (
        <Tooltip id="button-tooltip" {...props}>
          Please click this button to get a unique code behind a number to continue the top-up process.
        </Tooltip>
      );

      const renderTooltip2 = (props) => (
        <Tooltip id="button-tooltip" {...props}>
          make sure the value you transfer is in accordance with the nominal that already has a unique code
        </Tooltip>
      );

      const reloadToolpi = (props) => (
        <Tooltip id="button-tooltip" {...props}>
         refresh to topup again
        </Tooltip>
      );

        const generateUniqueCode = () => {
                const isValidInput = /^[1-9]\d{3,}$/.test(totaltopup);

               
                if (isValidInput) {
                  const randomDigits = Math.floor(Math.random() * 900)+100;
                  const formattedTotal = parseFloat(`${totaltopup}`)+parseFloat(`${randomDigits}`);
                  setUniqueCode(formattedTotal);
                  setTopup(formattedTotal);
                  setIsButtonDisabled(true);
                  setIsButtonDisabledTopup(false)
                } else {
                    settotalamoutnt(true);
                }
        };
      
    useEffect(() => {
        // Anda dapat menambahkan logika tambahan jika diperlukan
        // Contoh: Memuat data dari server

        // Misalnya, ini adalah simulasi pengambilan data yang memakan waktu
        setTimeout(() => {
            setIsLoading(false); // Langkah 2: Setel isLoading menjadi false setelah halaman selesai dimuat
        }, 900); // Menggunakan setTimeout untuk simulasi saja (2 detik).

        // Jika Anda ingin melakukan pengambilan data dari server, Anda dapat melakukannya di sini dan kemudian mengatur isLoading menjadi false setelah data berhasil dimuat.
    }, []); // Kosongkan array dependencies untuk menjalankan efek ini hanya sekali saat komponen dimuat.


    useEffect(() => {
      if (copied) {
        setTimeout(() => {
          setCopied(false);
        }, 2000);
      }
    }, [copied]);

    useEffect(() => {
        if (copied2) {
          setTimeout(() => {
            setCopied2(false);
          }, 2000);
        }
      }, [copied2]);
  
    const handleCopy = () => {
      navigator.clipboard.writeText(text);
      setCopied(true);
    };

    const handleCopy2 = () => {
        navigator.clipboard.writeText(totaltopup);
        setCopied2(true);
      };

    const handleTransfer = (e) => {
        const file = e.target.files[0];
        if (file) {
            const fileNameParts = file.name.split('.');
            const fileExtension = fileNameParts[fileNameParts.length - 1].toLowerCase();
            const allowedExtensions = ['jpg', 'jpeg', 'png'];
            const maxFileSizeInBytes = 5 * 1024 * 1024; // 5 MB

            if (!allowedExtensions.includes(fileExtension)) {
                alert('Only image files with formats png, jpg, or jpeg are allowed!');
                e.target.value = ''; // Mengosongkan input file
            } else if (file.size > maxFileSizeInBytes) {
                alert('File size must not exceed 5 MB!, otherwise contact admin');
                e.target.value = ''; // Mengosongkan input file
            } else {
                setTrf(file);
                if (file) {
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        setSelectedImage(reader.result);
                    };
                    reader.readAsDataURL(file);
                    }
            }
        }
    };
    

    const storePost = async (e) => {
        e.preventDefault();

        setIsLoading(true);
        const formData = new FormData();
        formData.append('totaltopup', totaltopup);
        formData.append('image', imgtrf);
        Inertia.post('/agent/wallet/topup', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
                window.location.reload();
                setIsLoading(false);
            },
        });
    }
    const reloadPage = () => {
        window.location.reload();
      };
  return (
    <>
    <Layout agent={agent} page={url}>
    {isLoading ? (
                <div className="container">
                    <div className="loading-container">
                        <div className="loading-spinner"></div>
                        <div className="loading-text">Loading...</div>
                    </div>
                </div>// Langkah 3: Tampilkan pesan "Loading..." saat isLoading true
            ) : (
                <>
                 <div className="container">
                    <div className="d-flex">
                        <span>
                        <img onerror="this.onerror=null; this.src='https://arcturus.my.id/logo/system/1695599539.png';" style={{width:'40px',margin:'5px'}} src={setting.logo_image} alt={setting.logo_image}/>
                       
                        </span>
                        <span>
                            <h1 className='my-3'>Arcturus Pay</h1>
                        </span>
                    </div>
                    
            <div className="row">
                <div className="col-lg-6">
                    <div className="card mb-3">
                        <div className="card-header">
                            <div className="d-flex justify-content-between">
                            <h3>
                                Saldo
                            </h3>
                            <span style={{fontSize:'18px',fontWeight:'700'}} className='badge badge-secondary mb-3'>{formatRupiah(agent.saldo ? agent.saldo : 0)}</span> 
                            </div>
                         
                        </div>
                        <div className="card-body">
                            <div className="row">
                                <div className="col-lg">
                                {session.success && (
                                            <div className="alert alert-success border-0 shadow-sm rounded-3">
                                                {session.success}
                                            </div>
                                        )}
                                {copied && <div className="alert alert-success">Copied!</div>}
                                {copied2 && <div className="alert alert-success">Copied!</div>}
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-lg-6">
                                 <p style={{fontWeight:'700'}}> <img src="https://www.bca.co.id/-/media/Feature/Card/List-Card/Tentang-BCA/Brand-Assets/Logo-BCA/Logo-BCA_Biru.png"
                                        style={{width:'50px'}} alt=""/>(PT Surya Langit Biru)</p> 
                                </div>
                                <div className="col-lg-6">
                                    <div className="d-flex mb-3">
                                    <span className='form-control'>{text}</span>
                                    <a href="#" className='btn btn-light' onClick={handleCopy}><i className='fa fa-copy'></i></a>
                                    </div>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-lg-12">
                                    <form onSubmit={storePost}>
                                        <div className="form-group mb-3">
                                            {pleaseamount && <>
                                            <p className='badge badge-danger'>please input total amount</p>
                                            </>}
                                            <input required type="number" className='form-control mb-2' onChange={handleTopupChange} placeholder='total top up...'/>
                                            <OverlayTrigger
                                            placement="top"
                                            delay={{ show: 250, hide: 400 }}
                                            overlay={renderTooltip}
                                            >
                                            <button
                                                className="btn btn-success mb-2"
                                                type="button"
                                                onClick={generateUniqueCode}
                                                disabled={isButtonDisabled}
                                            >
                                                Click for Unique Code
                                            </button>
                                            </OverlayTrigger>
                                            <OverlayTrigger
                                            placement="bottom"
                                            delay={{ show: 250, hide: 400 }}
                                            overlay={reloadToolpi}
                                            >
                                            <button
                                                className="btn btn-outline-light mb-2 mx-1"
                                                type="button"
                                                onClick={reloadPage}
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0 0 30 30">
                                                <path d="M 15 3 C 12.031398 3 9.3028202 4.0834384 7.2070312 5.875 A 1.0001 1.0001 0 1 0 8.5058594 7.3945312 C 10.25407 5.9000929 12.516602 5 15 5 C 20.19656 5 24.450989 8.9379267 24.951172 14 L 22 14 L 26 20 L 30 14 L 26.949219 14 C 26.437925 7.8516588 21.277839 3 15 3 z M 4 10 L 0 16 L 3.0507812 16 C 3.562075 22.148341 8.7221607 27 15 27 C 17.968602 27 20.69718 25.916562 22.792969 24.125 A 1.0001 1.0001 0 1 0 21.494141 22.605469 C 19.74593 24.099907 17.483398 25 15 25 C 9.80344 25 5.5490109 21.062074 5.0488281 16 L 8 16 L 4 10 z"></path>
                                                </svg>
                                            </button>
                                            </OverlayTrigger>
                                            {uniqueCode && (
                                                <>
                                                <p className='text-danger' style={{fontWeight:'700'}}>transfer below value please:</p>
                                                 <div className="d-flex mb-3">
                                                   <span className='badge badge-primary' style={{fontWeight:'700',fontSize:'20px'}}>{formatRupiah(uniqueCode)}</span>
                                                    <a href="#" className='btn btn-light' onClick={handleCopy2}><i className='fa fa-copy'></i></a>
                                                </div>
                                                </>
                                                  
                                            )}
                                        </div>
                                        <div className="form-group mb-3">
                                        <label for="image">Upload bank transfer receipt</label>
                                        <OverlayTrigger
                                            placement="top"
                                            delay={{ show: 250, hide: 400 }}
                                            overlay={renderTooltip2}
                                            >
                                            <input type="file" onChange={handleTransfer} className='form-control' required disabled={isButtonDisabledTopup}/> 
                                            
                                            </OverlayTrigger>
                                            <p className="text-danger" style={{fontWeight: '700', fontSize: '13px'}}>The image must be in
                                        PNG, JPG, or JPEG format, The image size cannot exceed 2MB.</p>
                                        </div>
                                       
                                        <div className="form-group">
                                        {selectedImage && (
                                                                    <div className="mb-3">
                                                                    <img
                                                                        src={selectedImage}
                                                                        alt="Selected"
                                                                        style={{ maxWidth: '200px', maxHeight: '200px' }}
                                                                    />
                                                                    </div>
                                                                )}
                                        </div>
                                         <div className="form-group">
                                         <OverlayTrigger
                                            placement="top"
                                            delay={{ show: 250, hide: 400 }}
                                            overlay={renderTooltip2}
                                            >
                                             <button disabled={isButtonDisabledTopup} type='submit' className='btn btn-primary'>top up</button>
                                             </OverlayTrigger>
                                         </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-lg-6">
                    <div className="card mb-5">
                        <div className="card-header">
                            Manual Transfer to BCA
                        </div>
                        <div className="card-body">
                            <ol>
                                <li> verify by ADMIN maximum up to 3 hours depending on transaction traffic or contact WA admin for immediately respond</li>
                                <li>ADMIN verification when we do TOP UP and IF your saldo is still available for the next transaction, no more ADMIN verification needed</li>
                            </ol>
                        </div>
                    </div>
                </div>
                </div>
                <div className="row">
                <div className="col-lg-6">
                    <div className="card">
                        <div className="card-header">
                        <h3>history</h3> 
                        </div>
                        <div className="card-body">
                        {history.map((item, index) => (
                                <>
                                <div key={index} >
                                    <div  className="card mb-3 rounded border-0 shadow">
                                        <div className="card-body">
                                            <p>Date : {formatDate(item.created_at)}</p>
                                            <span className={item.status == 'success' ? `badge badge-success` : `badge badge-warning`}>{item.status}</span>
                                            <p>{formatRupiah(item.saldo_master)}</p>
                                            {item.type_transaction == 'PAYMENT-SALDO' ? (
                                                <>
                                                 <p style={{fontWeight:'700',color:'#eb5151'}}>- {formatRupiah(item.saldo_add_minus)}</p>
                                                </>
                                            ):(
                                                <>
                                                 <p style={{fontWeight:'700',color:'#32cf76'}}>+ {formatRupiah(item.saldo_add_minus)}</p>
                                                </>
                                            )}
                                           
                                            <p>{formatRupiah(item.total_saldo)}</p>
                                            <p>{item.type_transaction}</p>
                                        </div>
                                    </div>
                                </div>
                                </>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </>
            )}
       
    </Layout>
    </>
  )
}