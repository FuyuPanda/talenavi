<?php

namespace App\Http\Controllers\Api;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;



class TaskController extends Controller
{
    //
    public function store(TaskRequest $request)
    {
        
         $task=Task::create($request->validated());  


            return response()->json([
            'message' => 'To do list has been successfuly created!',
            'todo' => $task,
            ],200);
    }

    public function export(Request $request)
    {
        $query = Task::query();
        if($request->has('title'))
        {
            $title = $request->title;
            $query->where('title','like','%'.$title.'%');
        }

        if($request->has('assignee'))
        {
            $assignee = explode(',',trim($request->assignee));
            $query->wherein('assignee',$assignee);
        }

        if($request->has('start'))
        {
            $start=$request->start;
            $query->where('dueDate','>=',$start);
        }

        if($request->has('end'))
        {
            $end = $request -> end;
            $query ->where('dueDate','<=',$end);
        }

        if($request->has('min'))
        {
            $min = $request->min;
            $query ->where('timeTracked','>=',$min);
        }

        if($request->has('max'))
        {
            $max = $request->max;
            $query->where('timeTracked','<=',$max);
        }

        if($request->has('status'))
        {
            $status = explode(',',trim($request->status));
            $query->wherein('status',$status);
        }

        if($request->has('priority'))
        {
            $priority = explode(',',trim($request->priority));
            $query->wherein('priority',$priority);
        }

        $tasks = $query->get(['title','assignee','dueDate','timeTracked','status','priority']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['title','assignee','dueDate','timeTracked','status','priority'], null, 'A1');

        foreach ($tasks as $i => $task) 
        {
            $sheet->fromArray(array_values($task->toArray()), null, 'A' . ($i + 2));
        }

        $row = count($tasks) + 3;
        $sheet->setCellValue("A{$row}", 'Total Time:');
        $sheet->setCellValue("B{$row}", $tasks->sum('timeTracked'));

        $row = $row+1;
        $sheet->setCellValue("A{$row}", 'Total To-Do:');
        $sheet->setCellValue("B{$row}", count($tasks));

        $writer = new Xlsx($spreadsheet);

        $file_name = 'todo.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(),$file_name);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$file_name.'');
        $writer->save('php://output');
        return response()->download($temp_file, $file_name)->deleteFileAfterSend(true);

        


    }

    public function chart(Request $request)
    {
        
    }


}
