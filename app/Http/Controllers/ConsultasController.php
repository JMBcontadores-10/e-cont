<?php

namespace App\Http\Controllers;
use CFDItoJson;
use Cleaner;

use Illuminate\Http\Request;

class ConsultasController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  public function index()
  {
    return view('consultas');
  }

  public function consultasi()
  {
    $host = 'localhost';
    $puerto = '27017';
    $conexion = new \MongoDB\Driver\Manager("mongodb://$host:$puerto");

    $filtrar = array();
    $options = array();

    $query = new \MongoDB\Driver\Query($filtrar, $options);
    $leerPreferencia = new \MongoDB\Driver\ReadPreference(\MongoDB\Driver\ReadPreference::RP_PRIMARY);

    $tabla= 'ingreso';
    // $col = Ingreso::where(['tipoco'=>$i])->get();
    $datos = $conexion->executeQuery("contarappv1.{$tabla}", $query, $leerPreferencia);
    return view('ingreso', compact('datos'));
  }

  public function consultase()
  {
    $host = 'localhost';
    $puerto = '27017';
    $conexion = new \MongoDB\Driver\Manager("mongodb://$host:$puerto");

    $filtrar = array();
    $options = array();

    $query = new \MongoDB\Driver\Query($filtrar, $options);
    $leerPreferencia = new \MongoDB\Driver\ReadPreference(\MongoDB\Driver\ReadPreference::RP_PRIMARY);
    $tabla= 'egreso';

    // $col = Ingreso::where(['tipoco'=>$i])->get();

    $datos = $conexion->executeQuery("contarappv1.{$tabla}", $query, $leerPreferencia);
    return view('egreso', compact('datos'));
  }

  public function consultasn()
  {
    $host = 'localhost';
    $puerto = '27017';
    $conexion = new \MongoDB\Driver\Manager("mongodb://$host:$puerto");

    $filtrar = array();
    $options = array();

    $query = new \MongoDB\Driver\Query($filtrar, $options);
    $leerPreferencia = new \MongoDB\Driver\ReadPreference(\MongoDB\Driver\ReadPreference::RP_PRIMARY);
    $tabla= 'nomina';

    // $col = Ingreso::where(['tipoco'=>$i])->get();

    $datos = $conexion->executeQuery("contarappv1.{$tabla}", $query, $leerPreferencia);
    return view('nomina', compact('datos'));
  }
  public function consultasp()
  {
    $host = 'localhost';
    $puerto = '27017';
    $conexion = new \MongoDB\Driver\Manager("mongodb://$host:$puerto");

    $filtrar = array();
    $options = array();

    $query = new \MongoDB\Driver\Query($filtrar, $options);
    $leerPreferencia = new \MongoDB\Driver\ReadPreference(\MongoDB\Driver\ReadPreference::RP_PRIMARY);
    $tabla= 'pago';

    // $col = Ingreso::where(['tipoco'=>$i])->get();

    $datos = $conexion->executeQuery("contarappv1.{$tabla}", $query, $leerPreferencia);
    return view('pago', compact('datos'));
  }

}
