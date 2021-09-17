<?php

namespace App\Http\Controllers;

use App\Exports\TasksExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExportController extends Controller
{
    public function exportAsExcel(Request $request)
    {
        return Excel::download(new TasksExport($request->user()), 'tasks.xlsx');
    }

    public function exportAsCsv(Request $request)
    {
        return (new TasksExport($request->user()))->download('tasks.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
