<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th colspan="4" class="text-center align-middle">
                    {{$rfcmonit}} - {{$nommonit}}
                </th>
            </tr>
            <tr>
                <th colspan="4" class="text-center align-middle">
                    Facturación por cliente - {{$fechamonit}}
                </th>
            </tr>
            <tr>
                <th class="text-center align-middle">RFC</th>
                <th class="text-center align-middle">Razón social</th>
                <th class="text-center align-middle"># Fact. Emitidas</th>
                <th class="text-center align-middle">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($infomonit as $datamonit)
                {!! $datamonit !!}
            @endforeach
        </tbody>
    </table>
</body>
