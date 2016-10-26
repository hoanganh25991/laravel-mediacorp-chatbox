<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;

class ExcelController extends Controller
{
    public function load(Request $req){
        if($req->method() == 'GET'){
            return view('excel.load');
        }


        if($req->file('excel_file')->isValid()){
            //store upload file
            $excelFile = $req->file('excel_file');
            $fileName = $excelFile->getClientOriginalName();
            $uploadedFilePath = $excelFile->storeAs('definition', $fileName);

            //excel_parsed
            $excelParsed = $req->get('excel_parsed');

            //save excelParsed to database
            $conversation = Conversation::where('name', $fileName)->first();
            if(!$conversation)
                $conversation = new Conversation();

            $conversation->fill([
                'name' => $fileName,
                'content' => $excelParsed
            ]);

            $conversation->save();

            //just for pretty notification
            $excelParsed = json_decode($excelParsed, true);

            return compact('excelParsed', 'uploadedFilePath');
        }
    }
}
