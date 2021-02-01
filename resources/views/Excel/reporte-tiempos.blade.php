<table>
    <thead>
        <tr>
            <th>Codigo</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Tiempo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $dato)
            <tr>
                <td>{{ $dato->codigo }}</td>
                <td>{{ $dato->nombres }}</td>
                <td>{{ $dato->apellidos }}</td>
                <td>{{ $dato->entrada }}</td>
                <td>{{ $dato->salida }}</td>
                <td>{{ $dato->tiempo }}</td>
            </tr>
        @endforeach
    </tbody>
</table>