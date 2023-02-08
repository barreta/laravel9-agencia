<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('home');
     return view('welcome');
});

Route::get('contactos', function () {

    $registros = [
        ['id' => 1, 'nombre' => 'Sergio', 'apellido' => 'Barragan'],
        ['id' => 2, 'nombre' => 'Roxana', 'apellido' => 'Bermudez'],
    ];

    return view('contactos', ['contactos' => $registros]);
});

Route::get('contacto/{slug}', function ($slug) {
    $post = $slug;
    return view('contacto', ['registro' => $slug]);
});



/// CURSO LARAVEL EducacionIT .........................

Route::get('/saludo', function () {
    return view('saludo');
});
//idem al anterior
Route::view('hola', 'saludo');

Route::get('/inicio', function () {
    return view('inicio');
});

Route::get('/form', function(){
    return view('formulario');
});

Route::post('/proceso2', function(){
    // captura el dato enviado
    // $nombre = $_GET['nombre'];
    // $nombre = request()->nombre;
    $nombre = request('nombre');

    return view('proceso', ['nombre' => $nombre]);
    
});

Route::get('/listaRegiones', function()
{
    $regiones = DB::select('SELECT idRegion, regNombre FROM regiones');

    return view('listaRegiones', ['regiones' => $regiones]);
});


##########  CRUD

Route::get('/regiones', function()
{
    $regiones = DB::select('SELECT idRegion, regNombre FROM regiones');

    return view('regiones', ['regiones' => $regiones]);
});

Route::get('/region/create', function()
{
    return view('regionCreate');
});

Route::post('/region/store', function()
{
   $regNombre = request('regNombre');
  
    try {
        DB::insert('insert into regiones (regNombre) values (:regNombre)', [$regNombre]);
        //redirección con mensaje OK
        return redirect('/regiones')
            ->with([
                        'mensaje' => 'Región '.$regNombre.' agregada correctamente.',
                        'css' => 'success'
                    ]);
    } 
    catch (\Throwable $th) 
    {
        //redirección con mensaje ERROR
        return redirect('/regiones')
            ->with([
                        'mensaje' => 'No se pudo agregar la región: '.$regNombre,
                        'css' => 'danger'
                    ]);
    }
});

Route::get('/regiones/edit/{id}', function($id)
{
    // $region = DB::select('SELECT idRegion, regNombre FROM regiones WHERE idRegion = :id', [ $id ]);

    $region = DB::table('regiones')->where('idRegion', $id)->first();

    return view('regionEdit', ['region' => $region]);
});

Route::patch('/region/update', function()
{
    $regNombre = request('regNombre');
    $idRegion = request('idRegion');

    try {
        //DB::update('update regiones set regNombre = :regNombre where idRegion = :idRegion', [$regNombre, $idRegion]);

        DB::table('regiones')
            ->where('idRegion', $idRegion)
            ->update([ 'regNombre' => $regNombre ]);

        //redirección con mensaje ERROR
        return redirect('/regiones')
            ->with([
                        'mensaje' => 'Región: '.$regNombre.' modificada correctamente.',
                        'css' => 'success'
                    ]);            
    } 
    catch (\Throwable $th) 
    {
        //redirección con mensaje ERROR
        return redirect('/regiones')
            ->with([
                        'mensaje' => 'No se pudo modificar: '.$regNombre,
                        'css' => 'danger'
                    ]);
    }
});


Route::get('/region/delete/{id}/{region}', function($id, $regNombre)
{
    // chequeamos si tiene un destino asignado a esa region
    // $cantidad = DB::select('SELECT 1 FROM destinos 
    //                           WHERE idRegion = :idRegion',
    //                            [ $id ]);

    // chequeamos si tiene un destino asignado a esa region
        $cantidad = DB::table('destinos')
                            ->where('idRegion', $id)
                            ->count();

        // $cantidad > 0
        if( $cantidad ){
            return redirect('/regiones')
            ->with([
                        'mensaje' => 'No se pudo eliminar la región: '.$regNombre.' ya que tiene destinos relacionados',
                        'css' => 'warning'
                    ]);
        }

    // retornamos vista de confirmación
    return view('regionDelete', 
                    [
                        'idRegion' => $id,
                        'regNombre' => $regNombre
                    ]
                );
});

Route::delete('/region/destroy', function()
{
    $regNombre = request('regNombre');
    $idRegion = request('idRegion');

    try{
        // DB::delete('delete regiones where idRegion = :idRegion', [$idRegion]);

        DB::table('regiones')->where('idRegion', $idRegion)->delete();

        //redirección con mensaje OK
        return redirect('/regiones')
            ->with([
                        'mensaje' => 'Región: '.$regNombre.' eliminada correctamente.',
                        'css' => 'success'
                    ]);        
    }
    catch (\Throwable $th) 
    {
        //redirección con mensaje ERROR
        return redirect('/regiones')
            ->with([
                        'mensaje' => 'No se eliminar modificar: '.$regNombre,
                        'css' => 'danger'
                    ]);
    }
});

######## CRUD de destinos

Route::get('/destinos', function()
{
    // $destinos = DB::select('SELECT idDestinos, destNombre, regNombre, destPrecio 
    //                 FROM destinos d
    //                 JOIN regiones r 
    //                 ON d.idRegion = r.idRegion');

    $destinos = DB::table('destinos as d')
                    ->join('regiones as r', 'd.idRegion', '=', 'r.idRegion')
                    ->get();

    //dd($destinos);
                    
    return view('destinos', ['destinos' => $destinos]);
});

Route::get('/destino/create', function()
{
    $regiones = DB::table('regiones')->get();
    return view('destinoCreate', ['regiones' => $regiones]);
});

Route::post('/destino/store', function()
{
    $destNombre = request('destNombre');
    $idRegion = request('idRegion');
    $destPrecio = request('destPrecio');
    $destAsientos = request('destAsientos');
    $destDisponibles = request('destDisponibles');


    try {
        /* Raw SQL */ 
        // DB::insert('insert into destinos 
        //                 (destNombre, idRegion, destPrecio, destAsientos, destDisponibles) 
        //             values 
        //                 (:destNombre , :idRegion , :destPrecio , :destAsientos , :destDisponibles)', 
        //                 [$destNombre, $idRegion, $destPrecio, $destAsientos, $destDisponibles]);

        DB::table('destinos')
            ->insert(
                [
                    'destNombre' => $destNombre,
                    'idRegion' => $idRegion,
                    'destPrecio' => $destPrecio,
                    'destAsientos' => $destAsientos,
                    'destDisponibles' => $destDisponibles
                ]
            );

        return redirect('/destinos')
            ->with([
                        'mensaje' => 'Destino: '.$destNombre.' agregada correctamente.',
                        'css' => 'success'
                    ]);   
    } catch (\Throwable $th) {
        return redirect('/destinos')
            ->with([
                        'mensaje' => 'No se pudo agregar el destino: '.$destNombre,
                        'css' => 'danger'
                    ]);   
    }
});