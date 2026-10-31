<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SPK WP | Data Hasil Akhir</title>
</head>
<body>
    <style type="text/css">
        table {
            font-family: sans-serif;
            color: #454545;
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid rgb(135, 135, 135);
            padding: 8px 20px;
        }

        h1, h2 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 25px;
        }
        
        h2 {
            font-weight: normal;
            font-size: 20px
        }
    </style>    
    <center>
        <h1>Hasil Akhir Perankingan WP</h1>
    </center>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Alternatif</th>
                <th>Nilai V</th>
                <th>Rank</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penilaians as $penilaian)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $penilaian->alternatif->namaAlternatif }}</td>
                <td>{{ round($penilaian->nilai_v, 4) }}</td>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>