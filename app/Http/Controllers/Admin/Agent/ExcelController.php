<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TemplateAgentExportClass;
use App\Imports\AgentImportClass; 
use App\Models\Agent; 


class ExcelController extends Controller
{
    public function downloadTemplate()
    {
        return Excel::download(new TemplateAgentExportClass, 'agent_import_template.xlsx');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        Excel::import(new AgentImportClass, $file,null, \Maatwebsite\Excel\Excel::XLSX);

        return redirect()->back()->with('success', 'Data imported successfully.');
    }
}
