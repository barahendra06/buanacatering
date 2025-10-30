<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use Image;

use App\User;
use App\Member;
use App\Activity;
use App\ActivityType;
use App\EmployeeDocument;
use App\Department;
use App\Branch;

use App\Http\Controllers\DateTrait;

use \Carbon\Carbon;
use Hash;
use Input;
use File;
use Auth;
use Excel;
use Mail;
use App\Mail\RegisterUser;
use App\Mail\ExpireUser;

class UserController extends Controller {

	use DateTrait;


	public function viewImage(Request $request)
	{
		$data['text'] = $request->text;
		$data['path'] = $request->path;
		return view('misc.viewImage', $data);
	}

	public function confirm($activation_code)
    {
        if(!$activation_code)
        {
			return redirect()->route('home')->withMessage('This activation link is invalid or already used!');
        }

        $user = User::where('activation_code', $activation_code)->first();

        if (!$user)
        {
			//no such user
			return redirect()->route('home')->withMessage('This activation link is invalid or already used!');
        }

		//confirm the user
        $user->confirmed = 1;
        $user->confirmed_at = Carbon::now();
        $user->activation_code = null;
        $user->save();
		Auth::login($user);
		
		
		//redirect to dashboard
		return redirect()->route('member-dashboard')->withMessage('Your account is now active!');
    }	
	
	public function expiration()
    {	
		//get all the expired member
		$expirationDate = Carbon::now()->subYears(MAXIMUM_AGE)->toDateString(); 
		//$expiredMember = Member::where('birth_date', '<=', $expirationDate)
		//					->with('user')->get();

		$expiredMember = Member::join('user', 'user.id', '=', 'user_id') 	
								->select('member.name', 'member.birth_date', 'member.id', 'member.user_id', 'user.role_id', 'user.confirmed', 'user.active', 'user.expired')		
								->where('birth_date', '<=', $expirationDate)
								->where('role_id', '=', 1)  	//we only check regular user 
								->where('confirmed', '=', 1)	//that already confirmed
								->where('active', '=', 1)		//and active
								->where('expired', '=', 0)		//and not yet expired
								->with('user')
                                ->get();	
				
		foreach($expiredMember as $member)
		{
			//set the expired flag
			$member->user->expired = 1;
			$member->user->active = 0;
			$member->user->save();
			
			//send the goodbye email
			// Mail::queue('emails.goodbye', ['member' => $member], function ($message) use ($member) {
			// 	 $message->from('support@mg.user.com', "user");
			// 	 $message->replyTo('support@mg.user.com', "user");
			// 	 $message->subject("Thank you and goodbye from user.com!");
			// 	 $message->to($member->user->email, $member->name);
			//  });
			if($member->user->email)
			{
				Mail::to($member->user->email, $member->name)->send(new ExpireUser(['member' => $member]));
			}
		}
	}

	public function resend(Request $request){


		$user = User::where('email',$request->email)->first();

		if(isset($user) and $user->confirmed == 0){
			//send verification mail to user
			//--------------------------------------------------------------------------------------------------------------
			$data['confirmation_code'] = $user->activation_code;
			$data['name'] = $user->member->name;
			$data['email'] = $user->email;

			Mail::to($data['email'], $data['name'])->send(new RegisterUser($data));
			return redirect()->back()->withMessage('We just resent the activation link to your email, please check your email to activate the account!');
		}
		else
		{
			return redirect()->back()->withMessage('Your account already active.');
		}
	}

	public function search(Request $request)
    {
        $users = User::confirmed();
		
        if($request->key)
        {
            $users = $users/* ->whereRaw("CONCAT(`users`.first_name,' ', `users`.last_name) like '%".$request->key."%'") */
                            ->orWhere('email', 'like', '%'.$request->key.'%');
        }
        $users = $users->limit(5)->get();
        $response = $users->map(function ($user)
        {
            return [
                'id' => $user->id,
                'text' => $user->member->name
            ];
        });

        return response()->json($response,200);
    }

	public function userBranch(Request $request)
	{
		$users = User::whereHas('branches')->get();

		$data['users'] = $users;
		$data['title'] = 'List User Branch';

		return view('user_branch.index', $data);
	}

	public function userBranchCreate(Request $request)
	{
		$users = User::whereDoesntHave('branches')->where('role_id', ROLE_OPERATIONAL)->get();
		$branches = Branch::active()->get();

		$data['branches'] = $branches;
		$data['users'] = $users;
		$data['title'] = 'Create User Branch';

		return view('user_branch.create', $data);
	}

	public function userBranchStore(Request $request)
	{
		try
		{
			foreach($request->user_ids as $user_id)
			{
				$user = User::find($user_id);
	
				$user->branches()->attach($request->branch_ids);
			}

			return redirect()->route('user-branch-index')->withMessage('Sukses menambahkan user branch');
		}
		catch(\Exception $e)
		{
			return redirect()->back()->withErrors('Terjadi kesalahan, silahkan coba lagi');
		}
	}

	public function userBranchEdit($id)
	{
		$user = User::find($id);
		$branches = Branch::active()->get();

		$userBranches = $user->branches->pluck('id')->toArray();

		$data['user'] = $user;
		$data['branches'] = $branches;
		$data['userBranches'] = $userBranches;
		$data['title'] = 'Edit User Branch';

		return view('user_branch.edit', $data);
	}

	public function userBranchUpdate($id, Request $request)
	{
		try
		{
			$user = User::find($id);
	
			$user->branches()->sync($request->branch_ids);

			return redirect()->route('user-branch-index')->withMessage('Sukses update user branch');
		}
		catch(\Exception $e)
		{
			return redirect()->back()->withErrors('Terjadi kesalahan, silahkan coba lagi');
		}
	}

	public function userBranchDelete($user_id)
	{
		try
		{
			$user = User::find($user_id);
			$user->branches()->detach();

			return redirect()->back()->withMessage('Berhasil hapus user branch '. $user->member->name);

		}
		catch(\Exception $e)
		{
			return redirect()->back()->withErrors('Terjadi kesalahan, silahkan coba lagi');
		}
	}
}