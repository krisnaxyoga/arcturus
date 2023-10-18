<!DOCTYPE html>
<html>
<head>
    <title>Top Up Confirmation</title>
</head>
<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
    <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
      <thead>
        <tr>
          <th style="text-align:left;">ARCTURUS</th>
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
            <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Top up status</span><b style="color:green;font-weight:normal;margin:0">{{ $history->status }}</b></p>
          </td>
        </tr>
        <tr>
          <td style="height:35px;"></td>
        </tr>
       
        <tr>
            <td colspan="2" style="padding:15px;">
    
                <p style="font-size:14px;margin:0;padding:10px;border:solid 1px #ddd;font-weight:bold;">Total top-up : </span> Rp. {{ number_format($history->saldo_add_minus	, 0, ',', '.')}}</p>
             
              </td>
        </tr>
      </tbody>
      <tfooter>
        <tr>
            <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
            <strong style="display:block;margin:0 0 10px 0;">Regards</strong> Arcturus<br><br>
            </td>
        </tr>
        </tfooter>
    </table>
  </body>
  
</html>
