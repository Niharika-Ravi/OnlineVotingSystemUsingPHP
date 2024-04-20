
<?php
  if(isset($_GET['delete_candidate'])){
        mysqli_query($db,"DELETE FROM candidate_details WHERE id='".$_GET['delete_candidate']."'") or die(mysqli_error($db));
        ?>
        <div class="alert alert-danger my-3" role="alert">Candidate has been deleted successfully!
        </div>
    <?PHP
    }
?>
<link rel="stylesheet" href="../assets/css/style.css">
<div class="row ">
    <center>
    <div class="col-6 ">
        <h3>Edit Candidate</h3>
        <?php

            $id= $_GET['CandidateEditPanel'];
            $fetchingelectiondetail=mysqli_query($db,"SELECT * FROM candidate_details where id='".$id."'")or die(mysqli_error($db));
            $dataArray=mysqli_fetch_assoc($fetchingelectiondetail);
            $pic=$dataArray['candidate_photo'];
            $election_topic=mysqli_query($db,"SELECT election_topic from elections where id='".$dataArray['election_id']."'");
            $selected_election_name=mysqli_fetch_assoc($election_topic);
            
            
            
            
        ?>
        
        <div class="row ">
    <div class="col-10  ">
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group m-3">
                <select class="form-control" name="election_id" required>
                    <option value="">Select Election</option>
                    <option value="<?php echo $dataArray['election_id']; ?>" selected><?php echo $selected_election_name['election_topic']; ?></option>
                                    
                    <?php
                        $fetchingElection=mysqli_query($db,"SELECT * FROM elections") or die(mysqli_error($db));
                        $ElectionCount=mysqli_num_rows($fetchingElection);
                        if($ElectionCount>0){
                                while($row=mysqli_fetch_assoc($fetchingElection))
                                {
                                    $election_id=$row['id'];
                                    $election_name=$row['election_topic'];
                                    $allowed_candidates=$row['no_of_candidates'];

                                    //Restricing access to adding more candidates
                                    $fetchingCandidate=mysqli_query($db,"SELECT * FROM candidate_details where election_id='".$election_id."'") or die(mysqli_error($db));
                                    $already_added_candidates=mysqli_num_rows($fetchingCandidate);

                                    if($already_added_candidates<$allowed_candidates)
                                    {                                
                                    ?>
                                    <option value="<?php echo $election_id; ?>"><?php echo $election_name; ?><optiom>
                                    <?php
                                    }
                                    
                                    }
                                }
                            
                    else{
                    ?>
                        <option value="">Please add election first</option>
                    <?Php
                    }        
                    ?>
                </select>
            </div>
            <div class="form-group m-3">
                <input type="text" name="candidate_name" placeholder="Candidate Name"  class="form-control" value="<?php echo $dataArray['candidate_name']; ?>" required/>
            </div>
            <div class="form-group m-3">
                <table width="100%" id="ProfilePreview">
                <tr><td>Current Picture: </td>
                <td><img src="<?php echo $pic; ?>" style="width:100px;height:100px;border-radius:10%"></td>
                <input style="display:none"name="PicReview" id="PicReview" value="<?php echo $pic; ?>"></input>
                <td><button onclick="ProfileChange()" class="btn btn-info" style="background-color: #BAE3F7;color:black;border:1px solid #719EF7">Change picture</button></td><tr></table>
                <input type="file" style='display:none;'id="candPic" name="candidate_photo" class="form-control" value="abc" />
            </div>
            <div class="form-group m-3">
                <input type="text" name="candidate_details" placeholder="Candidate Details"  class="form-control" value="<?php echo $dataArray['candidate_details']; ?>" required/>
            </div>
            <input type="submit" value="Update Candidate Details" name="update_candidate_btn" class="btn btn-success m-3" >
        </form>
                </div></div>


<?php
    if(isset($_POST['update_candidate_btn'])){
        $election_id=mysqli_real_escape_string($db,$_POST['election_id']);
        $candidate_name=mysqli_real_escape_string($db,$_POST['candidate_name']);
        $candidate_details=mysqli_real_escape_string($db,$_POST['candidate_details']);
        $inserted_by=$_SESSION['username'];
        $inserted_on=date('Y-m-d');

        //Adding photograph
        if($_POST['PicReview']=='NotApplicable'){
        $target_folder="../assets/images/candidate_photos/";
        $candidate_photo=$target_folder.rand(111111111,99999999999)."_".rand(111111111,99999999999).$_FILES['candidate_photo']['name'];
        $candidate_photo_tmp_name=$_FILES['candidate_photo']['tmp_name'];
        //file type
        $candidate_photo_type=strtolower(pathinfo($candidate_photo,PATHINFO_EXTENSION));
        $allowed_types=array("jpeg","png","jpg");
        echo   $candidate_photo_tmp_name;
        //file size
        $candidate_photo_size=$_FILES['candidate_photo']['size'];
        if($candidate_photo_size<2000000){
            if(in_array($candidate_photo_type,$allowed_types)){
                    if(move_uploaded_file($candidate_photo_tmp_name,$candidate_photo)){
                        //Inserting into db
                        mysqli_query($db,"UPDATE candidate_details SET election_id='".$election_id."',candidate_name='".$candidate_name."',candidate_details='".$candidate_details."',candidate_photo='".$candidate_photo."',inserted_by='".$inserted_by."',inserted_on='".$inserted_on."' where id='".$id."'") or die(mysqli_error($db));

                        echo "<script>location.assign('index.php?addCandidatePage=1&updated=1');</script>";
                    }
                    else{
                    ?>
                        <div class="alert alert-danger my-3" role="alert">Image Uploading failed.Please try again later.
                        </div>
                    <?php
                    }
            }
            else{
            ?>
                <div class="alert alert-danger my-3" role="alert">Invalid image type(Only jpeg,jpg,png files are allowed)
                </div>
            <?php
            }

        }
        else{
        ?>
            <div class="alert alert-danger my-3" role="alert">Candidate image is too large,please upload image within 2 mb.
            </div>
        <?php
        }
    }
    else{
        mysqli_query($db,"UPDATE candidate_details SET election_id='".$election_id."',candidate_name='".$candidate_name."',candidate_details='".$candidate_details."',candidate_photo='".$pic."',inserted_by='".$inserted_by."',inserted_on='".$inserted_on."' where id='".$id."'") or die(mysqli_error($db));

        echo "<script>location.assign('index.php?addCandidatePage=1&updated=1');</script>";
    }
     
        
    }
        
    
    
?>
</div>
<script>
    
    function ProfileChange(){
        document.getElementById("PicReview").value="NotApplicable";        
        document.getElementById("ProfilePreview").style.display="none";
        document.getElementById("candPic").style.display="block";
        document.getElementById("candPic").required=true;
        
    }
    

</script>