<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Password;
use Auth;
use App\User;
use App\Log;
use App\System;
use App\Password_Reset;
use File;
// use Notification;

use Illuminate\Support\Facades\Storage;
use App\Notifications\PasswordResetNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request)
    {
        if(Auth::attempt(['email'=> $request->email,'password' => $request->password ])){
            $user = Auth::user(); 

            // $success['token'] =  $user->createToken('MyApp')-> accessToken; 

            $success['user'] = $user;

            return response()->json([
                'success' => $success
            ], $this->successStatus); 
        }
        else{
            return response()->json([
                'status'=>'Fail',
                'error' => 'UnAuthenticated',
                'message' => 'Invalid Credentials'
                ]); 
        }
    }

    public function register(Request $request)
    {
        if(User::where("email",$request->email)->first()){
            return response()->json([
                'status'=>'Fail',
                'error' => 'Email already taken!',
                'message' => 'Email already taken!'
                ]); 
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if(Auth::attempt(['email'=> $request->email,'password' => $request->password ])){
            $success['user'] = $user;

            return response()->json([
                'success' => $success
            ], $this->successStatus);
        }
        else{
            return response()->json([
                'status'=>'Fail',
                'error' => 'UnAuthenticated',
                'message' => 'Invalid Credentials'
                ]); 
        }
    }

    public function sendResetLink(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!isset($user->id)) {
            return response()->json([
                'status' => 'Fail',
                'error' => 'User Error',
                'message' => 'This User Email Does not exist'
            ]);
        }

        $query = $this->sendResetEmail($request);

        if ($query[0] == null) {
            return response()->json([
                'status' => 'success',
                'token' => $query[1],
                'message' => 'A reset link has been sent to your email address.'
            ]);
        } 
        else {
            return response()->json([
                'status' => 'Fail',
                'error' => 'Network Error',
                'message' => 'A Network Error occurred. Please try again.'
            ]);
        }
    }

    private function sendResetEmail($request)
    {
        $user = User::where('email', $request->email)->first();

        $password_broker = app(PasswordBroker::class); //so we can have dependency injection
        // $token = $password_broker->createToken($user);

        Password_Reset::insert([
            'email' => $user->email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);
        $reset = Password_Reset::where('email', $request->email)->first();

        $link = $request->url .'/password/reset/'.$reset->token;

        $result = $user->PasswordResetNotification($link);

        return [
            $result,
            $reset->token
        ];
    }

    public function updatePassword(Request $request)
    {
        $detail = Password_Reset::where('email', $request->email)->first();
        $DBtoken = $detail->token;

        if($DBtoken == $request->token){

            $user = User::where('email', $request->email)->first();

            $result = $user->update([
                'password' => $request->password,
            ]);

            if($result){
                Password_Reset::where('email',$request->email)->delete();

                return response()->json([
                    'status' => 'Success',
                    'error' => 'Password Updated',
                    'message' => 'Password has been succesfully Reset'
                ]);
            }
            else{
                return response()->json([
                    'status' => 'Fail',
                    'error' => 'Network Error',
                    'message' => 'A Network Error occurred. Please try again.'
                ]);
            }    
        }
        else{
            return response()->json([
                'status' => 'Fail',
                'error' => 'Token Error',
                'message' => 'Token did not match'
            ]);
        }

    }

    public function getSystems(Request $request)
    {
        $systems = System::where('user_id', $request->id)->get();

        if($systems != null){
            return response()->json([
                'status' => 'Success',
                'customers' => $systems,
                'message' => 'All Data Collected'
            ]);
        }
        else{
            return response()->json([
                'status' => 'Fail',
                'customers' => 0,
                'message' => 'Customers does not exist'
            ]);
        }
    }

    public static function ftp_sync($connection, $dir){ 

        $conn_id = $connection; 
        $result;

        if ($dir != ".") { 
            if (ftp_chdir($conn_id, $dir) == false) { 
                return false;
            } 
            if (!(is_dir($dir))) 
                mkdir($dir); 
            chdir ($dir); 
        } 
    
        $contents = ftp_nlist($conn_id, "."); 
        $files = [];

        foreach ($contents as $file) { 
    
            if ($file == '.' || $file == '..') 
                continue; 
    
            if (@ftp_chdir($conn_id, $file)) { 
                ftp_chdir ($conn_id, ".."); 
                UserController::ftp_sync($conn_id,$file); 
            } 
            else {
                $local_file = $file;
                $openLocalFile = fopen($local_file, 'w');

                ftp_fget($conn_id, $openLocalFile, $file, FTP_BINARY); 
                array_push($files, $file);
            }
        } 
    
        ftp_chdir ($conn_id, ".."); 
        chdir (".."); 
        return $files;
    } 

    public function storeSystem(Request $request)
    {
        $ftp_server = $request->formData['ftpserver'];
        $ftp_username = $request->formData['ftpuser'];
        $ftp_password = $request->formData['ftppass'];
        $ftp_port = $request->formData['ftpdoor'];
        $ftp_folder = $request->formData['ftpfolder'];

        // set up basic connection using port/door number
        $conn_id = ftp_connect($ftp_server, $ftp_port);
        
        if($conn_id != false){
            // login with username and password
            $login_result = ftp_login($conn_id, $ftp_username, $ftp_password);

            if($login_result){

                $ftp_folder = str_replace('public_html','',$ftp_folder);
//                $ftp_folder = str_replace("world","Peter","Hello world!");

                $ftp = Storage::createFtpDriver([
                    'host'     => $ftp_server,
                    'username' => $ftp_username,
                    'password' => $ftp_password,
                    'port'     => $ftp_port,
                    'root'     => $ftp_folder,
                    //'passive'  => '',
                    //'ssl'      => '',
                    //'timeout'  => '30',
                ]);

                $ftp->delete('/webpack.mix.js');
                $ftp->delete('/.env');
                $ftp->delete('/.htaccess');
                $ftp->delete('/artisan');
                $ftp->delete('/composer.json');
                $ftp->delete('/extract.php');
                $ftp->delete('/index.zip');
                $ftp->delete('/server.php');

                try{
                    $ftp->deleteDirectory('/vendor');
                $ftp->deleteDirectory('/storage');
                $ftp->deleteDirectory('/routes');
                $ftp->deleteDirectory('/resources');
                $ftp->deleteDirectory('/public');
                $ftp->deleteDirectory('/node_modules');
                $ftp->deleteDirectory('/database');
                $ftp->deleteDirectory('/config');
                $ftp->deleteDirectory('/bootstrap');
                $ftp->deleteDirectory('/app');

                }catch(\Exception $e){
                    
                }
                
                $files = collect($ftp->listContents($ftp_folder, false));
                
                if(true){
                    $store = true;
                    $file_local = Storage::disk('local')->get($request->formData['template'] . '/index.zip');
                    $file_ftp = $ftp->put('index.zip', $file_local);
                    $file_local2 = Storage::disk('local')->get('extract.php');
                    $file_ftp = $ftp->put('extract.php', $file_local2);
                                        

                     $payload = file_get_contents($request->formData['url'] . '/extract.php');
                    if($store){
                        $system = System::create([
                            'name' => $request->formData['name'],
                            'url' => $request->formData['url'],
                            'user_id'=> $request->formData['user_id'],
                            'type' => $request->formData['type'],
                            'template' => $request->formData['template'],
                            'ftp_server' => $request->formData['ftpserver'],
                            'ftp_door' => $request->formData['ftpdoor'],
                            'ftp_username' => $request->formData['ftpuser'],
                            'ftp_password' => $request->formData['ftppass'],
                            'ftp_folder' => $request->formData['ftpfolder'],
                        ]);
                        
                        Log::create([
                            'title' => 'Customer: '.$system->name.' Added!',
                            'user_id' => $request->formData['user_id'],
                            'description' => 'Customer name: '.$system->name.', App Url: '.$system->url.', Type: '.$system->type.', and template: '.$system->template.'.'
                        ]);
                
                        if($system){
                            return response()->json([
                                'status' => 'Success',
                                'message' => 'Customers Successfully Added!'
                            ]);
                        }
                        else{
                            return response()->json([
                                'status' => 'Fail',
                                'error' => 'Database Error',
                                'message' => 'Failed to add Customer'
                            ]);
                        }
                    }else{
                        //Storage Error
                        return response()->json([
                            'status' => 'Fail',
                            'error' => 'Storage Error',
                            'message' => 'Failed to store data collected from ftp server'
                        ]);
                    }
                }else{
                    //Content Load error
                    return response()->json([
                        'status' => 'Fail',
                        'error' => 'Empty Directory',
                        'message' => 'Directory does not exist OR contains no files',
                        'directory' => $ftp_folder
                    ]);
                }
            }else{
                //Login error
                return response()->json([
                    'status' => 'Fail',
                    'error' => 'FTP Authentication Error',
                    'message' => 'Invalid FTP Credentials'
                ]);
            }
        }else{
            //ftp connection error
            return response()->json([
                'status' => 'Fail',
                'error' => 'Connection Error',
                'message' => 'FTP Connection could not be established'
            ]);
        }       
    }


    public function getHistory(Request $request)
    {
        $histories = Log::where('user_id', $request->id)->orderBy('created_at','desc')->get();

        if($histories != null){
            return response()->json([
                'status' => 'Success',
                'histories' => $histories,
                'message' => 'All Data Collected'
            ]);
        }
        else{
            return response()->json([
                'status' => 'Fail',
                'histories' => 0,
                'message' => 'No History'
            ]);
        }
    }
}