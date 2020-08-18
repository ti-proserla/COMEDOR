<table>
    <thead>
        <tr>
            <th>Codigo</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Fecha</th>
            <th>Servicios</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $dato)
            <tr>
                <td>{{$dato->codigo}}</td>
                <td>{{$dato->nombres}}</td>
                <td>{{$dato->apellidos}}</td>
                <td>{{$dato->fecha}}</td>
                <td>{{$dato->nombre_servicio}}</td>
            </tr>
        @endforeach
    </tbody>
</table>