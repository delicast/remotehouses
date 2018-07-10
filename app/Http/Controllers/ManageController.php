<?php

namespace App\Http\Controllers;


use App\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\Classes\shapefile;
require_once base_path().'/app/Classes/dbase_functions.php';
use App\Grids;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

ini_set('max_execution_time', 300);

class ManageController extends Controller
{
    //MANAGER FUNCIONS

    public  function admin($proj_id){

        $current_project=Auth::user()->projects()->find($proj_id);
        $projects_user_manage=Auth::user()->projects()->get();
        
        return view("manage/admin",compact('projects_user_manage','current_project'));
    }
    public  function new_project(){
        
        return view("manage/new_project");
    }
    public  function store_new_project(Request $request){
       
        $input=$request->all();
        
        $project_name=$input['project_name'];
        $project_desc=$input['project_desc'];
        $project_nature=$input['project_nature'];
        dd($input);
        
        dd(Input::file('project_shapefile'));
        
        $destinationPath = 'uploads';
        foreach ($_FILES['project_shapefile'] as $file) {
            echo '<li>' . $file . '</li>';
        }
            //$extension = Input::file($name)->getClientOriginalExtension(); // getting image extension
        $fileName = $recordnumber.'_'.$i.'.'.$extension; // renameing image
        Input::file($name)->move($destinationPath, $fileName); // uploading file to given path
    }
    
    //TO BE USED MANUALLY FROM HERE
    public function init_project(){

        if (Auth::user()->admin==0){
            return ("Not Authorised");
        }
        else{
            $project_id=13;
            $project_manager=1;
            $qlty_times=1;
            $description='Cheetah Conservation Botswana Site 1';
            $nature='Private';
            $name='Cheetah Conservation Botswana Site 1';
            $shortname='CCB_1'; //NAME FOR URL PROJECT
            $project_url='http://www.cheetahconservationbotswana.org/';
            $logo_file='CCBlogo.png';
            $shp_path_prj=base_path().'/public/uploads/CCB_1_area.shp';
            $shp_path_grid= base_path().'/public/uploads/CCB_1_grid_05min.shp';
            $area=1195;
            $pointtypes=array(0,1);  //0 for Households, 1 for Waterholes. Always array
            $grouping='CCB_1';

            //REMOVE IF GRID IS THE SAME!

            $this->load_grid($grouping,$shp_path_grid);
            
            $this->load_project($project_id,$qlty_times,$area,$description,$nature,$name,$shp_path_prj,$shortname,$project_url,$logo_file,$pointtypes);
            //$this->reload_project_shape($project_id,$shp_path_prj);
            //TARDA Mazo...mas de 30 sec

            $this->grid_to_project($project_id, $grouping);

            $this->user_to_project($project_id,$project_manager); //as manager

            return Redirect::to('manage/admin/'.$project_id);
        }

    }

    // USER TO PROJECT
    // Associates a user to a Project as Manager
    public  function user_to_project($project_id,$user_id){

        $level_id=2;

        User::find($user_id)->projects()->attach($project_id,['level_id' => $level_id]);
        User::find(12)->projects()->attach($project_id,['level_id' => $level_id]);

    }

    // GRID TO PROJECT
    // Associates a grid to a Project based on the grouping field of the grid table
    public  function grid_to_project($project_id,$grouping){

        DB::table('grid')->where('grouping',$grouping)->chunk(100, function($grids) {
            foreach ($grids as $grid) {

                DB::table('grid_project')->insert(
                    ['project_id' => 13, 'grid_id' => $grid->id]
                );
            }
        });

    }


    // LOAD_PROJECT
    // Loads a project from a shapefile and some parameters into the project table. For now I use it manually but the goal is that the
    // project manager can select the shapefile and load a project. Consider the shapefile has only 1 feature
    public  function load_project($project_id,$qlty_times,$area,$description,$nature,$name,$shp_path_prj,$shortname,$project_url,$logo_file,$pointtypes){


        try {
            $ShapeFile = new ShapeFile($shp_path_prj);
            $record = $ShapeFile->getRecord(SHAPEFILE::GEOMETRY_WKT);

            // Geometry
            $project=new Projects();
            // SHP Data
            $project->polygon_area=DB::raw("ST_GeomFromText('".$record['shp']."', 4326)");;
            $project->id=$project_id;
            $project->name=$name;
            $project->nature=$nature;
            $project->description=$description;
            $project->qlty_times_per_image= $qlty_times;
            $project->shortname=$shortname;
            $project->project_url=$project_url;
            $project->logo_file=$logo_file;
            $project->area=$area;
            $project->save();
            Projects::find($project_id)->pointtypes()->attach($pointtypes[0]);
            Projects::find($project_id)->pointtypes()->attach($pointtypes[1]);  //REMOVE IF ONLY 1 TYPE


        } catch (ShapeFileException $e) {
            exit('Error '.$e->getCode().': '.$e->getMessage());
        }

    }
    public function reload_project_shape($project_id,$shp_path_prj){
        try {
            $project=Projects::find($project_id);
            $ShapeFile = new ShapeFile($shp_path_prj);
            $record = $ShapeFile->getRecord(SHAPEFILE::GEOMETRY_WKT);
            $project->polygon_area=DB::raw("ST_GeomFromText('".$record['shp']."', 4326)");;
        } catch (ShapeFileException $e) {
            exit('Error '.$e->getCode().': '.$e->getMessage());
        }
    }

    // LOAD_GRID
    // Loads a grid from a shapefile into the grid table. For now I use it manually but the goal is that the
    // project manager can select the shapefile and load a grid
    public function load_grid($grouping,$shp_path_grid){


        try {

            //USE dbase functions to count the number of records on the shapefile
            //$basename = (substr($shp_path_grid, -4) == '.shp') ? substr($shp_path_grid, 0, -4) : $shp_path_grid;
            //$dbf_file = $basename.'.dbf';
            //$db = dbase_open($dbf_file, 1);
           // $num_records = dbase_numrecords($db);
           // dd($num_records);


            $ShapeFile = new ShapeFile($shp_path_grid);

            $i=0;
            while (($record = $ShapeFile->getRecord(SHAPEFILE::GEOMETRY_WKT))&&($i<25000)) {


                if (Grids::find($record['dbf']['MRMONAD'])==null){

                    // Geometry
                    $cell=new Grids;
                    // SHP Data
                    $cell->polygon=DB::raw("ST_GeomFromText('".$record['shp']."', 4326)");
                    // DBF Data
                    $cell->id=$record['dbf']['MRMONAD'];
                    $cell->grouping=$grouping;
                    $cell->save();
                    $i++;
                }

            }


        } catch (ShapeFileException $e) {
            exit('Error '.$e->getCode().': '.$e->getMessage());
        }
    }
    
    
}
