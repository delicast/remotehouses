
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header ">

            <a class="navbar-brand " href="#myPage">HouseHolds</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li class="scrollnavbar"><a href="/index#about">ABOUT</a></li>
                <li class="scrollnavbar"><a href="/index#projects">PROJECTS</a></li>
                <li class="scrollnavbar"><a href="/index#tutorial">TUTORIAL</a></li>
@if (Auth::guest())
    <li><a href="{{ url('/login') }}">Login</a></li>
    <li><a href="{{ url('/register') }}">Register</a></li>
@else
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{ url('/logout') }}">Logout</a></li>
        </ul>
    </li>
@endif

    </ul>
    </div>

    </div>
    </nav>