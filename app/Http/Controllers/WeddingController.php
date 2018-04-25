<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User;
use \App\Models\Wedding;
use Illuminate\Support\Facades\Validator;
use Response;
use Log;

class WeddingController extends Controller
{
	
	public function setup_wedding( Request $request ){
        $UserDetail = $request->userDetail;
        if($request->method() == 'POST'){
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
            // dd($UserDetail->user_type);
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

                    $groomDetail = User::where([
                        'mobile' => explode('-', $g_contact_number)[1]
                    ])->first();
                    // dd($groomDetail);
                    if($groomDetail){
                        // dd('exist');
                        if($groomDetail->user_type != 'bride'){
                            // $groomDetail->first_name = $g_first_name; // here bride can change groom name
                            // $groomDetail->last_name = $g_last_name;  // here bride can change groom name
                            $groomDetail->wedding_status = 1;
                            $groomDetail->save();
                            $Wedding->groom_id = $groomDetail->id;    
                        }else{
                            $response = [
                                'message' => 'Mobile is already registered.',
                            ];
                            return response()->json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
                        }
                    }else{
                        // dd('not');
                        // dd(explode('-', $g_contact_number)[0]);
                        $groom_creation = User::firstOrCreate([
                            'country_code' => explode('-', $g_contact_number)[0],
                            'mobile' => explode('-', $g_contact_number)[1],
                            'user_type' => 2]);
                        // dd($groom_creation);
                        $groom_creation->first_name = $g_first_name;
                        $groom_creation->last_name = $g_last_name;
                            // here i have to send this password to groom if he is not registered
                        $groom_creation->password = Hash::make(11111111); 
                        $groom_creation->wedding_status = 1;
                        $groom_creation->save();    
                        $Wedding->groom_id = $groom_creation->id;
                    }
                    $UserDetail->wedding_status = 1;
                    $UserDetail->save();
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
                    $Wedding = Wedding::firstOrNew(['groom_contact_number' => explode('-', $g_contact_number)[1]]);
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

                    $brideDetail = User::where(['mobile' => explode('-', $b_contact_number)[1]])->first();
                    if($brideDetail){
                        // dd($brideDetail->user_type);
                        // dd('exist');
                        if($brideDetail->user_type != 'groom'){
                            // $brideDetail->first_name = $b_first_name; // here bride can change groom name
                            // $brideDetail->last_name = $b_last_name;  // here bride can change groom name
                            $brideDetail->wedding_status = 1;
                            $brideDetail->save();
                            $Wedding->bride_id = $brideDetail->id;    
                        }else{
                            $response = [
                                'message' => 'Mobile is already registered.',
                            ];
                            return response()->json($response,__('messages.statusCode.SHOW_ERROR_MESSAGE'));
                        }
                    }else{
                        // dd('not');
                        $bride_creation = User::firstOrCreate([ 'country_code' => explode('-', $b_contact_number)[0], 'mobile' => explode('-', $b_contact_number)[1] ,'user_type' => 1]);
                        $bride_creation->first_name = $b_first_name;
                        $bride_creation->last_name = $b_last_name;
                            // here i have to send this password to bride if he is not registered
                        $bride_creation->password = Hash::make(11111111); 
                        $bride_creation->wedding_status = 1;
                        $bride_creation->save();    
                        $Wedding->groom_id = $bride_creation->id;
                    }
                    $UserDetail->wedding_status = 1;
                    $UserDetail->save();
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
        if($request->method() == 'GET'){
            if($UserDetail->user_type == 'bride'){
                $data = Wedding::where([ 'bride_id' => $UserDetail->id ])->first();
            }
            if($UserDetail->user_type == 'groom'){
                $data = Wedding::where([ 'groom_id' => $UserDetail->id ])->first();
            }
            
            $response = [
                'message' => __('messages.success.success'),
                'response' => $data
            ];
            return response()->json($response,__('messages.statusCode.ACTION_COMPLETE'));
        }
    }
}
