//import React
import React, { useState, useEffect } from 'react';
//import layout
import Layout from '../../../Layouts/Vendor';
import Form from 'react-bootstrap/Form';
//import Link
import { CKEditor } from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';


export default function Index({ session, props, attr,roomtype,vendor }) {

    const [roomtypeid, setRoomType] = useState('');
    const [near, setNear] = useState('');
    const [ratecode, setRagecode] = useState('');
    const [ratedesc, setRateDesc] = useState('');
    const [image, setImage] = useState(null);
    const [selectedImage, setSelectedImage] = useState(null);

    const [allowed, setAllowed] = useState('');
    const [images, setImages] = useState([]);
    const [selectedImages, setSelectedImages] = useState([]);

    const [adult, setMaxAdult] = useState('');
    const [service, setService] = useState('');
    const [content, setDescription] = useState('');
    const [extrabed, setExtrabed] = useState('');
    const [size, setSize] = useState('');
    const [selectAll, setSelectAll] = useState(false);

    const [selectedValues, setSelectedValues] = useState([]);
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        // Anda dapat menambahkan logika tambahan jika diperlukan
        // Contoh: Memuat data dari server

        // Misalnya, ini adalah simulasi pengambilan data yang memakan waktu
        setTimeout(() => {
            setIsLoading(false); // Langkah 2: Setel isLoading menjadi false setelah halaman selesai dimuat
        }, 1000); // Menggunakan setTimeout untuk simulasi saja (2 detik).

        // Jika Anda ingin melakukan pengambilan data dari server, Anda dapat melakukannya di sini dan kemudian mengatur isLoading menjadi false setelah data berhasil dimuat.
    }, []); // Kosongkan array dependencies untuk menjalankan efek ini hanya sekali saat komponen dimuat.


    const [checkboxItems, setCheckboxItems] = useState(attr.map((item) => ({ label: item.name, checked: false })));

    const handleCheckboxChange = (index) => {
        const updatedCheckboxItems = [...checkboxItems];
        updatedCheckboxItems[index].checked = !updatedCheckboxItems[index].checked;
        setCheckboxItems(updatedCheckboxItems);

        setSelectedValues((prevSelectedValues) => {
            if (updatedCheckboxItems[index].checked) {
                return [...prevSelectedValues, updatedCheckboxItems[index].label];
            } else {
                return prevSelectedValues.filter((value) => value !== updatedCheckboxItems[index].label);
            }
        });
    };

    const handleSelectAllChange = () => {
        setCheckboxItems(prevItems => {
          const updatedCheckboxItems = prevItems.map(item => ({
            ...item,
            checked: !selectAll,
          }));

          setSelectedValues(updatedCheckboxItems.reduce((selected, item) => {
            if (!selectAll) {
              return [...selected, item.label];
            } else {
              return selected.filter(value => value !== item.label);
            }
          }, []));

          setSelectAll(!selectAll);
          return updatedCheckboxItems;
        });
      };

    const handleDescChange = (event, editor) => {
        setDescription(editor.getData());
    };

    const handleServiceChange = (event, editor) => {
        setService(editor.getData());
    };

    const handleNearChange = (event, editor) => {
        setNear(editor.getData());
    };

    const handleFileChange = (e) => {

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
                setImage(file);
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

    const handleImageUpload = (e) => {
        const selectedImages = Array.from(e.target.files);
        console.log(selectedImages.length,"LENGS")
        // Periksa format dan ukuran file sebelum membuat URL
        const invalidFiles = selectedImages.filter((file) => {
            if (!file.type.match('image/(png|jpg|jpeg)')) {
                alert('Only image files with formats png, jpg, or jpeg are allowed!');
                e.target.value = '';
                return true; // File ini tidak valid
            }
    
            // if (file.size > 5 * 1024 * 1024) {
            //     alert('File size must not exceed 5 MB!');
            //     e.target.value = '';
            //     return true; // File ini tidak valid
            // }

            return false; // File ini valid
        });
    
        // Jika ada file yang tidak valid, hentikan pemrosesan
       if (selectedImages.length > 3) {
                alert('You can only upload a maximum of 3 images.');
                e.target.value = '';
                return true;
            }

        if (invalidFiles.length > 0 ) {
            e.target.value = '';
            return;
        }
    
        // Buat URL hanya untuk file yang memenuhi syarat
        const imagesArray = selectedImages.map((file) => {
            return URL.createObjectURL(file);
        });
    
        setImages(selectedImages);
        setSelectedImages(imagesArray);
    };

    const handleImageDelete = (index) => {
        setSelectedImages(prevImages => prevImages.filter((_, i) => i !== index));
        setImages(prevImages => prevImages.filter((_, i) => i !== index));
    };

    const storePost = async (e) => {
        e.preventDefault();

        setIsLoading(true);

        const formData = new FormData();
        formData.append('ratecode', ratecode);
        formData.append('ratedesc', ratedesc);
        selectedValues.forEach((adp, index) => {
            formData.append(`facilities[${index}]`, adp);
        });

        formData.append('near', near);
        formData.append('service', service);
        formData.append('size', size);
        formData.append('roomtypeid', roomtypeid);
        formData.append('adult', adult);
        formData.append('extra_bed', extrabed);

        formData.append('allowed', allowed);
        images.forEach((image, index) => {
            formData.append(`gallery[${index}]`, image);
        });

        formData.append('feature_image', image);
        formData.append('content', content);

        Inertia.post('/room/store', formData, {
            onSuccess: () => {
                // Lakukan aksi setelah gambar berhasil diunggah
                setIsLoading(false);
            },
        });
    }

    return (
        <>
            <Layout page={'/room/index'} vendor={vendor}>
            {isLoading ? (
                <div className="container">
                    <div className="loading-container">
                        <div className="loading-spinner"></div>
                        <div className="loading-text">Loading...</div>
                    </div>
                </div>// Langkah 3: Tampilkan pesan "Loading..." saat isLoading true
            ) : (
                // Tampilan halaman Anda yang sebenarnya
                <>
                <div className="container">
                    <div className="row">
                        <h1>Create Room Type</h1>
                        {session.success && (
                            <div className="alert alert-success border-0 shadow-sm rounded-3">
                                {session.success}
                            </div>
                        )}
                        <div className="col-lg-12">
                            <Tabs
                                defaultActiveKey="home"
                                id="fill-tab-example"
                                className="mb-3"
                                fill
                            >
                                <Tab eventKey="home" title="Room Type">
                                    <div className="col-lg-12">
                                        <form onSubmit={storePost}>
                                            <div className="card">
                                                <div className="card-body">
                                                    <div className="row">
                                                        {/* <div className="col-lg-3">
                                                            <div className="mb-3 ">
                                                                <label htmlFor="">Category</label>
                                                                <select required className='form-control' name="" id="" onChange={(e) => setRoomType(e.target.value)}>
                                                                    <option value="">-select category-</option>
                                                                    {roomtype.map((item) => (
                                                                    <>
                                                                    <option key={item.id} value={item.id}>{item.name}</option>
                                                                    </>
                                                                    ))}
                                                                </select>
                                                            </div>
                                                        </div> */}
                                                        <div className="col-lg-3">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Code</label>
                                                                <input required onChange={(e) => setRagecode(e.target.value)} type="text" className="form-control" id="exampleFormControlInput1" placeholder="Room Code" />
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-5">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Room Type Description</label>
                                                                <input required onChange={(e) => setRateDesc(e.target.value)} type="text" className="form-control" id="exampleFormControlInput1" placeholder="Room Description" />
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-4">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Room allotment</label>
                                                                <input required onChange={(e) => setAllowed(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Room allotment" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="row">
                                                        <div className="col-lg-3">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Feature Image</label>
                                                                <input onChange={handleFileChange} type="file" className="form-control" id="exampleFormControlInput1" placeholder="Feature Image" />
                                                            </div>
                                                            <div className="row">
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
                                                        </div>
                                                        <div className="col-lg-3">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">max Extra bed</label>
                                                                <input required onChange={(e) => setExtrabed(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Max Extra Bed" />
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-3">
                                                            <div className="mb-3 ">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Max Person</label>
                                                                <input required onChange={(e) => setMaxAdult(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="max adult" />
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-3">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Size</label>
                                                                <input required onChange={(e) => setSize(e.target.value)} type="number" className="form-control" id="exampleFormControlInput1" placeholder="Size Room" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="row">
                                                        <div className="col-lg-6">
                                                            <label htmlFor="">Amenities</label>
                                                            <label className="form-label mx-5">
                                                                <input
                                                                    className='form-check-input'
                                                                    type="checkbox"
                                                                    checked={selectAll}
                                                                    onChange={handleSelectAllChange}
                                                                />
                                                                All
                                                            </label>
                                                            <div className="row">
                                                                {checkboxItems.map((item, index) => (
                                                                    <div key={index} className="col-3 mb-3">
                                                                        <label className="form-label mx-3">
                                                                            <input
                                                                                className='form-check-input'
                                                                                type="checkbox"
                                                                                checked={item.checked}
                                                                                onChange={() => handleCheckboxChange(index)}
                                                                            />
                                                                            {item.label}</label>
                                                                    </div>
                                                                ))}
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-6">
                                                            <div className="mb-3">
                                                                <label htmlFor="exampleFormControlInput1" className="form-label">Gallery</label>
                                                                <input onChange={handleImageUpload} type="file" multiple className="form-control" id="exampleFormControlInput1" placeholder="Feature Image" />
                                                            </div>
                                                            <div className="mb-3 row">
                                                            {selectedImages.map((imageUrl, index) => (
                                                                <div key={index} className="col-4 mb-3">
                                                                    <img
                                                                    src={imageUrl}
                                                                    alt={`Selected ${index}`}
                                                                    style={{ maxWidth: '200px', maxHeight: '200px' }}
                                                                    />
                                                                    <button type="button"
                                                                            className="btn btn-danger btn-sm"
                                                                            style={{
                                                                            position: 'absolute',
                                                                            top: '5px',
                                                                            right: '5px',
                                                                            padding: '0',
                                                                            width: '20px',
                                                                            height: '20px',
                                                                            borderRadius: '50%',
                                                                            display: 'flex',
                                                                            alignItems: 'center',
                                                                            justifyContent: 'center',
                                                                            cursor: 'pointer',
                                                                            }}
                                                                            onClick={() => handleImageDelete(index)}
                                                                        >
                                                                            X
                                                                        </button>
                                                                </div>
                                                                ))}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <div className="row justify-content-between"> {/* Use justify-content-between to move the buttons to both ends */}
                                                            <div className="col-lg-auto">
                                                                <button type="submit" className="btn btn-primary">
                                                                    <i className="fa fa-save"></i> Save
                                                                </button>
                                                            </div>
                                                            <div className="col-lg-auto">
                                                                <Link href="/room/index" className="btn btn-danger">
                                                                    Cancel
                                                                </Link>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </Tab>
                                <Tab eventKey="profile" title="Description">
                                    <div className="row">
                                        <div className="col-lg-12">
                                            <form onSubmit={storePost}>
                                                <div className="card">
                                                    <div className="card-body">
                                                        <div className="mb-3">
                                                            <label className="form-label fw-bold">Description</label>
                                                              <CKEditor
                                                                editor={ClassicEditor}
                                                                data=""
                                                                   onReady={editor => {
                                                                    // You can store the "editor" and use when it is needed.
                                                                    console.log('Editor is ready to use!', editor);
                                                                }}
                                                                onChange={handleDescChange}
                                                                onBlur={(event, editor) => {
                                                                    console.log('Blur.', editor);
                                                                }}
                                                                onFocus={(event, editor) => {
                                                                    console.log('Focus.', editor);
                                                                }}
                                                            />
                                                        </div>

                                                        {/* <div className="mb-3">
                                                            <label htmlFor="">Service Information</label>
                                                            <CKEditor
                                                                editor={ClassicEditor}
                                                                data=""
                                                                  onReady={editor => {
                                                                    // You can store the "editor" and use when it is needed.
                                                                    console.log('Editor is ready to use!', editor);
                                                                }}
                                                                onChange={handleServiceChange}
                                                                onBlur={(event, editor) => {
                                                                    console.log('Blur.', editor);
                                                                }}
                                                                onFocus={(event, editor) => {
                                                                    console.log('Focus.', editor);
                                                                }}
                                                            />
                                                        </div>
                                                        <div className="mb-3">
                                                            <label htmlFor="">Hotel Nearby</label>
                                                            <CKEditor
                                                                editor={ClassicEditor}
                                                                data=""
                                                                 onReady={editor => {
                                                                    // You can store the "editor" and use when it is needed.
                                                                    console.log('Editor is ready to use!', editor);
                                                                }}
                                                                onChange={handleNearChange}
                                                                onBlur={(event, editor) => {
                                                                    console.log('Blur.', editor);
                                                                }}
                                                                onFocus={(event, editor) => {
                                                                    console.log('Focus.', editor);
                                                                }}
                                                            />
                                                        </div> */}
                                                        <hr />
                                                        <div className="row justify-content-between"> {/* Use justify-content-between to move the buttons to both ends */}
                                                            <div className="col-lg-auto">
                                                                <button type="submit" className="btn btn-primary">
                                                                    <i className="fa fa-save"></i> Save
                                                                </button>
                                                            </div>
                                                            <div className="col-lg-auto">
                                                                <button onClick={() => history.back()} className="btn btn-danger">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </Tab>
                            </Tabs>
                        </div>
                    </div>
                </div>
                </>
            )}
                
            </Layout>
        </>
    )
}
