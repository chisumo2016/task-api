<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TasksController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return TaskResource::collection(
            Task::all()
            //Task::where('user_id', Auth::user()->id)->get()
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request)
    {
        $request->validated($request->all());

        $task = Task::create([
            'user_id'       => Auth::user()->id,
            'name'          => $request->name,
            'description'   => $request->description,
            'priority'      => $request->priority,
        ]);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $task
     * @return TaskResource
     */
    public function show(Task $task)
    {
        return  $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : new TaskResource($task);
       // return  new TaskResource($task);
       // return  TaskResource::make($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $task
     * @return TaskResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Task $task)
    {
        if (Auth::user()->id !== $task->user_id){
            return $this->error('', 'You are not authorized to make this request',403);
        }

        $task->update($request->all());
        return  new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        return  $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) :$task->delete();
        //$task->delete();
        //return  response(null, 204);
    }

    private  function  isNotAuthorized($task)
    {
        if (Auth::user()->id !== $task->user_id){
            return $this->error('', 'You are not authorized to make this request',403);
        }
    }
}
