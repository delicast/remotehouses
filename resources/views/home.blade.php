
@extends('app')

@section('content')

@include('nav_home')

<div class="jumbotron text-center" style="padding: 40px 25px 25px 25px;">
    <h1 style="font-size: 40px">House Digitization</h1>

</div>


<div id="projects" class="container-fluid">
    <div class="row">
        <div class="col-sm-8">
            <h2>Current Projects</h2>

            @foreach($projects as $project)
                <h3><span class="glyphicon glyphicon-chevron-right"></span>
                    <a href="/{{$project->shortname}}"> {{$project->name}}</a>
                </h3>

            @endforeach


            <br>
            <h2>Past Projects</h2><br>
        </div>

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
        <h2>About</h2>
        About -- In progress
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








