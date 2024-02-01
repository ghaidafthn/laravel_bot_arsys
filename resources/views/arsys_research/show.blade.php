<!-- resources/views/arsys_research/show.blade.php -->

@extends('layouts.app')  // Assuming you have a layout file

@section('content')
    <h1>Arsys Research Details</h1>

    <p>ID: {{ $research->id }}</p>
    <p>Title: {{ $research->title }}</p>
    <p>Abstract: {{ $research->abstract }}</p>
    <!-- Add more details as needed -->
@endsection
