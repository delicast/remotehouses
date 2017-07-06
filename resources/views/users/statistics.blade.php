@extends('app')

@section('content')

    @include('nav_admin')

    <div id="permissions" class="container-fluid text-center">
        <h2>Users Statistics</h2>
        <h3>For All Projects </h3>

        <table class="table table-hover col-sm-12" style="text-align: left">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Images Digitized</th>
                <th>Houses Digitized</th>

            </tr>
            </thead>
            <tbody>

            @foreach($users as $user)

                <tr id='{{$user->id}}' onclick="selectRow({{$user->id}})">
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->grids_digitized()->count()}}</td>
                    <td>{{$user->households()->count()}}</td>


                </tr>

            @endforeach

            </tbody>
        </table>
    </div>

    <script>

        function selectRow(id){

            $('[class="success"]').attr("class","");
            $("#"+id).attr("class","success");
        };
    </script>
@endsection