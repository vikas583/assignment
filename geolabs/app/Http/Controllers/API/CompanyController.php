<?php 
	namespace App\Http\Controllers\API;
	use App\Model\Authenticator;
	use Illuminate\HTTP\Request;
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Contracts\Auth\Guard;
	use Illuminate\Auth\AuthenticationException;
	use Response;
	use Hash;
	use App\Company;


	class CompanyController extends Controller
	{

		private $authenticator;

	    public function __construct(Authenticator $authenticator)
	    {
	        $this->authenticator = $authenticator;
	    }


	    public function registercompany(Request $request){
	    	if( $request->isMethod('post')){
				$validator  = Validator::make($request->all(),[
					'email'=>'required|email',
					'password'=>'required',
					'c_password'=>'required|same:password',
					'address'=>'required',
					'contact_no'=>'required'
				]);

				if($validator->fails()){
					return response()->json(['status'=>0,'message'=>$validator->errors()]);
				}

				$input                =  $request->all();

				$already_data  =  Company::where(['email'=>$input['email']])->first();

				if(empty($already_data)){
					$input['password']    =  Hash::make($input['password']); 
					$company              =  Company::create($input);
					if($company){	
						$success['token']   =  $company->createToken('MyApp')->accessToken;
						return response()->json(['status'=>1,'message'=>'Registration successfully','data'=>$success]);
					} else {
						return response()->json(['status'=>0,'message'=>'Something went wrong']);
					}
				} else{
					return response()->json(['status'=>0,'message'=>'This mail already exist.Please try another email']);
				}
				
			} else{
				return response()->json(['status'=>0,'message'=>'Your request method was wrong']);
			}
		} 	

	    public function loginCompany(Request $request)
        { 
        	if( $request->isMethod('post')){
				$post_data    =  $request->all(); 
				//dd($post_data);
	            $validator  = Validator::make($request->all(),[
					'email'=>'required|email',
					'password'=>'required'
				]);

				if($validator->fails()){
					return response()->json(['status'=>0,'message'=>$validator->errors()]);
				}


	            $credentials = array($post_data['email'],$post_data['password'], 'company');

		        if (! $user = $this->authenticator->attempt(...$credentials)) {
		            return response()->json(['status'=>0,'message'=>'Invalid Credenatils']);
		        }

		        $token = $user->createToken('MyApp')->accessToken;

		        $data['token_type']  = 'Bearer';
		        $data['access_token']  = $token;
		        return response()->json(['status'=>1,'message'=>'Login successfully','data'=>$data]);
		    } else{
				return response()->json(['status'=>0,'message'=>'Your request method was wrong']);
			}
        }

		public function logoutApi()
		{ 
			if (Auth::check()) {
				$chk=Auth::user()->AauthAcessToken()->delete();
				if($chk){
					return response()->json(['status'=>'1','message'=>'Logout successfully']);
				}
				else{
					return response()->json(['status'=>'0','message'=>'Something went wrong. Please try again']);
				}
			}
			else{
				return response()->json(['status'=>'0','message'=>'Login first!']);
			}
		}

		public function getDetails(){  
			$user  = Auth::user();
			if(!empty($user)){
				return response()->json(['status'=>1,'data'=>$user]);
			} else {
				return response()->json(['status'=>0,'data'=>$user]);
			}
			
		}

		

		
	}
?>