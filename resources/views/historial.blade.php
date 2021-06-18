@extends('layouts.app')

@section('content')

    @php
    $rfc = Auth::user()->RFC;
    $nombre = Auth::user()->nombre;
    @endphp

@endsection
