<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class GeometryController extends Controller
{
    public  function get_cell_json($id){
        $sql=   "SELECT row_to_json(fc)
                 FROM ( SELECT 'FeatureCollection' As type, 
                 array_to_json(array_agg(f)) As features
                 FROM (SELECT 'Feature' As type
                    , ST_AsGeoJSON(ST_Transform(lg.polygon,3857))::json As geometry
                    , row_to_json((SELECT l FROM (SELECT id) As l
                      )) As properties
                   FROM grid  As lg  WHERE id='".$id."' ) As f )  As fc;";
    
        $json=DB::select($sql)[0]->row_to_json;
        return ($json);
    }
    public  function get_houses_done_on_project($id){
        $sql=   "SELECT row_to_json(fc)
                 FROM ( SELECT 'FeatureCollection' As type, 
                 array_to_json(array_agg(f)) As features
                 FROM (SELECT 'Feature' As type
                    , ST_AsGeoJSON(ST_Transform(lg.point,3857))::json As geometry
                    , row_to_json((SELECT l FROM (SELECT id) As l
                      )) As properties
                   FROM household  As lg  WHERE project_id='".$id."' ) As f )  As fc;";

        $json=DB::select($sql)[0]->row_to_json;
        return ($json);
    }
    public  function get_project_json($project_id)
    {
        //
        $sql=   "SELECT row_to_json(fc)
                 FROM ( SELECT 'FeatureCollection' As type, 
                 array_to_json(array_agg(f)) As features
                 FROM (SELECT 'Feature' As type
                    , ST_AsGeoJSON(ST_Transform(lg.polygon_area,3857))::json As geometry
                    , row_to_json((SELECT l FROM (SELECT id) As l
                      )) As properties
                   FROM project As lg WHERE id=$project_id  ) As f )  As fc;";

        $json=DB::select($sql)[0]->row_to_json;
        return ($json);
    }
    public  function get_project_extent()
    {
        //
        $sql=   "SELECT ST_XMax(ST_Transform(polygon_area,3857)) as x_max,
                     ST_XMin(ST_Transform(polygon_area,3857)) as x_min,
                     ST_YMax(ST_Transform(polygon_area,3857)) as y_max,
                     ST_Ymin(ST_Transform(polygon_area,3857)) as y_min 
                     FROM project;";


        $json=DB::select($sql);
        return ($json);
    }

}
