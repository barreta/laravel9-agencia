<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Listado de contactos</h1>

    @foreach($contactos as $contacto)
        <h4>{{ $contacto['id'] }} - {{ $contacto['nombre']}} - {{ $contacto['apellido']}} </h4>
    @endforeach
</body>
</html>