<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Management\College;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index(){
        return view('managements.app.index');
    } 
    
    public function assigmentIndex(){
        $colleges =College::get();
        return view('managements.assigments.index',compact('colleges'));
    }
}
