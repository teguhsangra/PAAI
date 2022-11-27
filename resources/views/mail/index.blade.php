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
    <p>Hai, Admin PAAI.or.id !</p>
    <br>
    <p>Pemberitahuan member baru dengan detail sebagai berikut :</p>
    <br>
    <table>
        <tr style="vertical-align: top;">
            <td width="50%">Nomor PAAI : </td>
            <td width="50%">{{ $member->code }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td width="50%">Nomor AAJI : </td>
            <td width="50%">{{ $member->aaji }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td width="50%">Nama : </td>
            <td width="50%">{{ $member->name }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td width="50%">Perusahaan Asuransi : </td>
            <td width="50%">{{ $member->company_name }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td width="50%">Email : </td>
            <td width="50%">{{ $member->email }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td width="50%">Nomor Telepon : </td>
            <td width="50%">{{ $member->phone }}</td>
        </tr>
    </table>
    <br>
    <table>
        <tr style="background-color: #385660 !important;color: #FFF;">
            <td colspan="5">Detail order</td>
        </tr>
        <tr>
            <td width="20%"><b>Order Code</b></td>
            <td width="15%"><b>Product</b></td>
            <td width="15%"><b>Mulai tanggal</b></td>
            <td width="25%"><b>Sampai tanggal</b></td>
            <td width="25%"><b>Total</b></td>
        </tr>
        <tr>
            <td>{{ $order->code }}</td>
            <td>{{ $order->product->name }}</td>
            <td>{{ date('j F Y', strtotime($order->start_date)) }}</td>
            <td>{{ date('j F Y', strtotime($order->end_date)) }}</td>
            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
        </tr>
    </table>
    <br>
    <ol>
        <li>Silahkan login melalui paai.or.id dan login menggunakan email dan password sesuai dengan tanggal lahir
            (yyyymmdd)</li>
        <li>Konfirmasi telah perpanjang dan member baru ada link telegram Join grup member di
            https://t.me/+Vkel57McodoPR96F</li>
    </ol>
    <br>
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
