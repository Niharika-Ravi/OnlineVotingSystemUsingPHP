<?php
    if(isset($_GET['added'])){
    ?>
        <div class="alert alert-success my-3" role="alert">Candidate has been added successfully!
        </div>
    <?PHP
    }
    elseif(isset($_GET['largeFile'])){
    ?>
        <div class="alert alert-danger my-3" role="alert">Candidate image is too large,please upload image within 2 mb.
        </div>
    <?php
    }
    elseif(isset($_GET['invalidFileType'])){
        ?>
            <div class="alert alert-danger my-3" role="alert">Invalid image type(Only jpeg,jpg,png files are allowed)
            </div>
        <?php
    }
    elseif(isset($_GET['uploadFailed'])){
        ?>
            <div class="alert alert-danger my-3" role="alert">Image Uploading failed.Please try again later.
            </div>
        <?php
    }
    elseif(isset($_GET['delete_candidate'])){
        mysqli_query($db,"DELETE FROM candidate_details WHERE id='".$_GET['delete_candidate']."'") or die(mysqli_error($db));
        ?>
        <div class="alert alert-danger my-3" role="alert">Candidate has been deleted successfully!
        </div>
    <?PHP
    }
    elseif(isset($_GET['updated'])){
        ?>
            <div class="alert alert-success my-3" role="alert">Candidate has been updated successfully!
            </div>
        <?PHP
        }
?>

<div class="row ">
    <div class="col-4  ">
        <h3>Add New Candidate</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group m-3">
                <select class="form-control" name="election_id" required>
                    <option value="">Select Election</option>
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
                <input type="text" name="candidate_name" placeholder="Candidate Name"  class="form-control"required/>
            </div>
            <div class="form-group m-3">
                <input type="file" name="candidate_photo" class="form-control"required/>
            </div>
            <div class="form-group m-3">
                <input type="text" name="candidate_details" placeholder="Candidate Details"  class="form-control"required/>
            </div>
            <input type="submit" value="Add Candidate" name="add_candidate_btn" class="btn btn-success m-3">
        </form>
        

    </div>
    <div class="col-8">
        <h3>Candidate Details</h3>
            <table class="table text-center">
                <thead>
                    <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Name</th>
                    <th scope="col">Details</th>
                    <th scope="col">Election</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $fetchingData=mysqli_query($db,"SELECT * FROM candidate_details") or die(mysqli_error($db));
                        $isAnyCandidateAdded=mysqli_num_rows($fetchingData);

                        if($isAnyCandidateAdded>0){
                            
                            $sno=1;
                            while($row=mysqli_fetch_assoc($fetchingData)){
                                $election_id=$row['election_id'];
                                $candidate_photo=$row['candidate_photo'];

                                $fetchingElectionName=mysqli_query($db,"SELECT election_topic from elections where id='".$election_id."'")or die(mysqli_error($db));
                                $fetchingElectionNameArray=mysqli_fetch_assoc($fetchingElectionName);
                                
                    ?>
                            
                            <tr>
                                <td><?php echo $sno++;?></td>
                                <td><img src="<?php echo $candidate_photo;?>" style="width:100px;height:100px;"class="candidate_photo"/></td>
                                <td><?php echo $row['candidate_name'];?></td>
                                <td><?php echo $row['candidate_details'];?></td>
                                <td><?php echo $fetchingElectionNameArray['election_topic'];?></td>
                                <td>
                                    <a href="index.php?CandidateEditPanel=<?php echo $row['id'];?>" class="btn small btn-warning">Edit</a>
                                    <button onclick="DeleteCandidate(<?php echo $row['id']; ?>)" class="btn small btn-danger">Delete</button>
                                </td>
                            </tr>
                    <?php
                            }
                        }
                        else{
                    ?>
                        <tr><td colspan="7">No Candidate Available</td></tr>
                    <?php
                        }
                    ?>
                </tbody>

            </table>
    </div>
</div>

<?php
    if(isset($_POST['add_candidate_btn'])){
        $election_id=mysqli_real_escape_string($db,$_POST['election_id']);
        $candidate_name=mysqli_real_escape_string($db,$_POST['candidate_name']);
        $candidate_details=mysqli_real_escape_string($db,$_POST['candidate_details']);
        $inserted_by=$_SESSION['username'];
        $inserted_on=date('Y-m-d');

        //Adding photograph
        $target_folder="../assets/images/candidate_photos/";
        $candidate_photo=$target_folder.rand(111111111,99999999999)."_".rand(111111111,99999999999).$_FILES['candidate_photo']['name'];
        $candidate_photo_tmp_name=$_FILES['candidate_photo']['tmp_name'];
        //file type
        $candidate_photo_type=strtolower(pathinfo($candidate_photo,PATHINFO_EXTENSION));
        $allowed_types=array("jpeg","png","jpg");

        //file size
        $candidate_photo_size=$_FILES['candidate_photo']['size'];
        if($candidate_photo_size<2000000){
            if(in_array($candidate_photo_type,$allowed_types)){
                    if(move_uploaded_file($candidate_photo_tmp_name,$candidate_photo)){
                        //Inserting into db
                        mysqli_query($db,"INSERT INTO candidate_details(election_id,candidate_name,candidate_details,candidate_photo,inserted_by,inserted_on) 
                        VALUES ('".$election_id."','".$candidate_name."','".$candidate_details."','".$candidate_photo."','".$inserted_by."','".$inserted_on."')") or die(mysqli_error($db));

                        echo "<script>location.assign('index.php?addCandidatePage=1&added=1');</script>";
                    }
                    else{
                        echo "<script>location.assign('index.php?addCandidatePage=1&uploadFailed=1');</script>";
                    }
            }
            else{
                echo "<script>location.assign('index.php?addCandidatePage=1&invalidFileType=1');</script>";
            }

        }
        else{
            echo "<script>location.assign('index.php?addCandidatePage=1&largeFile=1');</script>";
        }
        echo $candidate_photo_type;
        echo $candidate_photo;
        echo $candidate_photo_tmp_name;
        
        
    }
?>
<script>
    const DeleteCandidate=(c_id)=>
    {
        let c=confirm("Are you sure you want to delete this candidate?");

        if(c==true){
            location.assign("index.php?addCandidatePage=1&delete_candidate="+c_id);
        }

    }
</script>