
@extends('app')

@section('content')

@include('nav')

<div class="jumbotron text-center" style="padding: 40px 25px 25px 25px;">
    <h1 style="font-size: 40px">Digitize HouseHolds</h1>
    <p>{{$project->name}}</p>
    @if ($project->logo_file)
        <a href='{{$project->project_url}}'><img src='/img/{{$project->logo_file}}' width="150"></a>
    @endif
    <br><br>
    <a href="#digitize"><button type="button" class="btn btn-danger">Start Digitizing!</button></a>

</div>
<!-- Container (Progress Section) -->
<div id="progress" class="container-fluid text-center progress-style">
    <h2 class="inverse">Progress - {{$percentagem}}%</h2>
    <br>
    <div class="row ">
        <div class="progress">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                 aria-valuenow="{{$percentagem}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$percentagem}}%">
                {{$percentagem}}% Complete
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-th-large logo-small"></span>
            <h4 class="inverse">{{$num_images_processed_with_repetitions}} digitizations done</h4>
            <!-- <p>Completing {{$num_images_processed}} images</p> -->
        </div>
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-home logo-small"></span>
            <h4 class="inverse">{{$num_households}} HouseHolds digitized</h4>
        </div>
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-globe logo-small"></span>
            <h4 class="inverse">{{$sq_km}} square quilometers covered</h4>
        </div>
    </div>
    <br><br>
    <div class="row slideanim">

        <div class="col-sm-4">
            <span class="glyphicon glyphicon-th logo-small"></span>
            <h4 class="inverse">{{$num_images_left_real}} digitizations left</h4>
            <p>To complete {{$num_images}} images</p>
        </div>

        <div class="col-sm-4">
            <span class="glyphicon glyphicon-leaf logo-small"></span>
            <h4 class="inverse">{{$perc_void}}% </h4>
            <p>of images processed have no households</p>
        </div>

        <div class="col-sm-4">
            <span class="glyphicon glyphicon-user logo-small"></span>
            <h4 class="inverse">{{$num_users}} Active Collaborators</h4>
        </div>
    </div>
</div>
<!-- Container (Digitize Section) -->

<div id="digitize" class="container-fluid text-center digitize">
    <div class="row">
        @include('map')
    </div>
</div>

<!-- Container (Tutorial Section) -->
<div id="tutorial" class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <h2>Tutorial</h2><br>
            <h4>It's simple. Navigate the satellite image in search of households inside the given frame. Point out their position and move to the next image.
            Most images will have no households, leave those empty and move on with more images.</h4><br>
            <p>You can Edit (move) the households before you submit the result by dragging them with the mouse.
            To remove a household, click on remove household button and click on the household you would like to remove.</p>
            <br><p>Having problems?</p><button class="btn btn-default btn-lg">Get in Touch</button>
        </div>
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-info-sign logo"></span>
        </div>
    </div>
</div>
<!-- Container (About Section) -->

<div id="about" class="container-fluid">
    <div class="row">
        @include('project_description')
    </div>
</div>

<script>
    $(document).ready(function(){
        // Add smooth scrolling to all links in navbar + footer link
        $(".scrollnavbar a, footer a[href='#myPage']").on('click', function(event) {

            // Prevent default anchor click behavior
            event.preventDefault();

            // Store hash
            var hash = this.hash;

            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 900, function(){

                // Add hash (#) to URL when done scrolling (default click behavior)
                window.location.hash = hash;
            });
        });

        $(window).scroll(function() {
            $(".slideanim").each(function(){
                var pos = $(this).offset().top;

                var winTop = $(window).scrollTop();
                if (pos < winTop + 600) {
                    $(this).addClass("slide");
                }
            });
        });
    })
</script>


@endsection








