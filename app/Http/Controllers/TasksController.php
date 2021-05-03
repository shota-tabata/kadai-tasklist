<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Task;


class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     //タスク一覧
    public function index()
    {
        if (\Auth::check()) {
            
            $tasks = Task::all();
            
            return view('tasks.index', [
                'tasks' => $tasks,
                ]);
        }else{
            return view('welcome');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     //作成ページ
    public function create()
    {
        $task = new Task;
        
        
        return view('tasks.create', [
            'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     //
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',
            
            ]);
        
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = auth()->id();
        $task->save();
      
        return redirect('/');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     //詳細ページ
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        return view('tasks.show', [
            'task' => $task,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     //編集ページ
    public function edit($id)
    {

        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }else{
            
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',

            ]);

        $task = Task::findOrFail($id);
        
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            
            $task->delete();
        }
        
        return redirect('/');
    }
}
