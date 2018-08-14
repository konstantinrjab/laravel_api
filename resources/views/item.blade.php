@extends('layouts.app')

@section('content')

    <!-- Display Validation Errors -->

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3>{{$item->name}}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <img src="http://www.giftsnpromotions.com/images/no-image-large.png"
                     alt="">
            </div>
            <div class="col-6">
                <?php echo '<pre>'; dump($item); echo '</pre>'; ?>
            </div>
        </div>
    </div>

@endsection