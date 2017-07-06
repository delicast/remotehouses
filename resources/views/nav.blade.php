
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header ">

            <a class="navbar-brand " href="/">HouseHolds</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li class="scrollnavbar"><a href="/index#digitize">START DIGITIZING!</a></li>
                <li class="scrollnavbar"><a href="/index#progress">PROGRESS</a></li>
                <li class="scrollnavbar"><a href="/index#tutorial">TUTORIAL</a></li>
                <li class="scrollnavbar"><a href="/index#about">PROJECT</a></li>
@if (Auth::guest())
    <li><a href="{{ url('/login') }}">Login</a></li>
    <li><a href="{{ url('/register') }}">Register</a></li>
@else
    @if (Auth::user()->projects()->find($project->id))
        @if (Auth::user()->projects()->find($project->id)->pivot->level_id ==2)
            <li class=""><a href="/manage/admin/{{$project->id}}">ADMIN</a></li>
        @endif

    @endif
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