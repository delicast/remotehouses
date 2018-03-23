<?php

namespace App\Http\Controllers;

use App\GridsDigitizes;
use App\Households;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\Grid_bicuar;
use App\User;
use App\Houses;
use App\Grids;
use App\Projects;
use DB;
use Auth;
use Symfony\Component\Routing\RequestContextAwareInterface;

class indexController extends Controller
{
    public  function test(){
        return view("oltest");
    }
    public  function home(){
        $projects=Projects::all();
        
        return view("home",compact('projects'));
    }

    public  function index($proj_name){

        
        //PEGAR PROJECTO DO SHORTNAME DO URL
        $project=Projects::where('shortname','=',$proj_name)->first();
        $project_id=$project->id;



        $times=$project->qlty_times_per_image;
        $grids_complete=GridsDigitizes::select('grid_id')->where('project_id',$project_id);

        //SUBquery: Grids already digitized by the user in that project (IF user autenticated)
        if (Auth::user())
        {
            $grids_complete= $grids_complete->where('user_id','=',Auth::id());
        }

        //SUBquery: Completed grids list: all grids id that have been processed more that X times
        $grids_complete=$grids_complete->groupBy('grid_id')->havingRaw('count(grid_id) >= '.$times)->get();


        // All grids that are not in both  lists of completed grids obtained previously, get the first of a random order

        //$grid=$project->grids()->whereNotIn('grid.id', $grids_complete)->orderByRaw('random()')->first();

        $grid_id= DB::select("SELECT grid_id FROM grid_project 
                            WHERE grid_id NOT IN (
                              SELECT grid_id  AS counta FROM grid_digitize WHERE project_id='$project->id' 
                              GROUP BY grid_id 
                              HAVING count(grid_id) >= '$times' )
                            AND grid_project.project_id='$project->id'
                            ORDER BY RANDOM()
                            LIMIT 1");

        if (count($grid_id)== 0){
            dd('THIS PROJECT IS FINISHED');
        };



        $grid= Grids::where('id',$grid_id[0]->grid_id)->first();

        //dd($grid);
        //$grid=$project->grids()->first();

        $num_images=$project->grids()->count();
        


        //Counts all grids digitized without repetition because of the Qlty_times_per_image parameter
        $grids_complete=GridsDigitizes::select('grid_id')->where('project_id',$project_id)->groupBy('grid_id')->havingRaw('count(grid_id) >= '.$times)->get();
        $num_images_processed= count($grids_complete);

        //Counts all grids digitized with repetitions because of the Qlty_times_per_image parameter
        $num_images_processed_with_repetitions=GridsDigitizes::where('project_id',$project_id)->count();
        $num_images_left_real= $num_images * $project->qlty_times_per_image -$num_images_processed_with_repetitions;
        
        $num_images_left=$num_images-$num_images_processed;
        $num_households=Households::where('project_id',$project_id)->count();
        $sq_km=$num_images_processed*0.06;

        $percentagem=$num_images_processed_with_repetitions*100/$num_images* $project->qlty_times_per_image;
        $percentagem=number_format($percentagem,2);

        $num_images_void=GridsDigitizes::where('project_id',$project_id)->where('num_houses',0)->count();
       
        if ($num_images_processed_with_repetitions==0){
            $perc_void=0;
        }else{
            $perc_void=number_format($num_images_void*100/$num_images_processed_with_repetitions,0);
        }
        
        $num_users=$project->users()->count();


        return view("index", compact('num_users','grid','num_images_left','num_images_processed','num_images_processed_with_repetitions','num_images','num_images_left_real','num_households','sq_km','percentagem','perc_void','project'));
    }
}
