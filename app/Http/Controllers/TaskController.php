<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getAll(){
    	$tasks = Task::all();
    	return $tasks;
    }

    public function add(Request $request){
    	$task=Task::create($request->all());
    	return $task;
    }

    public function get($id){
    	$task=Task::find($id);
    	return $task;
    }

    public function edit($id,Request $request){

    	$task=$this->get($id);
    	$task->fill($request->all())->save();
    	return $task;
    }
    public function delete($id){
    	$task=$this->get($id);
    	$task->delete();
    	return $task;
    }
}
