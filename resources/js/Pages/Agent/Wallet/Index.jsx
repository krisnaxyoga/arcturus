//import React
import React,{ useState, useEffect } from 'react';

//import layout
import Layout from '../../../Layouts/Agent';

//import Link
import { Link, usePage } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ props, session, agent,history,setting }) {
    const { url } = usePage();
    const [copied, setCopied] = useState(false);
    const [text, setText] = useState("2027999995");
    const [totaltopup, setTopup] = useState('');
    const [imgtrf,setTrf] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    
    const [selectedImage, setSelectedImage] = useState(null);

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
  
    const handleCopy = () => {
      navigator.clipboard.writeText(text);
      setCopied(true);
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
                                            <input required type="number" className='form-control' onChange={(e)=>setTopup(e.target.value)} placeholder='total top up...'/>
                                        </div>
                                        <div className="form-group mb-3">
                                            <input type="file" onChange={handleTransfer} className='form-control' required/>
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
                                             <button type='submit' className='btn btn-primary'>top up</button>
                                         </div>
                                    </form>
                                </div>
                            </div>
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