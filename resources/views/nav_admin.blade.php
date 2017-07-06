
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header ">



        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" href="/{{$current_project->shortname}}">{{$current_project->name}}
                    </a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="/manage/admin/{{$current_project->id}}">Change Project
                    </a>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Users
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class=""><a href="/manage/users/permissions/{{$current_project->id}}">Permissions</a></li>
                        <li class=""><a href="/manage/users/statistics/{{$current_project->id}}">Statistics</a></li>
                    </ul>
                </li>


            <!--<li class=""><a href="/manage/new_project">New Project</a></li>
                <li class=""><a href="/manage/results">Results</a></li>
                <li class=""><a href="/manage/quality">Quality Control</a></li>
                -->

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