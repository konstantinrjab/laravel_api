@extends('layouts.app')

@section('content')

    <!-- Display Validation Errors -->

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Categories</h2>
            </div>
        </div>
        <div class="row">
            @foreach($categories as $category)
                <div class="card col-6 col-sm-4 col-md-3 col-lg-2">
                    <p>
                        <a href="categories/{{$category->id}}">{{$category->name}}</a>
                    </p>
                </div>
            @endforeach
        </div>
    </div>

@endsection