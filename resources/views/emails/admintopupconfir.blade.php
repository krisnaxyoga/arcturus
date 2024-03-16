<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
</head>
<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
    <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
      <thead>
        <tr>
          <th style="text-align:left;">Top Up Notification</th>
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
            <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Check Transfer</span><a href="{{$data->url_payment}}">click here</a></p>
            <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Agent Name</span> {{ $data->users->first_name }} {{ $data->users->last_name }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Agent Email</span> {{ $data->users->email }}</p>
            <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Check in dashbord</span><a href="https://arcturus.my.id/admin/topup/index">go to dashboard</a></p>
          </td>
        </tr>
        <tr>
          <td style="height:35px;"></td>
        </tr>
      
      </tbody>
      
    </table>
  </body>
  
</html>
