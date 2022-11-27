<table class="table table-border table-hover">
    <thead>
        <tr>
            <th>Nomor PAAI</th>
            <th>Nomor AAJI</th>
            <th>Nama</th>
            <th>Perusahaan</th>
            <th>Email</th>
            <th>Nomor Telepon</th>
            <th>Tanggal Lahir</th>
            <th>Berlaku Hingga</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($member as $item)
            <tr>
                <td class="cell">{{ $item->code }}</td>
                <td class="cell">{{ $item->aaji }}</td>
                <td class="cell">{{ $item->name }}</td>
                <td class="cell">{{ $item->company_name }}</td>
                <td class="cell">{{ $item->email }}</td>
                <td class="cell">{{ $item->phone }}</td>
                <td class="cell">{{ date('j F Y', strtotime($item->birth_date)) }}</td>
                <td class="cell">{{ date('j F Y', strtotime($item->expired_date)) }}</td>
                <td class="cell">
                    @if ($item->expired_date <= date('Y-m-d'))
                        Not Active
                    @else
                        Active
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
