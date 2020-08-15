<table>
    <thead>
        <tr>
            <th>Codigo</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            @foreach ($header as $col)
                <th>{{$col}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $dato)
            <tr>
                <td>{{$dato['codigo']}}</td>
                <td>{{$dato['nombres']}}</td>
                <td>{{$dato['apellidos']}}</td>
                @foreach ($header as $col)
                    <td>{{$dato[$col]}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>