<table>
    <thead>
        <tr>
            <th>Codigo</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Planilla</th>
            <th>Fecha</th>
            <th>Servicio</th>
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
                <td>{{$dato['nombre_planilla']}}</td>
                <td>{{$dato['fecha']}}</td>
                <td>{{$dato['nombre_servicio']}}</td>
                @foreach ($header as $col)
                    <td>{{$dato[$col]}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>