@extends( 'layouts.plantilla')

    @section('contenido')

        <div class="alert col-8 shadow mx-auto">
            <form action="/proceso2" method="post">
                @csrf
                <input type="text" name="nombre" class="form-control">
                <button class="btn btn-dark">enviar</button>
            </form>
        </div>

    @endsection