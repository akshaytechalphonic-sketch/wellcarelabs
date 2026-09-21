<table>
    <thead>
        <tr>
            <th>Sr.No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Submitted</th>
            <th>Status</th>
            <th>Message</th>
        </tr>
    </thead>

    <tbody>
        @foreach($inquiries as $i)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $i->name }}</td>
                <td>{{ $i->email }}</td>
                <td>{{ $i->phone }}</td>
                <td>{{ $i->created_at ? $i->created_at->format('d-m-Y h:i A') : '-' }}</td>
                <td>{{ ucfirst($i->status ?? 'Pending') }}</td>
                <td>{{ $i->message }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
