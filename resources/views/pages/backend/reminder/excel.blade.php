<table class="table table-border table-hover">
    <thead>
        <tr>
            <th>Code</th>
            <th>Customer</th>
            <th>Customer Email</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
    </thead>
    <tbody>
    @foreach($bookings as $booking)
        <tr>
            <td class="cell">{{ $booking->code }}</td>
            <td class="cell">{{ $booking->member_name }}</td>
            <td class="cell">{{ $booking->member_email }}</td>
            <td class="cell">{{date("j F Y",strtotime($booking->start_date))}}</td>
            <td class="cell">{{date("j F Y",strtotime($booking->end_date))}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
