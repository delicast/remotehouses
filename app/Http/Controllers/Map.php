<?php

namespace App\Http\Controllers;

use App\GridsDigitizes;
use App\Households;
use Illuminate\Http\Request;

use App\Http\Requests;


use App\User;
use DB;
use App\Projects;
use Illuminate\Support\Facades\Redirect;
use Auth;



class Map extends Controller
{
    //
    public function index()
    {
        //Primeira volta, só imagens que nunca foram processadas

        return Redirect::to('index#digitize');

    }

    public function save_and_next( Request $request){

        $project_id=$request['project_id'];
        $project=Projects::find($project_id);
        $project_shortname=$project->shortname;


        if(Auth::user()->projects()->find($project_id)== null){
            return Redirect::to('index#digitize');
        }



        $user=Auth::id();
        $bad_quality=0;
        $num_houses=0;

        $request->ip;
        $grid_processed_id=$request['grid_id'];

        $image_source=$request['image_source'];
        $reason_void= $request['reason_void'];

        
        
        if ($reason_void=="bad_quality") {
            $bad_quality=1;
        }

        if ($houses=$request['house']) {
            $num_houses = (count($houses));
        }
        Auth::user()->grids_digitized()->attach($grid_processed_id, ['bad_quality' => $bad_quality,'num_houses' => $num_houses, 'project_id'=>$project_id ]);

        if ($houses=$request['house']) {
            foreach ($houses as $house){
                
                    $x_house=$house["x"];
                    $y_house=$house["y"];
                    $new_house=new Households();
                    $new_house->point=DB::raw("ST_GeomFromText('POINT({$x_house} {$y_house})', 4326)");
                    $new_house->user_id=$user;
                    $new_house->grid_digitize_id=GridsDigitizes::orderBy('created_at','desc')->first()->id;
                    $new_house->project_id=$project_id;
                    $new_house->pointtype=0;  //Type Household
                    $new_house->save();

                }
        }
        if ($waterholes=$request['waterhole']) {
            foreach ($waterholes as $waterhole){

                $x_house=$waterhole["x"];
                $y_house=$waterhole["y"];
                $new_house=new Households();
                $new_house->point=DB::raw("ST_GeomFromText('POINT({$x_house} {$y_house})', 4326)");
                $new_house->user_id=$user;
                $new_house->grid_digitize_id=GridsDigitizes::orderBy('created_at','desc')->first()->id;
                $new_house->project_id=$project_id;
                $new_house->pointtype=1;  //Type Waterhole
                $new_house->save();

            }
        }


        return Redirect::to($project_shortname.'#digitize');

    }






}
