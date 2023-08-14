<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>

<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">

    <h1>Booking Confirmation</h1>
    <p>Dear {{ $booking->users->first_name }} {{ $booking->users->last_name }},</p>
    <p>booking id : {{ $booking->booking_code }}</p>

        <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px rgb(216, 114, 18);">
            <thead>
            <tr>
                <th style="text-align:left;">
                    <img
                        src="{{ $booking->vendor->logo_img }}"
                        alt="arcturus.com"
                        style="height: 20px; max-width: 100%; width: 30px;"
                        height="50"
                        width="157"
                    />
                </th>
                <th style="text-align:center;"> 
                        <img
                        src="{{ $setting->logo_image }}"
                        alt="arcturus.com"
                        style="height: 20px; max-width: 100%; width: 30px;"
                        height="50"
                        width="157"
                    />
                </th>
                <th style="text-align:right;">
                    <img
                        src="{{ $agent->logo_img }}"
                        alt="arcturus.com"
                        style="height: 20px; max-width: 100%; width: 30px;"
                        height="50"
                        width="157"
                    />
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="height:35px;"></td>
            </tr>
            <tr>
                <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
                <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Booking status</span><b style="color:green;font-weight:normal;margin:0">{{ $booking->booking_status }}</b></p>
                <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Booking ID</span> {{ $booking->booking_code }}</p>
                {{-- <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Order amount</span> Rs. 6000.00</p> --}}
                </td>
            </tr>
            <tr>
                <td style="height:35px;"></td>
            </tr>
            <tr>
                <td style="width:50%;padding:20px;vertical-align:top">
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">Guest Name</span> {{$booking->first_name}} {{$booking->last_name}}</p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span> {{$booking->email}}</p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Phone</span> {{$booking->phone}}</p>
                </td>
                <td style="width:50%;padding:20px;vertical-align:top">
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Address</span> {{$booking->address_line1}}</p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Night</span> {{$booking->night}}</p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Check in</span> {{$booking->checkin_date}}</p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Check Out</span> {{$booking->checkout_date}}</p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Total Guest</span> {{$booking->total_guest}}</p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Total Guest</span> {{$booking->total_guest}}</p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Deposit Policy</span> {{$contract->deposit_policy}}</p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Cenclellation Policy</span> {{$contract->cencellation_policy}}</p>
                </td>
            </tr>
           
           
            </tbody>
            <tfooter>
            <tr>
                <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
                <strong style="display:block;margin:0 0 10px 0;">Regards</strong> {{$booking->vendor->vendor_name}}<br> {{$booking->vendor->address_line1}},{{$booking->vendor->city}},{{$booking->vendor->country}}<br><br>
                <b>Phone:</b> {{$booking->vendor->phone}}<br>
                <b>Email:</b> {{$booking->vendor->email}}
                </td>
            </tr>
            </tfooter>
        </table>
    
</body>
</html>
