<?php

return [
	
	'success' => [
		'login' => 'Login successful.',
		'success' => 'Success.',
		'signup' => 'Signup successful.',
		'update' => 'Update successful.',
		'unsuccess' => 'Unsuccessful.',
		'logout' => 'Logout successfully.',
		'review_published' => 'review published successfully.',
		'review_un_published' => 'review un published successfully.',
		'appointment_accepted' => 'Appointment accepted Successfully.',
		'appointment_rejected' => 'Appointment rejected Successfully.',
		'password_updated' => 'Password updated successfully.',
		'passcode_updated' => 'Passcode updated successfully.',
		'appointment_rescheduled' => 'Appointment rescheduled Successfully.',
		'appointment_scheduled' => 'Appointment scheduled Successfully.',
		'NO_DATA_FOUND' => 'NO DATA FOUND',
		'otp_resend' => 'OTP resent successfully.',
		'otp_verified' => 'OTP verified successfully.',
		'email_forget_otp' => 'OTP sent successfully.',
		'reset_password' => 'Password is successfully reset .Please login again.',
		'mobile_changed' => 'Mobile number successfully changed',
		'complete_profile' => 'Profile is created.',
		'QA_added' => 'Qualification added successfully.',
		'QA_deleted' => 'Qualification deleted successfully.',
		'QA_already_exist' => 'Qualification already exist.',
		'mother_language_added' => 'Mother language added successfully.',
		'mother_language_already_exist' => 'Mother language already exist.',
		'ML_deleted' => 'Mother language deleted.',
		'speciality_added' => 'Speciality added successfully.',
		'SP_deleted' => 'Speciality deleted.',
		'speciality_already_exist' => 'Speciality already exist.',
		'patient_unblocked' => 'Patient unblocked successfully.',
		'patient_blocked' => 'Patient blocked successfully.',
		'docotr_approved' => 'Doctor approved successfully.',
		'speciality_updated' => 'Speciality updated successfully.',
		'qualificationy_updated' => 'Qualification updated successfully.',
		'mother_language_updated' => 'Mother language updated successfully',
		'Admin_profile_updated' => 'Admin profile updated successfully.',
		'profile_complete' => 'Profile completed successfully.',
		'profile_updated' => 'Profile updated successfully.',
		'feeds_hide' => 'Post hide successfully.',
		'feeds_deleted' => 'Post deleted.',
	],

	'error' => [
		'insert' => 'Error in record creation.',
		'incorrect_old_password' => 'Old password is incorrect.',
		'incorrect_old_passcode' => 'Old passcode is incorrect.',
		'mobile_already_taken' => 'Mobile number already exist.',
	],


	'statusCode' => [
		'ALREADY_EXIST' => '422',
		'PARAMETER_MISSING' => '422',
		'INVALID_ACCESS_TOKEN' => '401',
		'INVALID_CREDENTIAL' => '403',
		'ACTION_COMPLETE' => '200',
		'CREATE' => '201',
		'NO_DATA_FOUND' => '204',
		'IMAGE_FILE_MISSING' => '422',
		'SHOW_ERROR_MESSAGE' => '400',
		'NOT_FOUND' => '404',
		'BAD_REQUEST' => '500'
	],

	'required' => [
		'accessToken' => 'Access Token Required.',
		'user_id' => 'User id is required.',
		'requestToUserId' => 'RequestToUserId is required',
		'locale' => 'Locale is required',
		
	],

	'invalid' =>[
		'number' => 'Invalid number.',
		'detail' => 'Invalid details.',
		'request' => 'Invalid request.',
		'credentials' => 'Invalid credentials.',
		'accessToken' => 'Invalid accessToken.',
		'OTP' => 'Invalid OTP.',
		'NOT_FOUND' => 'Not found',
	],

	'adminMessages' => [
		'invalid' => 'Invalid credentials',
		'invalid_email' => 'Email not registered with us.',
		'resetLinkSend' => 'Password reset link sent at your registered email.',
		'password_reset_success' => 'Password reset successful.',
		'invalid_reset_token' => 'Invalid password reset token.',
		'password_changed' => 'Password changed successfully.',
		'block_success' => 'User blocked successfully.',
		'unblock_success' => 'User un-blocked successfully.',

	],

	'same' => [
		'same_number' => 'You have entered the same number.',
		'country_code' => 'Same country code as last.'
	],

	'invalid_old_password' => 'Old password is incorrect.',
	'favourite_list' => 'Favourite List',
];
