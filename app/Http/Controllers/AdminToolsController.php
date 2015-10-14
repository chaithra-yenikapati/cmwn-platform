<?php

namespace cmwn\Http\Controllers;
use cmwn\AdminTool;
use Illuminate\Support\Facades\Hash;
use cmwn\Http\Requests;
use cmwn\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use cmwn\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class AdminToolsController extends Controller
{
    public function uploadCsv(Request $request)
    {
        if (Request::isMethod('post')) {
            $validator = Validator::make(Input::all(), AdminTool::$uploadCsvRules);

            if ($validator->passes()) {
                $file = $request::get('yourcsv');
                //the files are stored in storage/app/*files*
                $output = Storage::put('yourcsv.csv', file_get_contents($file));
                    if($output){
                        return Redirect::to('admin/uploadcsv')->with('message', 'The following errors occurred')->withErrors
                        ('Your file has been successfully upload. You will receive email notification once the import is completed.');
                    }else {
                        return Redirect::to('admin/uploadcsv')->with('message', 'The following errors occurred')->withErrors
                        ('Something went wrong with your upload. Please try again.');
                    }
            }else{
                return Redirect::to('admin/uploadcsv')->with('message', 'The following errors occurred')->withErrors
                ($validator)->withInput();
            }

        }
        return view('admin/uploadcsv');
    }
}
