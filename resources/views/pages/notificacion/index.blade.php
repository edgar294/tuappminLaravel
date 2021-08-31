@extends('layouts.app')

@section('asset_init')
    
@endsection

@section('content')
    <notificaciones-component asset="{{ asset('/') }}" :auth="{{ Auth::user() }}"></notificaciones-component>
@endsection

@section('asset_end')
    
@endsection