
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!------ Include the above in your HEAD tag ---------->

<?php
    require_once("admin/inc/config.php");
    $fetchingElections=mysqli_query($db,"SELECT * FROM elections") or die(mysqli_error($db));
    while($data=mysqli_fetch_assoc($fetchingElections)){
        $starting_date=$data['starting_date'];
        $ending_date=$data['ending_date'];
        $curr_date=date("Y-m-d");
        $election_id=$data['id'];
        $status=$data['status'];

        if($status=='Active'){
        //Election which was once active has expired
        $date1=date_create($curr_date);
        $date2=date_create($ending_date);
        $diff=date_diff($date1,$date2);

        if((int)$diff->format("%R%a")<0){
            //Update
            mysqli_query($db,"UPDATE elections SET status='Expired' WHERE id='".$election_id."'") or die(mysqli_error($db));


        }}
        elseif($status=="Inactive"){
            //Election which was inactive has become active
            $date1=date_create($curr_date);
            $date2=date_create($ending_date);
            $diff=date_diff($date1,$date2);

            $date3=date_create($starting_date);
            $sdiff=date_diff($date1,$date2);
            if(((int)$diff->format("%R%a")>=0) && (((int)$diff->format("%R%a")<=0))){
                //Update
                mysqli_query($db,"UPDATE elections SET status='Active' WHERE id='".$election_id."'") or die(mysqli_error($db));


        }
        }

    }
?>

<!DOCTYPE html>
<html>
    
<head>
	<title>Login-Online Voting System</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abril+Fatface|Poppins">
	
</head>

<body >
		<div class="d-flex justify-content-center h-100" >
        		<div class="user_card" >
				<div class="d-flex justify-content-center" >
                
					<div class="brand_logo_container" >
                        
						<img src="assets/images/logo.png" class="brand_logo" alt="Logo">
					</div>
				</div>
                <?php
                    if(isset($_GET['sign-up'])){
                ?>
                    <div class="d-flex justify-content-center form_container">
                            
                            <form method="POST" action="">
                            <center>
                            <h5 style="font-family: 'Abril Fatface', serif;font-size: 24px;color:#4375E4;text-shadow:1px 0.5px black;" >COLLEGE VOTING SYSTEM</h5>
                                <div class="input-group mb-3">
                                    <div class="input-group-append" >
                                        <span class="input-group-text"><i class="fas fa-user" ></i></span>
                                    </div>
                                    <input type="text" name="su_username" class="form-control input_user" placeholder="Name" required/>
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="text" name="su_register_no" class="form-control input_pass" placeholder="Register No" required/>
                                </div>
                                
                                
                                    <div class="d-flex justify-content-center mt-3 login_container">
                            <button type="submit" name="sign_up_btn" class="btn login_btn" >Sign Up</button>
                        </div>
                            </form>
                        </div>
                
                        <div class="mt-2">
                            <div class="d-flex justify-content-center links">
                                Already have an account? <a href="index.php" class="ml-2">Sign In</a>
                            </div>
                        </div>
                <?php
                    }
                    else{
                ?>
                    <!--Login Page-->
                    <div class="d-flex justify-content-center form_container mt-2">
                            <form method="POST">
                            <br>
                            <h5 style="font-family: 'Abril Fatface', serif;font-size: 24px;color:#4375E4;text-shadow:1px 0.5px black;">COLLEGE VOTING SYSTEM</h5>
                            
                                <div class="input-group mb-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="register_no" class="form-control input_user"  placeholder="Register No" required/>
                                </div>  
                                
                                <div class="d-flex justify-content-center mt-3 login_container">
                            <button type="submit" name="login_btn" class="btn login_btn">Login</button>
                        </div>
                            </form>
                        </div>
                
                        <div class="mt-4">
                            <div class="d-flex justify-content-center links">
                                Don't have an account? <a href="?sign-up=1" class="ml-2">Sign Up</a>
                            </div>
                            
                        </div>
                <?php
                    }
                ?>
                <!--Message after registering or new sign up-->
                <?php
                    if(isset($_GET['registered'])){
                ?>
                    <span class="bg-white text-success text-center my-2">Your account has been created successfully</span>
                <?php
                    } 
                    elseif(isset($_GET['invalid'])){
                ?>
                <span class="bg-white text-danger text-center my-2">User with this register number already exists.Please check your register no!</span>
                 <?php
                    }
                    
                    if (isset($_GET['not_registered'])){
                ?>
                    <span class="bg-white text-warning text-center my-2">You are not registered</span>
                    
                <?php
                    }
            
                
            
                    if (isset($_GET['sign_up_success'])){
                ?>
                    <span class="bg-white text-success text-center my-2">Sign Up successful! Click on Sign In to login.</span>
                    
                <?php
                    }
                ?>
			</div>
		</div>
	</div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>


<?php
    require_once("admin/inc/config.php");
    
    if(isset($_POST['sign_up_btn'])){
        $su_username=mysqli_real_escape_string($db,$_POST['su_username']);
        $su_register_no=mysqli_real_escape_string($db,$_POST['su_register_no']);
        $array=array();
        $fetchregisterno=mysqli_query($db,"SELECT register_no FROM users") or die(mysqli_error($db));
        while($register_no_array=mysqli_fetch_assoc($fetchregisterno)){
            $a=$register_no_array['register_no'];
            array_push($array,$a);
            
        }
        
        $user_role="Voter";
        if(!(in_array($su_register_no,$array))){
            echo (in_array($su_register_no,$array));
            mysqli_query($db,"INSERT INTO users(username,register_no,user_role) VALUES('".$su_username."','".$su_register_no."','".$user_role."')")or die(mysqli_error($db));

        
        ?>
        <script>location.assign("index.php?sign-up=1&sign_up_success=1");</script>
        <?php
        }else{
            
        ?>
        <script>location.assign("index.php?sign-up=1&invalid=1");</script>
        <?php
        }

        
    }
    elseif(isset($_POST['login_btn'])){
        
        $register_no=mysqli_real_escape_string($db,$_POST['register_no']);
        
        //Fetching details
        $fetching_data=mysqli_query($db,"SELECT * FROM users WHERE register_no= '".$register_no."' ") or die(mysqli_error($d));
        if(mysqli_num_rows($fetching_data)>0){
                $data=mysqli_fetch_assoc($fetching_data);

                if($register_no==$data['register_no'] ){
                    session_start();
                    $_SESSION['user_role']=$data['user_role'];
                    $_SESSION['username']=$data['username'];
                    $_SESSION['user_id']=$data['id'];

                    if($data['user_role']=='Admin'){
                        $_SESSION['key']="AdminKey";
                    ?>
                        <script>location.assign("admin/index.php?homePage=1");</script>
                    <?php
                    }
                    else{
                        $_SESSION['key']="VoterKey";
                    ?>
                        <script>location.assign("voter/index.php");</script>
                    
                    <?php
                    }
                }
                
            }
            else{
            ?>
                <script>location.assign("index.php?sign-up&not_registered=1")</script>
                
            <?php
            }

        

    }
    ?>
</html>