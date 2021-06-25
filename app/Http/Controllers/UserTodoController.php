<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Validation\Rule;

class UserTodoController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $todos = $request->user()->todos()->get();
        return response()->json([
            'data' => $todos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newTodoData = $request->validate([
            'title' => 'required|string|unique:todos,title,NULL,id,user_id,' . auth()->user()->id,
            'description' => 'required|string|max:1000',
            'status' => ['required', Rule::in(Todo::status)]
        ]);
        $newTodoData["user_id"] = $request->user()->id;
        $newTodo = Todo::create($newTodoData);
        return response()->json([
            'data' => $newTodo
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return response()->json([
            'data' => $todo
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $updatedTodoData = $request->validate([
            'title' => 'string',
            'description' => 'max:1000',
            'user_id' => 'exists:users,id'
        ]);
        $todo->update($updatedTodoData);
        return response()->json([
            'success' => 'Update operation performed successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json([
            'success' => 'Done.'
        ], 200);
    }
}
