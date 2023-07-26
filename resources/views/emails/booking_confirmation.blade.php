<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>

    <h1>Booking Confirmation</h1>
    <p>Dear {{ $booking->users->first_name }} {{ $booking->users->last_name }},</p>
    <p>Your booking with booking ID: {{ $booking->id }} has been confirmed.</p>
    <p>Details:</p>
    <ul>
        <li>Booking Date: {{ $booking->booking_date }}</li>
        <li>Booking Status: {{ $booking->booking_status }}</li>
        <li>Guest Name: {{ $booking->first_name }} {{ $booking->last_name }}</li>
        <li>Guest Email: {{ $booking->email }}</li>
        <li>Check in: {{ $booking->checkin_date }}</li>
        <li>Check out: {{ $booking->checkout_date }}</li>
        <li>night: {{ $booking->night }}</li>
        <li>total room: {{ $booking->total_room }}</li>
        <li>Total Payment: Rp. {{ number_format($booking->price, 0, ',', '.')}}</li>
        <!-- Tambahkan data lain dari tabel booking sesuai kebutuhan -->
    </ul>
    <p>Thank you for your booking.</p>
</body>
</html>
