<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .heading{
            text-align: center;
            border: none;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th colspan="5" class="heading" style="text-align: center"><h2>Posts Created on {{ $from->format('d-m-Y') }}</h2></th>
        </tr>
        <tr>
            <th>S.No</th>
            <th>Created By</th>
            <th>Title</th>
            <th>Description</th>
            <th>Created At</th>
        </tr>
        @php $no = 1 @endphp
        @foreach($data as $post)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $post->email }}</td>
            <td>{{ $post->title }}</td>
            <td>{!! $post->description !!}</td>
            <td>{{ date('h:i A', strtotime($post->created_at)) }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
