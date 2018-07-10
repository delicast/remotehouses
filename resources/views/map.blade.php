
        <h2 class="inverse">Digitize! </h2>

        <div id="map" class="col-sm-8 map"  ></div>

        @if (Auth::guest())
            <div class="col-lg-3 col-lg-offset-1 alert-danger img-rounded" style="text-align: left; padding: 10px">
                <span class="glyphicon glyphicon-warning-sign"></span>
                You are a guest. You can experiment the digitization tool but no result will be saved.
                <br> To start collaborating, please <a href="/login">log in </a>
            </div>
            <div class="col-lg-3 col-lg-offset-1" style="height: 50px"></div>
        @elseif(Auth::user()->projects()->find($project->id))
            <div class="col-lg-3 col-lg-offset-1 alert-success img-rounded" style="text-align: left; padding: 10px">
                <span class="glyphicon glyphicon-blackboard"></span>
                You are authorised to digitize records for this project!
            </div>
            <div class="col-lg-3 col-lg-offset-1" style="height: 50px"></div>
        @else
            <div class="col-lg-3 col-lg-offset-1 alert-danger img-rounded" style="text-align: left; padding: 10px">
                <span class="glyphicon glyphicon-warning-sign"></span>
                You are not authorised to digitize on this project. You can experiment the digitization tool but no result will be saved.
                <br> To collaborate, please contact the project manager </a>
            </div>
            <div class="col-lg-3 col-lg-offset-1" style="height: 50px"></div>

        @endif


        <div id="addHouseholds" class="btn btn-default btn-lg col-lg-3 col-lg-offset-1 " onclick="addHouseholds()">
            <span class="glyphicon glyphicon-home"></span> Insert Households
        </div>
        <div class="col-sm-4" style="height: 20px"></div>

        @if ($project->pointtypes()->where('pointtype.id', 1)->exists())
            <div id="addWaterhole" class="btn btn-info btn-lg col-lg-3 col-lg-offset-1 " onclick="addWaterhole()">
                <span class="glyphicon glyphicon-tint"></span>  Insert Waterhole
            </div>
        @endif
        <div class="col-sm-4" style="height: 20px"></div>

        <div id="removeHouseholds" class="btn btn-warning btn-lg col-lg-3 col-lg-offset-1 " onclick="removeHouseholds()">
            <span class="glyphicon glyphicon-remove"></span> Remove Point
        </div>

        <div class="col-sm-4" style="height: 40px"></div>
        <div id="recenter" class="btn btn-danger btn-lg col-lg-2 col-lg-offset-2 " onclick="recenterMap()">
            <span class="glyphicon glyphicon-screenshot"></span> Recenter Map
        </div>
        <div class="col-sm-4" style="height: 40px"></div>


        <form action="/map" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="grid_id" value="{{$grid->id}}">
            <input type="hidden" name="project_id" value="{{$project->id}}">
            <input type="hidden" name="image_source" value="BING_MAPS_AERIAL">
            <div id="inputs_houses"></div>

            <button type="button" class="btn col-lg-3 col-lg-offset-1 btn-success btn-lg" data-toggle="modal" data-target="#confirmationModal" onclick="create_confirmation()">
                <span class="glyphicon glyphicon-ok"></span> Done!
            </button>

                <!-- Modal Confirmation  -->
            <div id="confirmationModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                            <h4 class='modal-title' id='modal-title'></h4>
                        </div>
                        <div class="modal-body" id='modal-body'>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                            <input type="submit" onclick="array_points_form()" class="btn btn-success" value="Done! Next Image">

                        </div>
                    </div>

                </div>
            </div>
        </form>

        <div class="col-sm-12" id="mouse-position"></div>

        <script>
            var grid_id = "{{$grid->id}}";
            var project_id = "{{$project->id}}";
        </script>
        <script src="{{ asset('/js/map_digitize.js') }}"></script>
