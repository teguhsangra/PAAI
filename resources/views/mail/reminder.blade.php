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
    </p>
    <br>
    <p>Cara Perpanjangan :</p>
    <ol>
        <li>Melakukan Pembayaran Regirstrasi ulang Tahunan sebesar Rp.100.000,-</li>
        <li>Mohon di Transfer ke rek : BCA a/n P A A I 5485 7999 98</li>
        <li>Setelah melakukan Pembayaran silahkan bapak dan ibu kirim bukti transfer ke admin +62 815 1718 9461</li>
        <li>cek masa aktif member dengan cara login di website </li>
        <li>konfirmasi telah perpanjang dan member baru ada link telegram Join grup member di
            https://t.me/+Vkel57McodoPR96F</li>
    </ol>
    <br>
    <p>Pemberitahuan kepada anggota PAAI, bahwa membership anda akan berakhir pada
        {{ date('j F Y', strtotime($booking->end_date)) }}</p>
    <p>Lakukan konfirmasi perpanjangan ke Admin PAAI.</p>
    <a href="https://wa.me/6281517189461" target="_blank" rel="noopener"
        style="-webkit-text-size-adjust: none;
        border-radius: 4px;
        color: #fff;
        display: inline-block;
        overflow: hidden;
        text-decoration: none;
        background-color: #48bb78;
        border-bottom: 8px solid #48bb78;
        border-left: 18px solid #48bb78;
        border-right: 18px solid #48bb78;
        border-top: 8px solid #48bb78;">Whatsapp
        me</a>
    <br>
    <p>Regards,
        <br>
        Web Registration <br>
        paai.or.id
    </p>
</body>

</html>
