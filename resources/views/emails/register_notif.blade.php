<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
    <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
      <thead>
        <tr>
          <th style="text-align:left;">Register Notification</th>
          <?php
            $sekarang = date("Y-m-d H:i:s");
            ?>
          <th style="text-align:right;font-weight:400;">{{$sekarang}}</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="height:35px;"></td>
        </tr>
        <tr>
          <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Type Vendor</span><b style="color:green;font-weight:normal;margin:0">{{ $member->type_vendor }}</b></p>
            <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Vendor Name</span> {{ $member->vendor_name }}</p>
          </td>
        </tr>
        <tr>
          <td style="height:35px;"></td>
        </tr>
        <tr>
          <td style="width:50%;padding:20px;vertical-align:top">
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">User Name</span> {{$data->first_name}} {{$data->last_name}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span> {{$data->email}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Address</span> {{$member->address_line1}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">country</span> {{$member->country}}</p>
          </td>
          
        </tr>
      </tbody>
      
    </table>
  </body>
  
</html>
