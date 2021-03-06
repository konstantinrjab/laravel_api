@extends('layouts.app')

@section('content')

    <!-- Display Validation Errors -->
    <div class="container">
        <div class="row text-center pt-3 pb-5">
            <div class="col-12">
                <h2>Category {{$category->name}}</h2>
                <h3>Category Items</h3>
            </div>
        </div>
        <div class="row">
            @foreach($category->items as $item)
                <div class="col-4">
                    <div class="card mt-2 mb-2">
                        <a href="/items/{{$item->id}}">
                            <img class="card-img-top"
                                 src="http://www.giftsnpromotions.com/images/no-image-large.png"
                                 alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    Name: {{($item->name)}}</li>
                                <li class="list-group-item">Created
                                    at: {{$item->created_at}}</li>
                            </ul>
                            <p class="card-text">Some quick example text to
                                build on the card title and make up the bulk of
                                the card's content.</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection