<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;

use Hash;
use Auth;
use Validator;
use Input;

class PasswordController extends Controller {
	
	/**
     * change password
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('auth.changepassword')->withTitle('Change Password');
    }
	
    /**
     * Get a validator for an incoming change password request
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function ChangePasswordValidator(array $data)
    {
        return Validator::make($data, [
			'password' => 'required|confirmed|min:6',
        ]);
    }

	/**
     * change password
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		//first we process the raw data from request
        $validator = $this->ChangePasswordValidator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
		
		$user = User::find(Auth::user()->id);

		if(Hash::check($request->get('old_password'), Auth::user()->password)) // checking for old password
		{
			$request->merge(['password' => Hash::make($request->password)]);
			$user->update($request->only(['password']));		

			return view('auth.changepassword')->withMessage('Password has changed..')->withTitle('Change Password');
		}
		else
		{
			return view('auth.changepassword')->withErrors('Please type the correct password')->withTitle('Change Password');			
		}

    }

    /**
     * change password
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMember(Request $request)
    {
            // dd($request);
        $user = User::find($request->id);
        // dd($user);
        $user->password = Hash::make($request->newPassword);
        $saved = $user->save();        
        if($saved){
            $message='Password member has changed..';   
        }else{
            $message='Failed to update password..';   
        }
        return redirect()->back()->withMessage($message);   
    }
			
}