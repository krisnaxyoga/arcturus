<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
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
            <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Booking status</span><b style="color:green;font-weight:normal;margin:0">{{ $booking->booking_status }}</b></p>
            <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Booking ID</span> {{ $booking->booking_code }}</p>
          </td>
        </tr>
        <tr>
          <td style="height:35px;"></td>
        </tr>
        <tr>
          <td style="width:50%;padding:20px;vertical-align:top">
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">Travel Agent</span>
              {{$booking->users->vendors->vendor_name}} <br>
              <?php if($booking->users->vendors->affiliate == $booking->vendor->affiliate){?>
                <b style="color:green;font-weight:normal;margin:0"> powered by ARCTURUS </b>
                <?php } else { ?>
                    <?php if($booking->vendor->affiliate) { ?> 
                      <b style="color:green;font-weight:normal;margin:0"> powered by {{$affiliator->vendor_name}} </b>
                      <?php } ?>
                  <?php } ?>

              </p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">Guest Name</span> {{$booking->first_name}} {{$booking->last_name}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span> {{$booking->email}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Phone</span> {{$booking->phone}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Address</span> {{$booking->address_line1}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Check in</span> {{$booking->checkin_date}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Check Out</span> {{$booking->checkout_date}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Night</span> {{$booking->night}}</p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Total Guest</span> {{$booking->total_guests}}</p>
          </td>
          <td style="width:50%;padding:20px;vertical-align:top">
            
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Special Request</span> {{$booking->special_request}}</p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Deposit Policy</span> {!!$contract->deposit_policy!!}</p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Cenclellation Policy</span> {!!$contract->cencellation_policy!!}</p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Benefit Policy</span> {!!$contract->benefit_policy!!}</p>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="font-size:20px;padding:30px 15px 0 15px;">Room Type</td>
        </tr>
        <tr>
          <td colspan="2" style="padding:15px;">
            <?php foreach ($hotelbook as $key => $item) { ?>
              <table cellspacing="0" cellpadding="0" border="0" style="font-size: 14px; margin: 0; padding: 10px; border: solid 1px #ddd; font-weight: bold; width: 100%;">
                <tr>
                    <td style="width: 50%;"><span style="display: block; font-size: 13px; font-weight: normal;">{{ $item->total_room}} &nbsp; | &nbsp;{{ $item->room->ratedesc}}</span></td>
                    <td style="text-align: right;"><span style="font-size: 13px; font-weight: normal;">Rp. {{ number_format($item->pricenomarkup, 0, ',', '.')}} / Night</span></td>
                </tr>
            </table>
            <?php } ?>

            <p style="font-size:14px;margin:0;padding:10px;border:solid 1px #ddd;font-weight:bold;">Total amount : </span> Rp. {{ number_format($booking->pricenomarkup, 0, ',', '.')}}</p>
         
          </td>
        </tr>
      </tbody>
      <tfooter>
        <tr>
            <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
            <strong style="display:block;margin:0 0 10px 0;">Regards</strong> ARCTURUS<br><br>
            <b>Email:</b> info@arcturus.my.id
            </td>
        </tr>
        </tfooter>
    </table>
  </body>
  
</html>
