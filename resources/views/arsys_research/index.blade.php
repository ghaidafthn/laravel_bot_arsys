<!-- resources/views/arsys_research/index.blade.php -->

<h1>Arsys Research List</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Supervisor</th> <!-- Adjust the column header as needed -->
            <!-- Add more columns as needed -->
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($researches as $research)
            <tr>
                <td>{{ $research->id }}</td>
                <td>{{ $research->title }}</td>
                <td>
                    {{$supervisors->supervisor_id}}
                </td>
                <!-- Add more columns as needed -->
                <td><a href="{{ route('arsys-research.show', $research->id) }}">View Details</a></td>
            </tr>
        @endforeach
    </tbody>
</table>