<?php
ob_start();
//session_start();
class database
	{
		private $func;

		function __construct($pdo)
		{
			$this->pdo=$pdo; 
		}

//////////////////////////////////////////////////  Users Zone //////////////////////////////////////////////////
		function adminLogin()
		{
			$user_name= $_POST['user_name'];
			$password= $_POST['password'];
			$query=$this->pdo->prepare("select * from users where user_name='$user_name' && password= '$password'");
			$query->execute();
			$count =$query->rowCount();
			if ($count == 1)
			{	
				while($admin=$query->fetch(PDO::FETCH_OBJ))
					{
						session_start();
						$_SESSION['username']=$admin->user_name;	
						$_SESSION['clientId']=$admin->id;
						$_SESSION['userType']="admin";	
					}
				header("Location:index.php?cpages=booking");
				exit();
			}
			else
			{
				echo '
					<div class="alert alert-danger">
					<strong>فشل</strong>لم تتم عملية التسجيل 
					</div>
					';
			}
		}
	

		function adminLogout()
		{
			session_start();
			session_destroy();
			header('Location:index.php');
			exit();
		}

		function addUser()
		{
			$full_name = $_POST['full_name'];
			$user_name = $_POST['user_name'];
			$password = $_POST['password'];
			$cpassword = $_POST['cpassword'];
			if($password == $cpassword){
			$query= $this->pdo->prepare("insert into users values ('','$full_name','$user_name','$password')");
			$query->execute();
			$rowsadded = $query->rowCount();
			if ($rowsadded == 1 )
				{	
					print "<div class='alert alert-primary alert-white-alt rounded'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<div class='icon'><i class='fa fa-check'></i></div>
						<strong>Success! </strong>تم اضافة مستخدم جديد بنجاح</div>";
				}
			else
				{
					print "<div class='alert alert-danger alert-white rounded'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<div class='icon'><i class='fa fa-times-circle'></i></div>
						<strong>Error!</strong> عفوا حاول مرة اخرى</div>";
				}
			}else{
				print "<div class='alert alert-danger alert-white rounded'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-times-circle'></i></div>
					<strong>Error!</strong> عفوا يجب ان تتطابق كلمتي السر </div>";
			}
			
		}

		function allUsers()
		{
			$var_i=0;
			$expiry = date("Y-m-d");	
			$query=$this->pdo->prepare("SELECT *  FROM users ");
			$query->execute();

			while($user=$query->fetch(PDO::FETCH_OBJ))
			{
				$var_i++;
				print "<tr class='odd gradeX'>
							<td>$var_i</td>
							<td>$user->full_name</td>
							<td>$user->user_name</td>
							<td>
								<form role='form' action='' method='post'>
									<input  type='hidden' value='$user->id' name='delete'>
									<button type='submit' name='delete' value='$user->id' class='btn btn-danger  col-md-5'> حذف </button>
								</form>
								<a  class='btn btn-sm btn-info btn-flat pull-left'>تعديل</a>
							</td>
						</tr>";
			}
		}

		public function deleteUser()
		{
			$user = $_POST['delete'];
		
			print "<div class='alert alert-dismissible alert-warning alert-danger rounded text-center col-md-8'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<div class='icon'><i class='fa fa-warning'></i></div>
						<strong>تنبيه!</strong> هل انت متأكد من مسح العنصر  ? 
						<form role='form' action='' method='post'>
							<input  type='hidden' value='$user' name='confirm_delete'>
							<button type='submit' name='delete' class='btn btn-primary  col-md-4'> تأكيد الحذف </button>
						</form>
					</div>";
		}

		public function deleteUserfirmed()
		{

			$users_id = $_POST['confirm_delete'];
		
			$query=$this->pdo->prepare("delete from users where id = $users_id");
			$query->execute();
			$deleted_row= $query->rowCount();
			if ($deleted_row == 1 )
			{	
			print "<div class='alert alert-dismissible alert-success alert-white-alt rounded'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-check'></i></div>
					<strong>نجاح! </strong>تم حذف الحجز بنجاح</div>";
			}
			else
			{
			print "<div class='alert alert-dismissible alert-danger alert-white rounded'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-times-circle'></i></div>
					<strong>خطأ!</strong> حاول مرة اخرى</div>";
			}

		}

		function allBookings()
		{
			$expiry = date("Y-m-d");	
			$query=$this->pdo->prepare("SELECT *  FROM bookings ");
			$query->execute();
			$var_i = 0;
			while($destans=$query->fetch(PDO::FETCH_OBJ))
			{
				$var_i++;
				print "<tr class='odd gradeX'>
							<td>$var_i</td>
							<td>$destans->c</td>
							<td>$destans->c</td>
							<td>$destans->ticket_owner</td>
							<td>$destans->ticket_for</td>
							<td>$destans->mobile</td>
							<td>$destans->travel_time</td>
							<td>$destans->seates_num</td>
							<td>$destans->total_fees</td>
							<td>
								<form role='form' action='' method='post'>
									<input  type='hidden' value='$destans->id' name='delete'>
									
									<button type='submit' name='delete' value='$destans->id' class='btn btn-danger  col-md-5'> حذف </button>
												
								</form>
								<a  class='btn btn-sm btn-info btn-flat pull-left'>تعديل</a>
						
							</td>
						</tr>";
			
				
			}
		}

		public function deleteBooking()
		{
			$booking = $_POST['delete'];
		
			print "<div class='alert alert-warning alert-danger rounded text-center col-md-8'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-warning'></i></div>
					<strong>تنبيه!</strong> هل انت متأكد من مسح العنصر  $booking ? 	
						<form role='form' action='' method='post'>
							<input  type='hidden' value='$booking' name='confirm_delete'>
							<button type='submit' name='delete' class='btn btn-primary  col-md-4'> تأكيد الحذف </button>
										
						</form>
					</div>";
		}

		public function deleteBookingconfirmed()
		{

			$book_id = $_POST['confirm_delete'];
		
			$query=$this->pdo->prepare("delete from ticket where id = $book_id");
			$query->execute();
			$deleted_row= $query->rowCount();
			if ($deleted_row == 1 )
			{	
			print "<div class='alert alert-primary alert-white-alt rounded'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-check'></i></div>
					<strong>نجاح! </strong>تم حذف الحجز بنجاح</div>";
			}
			else
			{
			print "<div class='alert alert-danger alert-white rounded'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-times-circle'></i></div>
					<strong>خطأ!</strong> حاول مرة اخرى</div>";
			}

		}

		//////////////////////////////////////////////////  Destinations Zone //////////////////////////////////////////////////
		function addDestinations()
		{
			$takeoffs = $_POST['from'];
			$destination = $_POST['to'];
			$price= $_POST['price'];

			$query= $this->pdo->prepare("insert into travel_line values ('','$takeoffs','$destination','$price')");
			$query->execute();
			$pid= $this->pdo->lastInsertId();		
			$rowsadded = $query->rowCount();			
			if ($rowsadded == 1 )
			{	
				echo '
				<div class="alert alert-success">
				<strong>نجاح</strong>تمت عملية الإضافة بنجاح
			</div>
				';
			}
			else
			{
				echo"not added";
			}			
		}
		function allDestinations()
		{
			$expiry = date("Y-m-d");	
			$query=$this->pdo->prepare("SELECT  * FROM travel_line_prices ");
			$query->execute();
			$var_i = 0 ;
			while($destans=$query->fetch(PDO::FETCH_OBJ))
			{		
				$var_i ++;
				print "<tr class='odd gradeX'>
							<td>$var_i</td>
							<td>$destans->takeoffs</td>
							<td>$destans->destination</td>
							<td>$destans->ticket_price</td>
							<td>
								<form role='form' action='' method='post'>
									<input  type='hidden' value='$destans->line_id' name='delete'>
									<button type='submit' name='delete' value='$destans->line_id' class='btn btn-danger  col-md-5'> حذف </button>
								</form>
								<a  class='btn btn-sm btn-info btn-flat pull-left'>تعديل</a>
							</td>
						</tr>";
			}
		}
		public function deleteDestination()
		{
			$line = $_POST['delete'];		
			print "<div class='alert alert-warning alert-danger rounded text-center col-md-8'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-warning'></i></div>
					<strong>تنبيه!</strong> هل انت متأكد من مسح العنصر  $line ? 
					<form role='form' action='' method='post'>
						<input  type='hidden' value='$line' name='confirm_delete'>
						<button type='submit' name='delete' class='btn btn-primary  col-md-4'> تأكيد الحذف </button>			
					</form>
				</div>";
		}

		public function deleteDestinationconfirmed()
		{
			$line_id = $_POST['confirm_delete'];		
			$query=$this->pdo->prepare("delete from travel_line where id = $line_id");
			$query->execute();
			$deleted_row= $query->rowCount();
			if ($deleted_row == 1 )
			{	
			print "<div class='alert alert-primary alert-white-alt rounded'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-check'></i></div>
					<strong>نجاح! </strong>تم حذف الحجز بنجاح</div>";
			}
			else
			{
			print "<div class='alert alert-danger alert-white rounded'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-times-circle'></i></div>
					<strong>خطأ!</strong> حاول مرة اخرى</div>";
			}
		}
		//////////////////////////////////////////////////  Travel Lines Zone//////////////////////////////////////////////////
      public function getAllTakeOffs()
		{
			$expiry = date("Y-m-d");	
			$query=$this->pdo->prepare("SELECT *  FROM cities ");
			$query->execute();

			echo '<select name="from" class="form-control">'; // Open your drop down box
			echo '<option value="" disabled> اختر مدينة</option>';
			while($city=$query->fetch(PDO::FETCH_OBJ))
			{
				echo '<option value="'.$city->id.'">'.$city->name.'</option>';
				
			}
			echo '</select>';

		}

		public function getAlldestinations()
		{
			$expiry = date("Y-m-d");	
			$query=$this->pdo->prepare("SELECT *  FROM cities ");
			$query->execute();

			echo '<select name="to" class="form-control">'; // Open your drop down box
			echo '<option value="" disabled> اختر مدينة</option>';
			while($city=$query->fetch(PDO::FETCH_OBJ))
			{
				echo '<option value="'.$city->id.'">'.$city->name.'</option>';
				
			}
			echo '</select>';

		}

		public function getAlltravelLine()
		{
			$expiry = date("Y-m-d");	
			$query=$this->pdo->prepare("SELECT *  FROM travel_line_prices ");
			$query->execute();

			echo '<select name="buss_line" class="form-control">'; // Open your drop down box
			echo '<option value="" disabled> اختر مدينة</option>';
			while($line=$query->fetch(PDO::FETCH_OBJ))
			{
				echo '<option value="'.$line->line_id.'">'.$line->takeoffs.' إلى '.$line->destination.'</option>';
				
			}
			echo '</select>';	
		}

		//////////////////////////////////////////////////  Users Buss //////////////////////////////////////////////////
				
		function addNewBuss()
		{
			$model = $_POST['car_model'];
			$plate = $_POST['plate'];
			$color= $_POST['color'];
			$seats = $_POST['seats'];

			$buss_query= $this->pdo->prepare("insert into busses values ('','$model','$plate','$color','$seats')");
			$buss_query->execute();
			$buss_id= $this->pdo->lastInsertId();
			$new_row = $buss_query->rowCount();
			
			if ($new_row == 1 )
			{	
				echo '
				<div class="alert alert-success">
				<strong>نجاح</strong>تمت عملية الإضافة بنجاح
				</div>
				';
			}else{
					echo"عفوا لم تتم عميلة الإضافة";
				}
			
		}

		function allBusses()
		{
			$expiry = date("Y-m-d");	
			$query=$this->pdo->prepare("SELECT *  FROM busses ");
			$query->execute();
			$var_i = 0 ;
			while($buss=$query->fetch(PDO::FETCH_OBJ))
			{			$var_i ++;
				print "<tr class='odd gradeX'>
							<td>$var_i</td>
							<td>$buss->model</td>
							<td>$buss->plate_number</td>
							<td>$buss->color</td>
							<td>$buss->seats</td>
							<td>
								<form role='form' action='' method='post'>
									<input  type='hidden' value='$buss->id' name='delete'>
									
									<button type='submit' name='delete' value='$buss->id' class='btn btn-danger  col-md-5'> حذف </button>
												
								</form>
								<a  class='btn btn-sm btn-info btn-flat pull-left'>تعديل</a>
						
							</td>
						</tr>";
			
				
			}
		}

		public function deleteBuss()
		{
			$buss = $_POST['delete'];
		
			print "<div class='alert alert-warning alert-danger rounded text-center col-md-8'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-warning'></i></div>
					<strong>تنبيه!</strong> هل انت متأكد من مسح العنصر  $buss ?	
						<form role='form' action='' method='post'>
							<input  type='hidden' value='$buss' name='confirm_delete'>
							<button type='submit' name='delete' class='btn btn-primary  col-md-4'> تأكيد الحذف </button>
										
						</form>
					</div>";
		}

		public function deleteBussconfirmed()
		{

			$buss_id = $_POST['confirm_delete'];
		
			$query=$this->pdo->prepare("delete from travel_line_buss where buss_id = $buss_id");
			$query->execute();
			$deleted_row= $query->rowCount();
			if ($deleted_row == 1 )
			{
				$query1=$this->pdo->prepare("delete from busses where id = $buss_id");
				$query1->execute();
				$deleted_row1= $query1->rowCount();

				if ($deleted_row1 == 1 )
					{	print "<div class='alert alert-primary alert-white-alt rounded'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<div class='icon'><i class='fa fa-check'></i></div>
							<strong>نجاح! </strong>تم حذف الحجز بنجاح</div>";
					}
					else
					{   print "<div class='alert alert-danger alert-white rounded'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<div class='icon'><i class='fa fa-times-circle'></i></div>
							<strong>خطأ!</strong> حاول مرة اخرى</div>";
					}
			}else 
			{
				$query1=$this->pdo->prepare("delete from busses where id = $buss_id");
				$query1->execute();
				$deleted_row1= $query1->rowCount();
				if ($deleted_row1 == 1 )
					{	 print "<div class='alert alert-primary alert-white-alt rounded'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<div class='icon'><i class='fa fa-check'></i></div>
							<strong>نجاح! </strong>تم حذف الحجز بنجاح</div>";
					}
					else
					{   
						print "<div class='alert alert-danger alert-white rounded'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
							<div class='icon'><i class='fa fa-times-circle'></i></div>
							<strong>خطأ!</strong> حاول مرة اخرى</div>";
					}
			}
		}
		
		////////////////////////////////////////////////// Clients Zone //////////////////////////////////////////////////
	
		function allClients()
		{
			$expiry = date("Y-m-d");	
			$query=$this->pdo->prepare("SELECT *  FROM client ");
			$query->execute();
			$var_i =0;
			while($client=$query->fetch(PDO::FETCH_OBJ))
			{
				$var_i++;			
				print "<tr class='odd gradeX'>
							<td>$var_i</td>
							<td>$client->first_name  $client->last_name</td>
							<td>$client->gender</td>
							<td>$client->phone</td>
							<td>$client->email</td>
							<td>$client->join_date</td>
							<td>
								<form role='form' action='' method='post'>
									<input  type='hidden' value='$client->id' name='delete'>
									
									<button type='submit' name='delete' value='$client->id' class='btn btn-danger  col-md-5'> حذف </button>
												
								</form>
								<a  class='btn btn-sm btn-info btn-flat pull-left'>تعديل</a>
						
							</td>
						</tr>";
			
				
			}
		}

		function addClients()
		{
			$firstname = $_POST['first_name'];
			$lastname = $_POST['last_name'];
			//$gender = $_POST['gender'];
			$gender="male";
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$password= $_POST['password'];
			$cpassword= $_POST['cpassword'];

			if($password == $cpassword){
				$query= $this->pdo->prepare("insert into client values ('','$firstname','$lastname','
				$gender','$email','$phone','$password','')");
				$query->execute();
				$pid= $this->pdo->lastInsertId();
			
				$rowsadded = $query->rowCount();
				
				if ($rowsadded == 1 )
				{	
					
					header("Location:?cpages=login");
				}
				else
				{
					echo '
					<div class="alert alert-danger">
					<strong>فشل</strong>لم تتم عملية التسجيل 
					</div>
					';
				}
			}else{
				echo '
					<div class="alert alert-danger">
					<strong>فشل</strong>عفوا كلمتي السر غير متطابقتين
					</div>
					';
			}
			
		}


/// mobile data    




public function getBusesMobile2()
		{
			$app= new \stdClass();
			$innerapp= new \stdClass();
			//$journey_date="24-12-2019";
			$destination="kitale";
			$source="NAIROBI";
			
			//$query=$this->pdo->prepare("select * from busses where source= 	'$source' && = destination= '$destination'&& journey_date= '$journey_date'");
			//$query=$this->pdo->prepare("select * from busses where source='$source' && destination= '$destination'&& journey_date= '$journey_date'");
			$query->execute();			
			$return_arr=array();
			$bus_list = $query->fetchAll();
			//while($bus=$query->fetch(PDO::FETCH_ASSOC))			
			foreach ($bus_list as $bus)			
			{	
				
				$innerapp->model = $bus->model;
				$innerapp->plate_number = $bus->plate_number;
				$innerapp->color = $bus->color;
				$innerapp->seats = $bus->seats;
				$innerapp->rand_tag = $bus->rand_tag;
				$innerapp->price = $bus->price;
				$innerapp->departure_time = $bus->departure_time;
				$innerapp->journey_date = $bus->journey_date;
				$innerapp->bus_company = $bus->bus_company;
				$innerapp->source = $bus->source;
				$innerapp->destination = $bus->destination;
				$innerapp->duration = $bus->duration;




				array_push($return_arr,$innerapp);				
				
			}
			$jsonData = json_encode($return_arr);
			echo $jsonData;	
		}



		////// ACTUAL ONE

		function BKUPgetBusesMobile()
		{
			$app= new \stdClass();
			
			$return_arr=array();
			$journey_date="23-12-2019";
			$destination="kitale";
			$source="NAIROBI";
			
			//$query=$this->pdo->prepare("select * from busses where source= 	'$source' && = destination= '$destination'&& journey_date= '$journey_date'");
			//$query=$this->pdo->prepare("select * from busses where source='$source' && destination= '$destination'&& journey_date= '$journey_date'");
			$query=$this->pdo->prepare("select * from busses where source='$source' && destination= '$destination'");
			$query->execute();
			$var_i = 0 ;
			while($bus=$query->fetch(PDO::FETCH_OBJ))
			{			
				$innerapp= new \stdClass();
				$pointList_details= new \stdClass();
				$booked_seat=array();

				// $innerapp->model = $bus->model;
				// $innerapp->plate_number = $bus->plate_number;
				// $innerapp->color = $bus->color;
				// $innerapp->seats = $bus->seats;
				// $innerapp->rand_tag = $bus->rand_tag;
				// $innerapp->price = $bus->price;
				// $innerapp->departure_time = $bus->departure_time;
				// $innerapp->journey_date = $bus->journey_date;
				// $innerapp->bus_company = $bus->bus_company;
				// $innerapp->source = $bus->source;
				// $innerapp->destination = $bus->destination;
				$bus_tag= $bus->rand_tag;
				///getting the booked seats 
				    $query2=$this->pdo->prepare("select * from bookedseats where bustag='$bus_tag'");
					$query2->execute();
					if($query2->rowCount()>0){
			        while($seat=$query2->fetch(PDO::FETCH_OBJ))
			               {
							   $seatB=$seat->booked_seats;
							array_push($booked_seat,$seatB);
									   }
								}
				$innerapp->booked=$booked_seat;	

				//getting the pick up locations	


				$query3=$this->pdo->prepare("select * from bus_pick_up_times where bus_tag='$bus_tag'");
			//	$query3=$this->pdo->prepare("select * from bus_pick_up_times where bus_tag='bus_id157877636889GHY'");
					$query3->execute();
					if($query3->rowCount()>0){
			        while($points=$query3->fetch(PDO::FETCH_OBJ))
						   {//get point id
							 $pick_point_data= array();
							   $point_id=$points->point_id;
							   $point_time=$points->pick_up_time;
								   //getting the pick up locations and times

								   $query4=$this->pdo->prepare("select * from city_stops where point_id='$point_id'");
								   $query4->execute();
								   $innerapp2= new \stdClass();
								  
								   if($query4->rowCount()>0){
									

									
								   while($point_details=$query4->fetch(PDO::FETCH_OBJ))
										  {
											
											  $point_name=$point_details->pick_point;
											  
										  // array_push($booked_seat,$seatB);
										  $pt = $point_name.' - '. $point_time;
										  $pt = $point_name.' - '. $point_time;
										array_push($pick_point_data,$pt);									 
										  
													  }													 
											   }
											  // array_push($pick_pointz,$pick_point_data);	
											  $innerapp2->pickPoints=$pick_point_data;						
								
				//end of gettin point data

									   }
									   array_push($return_arr,$innerapp2);	
								}
		
				array_push($return_arr,$innerapp);
								
				
			}
			$jsonData = json_encode($return_arr);
			echo $jsonData;	
				
				
			
		}



//// TEST backup
function getBusesMobile()
		{
			$app= new \stdClass();
			
			$return_arr=array();
			// $journey_date="23-12-2019";
			// $destination="kitale";
			// $source="NAIROBI";

			//$journey_date=$_POST['date'];
			$destination=$_POST['destination'];
			$source=$_POST['source'];
			
			//$query=$this->pdo->prepare("select * from busses where source= 	'$source' && = destination= '$destination'&& journey_date= '$journey_date'");
			//$query=$this->pdo->prepare("select * from busses where source='$source' && destination= '$destination'&& journey_date= '$journey_date'");
			$query=$this->pdo->prepare("select * from busses where source='$source' && destination= '$destination'");
			$query->execute();
			$var_i = 0 ;
			while($bus=$query->fetch(PDO::FETCH_OBJ))
			{			
				$innerapp= new \stdClass();
				//$innerapp= new \stdClass();
				$pointList_details= new \stdClass();
				$booked_seat=array();
				$pick_point_data=array();


				$innerapp->model = $bus->model;
				$innerapp->duration = $bus->duration;
				$innerapp->plate_number = $bus->plate_number;
				$innerapp->color = $bus->color;
				$innerapp->seats = $bus->seats;
				$innerapp->rand_tag = $bus->rand_tag;
				$innerapp->price = $bus->price;
				$innerapp->departure_time = $bus->departure_time;
				$innerapp->journey_date = $bus->journey_date;
				$innerapp->bus_company = $bus->bus_company;
				$innerapp->source = $bus->source;
				$innerapp->destination = $bus->destination;
				$bus_tag= $bus->rand_tag;
				///getting the booked seats 
				    $query2=$this->pdo->prepare("select * from bookedseats where bustag='$bus_tag'");
					$query2->execute();
					if($query2->rowCount()>0){
			        while($seat=$query2->fetch(PDO::FETCH_OBJ))
			               {
							   $seatB=$seat->booked_seats;
							array_push($booked_seat,$seatB);
									   }
								}
				$innerapp->booked=$booked_seat;	

				///GETING THE PICK UP LOCATIONS
				
				$query3=$this->pdo->prepare("select * from bus_pick_up_times where bus_tag='$bus_tag'");
				$query3->execute();				
				if($query3->rowCount()>0){
				while($bus_data=$query3->fetch(PDO::FETCH_OBJ))
					   {
						   $point_id=$bus_data->point_id;						   
						   $point_time=$bus_data->pick_up_time;
						   $pickPointNtime=$this->getPickUpData($point_id,$point_time);					
						   array_push($pick_point_data,$pickPointNtime);					
								   }
							}
			//$innerapp->booked=$booked_seat;
			$innerapp->pickPoint=$pick_point_data;	
								
		
				array_push($return_arr,$innerapp);
								
				
			}
			$jsonData = json_encode($return_arr);
			echo $jsonData;	
				
				
			
		}











//end of backkup


function getPickUpData($point_id,$point_time){
	$query=$this->pdo->prepare("select * from city_stops where point_id='$point_id'");
	$query->execute();
	if($query->rowCount()>0){
	$stop_data=$query->fetch(PDO::FETCH_OBJ);
		   
			   $stop_name=$stop_data->pick_point;
			   $stop_desc=$stop_data->description;
			  // echo "in function</br>";
			   $Point_name_n_time=$stop_name.' - '.$point_time;
			   return $Point_name_n_time;

			//array_push($booked_seat,$seatB);
					   
				}
//$innerapp->booked=$booked_seat;








}






		function mobSetBookedSeats(){

			$bus_tag = $_POST['bus_tag'];
			$seat_nos = $_POST['selectedIds'];

			$app= new \stdClass();

			// $bus_tag="rerer";
			// 	 $seat_no="24";
			$seat_arr = explode (",", $seat_nos);
			$no =sizeOf($seat_arr);
			$ctr=0;
			for($i=0;$i<$no;$i++)
			{
				if($seat_arr[$i]!=""&& $bus_tag!="")
				{

				$query= $this->pdo->prepare("INSERT INTO `bookedseats`VALUES ('$bus_tag', '$seat_arr[$i]', '')");
				$query->execute();
				$pid= $this->pdo->lastInsertId();			
				$rowsadded = $query->rowCount();
				if ($rowsadded >0 )
				{	
					$ctr++;
							
				}
				else{
					//$app->status = "failed";

				}
			}



			}
			if($ctr==$no){
				$app->status = "success";

			}
			else
			{
				$app->status = "failed";

			}
			
			


		$app= new \stdClass();
		
					$jsonData = json_encode($app);
                    echo $jsonData."\n"; 

	}




		


		function addClientsMobile()
		{	$app= new \stdClass();


			 $firstname = $_POST['first_name'];
			 $lastname = $_POST['last_name'];
			 $gender = $_POST['gender'];
			 $email = $_POST['email'];
			 $phone =  $_POST['phone'];
			$password= $_POST['password'];
			//$firstname = "test";
			//$lastname = "test";
			// $gender = "test";
			//$email = "test";
			//$phone =  "test";
			$date=date("d-m-Y , g:i a").'';
			//$password= "teewrdfr";	
			
			

			$query=$this->pdo->prepare("select * from client where email='$email' && password= '$password'");
			$query->execute();
			$count =$query->rowCount();
			if ($count==0)
			{
				$query= $this->pdo->prepare("insert into client values ('','$firstname','$lastname','$gender','$email','$phone','$password','','$date')");
				$query->execute();
				$pid= $this->pdo->lastInsertId();			
				$rowsadded = $query->rowCount();
				if ($rowsadded == 1 )
				{	
					$app->status = "client_added";
					$app->first_name = $firstname;
					$app->last_name=$lastname;
					$app->email=$email;
					// $app->first_name = "frank";
					// $app->last_name="frank";
					// $app->email="frank@qmail.com";	
					// $app->password="testdata";	
					$jsonData = json_encode($app);
                    echo $jsonData."\n"; 					
				}
				else
				{
					$app->status = "client_not_added_inner";
					$app->uid = "failed";
					$jsonData = json_encode($app);
                    echo $jsonData."\n"; 
					
				}
					

			}
			else{
				$app->status = "email_already_registered";
					$jsonData = json_encode($app);
                    echo $jsonData."\n"; 

			}




				
			
			
		}

		function ClientLoginMobile()
		{
			$app= new \stdClass();
			$email= $_POST['email'];
			$password= $_POST['password'];
			// $email= "test";
			// $password= "teewrdfr";
			$query=$this->pdo->prepare("select * from client where email='$email' && password= '$password'");
			$query->execute();
			$count =$query->rowCount();
			if ($count==1)
			{	
				$client=$query->fetch(PDO::FETCH_OBJ);
				$app->status = "login_success";	
				$app->first_name = $client->first_name;
				$app->last_name= $client->last_name;
				$app->email=$client->email;
				$app->phone=$client->phone;
				$jsonData = json_encode($app);
                    echo $jsonData."\n"; 	
				
				
				
			}
			else
			{
				$app->status="failed" ;	$jsonData = json_encode($app);
				echo $jsonData."\n"; 	
				
			}
		}



		public function getCities()
		{
			$app= new \stdClass();
			$source_cities=[];	
			$dest_cities=[];			
			$query=$this->pdo->prepare("SELECT DISTINCT `source` FROM `busses`");
			$query->execute();		
			while($city=$query->fetch(PDO::FETCH_OBJ))
			{ 
				array_push($source_cities,$city->source);				
			}
			$app->sourceList=$source_cities;
			$query=$this->pdo->prepare("SELECT DISTINCT `destination` FROM `busses`");
			$query->execute();		
			while($city=$query->fetch(PDO::FETCH_OBJ))
			{ 
				array_push($dest_cities,$city->destination);				
			}			
			$app->destList=$dest_cities;		
			echo json_encode($app);			
		}

	//	`city_stops`(`bus_company`, `city`, `pick_point`, `description`, `boarding`, `depture`, `bus_tag`)


	public function getCompanyCities()
	{
		$app= new \stdClass();
		//$bus_company="kings travels";
		$cities=[];	
				
		$bus_company = $_POST['busCompany'];
		$query=$this->pdo->prepare("SELECT DISTINCT `city` FROM `company_cities` where `bus_company`='$bus_company' ORDER BY `city` ASC ");
		$query->execute();		
		while($city=$query->fetch(PDO::FETCH_OBJ))
		{ 
			array_push($cities,$city->city);
			
		}
		$app->cityList=$cities;
	
		echo json_encode($app);

		
	}

	
	public function getCompanyCitiesStops()
	{
		$app= new \stdClass();
		//$bus_company="kings travels";						
		//$source_city = 'Nairobi';

		$bus_company=$_POST['busCompany'];						
		$source_city = $_POST['city'];

		
		$cities=[];	
		$query=$this->pdo->prepare("SELECT DISTINCT `pick_point` FROM `city_stops` where `bus_company`='$bus_company'  AND `city`='$source_city' ORDER BY `city` ASC ");
		$query->execute();		
		while($city=$query->fetch(PDO::FETCH_OBJ))
		{ 
			array_push($cities,$city->pick_point);
			
		}
		$app->pointList=$cities;
	
		echo json_encode($app);

		
	}




		
	



		public function getClientBookings()
		{
			$app= new \stdClass();
			//$email=$_POST[email];	
			$email="fr@g.com";			
			$query=$this->pdo->prepare("SELECT *  FROM ticket where client_id='$email' ");
			$query->execute();
			$row_count=$query->fetch(PDO::FETCH_OBJ);
			$count =$query->rowCount();
			if($count!=0){
				$app->status="bookings available";
				$app->fromCity=$row_count->startlocation;
				$app->toCity=$row_count->destination;
				$app->owner=$row_count->owner;
				$app->date=$row_count->date;
				$app->seates=$row_count->seates;
			}
			else 
			{
				$app->status="no bookings";


			}
			echo json_encode($app);
			
		}





		function clietBookingMobile()
		{
			 $app= new \stdClass();
			 $from = $_POST['from'];
			 $destination = $_POST['to'];
			 $date = $_POST['date'];
			 $owner = $_POST['owner'];
			 $seates = $_POST['seates'];
			// $client_id = $_SESSION['clientId'];
			// $user_type = $_SESSION['userType'];

			//$from = "nairobi";
			//$destination = "nairobi kL";
			//$date = '22\\4\\2019';
			//$owner = 'owner';
			//$seates = "3";
			$client_id =$_POST['email'];
			// $user_type = $_SESSION['userType'];

			$query= $this->pdo->prepare("insert into ticket values (NULL,'$client_id','$from','$destination','$owner','$date','$seates')");
			$query->execute();
				
			$rowsadded = $query->rowCount();
			
			if ($rowsadded == 1 )
			{	
				$app->status="succesfuly added well ";				
			}
			else
			{
		
				$app->status="not added";
			}
			echo json_encode($app);
			
		}

		
		public function deleteClient()
		{
			$booking = $_POST['delete'];
		
			print "<div class='alert alert-warning alert-danger rounded text-center col-md-8'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-warning'></i></div>
					<strong>تنبيه!</strong> هل انت متأكد من مسح العنصر  $booking ? 
						<form role='form' action='' method='post'>
							<input  type='hidden' value='$booking' name='confirm_delete'>
							<button type='submit' name='delete' class='btn btn-primary  col-md-4'> تأكيد الحذف </button>
										
						</form>
					</div>";
		}

		public function deleteClientconfirmed()
		{

			$client_id = $_POST['confirm_delete'];
		
			$query=$this->pdo->prepare("delete from client where id = $client_id");
			$query->execute();
			$deleted_row= $query->rowCount();
			if ($deleted_row == 1 )
			{	
			print "<span class='alert alert-success  rounded'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
						<div class='icon'><i class='fa fa-check'></i></div>
						<strong>نجاح! </strong>تم حذف الحجز بنجاح
					</span>";
			}
			else
			{
			print "<div class='alert alert-danger alert-white rounded'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
					<div class='icon'><i class='fa fa-times-circle'></i></div>
					<strong>خطأ!</strong> حاول مرة اخرى</div>";
			}

		}

		function ClientLogin()
		{
			$email= $_POST['email'];
			$password= $_POST['password'];
			$query=$this->pdo->prepare("select * from client where email='$email' && password= '$password'");
			$query->execute();
			$count =$query->rowCount();
			if ($count == 1)
			{	
				while($client=$query->fetch(PDO::FETCH_OBJ))
					{
						session_start();
						$_SESSION['username']=$client->first_name.' '.$client->last_name ;	
						$_SESSION['clientId']=$client->id;
						$_SESSION['userType']="client";		
					}
				
				header("Location:?cpages=book");
				exit();
			}
			else
			{
				echo '
				<div class="alert alert-danger">
				<strong>فشل</strong>عفوا اسم المستخدم او كلمة المرور خطأ
				</div>
					';
			}
		}

		function clietLogout()
		{
			session_start();
			session_destroy();
			header("Location:?cpages=login");
			exit();
		}

		function clietBooking()
		{
			$from = $_POST['from'];
			$destination = $_POST['to'];
			$date = $_POST['date'];
			$owner = $_POST['owner'];
			$seates = $_POST['seates'];
			$client_id = $_SESSION['clientId'];
			$user_type = $_SESSION['userType'];

			$query= $this->pdo->prepare("insert into ticket values (NULL,'$client_id','$from','$destination','$owner','$date','$seates')");
			$query->execute();
			$pid= $this->pdo->lastInsertId();
		
			$rowsadded = $query->rowCount();
			
			if ($rowsadded == 1 )
			{	
				
				// ini_set("SMTP","ssl://smtp.gmail.com");
				// ini_set("smtp_port","465");
				// $to_email = 'franksaraencci@gmail.com';
				// $subject = 'Testing PHP Mail';
				// $message = 'This mail is sent using the PHP mail function';
				// $headers = 'From: adam.mousa13@gmail.com';
				// mail($to_email,$subject,$message,$headers);
				// mail($to_email_address,$subject,$message,[$headers],[$parameters]);
				
				// echo '
				// <div class="alert alert-success">
				// <strong>نجاح</strong>تمت عملية الحجز بنجاح
				// </div>
				// 	';
			}
			else
			{
				echo"عفوا لم يتم الحجز بنجاح ";
			}
			
		}

		function addNewBussMobile()
		{
			$app= new \stdClass();
			$plateNo = $_POST['plateNo'];
			$date = $_POST['date'];
			$seats = $_POST['seats'];
			$duration = $_POST['duration'];
			$price = $_POST['price'];
			$destination = $_POST['destination'];
			$from = $_POST['from'];
			$jorneyTme = $_POST['jorneyTme'];
			$buscompany = $_POST['buscompany'];
			$color = $_POST['busColor'];
			$model = $_POST['busModel'];
			$startPoint = $_POST['startPoint'];


			$rand_tag="bus_id".time()."89GHY";
			
			


			// $ = $_POST[''];
			// $ = $_POST[''];
			// $ = $_POST[''];
			//   (`id`, `model`, `plate_number`, `color`, `seats`, 
			// `rand_tag`, `price`, `departure_time`, `journey_date`, 
			// `bus_company`, `source`, `destination`,
			//  `duration`)

			$query= $this->pdo->prepare("insert into busses values ('','$model','$plateNo','$color','$seats'
			,'$rand_tag','$price','$jorneyTme','$date',
			'$buscompany','$from','$destination','$duration')");
			$query->execute();
				
			$rowsadded = $query->rowCount();
			
			if ($rowsadded == 1 )
			{	
				$app->status="bus_added_success";				
			}
			else
			{
				
				$app->status="failed_to_add";
			}

				
				$jsonData = json_encode($app);
                    echo $jsonData."\n"; 	
				
	
			// $plate = $_POST['plate'];
			// $color= $_POST['color'];
			// $seats = $_POST['seats'];

			// $buss_query= $this->pdo->prepare("insert into busses values ('','$model','$plate','$color','$seats')");
			// $buss_query->execute();
			// $buss_id= $this->pdo->lastInsertId();
			// $new_row = $buss_query->rowCount();
			
			// if ($new_row == 1 )
			// {	
			// 	echo '
			// 	<div class="alert alert-success">
			// 	<strong>نجاح</strong>تمت عملية الإضافة بنجاح
			// 	</div>
			// 	';
			// }else{
			// 		echo"عفوا لم تتم عميلة الإضافة";
			// 	}
			
		}
		function mobgetagentData()
		{
			//$bus_company= $_POST['bus_company'];
			$bus_company="kings travels" ;
			$app= new \stdClass();
			$query2=$this->pdo->prepare("select * from busses where bus_company='$bus_company'");
			$query2->execute();
			$count2 =$query2->rowCount();				
			$app->busnos=$count2;

			$jsonData = json_encode($app);
            echo $jsonData."\n"; 	

		}

		function addCompanyCityMobile()
		{
			$app= new \stdClass();
			$bus_company = $_POST['busCompany'];
			$city = $_POST['city'];
			// $bus_company ="test company";
			// $city = "test city";
			$query= $this->pdo->prepare("insert into company_cities values ('$bus_company','$city')");
			$query->execute();				
			$rowsadded = $query->rowCount();			
			if ($rowsadded == 1 )
			{	
				$app->status="bus_added_success";				
			}
			else
			{
				
				$app->status="failed_to_add";
			}				
				$jsonData = json_encode($app);
                    echo $jsonData."\n"; 			
	
			
		}




		function addCompanyCityStopsMobile()
		{
			$app= new \stdClass();
			$bus_company = $_POST['busCompany'];
			$city = $_POST['city'];
			$pick_point = $_POST['stopName'];
			$description = $_POST['description'];
			$point_id = $bus_company.$city.$pick_point;




			
			// $boarding = $_POST['boarding'];
			// $depture = $_POST['depture'];
			// $bus_tag = $_POST['bus_tag'];			
			// $bus_company ="test company";
			// $city = "test city";



			// $bus_company ="test company";
			// $city = 'city';
			// $pick_point = 'pick_point';
			// $description = 'description';

			//	`city_stops`(`bus_company`, `city`, `pick_point`, `description`, )
			$query= $this->pdo->prepare("insert into city_stops values ('$bus_company','$city','$pick_point','$description','$point_id')");
			$query->execute();				
			$rowsadded = $query->rowCount();			
			if ($rowsadded == 1 )
			{	
				$app->status="bus_added_success";				
			}
			else
			{
				
				$app->status="failed_to_add";
			}				
				$jsonData = json_encode($app);
                    echo $jsonData."\n"; 			
	
			
		}








		function agentLoginMobile()
		{

			$app= new \stdClass();

			// $email= $_POST['email'];
			// $password= $_POST['password'];
		    // $passcode= $_POST['passcode'];
			$email= "king@king.com";
			$password= "admin12345678";
			$passcode= "kingking";	

	
			$query=$this->pdo->prepare("select * from agents where email='$email' && password= '$password' && passcode= '$passcode'");
			$query->execute();
			$count =$query->rowCount();
			if ($count==1)
			{	
				$client=$query->fetch(PDO::FETCH_OBJ);
				$app->status = "login_success";	
				// $app->first_name = $client->first_name;
				// $app->last_name= $client->first_name;
				// $app->email=$client->email;
				$app->buscompany=$client->bus_company;


				$query2=$this->pdo->prepare("select * from busses where bus_company='$client->bus_company'");
				$query2->execute();
				$count2 =$query2->rowCount();
				
				$app->busnos=$count2;

				$jsonData = json_encode($app);
                    echo $jsonData."\n"; 	
				
				
				
			}
			else
			{
				$app->status="failed" ;	$jsonData = json_encode($app);
				echo $jsonData."\n"; 	
				
			}



		}









		////////////////////////////////////////////////////////////  Reports ////////////////////////////////////////////////////////////

		public function numofpassengers()
		{
			$query=$this->pdo->prepare("SELECT SUM(seates) as total FROM ticket  ");
			$query->execute();

			while($client=$query->fetch(PDO::FETCH_OBJ))
			{
			echo $client->total;		
			}
		}

		public function numofusers()
		{
			$query=$this->pdo->prepare("SELECT COUNT(id) as total FROM users  ");
			$query->execute();

			while($client=$query->fetch(PDO::FETCH_OBJ))
			{
			echo $client->total;		
			}
		}

		public function numofclients()
		{
			$query=$this->pdo->prepare("SELECT COUNT(id) as total FROM client  ");
			$query->execute();

			while($client=$query->fetch(PDO::FETCH_OBJ))
			{
			echo $client->total;		
			}
		}

		public function numofbuss()
		{
			$query=$this->pdo->prepare("SELECT COUNT(id) as total FROM busses  ");
			$query->execute();

			while($client=$query->fetch(PDO::FETCH_OBJ))
			{
			echo $client->total;		
			}
		}

		public function numoflines()
		{
			$query=$this->pdo->prepare("SELECT COUNT(id) as total FROM travel_line  ");
			$query->execute();

			while($client=$query->fetch(PDO::FETCH_OBJ))
			{
			echo $client->total;		
			}
		}

		public function numofcities()
		{
			$query=$this->pdo->prepare("SELECT COUNT(id) as total FROM cities  ");
			$query->execute();

			while($client=$query->fetch(PDO::FETCH_OBJ))
			{
			echo $client->total;		
				}
		}	
		/////////// new additions for easy access in writing
		public function AddPointPickUpTime()
		{
			$app= new \stdClass();

			$bus_company = $_POST['busCompany'];
			$pick_point = $_POST['stopName'];
			$time = $_POST['pickUptime'];
			$bus_tag = $_POST['busTag'];

			// $bus_company = 'kings travels';
			// $bus_tag = 'bus_id157877636889GHY';
			// $pick_point = 'port longer';
			// $time = '22:12';


			//write code here 
			  // gettign the point id
			$point_id= $this->getPointid($bus_company,$pick_point);
			//echo $point_id;
			//adding pickUpTime
			
			$query= $this->pdo->prepare("insert into bus_pick_up_times values ('$point_id','$time','$bus_tag')");
			$query->execute();				
			$rowsadded = $query->rowCount();			
			if ($rowsadded == 1 )
			{	
				$app->status="bus_added_success";				
			}
			else
			{
				
				$app->status="failed_to_add";
			}				
				$jsonData = json_encode($app);
                    echo $jsonData."\n"; 	




		}

		private function getPointid($bus_company,$pick_point){
			$query=$this->pdo->prepare("select * from city_stops where bus_company='$bus_company' AND pick_point='$pick_point'");
			$query->execute();
			if($query->rowCount()>0){
			$stop_data=$query->fetch(PDO::FETCH_OBJ);
					   return $stop_data->point_id;					  
						}
		}






	}



	
?>


