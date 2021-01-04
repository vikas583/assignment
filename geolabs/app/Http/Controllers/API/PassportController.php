<?php 
	namespace App\Http\Controllers\API;
	
	use Illuminate\HTTP\Request;
	use App\Http\Controllers\Controller;
	use App\User;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\Facades\Storage;
	use Response;
	use Hash;
	use App\Instruments;
	use App\Company;
	use App\Project;
	use App\Sensortype;
	use App\Sensors;
	use App\Companyprojects;
	use App\CsvData;
	use App\CsvFilesUses;
	use Carbon\Carbon;
	use App\FinalTransformedCsvData;

	class PassportController extends Controller
	{


		public function register(Request $request){
			if( $request->isMethod('post')){
				$validator  = Validator::make($request->all(),[
					'name'=>'required',
					'email'=>'required|email',
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

		public function login(Request $request){


			
				$validator  = Validator::make($request->all(),[
					'email'=>'required|email',
					'password'=>'required'
				]);

				if($validator->fails()){
					return response()->json(['status'=>0,'message'=>$validator->errors()]);
				}

				if(Auth::attempt(['email'=>request('email'),'password'=>request('password')])){
					$user  = Auth::user();
					$success[] = $user->CreateToken('MyApp')->accessToken;
					return response()->json(['status'=>1,'message'=>'Login successfully !','data'=>$success]);
				} else {
					return response()->json(['status'=>0,'message'=>'Unauthoriesed !']);
				}

			
		}

		public function getDetails(){

			$user  = Auth::user();
			return response()->json(['status'=>1,'data'=>$user]);
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

		public function forgetpassword()
		{
			
		}

		public function addInstrument(Request $request)
		{
			if($request->isMethod('post')){
				$data  =  $request->all();
				if (!Auth::check()) {
					return response()->json(['status'=>'0','message'=>'Please login first !']);
				}

				$validator  = Validator::make($request->all(),[
				'instrument_type'=>'required',
				'instrument_type_short'=>'required',
				'northing'=>'required',
				'easting'=>'required',
				'latitude'=>'required',
				'longitude'=>'required',
				'surface_elevation'=>'required',
				'sheet_number'=>'required',
				'installation_notes'=>'required'
			]);

			if($validator->fails()){
				return response()->json(['message'=>$validator->errors(),'status'=>'0']);
			}

				$data['instrument_type']  =  json_encode($data['instrument_type']);
				if(Instruments::create($data)){
					return response()->json(['status'=>'1','message'=>'Instrument add successfully !']);
				} else{
					return response()->json(['status'=>'0','message'=>'Something went wrong. Please try again !']);
				}
			} else{
				return response()->json(['status'=>'0','message'=>'Please method select post type!']);
			}
		}


		public function getInstrument()
		{
			//echo  phpinfo(); die;
			$data   =  Instruments::get();
			if(!empty($data)){
				return response()->json(['status'=>'1','message'=>'Instrument data !','data'=>$data]);
			} else{
				return response()->json(['status'=>'0','message'=>'No instrument found!']);
			}
		}


		public function getInstrumentFilter(Request $request)
		{
			if($request->isMethod('post')){
				$data =    $request->all();

				$validator  = Validator::make($request->all(),[
				'instrument_type_short'=>'required',
			    ]);

				if($validator->fails()){
					return response()->json(['message'=>$validator->errors(),'status'=>'0']);
				}

					$get_data  =  Instruments::where(['instrument_type_short'=>$data['instrument_type_short']])->get();
					
					if(count($get_data)>0){
					    return response()->json(['status'=>'1','message'=>'Instrument data !','data'=>$get_data]);
					} else{
						return response()->json(['status'=>'0','message'=>'No instrument found!']);
					}
				
			} else{
				return response()->json(['status'=>'0','message'=>'Please method select post type!']);
			}
		}


		public function getFile(Request $request)
		{
			$ftp_server = "216.168.50.70";
			$ftp_username  = "TPOGJV";
			$ftp_userpass  = "Fr@tier";
			$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
			$login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);

		//	echo ftp_get_option($ftp_conn,FTP_TIMEOUT_SEC); die;

			$local_file = "reminder-left.png";
			$server_file = "http://nalliancelabs.com/geolabs/storage/app/public/reminder-left.png";

			// download server file
			if (ftp_get($ftp_conn, $local_file, $server_file, FTP_ASCII))
			 {
			  echo "Successfully written to $local_file.";
			 }
			else
			  {
			  echo "Error downloading $server_file.";
			  }

			// close connection
			ftp_close($ftp_conn);
		}


		//  Old Server code 


		// public function download(Request $request) {

		// 	date_default_timezone_set('America/Los_Angeles');
		// 	$current_date  =  date('Y-m-d');
		//     $current_time  =  date('H-i-s');

		// 	//echo "<pre>";print_r($current_date);
		// 	//echo "<pre>";print_r($current_time); die;

	 //        $filename  =  $current_date.' '.$current_time.'.csv';

	 //        $filename  = "10991-readings-2019_12_21_02_00_00.csv.OK";

	 //        if(Storage::disk('ftp')->exists($filename)) {

	 //        	$csv =  Storage::disk('ftp')->get($filename);


	 //        	$array  =  $this->csvstring_to_array($csv);


	 //        	//$csvnew = array_map('str_getcsv',$csv);

	 //        	echo "<pre>";print_r($array);die;

			    
	 //        } 

	       // return Storage::disk('ftp')->download($filename);
	 
		//}





		// public function download(Request $request) {

		// 	date_default_timezone_set('America/Anchorage');
		// 	$current_date  =  date('Y-m-d');
		//     $current_time  =  date('H-i-s');

		// 	//echo "<pre>";print_r($current_date);
		// 	//echo "<pre>";print_r($current_time); die;

	 //        $filename  =  $current_date.' '.$current_time.'.csv';

	 //        $filename  = "10991-readings-2019_12_19_02_00_00.csv.OK";

	 //        if(Storage::disk('ftp')->exists($filename)) {

	 //        	$csv =  Storage::disk('ftp')->get($filename);

	 //        	//$csvnew = array_map('str_getcsv',$csv);

	 //        	echo "<pre>";print_r($csv);die;

			    
	 //        } 

	 //       // return Storage::disk('ftp')->download($filename);
	 
		// }


		public function download(Request $request) {

			error_reporting(E_ALL);

			date_default_timezone_set('America/Los_Angeles');
		

			$ftp_server   =  "ftp.sixense-group.us";

			$ftp_user_name =  "L@ds3ns!ng";

			$ftp_user_pass  = "U8UcJF";

			$conn_id = ftp_connect($ftp_server);

			

			// login with username and password
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

			//echo "<pre>";print_r($login_result);die;
			ftp_pasv($conn_id, true);
			// get contents of the current directory
			$folders = ftp_nlist($conn_id, "/Gateway-13959");

			//dd($folders);

			if(!empty($folders)){
				foreach($folders as $foldval) {
					$filefolders = ftp_nlist($conn_id,$foldval);

					foreach($filefolders as $filevalue){
						if(Storage::disk('ftp')->exists($filevalue)) {
							$ext = pathinfo($filevalue, PATHINFO_EXTENSION);
							//echo $ext;die;
							if($ext == 'OK'){
								$file_save_data['folder_name']   =  $filevalue;
								$file_save_data['date']          =  date('Y-m-d');
								$file_save_data['time']          =  date('H:i:s');

								if(CsvFilesUses::create($file_save_data)){
									$useddata  =  CsvFilesUses::get()->toArray();
									if(!in_array($filevalue,$useddata)){
										$getdata   =  Storage::disk('ftp')->get($filevalue);
										$dataArray =  $this->csvstring_to_array($getdata);
										$index  = 0;
										$array1  = '';
					                    $array2  = '';
										for($i = 0;$i <= count($dataArray); $i++){
											if($index == 9){
												$array1  = $dataArray[$i];
											} elseif($index == 10){
												$array2  =  $dataArray[$i];
											}
											$index++;
										} 

										for($j = 0 ; $j <= count($array1); $j++){
											if($j>0){
												if(!empty($array1[$j])) {
													if(!is_null($array1[$j])){
														if(!empty($array1[$j]) && !ctype_space($array1[$j])){
															$save_data['date_time']    = @$array2[0];
															$save_data['sensor_name']  = @$array1[$j];
															$save_data['sensor_value'] = @$array2[$j];
															$save_data['date']         = date('Y-m-d');
															$save_data['time']         = date('H:i:s');
															CsvData::create($save_data);
														}
													}
													
												}
											}
										}
									}
								}
							}
							
					    } 
					}	
			   }
               
			}
		}
		


	private function csvstring_to_array($string, $separatorChar = ',', $enclosureChar = '"', $newlineChar = "\n") {
		    // @author: Ravi Chauhan
		    $array = array();
		    $size = strlen($string);
		    $columnIndex = 0;
		    $rowIndex = 0;
		    $fieldValue="";
		    $isEnclosured = false;
		    for($i=0; $i<$size;$i++) {

		        $char = $string{$i};
		        $addChar = "";

		        if($isEnclosured) {
		            if($char==$enclosureChar) {

		                if($i+1<$size && $string{$i+1}==$enclosureChar){
		                    // escaped char
		                    $addChar=$char;
		                    $i++; // dont check next char
		                }else{
		                    $isEnclosured = false;
		                }
		            }else {
		                $addChar=$char;
		            }
		        }else {
		            if($char==$enclosureChar) {
		                $isEnclosured = true;
		            }else {

		                if($char==$separatorChar) {

		                    $array[$rowIndex][$columnIndex] = $fieldValue;
		                    $fieldValue="";

		                    $columnIndex++;
		                }elseif($char==$newlineChar) {
		                    echo $char;
		                    $array[$rowIndex][$columnIndex] = $fieldValue;
		                    $fieldValue="";
		                    $columnIndex=0;
		                    $rowIndex++;
		                }else {
		                    $addChar=$char;
		                }
		            }
		        }
		        if($addChar!=""){
		            $fieldValue.=$addChar;

		        }
		    }

		    if($fieldValue) { // save last field
		        $array[$rowIndex][$columnIndex] = $fieldValue;
		    }
		    return $array;
    }

		public function getCompany(){
			$data = Company::get();
			if($data){
				return response()->json(['status'=>1,'message'=>'Company fetcehed successfully!','data'=>$data]);
			}
			else{
				return response()->json(['status'=>0,'message'=>'Something went wrong,Try again later!']);
			}
		}

		public function deleteCompany(Request $request){
			if($request->isMethod('post')){
				$data = $request->all();
				$del = Company::where('id',$data['company_id'])->delete();
				if($del){
					return response()->json(['status'=>1,'message'=>'Company deleted successfully!']);
				}
				else{
					return response()->json(['status'=>0,'message'=>'Something went wrong,Try again later!']);
				}
			}else{
				return response()->json(['status'=>0,'message'=>'Your request method was wrong!']);
			}
		}

		public function updateCompany(Request $request){
			if($request->isMethod('post')){
				$data = $request->all();
				$arr['email']=$data['email'];
				$arr['address']=$data['address'];
				$arr['contact_no']=$data['contact_no'];
				$arr['password']=Hash::make($data['password']);
				$upd = Company::where('id',$data['company_id'])->update($arr);
				if($upd){
					return response()->json(['status'=>1,'message'=>'Updated successfully!']);
				}
				else{
					return response()->json(['status'=>0,'message'=>'Something went wrong,Try again later!']);
				}
			}
			else{
				return response()->json(['status'=>0,'message'=>'Your request method was wrong!']);
			}
		}

		public function getCompanyById(Request $request){
			if( $request->isMethod('post')){
				$validator  = Validator::make($request->all(),[
					'company_id'=>'required',
				]);

				if($validator->fails()){
					return response()->json(['status'=>0,'message'=>$validator->errors()]);
				}

				$post_data  =  $request->all();

				$data = Company::where(['id'=>$post_data['company_id']])->first();
				//dd($data);
				if($data){
					return response()->json(['status'=>1,'message'=>'Company fetcehed successfully!','data'=>$data]);
				}
				else{
					return response()->json(['status'=>0,'message'=>'No data found!']);
				}
			} else{
				return response()->json(['status'=>0,'message'=>'Please method select post type!']);
			}
		}

		public function addproject(Request $request){
			if($request->isMethod('post')){
				if(isset($request['project_id'])){
					$arr['project_name'] = $request['project_name'];
					$arr['project_description'] = $request['project_description'];
					$arr['location'] = $request['location'];
					$upd = Project::where('id',$request['project_id'])->update($arr);
					if($upd){
						return response()->json(['status'=>1,'message'=>'Project updated successfully!']);
					}else{
						return response()->json(['status'=>0,'message'=>'Something went wrong, try again later!']);
					}
				}else{
					$data = $request->all();
					if (!Auth::check()) {
						return response()->json(['status'=>'0','message'=>'Please login first !']);
					}
					else{
						$validator  = Validator::make($request->all(),[
							'project_name'=>'required',
							'location'=>'required'
						]);
						if($validator->fails()){
							return response()->json(['message'=>$validator->errors(),'status'=>'0']);
						}
						if(Project::create($data)){
							return response()->json(['status'=>'1','message'=>'Project added successfully !']);
						} else{
							return response()->json(['status'=>'0','message'=>'Something went wrong. Please try again !']);
						}
					}
				}
				
			}
			else{
				return response()->json(['status'=>'0','message'=>'Please check method type!']);
			}
		}

		public function addsensortype(Request $request){
			if($request->isMethod('post')){
				if(!isset($request['sensor_type_id'])){
					$data = $request->all();
					if (!Auth::check()) {
						return response()->json(['status'=>'0','message'=>'Please login first !']);
					}else{
						$validator  = Validator::make($request->all(),[
							'type_name'=>'required'
						]);
						if($validator->fails()){
							return response()->json(['message'=>$validator->errors(),'status'=>'0']);
						}
						$arr['type_name']=$data['type_name'];
						$arr['unit']=$data['unit'];
						$check = SensorType::where('type_name',$arr['type_name'])->count();
						// dd($check);
						if($check>0){
							return response()->json(['status'=>0,'message'=>'Sensor type name already there!']);
						}
						if(isset($request['sensor_image'])){
							$file=$request['sensor_image'];
							$file->move('public/icons/',$file->getClientOriginalName());
							$arr['icon']=$file->getClientOriginalName();
							
						}
						// dd($arr);
						if(Sensortype::create($arr)){
							return response()->json(['status'=>'1','message'=>'Sensor Type added successfully !']);
						} else{
							return response()->json(['status'=>'0','message'=>'Something went wrong. Please try again !']);
						}
					}
				}
				else{
					$d = $request->all();
					$arr['type_name'] = $d['type_name'];
						$upd = Sensortype::where('id',$d['sensor_type_id'])->update($arr);
						if($upd){
							return response()->json(['status'=>1,'message'=>'Sensor type updated!']);
						}
						else{
							return response()->json(['status'=>0,'message'=>'No sensor type found!']);
						}
										
				}
				
			}
			else{
				return response()->json(['status'=>'0','message'=>'Please check method type!']);
			}
		}

		public function getsensortype(){
			$data = Sensortype::get();
			if($data){
				return response()->json(['data'=>$data,'status'=>1,'message'=>'Sensor type fetched successfully!']);
			}else{
				return response()->json(['status'=>0,'message'=>'No sensor type found!']);
			}
		}

		public function deletesensortype(Request $request){
			$sensor_type_id = $request['sensor_type_id'];
			$del = Sensortype::where('id',$sensor_type_id)->delete();
			if($del){
				return response()->json(['status'=>1,'message'=>'Sensor type deleted successfully!']);
			}else{
				return response()->json(['status'=>0,'message'=>'Something went wrong!']);
			}
		}

		public function addsensor(Request $request){
			if($request->isMethod('post')){
				if(isset($request['sensor_id'])){
					$arr['sensor_name'] = $request['sensor_name'];
					$arr['latitude'] = $request['latitude'];
					$arr['longitude'] = $request['longitude'];
					$arr['formula']=$request['formular'];
					$upd = Sensors::where('id',$request['sensor_id'])->update($arr);
					if($upd){
						return response()->json(['status'=>1,'message'=>'Sensor updated successfully!']);
					}else{
						return response()->json(['status'=>0,'message'=>'something went wrong, try again later!']);
					}
				}else{
					$data = $request->all();
					if (!Auth::check()) {
						return response()->json(['status'=>'0','message'=>'Please login first !']);
					}
					else{
						$validator  = Validator::make($request->all(),[
							'sensor_type_id'=>'required',
							'sensor_name'=>'required',
							'latitude'=>'required',
							'longitude'=>'required'
						]);
						if($validator->fails()){
							return response()->json(['message'=>$validator->errors(),'status'=>'0']);
						}
						$chk = Sensortype::where('id',$data['sensor_type_id'])->get();
						if($chk){
							if(Sensors::create($data)){
								return response()->json(['status'=>'1','message'=>'Sensor added successfully !']);
							} else{
								return response()->json(['status'=>'0','message'=>'Something went wrong. Please try again !']);
							}
						}else{
							return response()->json(['status'=>'0','message'=>'Sensor type doesnot exsist!']);
						}
						
					}
				}
				
			}else{
				return response()->json(['status'=>'0','message'=>'Please check method type!']);
			}
		}

		public function getsensor(Request $request){
			$data = Sensors::with('sensor_type_details')->get();
			if($data){
				return response()->json(['status'=>1,'message'=>'Sensors fetched successfully!','data'=>$data]);
			}else{
				return response()->json(['status'=>0,'message'=>'No sensor found!!']);
			}
		}

		public function deletesensor(Request $request){
			$sensor_id = $request['sensor_id'];
			$del = Sensors::where('id',$sensor_id)->delete();
			if($del){
				return response()->json(['status'=>1,'message'=>'Sensor deleted successfully!']);
			}else{
				return response()->json(['status'=>0,'message'=>'Something went wrong!']);
			}
		}

		public function getproject(){
			$data = Project::get();
			if($data){
				return response()->json(['status'=>1,'message'=>'Projects fetched successfully!','data'=>$data]);
			}
			else{
				return response()->json(['status'=>0,'message'=>'No project found!!']);
			}
		}

		public function deleteproject(Request $request){
			$project_id = $request['project_id'];
			$del = Project::where('id',$project_id)->delete();
			if($del){
				return response()->json(['status'=>1,'message'=>'Project deleted successfully!']);
			}else{
				return response()->json(['status'=>0,'message'=>'Something went wrong!']);
			}
		}

		public function assignproject(Request $request){
			if($request->isMethod('POST')){
				$data = $request->all();
				if(!Auth::check()){
					return response()->json(['status'=>'0','message'=>'Please login first !']);
				}
				else{
					$validator  = Validator::make($request->all(),[
						'company_id'=>'required',
						'project_id'=>'required'
					]);
					if($validator->fails()){
						return response()->json(['message'=>$validator->errors(),'status'=>'0']);
					}
					$chk = Companyprojects::where('company_id',$data['company_id'])->where('project_id',$data['project_id'])->get();
					// dd($chk);
					if($chk){
						return response()->json(['status'=>0,'message'=>'This project has already been assigned to company!']);
					}else{
						if(Companyprojects::create($data)){
							return response()->json(['status'=>'1','message'=>'Project assigned successfully !']);
						} else{
							return response()->json(['status'=>'0','message'=>'Something went wrong. Please try again !']);
						}
					}
					
				}
			}else{
				return response()->json(['status'=>0,'message'=>'request method is wrong!',]);
			}
		}
		public function getassignproject(){
			$data= Companyprojects::with('project_details')->with('company_details')->get();
			if($data){
				return response()->json(['status'=>1,'message'=>'Assigned projects fetched successfully!','data'=>$data]);
			}
			else{
				return response()->json(['status'=>0,'message'=>'No data found!']);
			}
		
		}


		public function csvSensorByName(Request $request){
			if( $request->isMethod('post')){
				$post_data  =  $request->all();
				

				if(empty($post_data['sensor_name'])){
					return response()->json(['status'=>0,'message'=>'Sensor name required!!']);
				}
				$data = CsvData::where(['sensor_name'=>$post_data['sensor_name']])->get();
				if($data){
					return response()->json(['status'=>1,'message'=>'sensor data!','data'=>$data]);
				}else{
					return response()->json(['status'=>0,'message'=>'No sensor found!!']);
				}
			} else{
				return response()->json(['status'=>0,'message'=>'Your request method was wrong!!']);
			}
		}
		

		public function csvSensor(Request $request){
			
			$data = CsvData::get();
			if($data){
				return response()->json(['status'=>1,'message'=>'sensor data!','data'=>$data]);
			}else{
				return response()->json(['status'=>0,'message'=>'No sensor found!!']);
			}
		}

		public function uploadsensors(Request $request){
			$file = $request['file'];
			$filename = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			// $tempPath = $file->getRealPath();
			$fileSize = $file->getSize();
			$mimeType = $file->getMimeType();
			$valid_extension = array("csv");
			$maxFileSize = 2097152; 

			if(in_array(strtolower($extension),$valid_extension)){

				if($fileSize <= $maxFileSize){

					$location = 'uploads';

					$file->move('public/uploads',$filename);
		  
					$filepath = public_path($location."/".$filename);
		  
					$file = fopen($filepath,"r");

					$importData_arr = array();
					$i = 0;
		  
					while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
					   $num = count($filedata);
					   
					   	if($i == 0){
					   		// echo $filedata[0];
					   		// echo "hi";
					   		// echo $filedata[1];
					   		// echo "bye";
					   		// echo $filedata[2];
					   		// echo "byeye";
					   		// echo $filedata[3];
					   		// die;
							 //dd($filedata[0].'	'.$filedata[1].'	'.$filedata[2].'	'.$filedata[3]);
					   		// if($filedata[0] != 'sensor_type_name' || $filedata[1] != 'sensor_name' || $filedata[2] != 'lat' || $filedata[3] != 'long' ){
					   		// 	return response()->json(['status'=>0,'message'=>'Mismatch,Please check and try again!']);
					   		// }

							$i++;
							continue;
						}
					   for ($c=0; $c < $num; $c++) {
					   	//echo "<pre>"; print_r($filedata);
						  $importData_arr[$i][] = $filedata[$c];
					   }
					   $i++;
					}
					

				   // echo "<pre>";print_r($importData_arr);die;

					foreach($importData_arr as $imp_data){
						//echo $imp_data[0]; die;
						$s_type_id = SensorType::where('type_name',$imp_data[0])->first();
						//dd($s_type_id);
						if($s_type_id){
							$insData = array(
								"sensor_type_id"=>$s_type_id['id'],
								"sensor_name"=>$imp_data[1],
								"latitude"=>$imp_data[2],
								"longitude"=>$imp_data[3]
							);
							Sensors::insert($insData);
						}else{
							return response()->json(['status'=>0,'message'=>'Sensor type does not exsist,Please check and try again!']);
						}
						// $insData = array(
						// 	"sensor_type_id"=>$imp_data[0],
						// 	"sensor_name"=>$imp_data[1],
						// 	"latitude"=>$imp_data[2],
						// 	"longitude"=>$imp_data[3]
						// );
						// Sensors::insert($insData);
					}
					return response()->json(['status'=>1,'message'=>'records inserted successfully!']);

				}
				else{
					return response()->json(['status'=>0,'message'=>'File size must be less then 2 MB!']);
				}
			}else{
				return response()->json(['status'=>0,'message'=>'File must be of CSV format!']);
			}
			return response()->json(['status'=>1,'message'=>'true']);
		}

		public function getreport(Request $request){
			if($request->isMethod('POST')){
				$data = $request->all();
				$from = $data['from'];
				$to = $data['to'];
				$from_time = $data['from_time'];
				$to_time = $data['to_time'];
				if($from_time!=NULL && $from_to!=NULL){
					$arr = CsvData::where('sensor_name',$data['sensor_name'])->whereBetween('date',[$from,$to])->whereBetween('time',[$from_time,$to_time])->get();
					if($arr){
						return response()->json(['status'=>1,'message'=>'Data fetched successfully!','data'=>$arr]);
					}
					else{
						return response()->json(['status'=>0,'message'=>'something went wrong,Try again later!']);
					}
				}else{
					$arr = CsvData::where('sensor_name',$data['sensor_name'])->whereBetween('date',[$from,$to])->get();
					if($arr){
						return response()->json(['status'=>1,'message'=>'Data fetched successfully!','data'=>$arr]);
					}
					else{
						return response()->json(['status'=>0,'message'=>'something went wrong,Try again later!']);
					}

				}

				
				
			}else{
				return response()->json(['status'=>1,'message'=>'Please check request method!']);
			}
		}

		public function getmultiplesensordata(Request $request){
			if($request->isMethod('POST')){
				date_default_timezone_set('America/Los_Angeles');
				$c_date  = date('Y-m-d');
				$c_time = date('H:i:s'); 
				//$d_time = date('H:i:s',strtotime($c_time) - (5*60*60));
				$d_time = date("H:i:s", strtotime("-30 minutes", strtotime($c_time)));
				$data  = $request->all();
				if(!empty($data['sensor_name'])){
					if(isset($data['from_date']) && isset($data['to_date']) && isset($data['time_from']) && isset($data['time_to'])){
						$d = CsvData::whereIn('sensor_name',$data['sensor_name'])->whereBetween('date',[$data['from_date'],$data['to_date']])->whereBetween('time',[$data['time_from'],$data['time_to']])->get();
					} else{
					    $d = CsvData::whereIn('sensor_name',$data['sensor_name'])->where('date',date('Y-m-d'))->where(['date'=>$c_date])->whereBetween('time',[$d_time,$c_time])->take(20)->get();
					}
					$result = array();
					foreach ($d as $element) {
					    $result[$element['sensor_name']][] = $element;
					}
					return response()->json(['status'=>1,'message'=>'Data fetched successfully!','data'=>$result]);
				} else{
					return response()->json(['status'=>0,'message'=>'Sensor name required!']);
				}
				
			}
			else{
				return response()->json(['status'=>0,'message'=>'Please check request method!']);
			}
		}

		public function getsensorsontype(Request $request){
			if($request->isMethod('POST')){
				$sensor_type_id = $request['sensor_type_id'];
				$sensors = Sensors::where('sensor_type_id',$sensor_type_id)->get();
				if(count($sensors)>0){
					return response()->json(['status'=>1,'message'=>'Data fetched successfully!','data'=>$sensors]);
				}else{
					return response()->json(['status'=>0,'message'=>'No data found, Corresponding to this sensor type id!']);
				}
			}
			else{
				return response()->json(['status'=>0,'message'=>'Please check the request method!']);
			}
		}

		public function convertvalue(){
			// $csv_data_value = Csvdata::where('sensor_name','freqSqInDigit-10991-VW-Ch1')->first();
			// $data = Sensors::where('sensor_name','freqSqInDigit-10991-VW-Ch1')->first();
			$csv_data_val = Csvdata::get()->first();
			dd($csv_data_val);
			// foreach($csv_data_val as $d){
			// 	if($d['sensor_name']!=NULL){
					
			// 		dd($d['sensor_name']);
			// 	}
			// }
			// $eq = str_replace('x',$csv_data_value->sensor_value,$data->formula);
			
		}

		private function evaluate_expression($eq){
			$temp_op = preg_replace('([^\\+\\-*\\/%\\^])', ' ', trim($eq));
			$temp_op = explode(' ', trim($temp_op));
			
			foreach ($temp_op as $key => $val)
			{
				if ($val)
					$operators[] = $val;
			}
			$numbers = preg_replace('([^0-9\\.])', ' ', trim($eq));
			$numbers = explode(' ', $numbers);
			$i = 0;
			
			foreach ($numbers AS $key => $val)
			{
				if ($key == 0)
				{
					$answer = $val;
					continue;
				}
			
				if ($val)
				{
					switch ($operators[$i])
					{
						case '+':
							$answer += $val;
							break;
							
						case '-':
							$answer -= $val;
							break;
							
						case '*':
							$answer *= $val;
							break;
							
						case '/':
							$answer /= $val;
							break;
							
						case '^':
							$answer ^= $val;
							break;
							
						case '%':
							$answer %= $val;
					}
					
					$i++;
				}
			}

			return $answer;
			
		}
		


	}
?>