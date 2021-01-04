<?php 
	namespace App\Http\Controllers\API;

	use Illuminate\HTTP\Request;
	use App\HTTP\Controllers\Controller;
	use App\User;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Validator;
	use Hash;

	class UserController extends Controller
	{
	   public function register(Request $request){
	   	    if($request->isMethod('post')){
				$validator  = Validator::make($request->all(),[
					'full_name'=>'required',
					'phone'=>'required',
					'password'=>'required',
					'c_password'=>'required|same:password',
				]);
				if($validator->fails()){
					return response()->json(['status'=>0,'message'=>$validator->errors()]);
				}
				$input  = $request->all();
				$input['password']    = Hash::make($input['password']);
				$user  =  User::create($input);	
				$success['token']   =  $user->createToken('MyApp')->accessToken;
				return response()->json(['status'=>1,'data'=>$success]);
			} else{
				return response()->json(['status'=>0,'message'=>'Your request method was wrong']);
			}
		}

	   
	    public function login(Request $request)
	    {
	        $credentials = [
	            'phone' => $request->phone,
	            'password' => $request->password
	        ];
	 
	        if (auth()->attempt($credentials)) {
	            $token = auth()->user()->createToken('MyApp')->accessToken;
	            return response()->json(['token' => $token], 200);
	        } else {
	            return response()->json(['error' => 'UnAuthorised'], 401);
	        }
	    }
	}
?>