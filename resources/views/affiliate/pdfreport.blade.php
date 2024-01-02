<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
<style>
    body{margin-top:20px;
background-color: #f7f7ff;
}
#invoice {
    padding: 0px;
}

.invoice {
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px
}

.invoice header {
    padding: 10px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #0d6efd
}

.invoice .company-details {
    text-align: right
}

.invoice .company-details .name {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .contacts {
    margin-bottom: 20px
}

.invoice .invoice-to {
    text-align: left
}

.invoice .invoice-to .to {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .invoice-details {
    text-align: right
}

.invoice .invoice-details .invoice-id {
    margin-top: 0;
    color: #0d6efd
}

.invoice main {
    padding-bottom: 50px
}

.invoice main .thanks {
    margin-top: -100px;
    font-size: 2em;
    margin-bottom: 50px
}

.invoice main .notices {
    padding-left: 6px;
    border-left: 6px solid #0d6efd;
    background: #e7f2ff;
    padding: 10px;
}

.invoice main .notices .notice {
    font-size: 1.2em
}

.invoice table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px
}

.invoice table td,
.invoice table th {
    padding: 15px;
    background: #eee;
    border-bottom: 1px solid #fff
}

.invoice table th {
    white-space: nowrap;
    font-weight: 400;
    font-size: 16px
}

.invoice table td h3 {
    margin: 0;
    font-weight: 400;
    color: #0d6efd;
    font-size: 1.2em
}

.invoice table .qty,
.invoice table .total,
.invoice table .unit {
    text-align: right;
    font-size: 1.2em
}

.invoice table .no {
    color: #fff;
    font-size: 1.6em;
    background: #0d6efd
}

.invoice table .unit {
    background: #ddd
}

.invoice table .total {
    background: #0d6efd;
    color: #fff
}

.invoice table tbody tr:last-child td {
    border: none
}

.invoice table tfoot td {
    background: 0 0;
    border-bottom: none;
    white-space: nowrap;
    text-align: right;
    padding: 10px 20px;
    font-size: 1.2em;
    border-top: 1px solid #aaa
}

.invoice table tfoot tr:first-child td {
    border-top: none
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0px solid rgba(0, 0, 0, 0);
    border-radius: .25rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
}

.invoice table tfoot tr:last-child td {
    color: #0d6efd;
    font-size: 1.4em;
    border-top: 1px solid #0d6efd
}

.invoice table tfoot tr td:first-child {
    border: none
}

.invoice footer {
    width: 100%;
    text-align: center;
    color: #777;
    border-top: 1px solid #aaa;
    padding: 8px 0
}

@media print {
    .invoice {
        font-size: 11px !important;
        overflow: hidden !important
    }
    .invoice footer {
        position: absolute;
        bottom: 10px;
        page-break-after: always
    }
    .invoice>div:last-child {
        page-break-before: always
    }
}

.invoice main .notices {
    padding-left: 6px;
    border-left: 6px solid #0d6efd;
    background: #e7f2ff;
    padding: 10px;
}

@media print {
            #invoice {
                width: 1600px !important; /* Tetapkan lebar elemen cetak ke 1600px saat mencetak */
            }
        }
</style>
<style>
    ul.no-bullet {
        list-style: none; /* Menghilangkan tanda bullet */
        padding: 0; /* Menghilangkan padding */
    }

    ul.no-bullet li {
        margin: 0; /* Menghilangkan margin */
    }
</style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div id="loading-indicator" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>Loading...</div>
                </div>
                <div class="toolbar hidden-print">
                    <div class="text-end">
                        <button type="button" id="generate-pdf" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Download PDF</button>
                    </div>
                    <hr>
                </div>
                <div id="invoice">
                    <div class="invoice overflow-auto">
                        <div>
                            <header>
                                <div class="row">
                                    <div class="col">
                                        <a href="javascript:;">
                                            <img onerror="this.onerror=null; this.src='https://arcturus.my.id/logo/system/1695599539.png';" style="width: 40px" src="{{ $settings->logo_image }}" alt="{{ $settings->logo_image }}">
                                                    </a> <br>
                                                    <h2 class="name">arcturus</h2>
                                    </div>
                                    <div class="col company-details">
                                        <h2 class="name">
                                            <a target="_blank" style="text-decoration: none;" class="text-dark fw-bold" href="javascript:;">
                                        PT. Surya Langit Biru
                                        </a>
                                        </h2>
                                        <div>Bank Central Asia : 2027999995</div>
                                        <div>VAT ID : 40.905.322.0-915.000</div>
                                        <div>accounting@arcturus.my.id</div>
                                    </div>
                                </div>
                            </header>
                            <main>
                                <div class="row contacts justify-content-between">
                                    <div class="col-4 invoice-to">
                                        <h2 class="to">BILL TO:</h2>
                                        <div class="text-gray-light">{{ $data[0]->vendor->vendor_name }}</div>
                                        <div class="address">{{ $data[0]->vendor->address_line1 }}</div>
                                        <div class="email">
                                        </div>
                                    </div>
                                    <div class="col-4 invoice-details">
                                        <h1 class="invoice-id">INVOICE</h1>
                                        <div class="date">Invoice Period      : {{date('d/m/Y', strtotime($startdate))}} - {{date('d/m/Y', strtotime($startdate))}}</div>
                                        <div class="date">Invoice #     : {{'#'.Str::random(8);}}</div>
                                        <div class="date">Invoice Date        : {{ date('d/m/Y', strtotime(now())) }}</div>
                                    </div>
                                </div>
                                <?php
                                    $totalCommision = 0; // Inisialisasi total komisi

                                    foreach ($data as $item) {
                                        // Hitung komisi untuk setiap item
                                        $commision = is_null($item->total_ammount)
                                            ? $item->pricenomarkup * 0.025
                                            : $item->total_ammount * 0.025;

                                        $totalCommision += $commision; // Tambahkan komisi ke total
                                    }
                                    ?>
                                <table>
                                    <thead>
                                        <tr>
                                            {{-- <th class="text-left bg-primary text-light">HOTEL NAME</th> --}}
                                            {{-- <th class="text-right bg-primary text-light">BOOKING DATE</th> --}}

                                            <th class="text-right bg-primary text-light p-1">GUEST NAME</th>
                                            <th class="text-right bg-primary text-light p-1">BOOKING DATE</th>
                                            <th class="text-right bg-primary text-light p-1">CHECK IN</th>
                                            <th class="text-right bg-primary text-light p-1">CHECK OUT</th>
                                            <th class="text-right bg-primary text-light p-1">GUEST</th>
                                            <th class="text-right bg-primary text-light p-1">ROOM</th>
                                            <th class="text-right bg-primary text-light p-1">NIGHT</th>
                                            <th class="text-right bg-primary text-light p-1">RATE</th>
                                            <th class="text-right bg-primary text-light p-1">TOTAL REVENUE</th>
                                            <th class="text-right bg-primary text-light p-1">COMMISION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                        <tr>
                                            {{-- <td>{{ $item->vendor->vendor_name }}</td> --}}

                                            <td>  {{ $item->first_name }} {{ $item->last_name }}
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($item->booking_date)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->checkin_date)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->checkout_date)) }}</td>
                                            <td>{{ $item->total_guests }}</td>
                                            <td>{{ $item->total_room }}</td>
                                            <td>{{ $item->night }}</td>
                                            <td>Rp. {{ number_format((($item->pricenomarkup / $item->night) / $item->total_room) ?? '0', 0, ',', '.') }}</td>
                                            <td> @if (is_null($item->total_ammount))
                                                    Rp. {{ number_format($item->pricenomarkup ?? '0', 0, ',', '.')  }}
                                                @else
                                                    Rp. {{ number_format($item->total_ammount ?? '0', 0, ',', '.')  }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (is_null($item->total_ammount))
                                                Rp. {{ number_format(($item->pricenomarkup * 0.025) ?? '0', 0, ',', '.') }}
                                                @else
                                                Rp. {{ number_format(($item->pricenomarkup * 0.025) ?? '0', 0, ',', '.') }}
                                                @endif
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td colspan="2">TOTAL</td>
                                            <td>Rp. {{ number_format($totalCommision ?? '0', 0, ',', '.') }}</td> <!-- Menampilkan total komisi -->
                                        </tr>
                                    </tfoot>
                                </table>
                                {{-- <div class="thanks mt-1">Thank you!</div>
                                <div class="notices">
                                    <div>NOTICE:</div>
                                    <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                                </div> --}}
                            </main>
                            {{-- <footer>Invoice was created on a computer and is valid without the signature and seal.</footer> --}}
                        </div>
                        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                        <div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
    <script>
       document.getElementById("generate-pdf").addEventListener("click", function () {
        // Tampilkan loading indicator
        var loadingIndicator = document.getElementById("loading-indicator");
        loadingIndicator.style.display = "block";

        var printContent = document.getElementById("invoice");

        html2canvas(printContent, {
            windowWidth: 1600,
            width: 1600
        }).then(function (canvas) {
            var imgData = canvas.toDataURL('image/png');

            var docDefinition = {
                content: [
                    {
                        image: imgData,
                        width: 650,
                        pageBreak: 'after'
                    },
                    {
                        image: imgData,
                        width: 650,
                        absolutePosition: {x: 40, y: -750}
                    }
                ]
            };

            // Sembunyikan loading indicator setelah rendering selesai
            loadingIndicator.style.display = "none";

            pdfMake.createPdf(docDefinition).download('Report.pdf');
        });
    });
    </script>

</body>
</html>


