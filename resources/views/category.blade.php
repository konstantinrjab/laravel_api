@extends('layouts.app')

@section('content')

    <!-- Display Validation Errors -->

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Category {{$category->name}}</h2>
                <form action="/items" method="post">
                    <input type="hidden" name="category" value="{{$category->id}}">
                    <input type="submit" value="View Category Items">
                </form>
            </div>
        </div>
        <div class="row">
            @foreach($category as $key => $value)
                <div class="col-12">
                    <p>
                        {{$key}}: {{($value == false ? 'false' : $value)}}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

@endsection