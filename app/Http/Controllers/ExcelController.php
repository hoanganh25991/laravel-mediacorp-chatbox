<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function load(Request $req){
        if($req->method() == 'GET'){
            return view('excel.load');
        }


        if($req->file('excel_file')->isValid()){
            $excelFile = $req->file('excel_file');

//            $fileName = $req->get('file_name');
//            $fileName = $excelFile->_originalName;
            $fileName = $excelFile->getClientOriginalName();
//            dd($excelFile);
            $path = $excelFile->storeAs('definition', $fileName);

            return $path;
        }
    }
}
