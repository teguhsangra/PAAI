<!DOCTYPE html>
<html>

<body
    style="-webkit-text-size-adjust: none;
background-color: #ffffff;
color: #718096;
height: 100%;
line-height: 1.4;
margin: 0;
padding: 0;
width: 100% !important;">
    <p>Hai, Member PAAI.or.id !</p>
    <br>
    <p>Pemberitahuan member baru dengan detail sebagai berikut :</p>
    <p>
        {{ $booking->member->name }}
        <br>
        {{ date('j F Y', strtotime($booking->start_date)) }} - {{ date('j F Y', strtotime($booking->end_date)) }}
        <br>
        {{ $booking->member->email }}
    </p>
    <br>
    <ol>
        <li>Silahkan login melalui paai.or.id dan login menggunakan email dan password sesuai dengan tanggal lahir
            (yyyymmdd)</li>
        <li>Konfirmasi telah perpanjang dan member baru ada link telegram Join grup member di
            https://t.me/+Vkel57McodoPR96F</li>
    </ol>
    <br>
    <p>Pemberitahuan kepada anggota PAAI, bahwa akun anda telah Diaktifkan.</p>
    <p>Silahkan mengakses halaman PAAI anda dengan klik tombol dibawah ini</p>
    <a href="{{ url('login') }}" target="_blank" rel="noopener"
        style="-webkit-text-size-adjust: none;
        border-radius: 4px;
        color: #fff;
        display: inline-block;
        overflow: hidden;
        text-decoration: none;
        background-color: #484abb;
        border-bottom: 8px solid #484abb;
        border-left: 18px solid #484abb;
        border-right: 18px solid #484abb;
        border-top: 8px solid #484abb;">Login
        here</a>
    <br>
    <p>Regards,
        <br>
        Web Registration <br>
        paai.or.id
    </p>
</body>

</html>
