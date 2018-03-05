<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Response;
use Hash;
use Exception;
use Config;
use \App\Models\User;
use \App\Models\Wedding;
use \App\Models\CreateFunction;
use Twilio\Rest\Client;
use Illuminate\Validation\Rule;

class CommonController extends Controller
{
    
    public function sign_up(Request $request){
        Log::info('CommonController----sign_up----'.print_r($request->all(),True));
        $timezone = $request->header('timezone');
        $mobile = $request->mobile;
        $password = Hash::make($request->password);
        $accessToken  = md5(uniqid(rand(), true));
        $otp = rand(100000,1000000);
        $device_token = $request->device_token;
        $device_type = $request->device_type;
        $user_type = $request->user_type;
        $validations = [
            'mobile' => 'required|unique:users',
            'password' => 'required|min:8',
            'device_token' => 'required',
            'device_type' => 'required|numeric',
            'user_type' => 'required|numeric',
        ];
        if($timezone){
            $this->setTimeZone($timezone);
        }

        $validator = Validator::make($request->all(),$validations);
        if($validator->fails()){
            $response = [
            'message' => $validator->errors($validator)->first()
            ];
            return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{
            $user = new \App\User;
            $user->mobile = $mobile;
            $user->password = $password;
            $user->otp = $otp;
            $user->device_token = $device_token;
            $user->remember_token = $accessToken;
            $user->device_type = $device_type;
            $user->user_type = $user_type;
            $user->created_at = time();
            $user->updated_at = time();
            $user->save();
            $userData = User::where(['id' => $user->id])->first();
            $userData['otp_response'] = $this->sendOtp($mobile,$otp);
            $response = [
                'message' =>  __('messages.success.signup'),
                'response' => $userData,

            ];
            Log::info('CommonController----sign_up----'.print_r($response,True));
            return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
        }
    }

    public function social_sign_up_and_login(Request $request){
        Log::info('CommonController----social_sign_up_and_login----'.print_r($request->all(),True));
        $social_id = $request->social_id;
        $email = $request->email;
        $device_token = $request->device_token;
        $device_type = $request->device_type;
        $mobile = $request->mobile;
        $user_type = $request->user_type;
        $accessToken  = md5(uniqid(rand(), true));
        $timezone = $request->header('timezone');
        $otp = rand(100000,1000000);
        if($timezone){
            $this->setTimeZone($timezone);
        }
        $validations = [
            'social_id' => 'required',
            'email' => 'required|email',
            'device_token' => 'required',
            'device_type' => 'required|numeric',
            'user_type' => 'required|numeric',
            // 'mobile' => 'required|unique:users'
        ];
        $validator = Validator::make($request->all(),$validations);
        if($validator->fails()){
            $response = [
            'message' => $validator->errors($validator)->first()
            ];
            return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{
            $user = User::where(['social_id'=>$social_id,'email'=> $email])->first();
            // dd($user);
            if(!$user){
                $validations = [
                    'email' => 'required|email|unique:users',
                    'social_id' => 'required|unique:users',
                    'mobile' => 'required|unique:users'
                ];
                $validator = Validator::make($request->all(),$validations);
                if($validator->fails()){
                    $response = [
                    'message' => $validator->errors($validator)->first()
                    ];
                    return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
                }else{
                    $User = new User;
                    $User->mobile = $mobile;
                    $User->email = $email;
                    $User->social_id = $social_id;
                    $User->device_token = $device_token;
                    $User->device_type = $device_type;
                    $User->user_type = $user_type;
                    $User->remember_token = $accessToken;
                    $User->created_at = time();
                    $User->updated_at = time();
                    $User->save();
                    $userData = User::where(['id' => $User->id])->first();
                    $userData['otp_response'] = $this->sendOtp($mobile,$otp);
                    if($User){
                        $response = [
                            'messages' => __('messages.success.signup'),
                            'response' => $userData
                        ];
                        return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
                    }
                }
            }else{
                $Obj = new User;
                $User = User::find($user->id);
                $User->remember_token = $accessToken;
                $User->updated_at = time();
                $User->save();
                $userData = User::where(['id' => $User->id])->first();
                $response = [
                    'messages' => __('messages.success.login'),
                    'response' => $userData
                ];
                Log::info('CommonController----social_sign_up_and_login----'.print_r($response,True));
                return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
            }
        }
    }

    public function otpVerify( Request $request ) {
        Log::info('----------------------CommonController--------------------------otpVerify'.print_r($request->all(),True));
       $otp = $request->input('otp');
       $user_id = $request->input('user_id');
       $key = $request->input('key'); // 1 (sign up otp verification) 2 (forget otp verification)
        $validations = [
            'user_id'   => 'required',
            'otp'   => 'required',
            'key'   => 'required',
        ];
        $validator = Validator::make($request->all(),$validations);
        if( !empty( $user_id ) ) {
            $user = new User;
            $userDetail = User::where(['id' => $user_id])->first();
            if(count($userDetail)){
                if( $validator->fails() ) {
                    $response = [
                     'message' => $validator->errors($validator)->first(),
                    ];
                    return Response::json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
               } else {
                switch ($key) {
                    case 1: // 1 (sign up otp verification)
                        if( $userDetail->otp == $otp || $otp == 123456){
                                $userDetail->otp = '';
                                $userDetail->otp_verified = 1;
                                $userDetail->updated_at = time();
                                $userDetail->save();
                                $Response = [
                                  'message'  => trans('messages.success.otp_verified'),
                                  'response' => User::find($userDetail->id)
                                ];
                                Log::info('CommonController----otpVerify----'.print_r($Response,True));
                                return Response::json( $Response , trans('messages.statusCode.ACTION_COMPLETE') );
                            } else {
                                $Response = [
                                    'message'  => trans('messages.invalid.OTP'),
                                ];
                                return Response::json( $Response , trans('messages.statusCode.SHOW_ERROR_MESSAGE') );
                            }
                        break;
                    
                    case 2: //2 (forget otp verification)
                        if( $userDetail->email_otp == $otp || $otp == 123456){
                                $userDetail->email_otp = null;
                                $userDetail->email_otp_verified = null;
                                $userDetail->updated_at = time();
                                $userDetail->save();
                                $Response = [
                                  'message'  => trans('messages.success.otp_verified'),
                                  'response' => User::find($userDetail->id)
                                ];
                                Log::info('CommonController----otpVerify----'.print_r($Response,True));
                                return Response::json( $Response , trans('messages.statusCode.ACTION_COMPLETE') );
                            } else {
                                $Response = [
                                    'message'  => trans('messages.invalid.OTP'),
                                ];
                                return Response::json( $Response , trans('messages.statusCode.SHOW_ERROR_MESSAGE') );
                            }
                        break;
                }
                        
               }
            }else{
                $response['message'] = trans('messages.invalid.detail');
                return response()->json($response,trans('messages.statusCode.INVALID_ACCESS_TOKEN'));
            }
        } else {
            $Response = [
              'message'  => trans('messages.required.user_id'),
            ];
          return Response::json( $Response , trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }
    }

    public function login(Request $request){
        Log::info('CommonController----login----'.print_r($request->all(),True));
        $mobile = $request->mobile;
        $password = $request->input('password');
        $user_type = $request->user_type;
        $device_token = $request->device_token;
        $device_type = $request->device_type;
        $accessToken  = md5(uniqid(rand(), true));
        $timezone = $request->header('timezone');
        $validations = [
            'mobile' => 'required',
            'password' => 'required|min:8',
            'device_token' => 'required',
            'device_type' => 'required|numeric',
            'user_type' => 'required'
        ];
        if($timezone){
            $this->setTimeZone($timezone);
        }
        $validator = Validator::make($request->all(),$validations);
        if($validator->fails()){
            $response = [
            'message' => $validator->errors($validator)->first()
            ];
            return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{
            $userDetail = User::Where(['mobile' => $mobile])->first();
            if(!empty($userDetail)){

                    switch ($user_type) {
                        case '1':
                            $user_type = 'bride';
                            break;
                        case '2':
                            $user_type = 'groom';
                            break;
                        case '3':
                            $user_type = 'host';
                            break;
                        case '4':
                            $user_type = 'vendor';
                            break;
                    }
                    if($userDetail->user_type == $user_type){
                        if(Hash::check($password,$userDetail->password)){
                            $User = new User;
                            $UserDetail = $User::find($userDetail->id);
                            $UserDetail->device_token = $device_token;
                            $UserDetail->device_type = $device_type;
                            $UserDetail->remember_token = $accessToken;
                            $UserDetail->updated_at = time();
                            $UserDetail->save();
                            $result = $User::find($userDetail->id); 
                            $response = [
                                'message' =>  __('messages.success.login'),
                                'response' => $result
                            ];
                            return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
                        }else{
                            $response = [
                                'message' =>  __('messages.invalid.detail')
                            ];
                            return response()->json($response,__('messages.statusCode.INVALID_CREDENTIAL'));
                        }
                    }else{
                        $response = [
                            'message' =>  __('messages.invalid.detail')
                        ];
                        return response()->json($response,__('messages.statusCode.INVALID_CREDENTIAL'));
                    }
                }else{
                    $response = [
                        'message' =>  __('messages.invalid.detail')
                    ];
                    Log::info('CommonController----login----'.print_r($response,True));
                    return response()->json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
                }
        }
    }

    public function logout( Request $request ) {
        Log::info('----------------------CommonController--------------------------logout'.print_r($request->all(),True));
        $accessToken =  $request->header('accessToken');
        if( !empty( $accessToken ) ) {
            $user = new \App\User;
            $userDetail = User::where(['remember_token' => $accessToken])->first();
            if(count($userDetail)){
                $User = User::find($userDetail->id);
                $User->remember_token = "";
                $User->updated_at = time();
                $User->save();
                $Response = [
                  'message'  => trans('messages.success.logout'),
                ];
                return Response::json( $Response , trans('messages.statusCode.ACTION_COMPLETE') );  
            }else{
                $response['message'] = trans('messages.invalid.detail');
                return response()->json($response,trans('messages.statusCode.INVALID_ACCESS_TOKEN'));
            }
        } else {
            $Response = [
              'message'  => trans('messages.required.accessToken'),
            ];
          return Response::json( $Response , trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }
    }

    public function sendOtp($mobile,$otp) {
        try{
            $sid = 'AC6ceef3619be02e48da4aba2512cc426b';
            $token = 'eeaa38187028b4a0a9c4f4e105162b6e';
            $client = new Client($sid, $token);
            $number = $client->lookups
                ->phoneNumbers("+14154291712")
                ->fetch(array("type" => "carrier"));
            $client->messages->create(
                $mobile, array(
                    'from' => '+14154291712',
                    'body' => 'Wed Mojo: please enter this code to verify :'.$otp
                )
            );
            $response = [
                'message' => 'success',
                'status' => 1
            ];
            return $response;
        } catch(Exception $e){
            // dd($e->getMessage());
            $response = [
                'message' => $e->getMessage(),
                'status' => 0
            ];
            return $response;
        }
    }

    public function forgetPassword(Request $request) {
        Log::info('----------------------CommonController--------------------------forgetPassword'.print_r($request->all(),True));
        $mobile = $request->mobile;
        $otp = rand(100000,1000000);
        $locale = $request->header('locale');
        $key = $request->key;
        $validations = [
            'mobile'=>'required',
            'key' => 'required'
        ];
        $validator = Validator::make($request->all(),$validations);
        if( $validator->fails() ){
           $response = [
            'message'=>$validator->errors($validator)->first()
           ];
           return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{
            $UserDetail = User::Where(['mobile' => $mobile])->first();
            if(count($UserDetail)){
                switch ($key) {
                    case '1':
                        $UserDetail->otp = $otp;
                        $UserDetail->otp_verified = 0;
                        $UserDetail->updated_at = time();
                        $UserDetail->save();
                        $user = new User;
                        $userData = $user->where(['id' => $UserDetail->id])->first();
                        $userData['otp_response'] = $this->sendOtp($mobile,$otp);
                        $response = [
                            'message' => trans('messages.success.email_forget_otp'),
                            'response' => $userData
                        ];
                        Log::info('CommonController----forgetPassword----'.print_r($response,True));
                        return Response::json($response,__('messages.statusCode.ACTION_COMPLETE'));
                        break;
                }
            } else {
                $response=[
                'message' => trans('messages.invalid.credentials'),
            ];
              return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
            }
        }
    }

    public function resendOtp(Request $request){
        Log::info('----------------------CommonController--------------------------resendOtp'.print_r($request->all(),True));
        $key = $request->key; // 1 for send otp at mobile
        $email = $request->email;
        $mobile = $request->mobile;
        $country_code = $request->country_code;
        $user_id          = $request->input('user_id');
        $otp = rand(100000,1000000);
        $locale = $request->header('locale');

        $validations = [
            'key' => 'required|numeric',
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(),$validations);
        if( $validator->fails() ){
           $response = [
            'message'=>$validator->errors($validator)->first()
           ];
           return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{
            $userDetail=User::where(['id' => $user_id])->first();
            // dd($userDetail);
            if(count($userDetail)){
                $USER = new User;
                if($key == 1){ // otp at mobile
                    $otp_status = $this->sendOtp($userDetail->mobile,$otp);
                    $userDetail->otp = $otp;
                    $userDetail->otp_verified = 0;
                    $userDetail->updated_at = time();
                    $userDetail->save();
                }
                if($key == 2){ // otp at email
                    $data = [
                        'otp' => $otp,
                        'email' => $userDetail->email
                    ];
                    $userDetail->email_otp = $otp;
                    $userDetail->email_otp_verified = 0;
                    $userDetail->updated_at = time();
                    $userDetail->save();
                    try{
                        Mail::send(['text'=>'otp'], $data, function($message) use ($data)
                        {
                             $message->to($data['email'])
                                    ->subject ('OTP');
                             $message->from('techfluper@gmail.com');
                       });  
                    }catch(Exception $e){
                        $response=[
                            'message' => $e->getMessage()
                    ];
                        return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
                    }
                }
                
                if($key == 1){ // otp at mobile
                    $Response = [
                      'message'  => trans('messages.success.otp_resend'),
                      'response' => User::where(['id' => $userDetail->id])->first(),
                    ];
                    $Response['response']['otp_status'] = $otp_status;
                }
                if($key == 2){ // otp at email
                    $Response = [
                      'message'  => trans('messages.success.email_forget_otp'),
                      'response' => User::where(['id' => $userDetail->id])->first(),
                    ];
                }
                Log::info('CommonController----resendOtp----'.print_r($Response,True));
                return Response::json( $Response , trans('messages.statusCode.ACTION_COMPLETE') );  
            }else{
                $response['message'] = trans('messages.invalid.detail');
                return response()->json($response,trans('messages.statusCode.INVALID_ACCESS_TOKEN'));
            }
        }
    }

    public function change_password(Request $request){
        Log::info('----------------------CommonController--------------------------change_password'.print_r($request->all(),True));
        $accessToken = $request->header('accessToken');
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $key = $request->key; // 1 (change password) 2 (Reset password)
        $locale = $request->header('locale');
        if(empty($locale)){
            $locale = 'en';
        }
        \App::setLocale($locale);
        $validations = [
            'key' => 'required'
        ];
        $validator = Validator::make($request->all(),$validations);
        if($validator->fails()){
            $response = [
                'message' => $validator->errors($validator)->first()
            ];
            return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }

        switch ($key) {
            //////////////////////////////////////
            ///// CHANGE PASSWORD
            /////////////////////////////////////
            case 1:
                $validations = [
                    'old_password' => 'required|min:8',
                    'new_password' => 'required|min:8'
                ];
                $validator = Validator::make($request->all(),$validations);
                if($validator->fails()){
                    $response = [
                        'message' => $validator->errors($validator)->first()
                    ];
                    return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
                }else{
                    $UserDetail = User::where(['remember_token' => $accessToken])->first();
                    if(count($UserDetail)){
                        // dd($UserDetail->password);
                        if(Hash::check($old_password,$UserDetail->password)){
                            // dd("correct");
                            $User = User::find($UserDetail->id);
                            $User->password = Hash::make($new_password);
                            $User->save();
                            $userDetail = new \App\User;
                            $Response = [
                              'message'  => trans('messages.success.password_updated'),
                              'response' => $userDetail::find($UserDetail->id)
                            ];
                            return Response::json( $Response , trans('messages.statusCode.ACTION_COMPLETE') );
                        }else{
                            $Response = [
                              'message'  => trans('messages.error.incorrect_old_password'),
                            ];
                            return Response::json( $Response , trans('messages.statusCode.SHOW_ERROR_MESSAGE') );
                        }
                    }else{
                        $Response = [
                          'message'  => trans('messages.invalid.detail'),
                        ];
                        return Response::json( $Response , trans('messages.statusCode.INVALID_ACCESS_TOKEN') );
                    }
                }
                break;
            //////////////////////////////////////////////
            /////// RESET PASSWORD
            /////////////////////////////////////////////
            case 2: 
                $validations = [
                    'new_password' => 'required|min:8'
                ];
                $validator = Validator::make($request->all(),$validations);
                if($validator->fails()){
                    $response = [
                        'message' => $validator->errors($validator)->first()
                    ];
                    return response()->json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
                }else{
                    $UserDetail = User::where(['remember_token' => $accessToken])->first();
                    if(count($UserDetail)){
                        // dd($UserDetail->password);
                        $User = User::find($UserDetail->id);
                        $User->password = Hash::make($new_password);
                        $User->save();
                        $userDetail = new \App\User;
                        $Response = [
                          'message'  => trans('messages.success.password_updated'),
                          'response' => $userDetail::find($UserDetail->id)
                        ];
                        return Response::json( $Response , trans('messages.statusCode.ACTION_COMPLETE') );
                    }else{
                        $Response = [
                          'message'  => trans('messages.invalid.detail'),
                        ];
                        return Response::json( $Response , trans('messages.statusCode.INVALID_ACCESS_TOKEN') );
                    }
                }
                break;
            //////////////////////////////////////////////
            /////// DEFAULT RESPONSE
            /////////////////////////////////////////////
            default:
                $Response = [
                  'message'  => trans('messages.invalid.request'),
                ];
                return Response::json( $Response , trans('messages.statusCode.INVALID_ACCESS_TOKEN') );
                break;
        }
    }

    public function changeMobileNumber( Request $request ) {
        Log::info('----------------------CommonController--------------------------changeMobileNumber'.print_r($request->all(),True));
        $country_code = $request->country_code;
        $mobile      =  $request->mobile;
        $accessToken =  $request->header('accessToken');
        $otp = rand(100000,1000000);
        $isChangedCountryCode = $request->isChangedCountryCode;
        $isChangedMobile = $request->isChangedMobile;
        $userDetail  = [];
        $locale = $request->header('locale');
        $timezone = $request->header('timezone');
        if($timezone){
            $this->setTimeZone($timezone);
        }
        if(empty($locale)){
            $locale = 'en';
        }

        if(!empty($locale)){
            \App::setLocale($locale);
            $validations = [
                'mobile'       => 'required',
            ];
            $validator = Validator::make($request->all(),$validations);
            if( !empty( $accessToken ) ) {
                $userDetail = User::Where(['remember_token' => $accessToken])->first();
                if(count($userDetail)){
                    if( $validator->fails() ) {
                        $response = [
                                'message'   =>  $validator->errors($validator)->first(),
                            ];
                        return Response::json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
                    } else {
                        $validations = [
                            'mobile' => [
                                'required',
                                Rule::unique('users')->ignore($userDetail->id),
                            ],
                        ];
                        $validator = Validator::make($request->all(),$validations);
                        if( $validator->fails() ) {
                            $response = [
                                'message'   =>  $validator->errors($validator)->first()
                            ];
                            return Response::json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
                        } else {
                                $User = new \App\User;
                                $UserDetail = $User::find($userDetail->id);
                                $UserDetail->mobile = $mobile;
                                $UserDetail->otp = $otp;
                                $UserDetail->otp_verified = '0';
                                $UserDetail->save();
                                $this->sendOtp($mobile, $otp);
                                $response = [
                                    'message' => __('messages.success.success'),
                                    'response' => User::where(['id' => $userDetail->id])->first()
                                ];
                                return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
                        }
                    }
              }else{
                $response['message'] = trans('messages.invalid.detail');
                return response()->json($response,trans('messages.statusCode.INVALID_ACCESS_TOKEN'));
              }
            }else {
                $Response = [
                    'message'  => trans('messages.required.accessToken'),
                ];
                return Response::json( $Response , trans('messages.statusCode.SHOW_ERROR_MESSAGE') );
            }
        }else{
            $response = [
                'message' =>  __('messages.required.locale')
            ];
            return response()->json($response,__('messages.statusCode.INVALID_CREDENTIAL'));
       }
    }

    public function update_profile(Request $request){
        Log::info('----------------------CommonController--------------------------update_profile'.print_r($request->all(),True));
        $accessToken = $request->header('accessToken');
        $destinationPathOfProfile = public_path().'/'.'Images/';
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        // $mobile = $request->mobile;
        $email = $request->email;
        $profile_image = $request->profile_image;

        $USER = User::Where(['remember_token' => $accessToken])->first();
        if(count($USER)){
            $validations = [
                'profile_image' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                /*'mobile' => [
                    'required',
                    Rule::unique('users')->ignore($USER->id),
                ],*/
                'email' => [
                    Rule::unique('users')->ignore($USER->id),
                ],
            ];
            $validator = Validator::make($request->all(),$validations);
            if( $validator->fails() ) {
                $response = [
                    'message' => $validator->errors($validator)->first(),
                ];
                return Response::json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
            } else {
                if(isset($_FILES['profile_image']['tmp_name'])){
                    $big = explode('/', $USER->profile_image['big']);
                    $small = explode('/', $USER->profile_image['small']);
                    $thumbnail = explode('/', $USER->profile_image['thumbnail']);
                    if( file_exists( public_path().'/Images/'.end($big) ) ) {
                        unlink(public_path().'/Images/'.end($big));
                    }
                    if(file_exists(public_path().'/Images/'.end($small))){
                        unlink(public_path().'/Images/'.end($small));
                    }
                    if( file_exists(public_path().'/Images/'.end($thumbnail)) ) {
                        unlink(public_path().'/Images/'.end($thumbnail));
                    }
                    $uploadedfile = $_FILES['profile_image']['tmp_name'];
                    $fileName1 = substr($this->uploadImage($profile_image,$uploadedfile,$destinationPathOfProfile),9); 
                    $USER->profile_image = $fileName1;
                }
                $user = new User;
                $USER->first_name = $first_name;
                $USER->last_name = $last_name;
                $USER->email = $email;
                $USER->updated_at = time();
                $USER->save();
                $userData = $user::where(['id' => $USER->id])->first();
                $response = [
                    'message' =>  __('messages.success.profile_updated'),
                    'response' => $userData,
                ];
                Log::info('CommonController----complete_profile----'.print_r($response,True));
                return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
            }
        }else{
            $response = [
                'message' => __('messages.invalid.detail'),
            ];
            return Response::json($response,trans('messages.statusCode.INVALID_ACCESS_TOKEN'));
        }
    }

    public function uploadImage($photo,$uploadedfile,$destinationPathOfPhoto){
        /*$photo = $request->file('photo');
        $uploadedfile = $_FILES['photo']['tmp_name'];
        $destinationPathOfPhoto = public_path().'/'.'thumbnail/';*/
        $fileName = time()."_".$photo->getClientOriginalName();
        $src = "";
        $i = strrpos($fileName,".");
        $l = strlen($fileName) - $i;
        $ext = substr($fileName,$i+1);

        if($ext=="jpg" || $ext=="jpeg" || $ext=="JPG" || $ext=="JPEG"){
            $src = imagecreatefromjpeg($uploadedfile);
        }else if($ext=="png" || $ext=="PNG"){
            $src = imagecreatefrompng($uploadedfile);
        }else if($ext=="gif" || $ext=="GIF"){
            $src = imagecreatefromgif($uploadedfile);
        }else{
            $src = imagecreatefrombmp($uploadedfile);
        }
        $newwidth  = 200;
        list($width,$height)=getimagesize($uploadedfile);
        $newheight=($height/$width)*$newwidth;
        $tmp=imagecreatetruecolor($newwidth,$newheight);
        imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
        $filename = $destinationPathOfPhoto.'small'.'_'.$fileName; 
        imagejpeg($tmp,$filename,100);
        imagedestroy($tmp);
        $filename = explode('/', $filename);

        $newwidth1  = 400;
        list($width,$height)=getimagesize($uploadedfile);
        $newheight1=($height/$width)*$newwidth1;
        $tmp=imagecreatetruecolor($newwidth1,$newheight1);
        imagecopyresampled($tmp,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
        $filename = $destinationPathOfPhoto.'big'.'_'.$fileName; 
        imagejpeg($tmp,$filename,100);
        imagedestroy($tmp);
        $filename = explode('/', $filename);

        $newwidth2  = 100;
        list($width,$height)=getimagesize($uploadedfile);
        $newheight2=($height/$width)*$newwidth2;
        $tmp=imagecreatetruecolor($newwidth2,$newheight2);
        imagecopyresampled($tmp,$src,0,0,0,0,$newwidth2,$newheight2,$width,$height);
        $filename = $destinationPathOfPhoto.'thumbnail'.'_'.$fileName; 
        imagejpeg($tmp,$filename,100);
        imagedestroy($tmp);
        $filename = explode('/', $filename);
        return $filename[7];
    }


    public function setup_wedding( Request $request ){
        $UserDetail = $request->userDetail;
        $groom_id = null;
        $bride_id = null;
        $host_id = null;
        $vendor_id = null;
        $validations = [
            'g_first_name' => 'required',
            'g_last_name' => 'required',
            'g_contact_number' => 'required',
            'b_first_name' => 'required',
            'b_last_name' => 'required',
            'b_contact_number' => 'required',
            'location' => 'required',
            'wedding_date' => 'required',
            'g_image' => 'required',
            'b_image' => 'required',
        ];
        $messages = [
            'g_first_name.required' => 'Groom first name is required.',
            'g_last_name.required' => 'Groom last name is required.',
            'g_contact_number.required' => 'Groom contact number is required.',
            'g_image.required' => 'Groom image is required',
            'b_first_name.required' => 'Bride first name is required.',
            'b_last_name.required' => 'Bride last name is required.',
            'b_contact_number.required' => 'Bride contact number is required.',
            'b_image.required' => 'Bride image is required',
        ];
        $validator = Validator::make($request->all(),$validations,$messages);
        if( $validator->fails() ) {
            $response = [
                'message' => $validator->errors($validator)->first(),
            ];
            return Response::json($response,trans('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }


        $g_first_name = $request->g_first_name; 
        $g_last_name = $request->g_last_name; 
        $g_contact_number = $request->g_contact_number; 

        $b_first_name = $request->b_first_name; 
        $b_last_name = $request->b_last_name; 
        $b_contact_number = $request->b_contact_number; 
        $location = $request->location;
        $wedding_date = $request->wedding_date;

        $groom_image = $request->g_image;
        $bride_image = $request->b_image;

        switch ($UserDetail->user_type) {
            case 'bride':
                $user_type = 1;
                $Wedding = Wedding::firstOrNew(['bride_id' => $UserDetail->id]);
                $Wedding->bride_first_name = $b_first_name;
                $Wedding->bride_last_name = $b_last_name;
                $Wedding->bride_contact_number = $b_contact_number;

                /*$brideDetail = User::where(['id' => $UserDetail->id])->first();
                $brideDetail->first_name = $b_first_name;
                $brideDetail->last_name = $b_last_name;
                $brideDetail->save();*/  // for update detail of bride at user table

                $Wedding->groom_first_name = $g_first_name;
                $Wedding->groom_last_name = $g_last_name;
                $Wedding->groom_contact_number = $g_contact_number;
                $Wedding->location = $location;
                $Wedding->wedding_date = $wedding_date;
                
                $destinationPath = public_path().'/'.'Images/WeddingImages';
                $g_filename = time().'_g_'.$groom_image->getClientOriginalName();
                $b_filename = time().'_b_'.$bride_image->getClientOriginalName();

                $groom_image->move($destinationPath,$g_filename);
                $bride_image->move($destinationPath,$b_filename);


                $Wedding->groom_image = $g_filename;
                $Wedding->bride_image = $b_filename;

                $groomDetail = User::where(['mobile' => $g_contact_number])->first();
                if($groomDetail){
                    // dd('exist');
                    if($groomDetail->user_type != 'bride'){
                        // $groomDetail->first_name = $g_first_name; // here bride can change groom name
                        // $groomDetail->last_name = $g_last_name;  // here bride can change groom name
                        // $groomDetail->save();
                        $Wedding->groom_id = $groomDetail->id;    
                    }else{
                        $response = [
                            'message' => 'Mobile is already registered.',
                        ];
                        return response()->json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
                    }
                }else{
                    // dd('not');
                    $groom_creation = User::firstOrCreate(['mobile' => $g_contact_number ,'user_type' => 2]);
                    $groom_creation->first_name = $g_first_name;
                    $groom_creation->last_name = $g_last_name;
                        // here i have to send this password to groom if he is not registered
                    $groom_creation->password = Hash::make(11111111); 
                    $groom_creation->save();    
                    $Wedding->groom_id = $groom_creation->id;
                }
                $Wedding->save();
                $response = [
                    'message' => 'success',
                    'response' => $Wedding
                ];
                return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
                break;

            case 'groom':
                $user_type = 2;
                $groom_id = $UserDetail->id;
                $Wedding = Wedding::firstOrNew(['groom_contact_number' => $g_contact_number]);
                $Wedding->groom_id = $groom_id;
                $Wedding->groom_first_name = $g_first_name;
                $Wedding->groom_last_name = $g_last_name;
                $Wedding->groom_contact_number = $g_contact_number;

                $Wedding->bride_first_name = $b_first_name;
                $Wedding->bride_last_name = $b_last_name;
                $Wedding->bride_contact_number = $b_contact_number;
                $Wedding->location = $location;
                $Wedding->wedding_date = $wedding_date;

                $destinationPath = public_path().'/'.'Images/WeddingImages';
                $g_filename = time().'_g_'.$groom_image->getClientOriginalName();
                $b_filename = time().'_b_'.$bride_image->getClientOriginalName();

                $groom_image->move($destinationPath,$g_filename);
                $bride_image->move($destinationPath,$b_filename);


                $Wedding->groom_image = $g_filename;
                $Wedding->bride_image = $b_filename;

                $brideDetail = User::where(['mobile' => $b_contact_number])->first();
                if($brideDetail){
                    // dd($brideDetail->user_type);
                    // dd('exist');
                    if($brideDetail->user_type != 'groom'){
                        // $brideDetail->first_name = $b_first_name; // here bride can change groom name
                        // $brideDetail->last_name = $b_last_name;  // here bride can change groom name
                        // $brideDetail->save();
                        $Wedding->bride_id = $brideDetail->id;    
                    }else{
                        $response = [
                            'message' => 'Mobile is already registered.',
                        ];
                        return response()->json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
                    }
                }else{
                    // dd('not');
                    $bride_creation = User::firstOrCreate(['mobile' => $b_contact_number ,'user_type' => 1]);
                    $bride_creation->first_name = $b_first_name;
                    $bride_creation->last_name = $b_last_name;
                        // here i have to send this password to bride if he is not registered
                    $bride_creation->password = Hash::make(11111111); 
                    $bride_creation->save();    
                    $Wedding->groom_id = $bride_creation->id;
                }

                $Wedding->save();
                $response = [
                    'message' => 'success',
                    'response' => $Wedding
                ];
                return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
                break;


            /*case 'host':
                $user_type = 3;
                $host_id = $UserDetail->id;
                break;
            case 'vendor':
                $user_type = 4;
                $vendor_id = $UserDetail->id;
                break;*/
        }
    }


    public function create_host(Request $request){
        $UserDetail = $request->userDetail;
        $name = $request->name;
        $relation = $request->relation;
        $mobile = $request->mobile;

        $validations = [
            'name' => 'required',
            'relation' => 'required',
            'mobile' => 'required|unique:users',
        ];
        $validator = Validator::make($request->all(),$validations);
        if( $validator->fails() ){
           $response = [
            'message'=>$validator->errors($validator)->first()
           ];
           return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{

            $UserDetail = User::firstOrCreate(['mobile' => $mobile ,'user_type' => 3]);
            $UserDetail->first_name = $name;
            $UserDetail->relation = $relation;
            $UserDetail->created_by_user_id = $UserDetail->id;
            $UserDetail->save();
            $response = [
                'message' => 'success',
                'response' => $UserDetail
            ];
            return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
        }
    }

    public function create_function(Request $request){
        $UserDetail = $request->userDetail;
        $function_name = $request->function_name;
        $function_image = $request->function_image;
        $function_date = $request->function_date;

        $validations = [
            'function_name' => 'required',
            'function_image' => 'required',
            'function_date' => 'required',
        ];
        $validator = Validator::make($request->all(),$validations);
        if( $validator->fails() ){
           $response = [
            'message'=>$validator->errors($validator)->first()
           ];
           return Response::json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
        }else{

            $function_detail = CreateFunction::firstOrCreate(['function_name' => $function_name, 'function_date' => $function_date,'created_by_user_id' => $UserDetail->id]);

            $destinationPath = public_path().'/'.'Images/FunctionImages';
            $fileName = time().'_'.$function_image->getClientOriginalName();
            $function_image->move($destinationPath,$fileName);
            $function_detail->function_image = $fileName;
            $function_detail->save();
            $response = [
                'message' => 'success',
            ];
            return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
        }
    }
}
