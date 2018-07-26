@extends('layouts.app')

@section('content')

    <!-- Display Validation Errors -->

    <div class="container">
        <div class="row">
            <div class="col-12">
                Categories
            </div>
        </div>
        <div class="row">
            @foreach($categories as $category)
                <div class="">{{$category}}</div>
            @endforeach
        </div>
    </div>

@endsection