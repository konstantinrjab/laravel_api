@extends('layouts.app')

@section('content')

    <!-- Display Validation Errors -->

    <?php var_dump($items); ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Categories</h2>
            </div>
        </div>
        <div class="row">
            {{--@foreach($items as $item)--}}
                {{--<div class="card col-6 col-sm-4 col-md-3 col-lg-2">--}}
                    {{--<p>--}}
                        {{--<a href="categories/{{$item->id}}">{{$item->name}}</a>--}}
                    {{--</p>--}}
                {{--</div>--}}
            {{--@endforeach--}}
        </div>
    </div>

@endsection