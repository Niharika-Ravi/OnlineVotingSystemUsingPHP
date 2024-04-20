

<div class="row ">
    <center>
    <div class="col-6 ">
        <h3>Edit Election</h3>
        <?php

            $id= $_GET['editPanel'];
            $fetchingelectiondetail=mysqli_query($db,"SELECT * FROM elections where id='".$id."'")or die(mysqli_error($db));
            $dataArray=mysqli_fetch_assoc($fetchingelectiondetail);

            
            
        ?>
        <form method="POST">
            <div class="form-group m-3">
                <input type="text" name="election_topic" placeholder="Election Topic"  class="form-control" value="<?php echo $dataArray['election_topic'];?>" required/>
            </div>
            <div class="form-group m-3">
                <input type="number" name="no_of_candidates" placeholder="Number of Candidates"  class="form-control" value="<?php echo $dataArray['no_of_candidates'];?>" required/>
            </div>
            <div class="form-group m-3">
                <input type="text" onfocus="this.type='Date'" name="starting_date" placeholder="Starting Date"  class="form-control" value="<?php echo $dataArray['starting_date'];?>" required/>
            </div>
            <div class="form-group m-3">
                <input type="text" onfocus="this.type='Date'" name="ending_date" placeholder="Ending Date"  class="form-control" value="<?php echo $dataArray['ending_date'];?>" required/>
            </div>
            <input type="submit" value="Update Election" onclick="Update(<?php $dataArray['id'];?>)" name="update_election_btn" class="btn btn-success m-3">
        </form>
        <?php
            if(isset($_POST['update_election_btn'])){
                $election_topic=mysqli_real_escape_string($db,$_POST['election_topic']);
                $no_of_candidates=mysqli_real_escape_string($db,$_POST['no_of_candidates']);
                $starting_date=mysqli_real_escape_string($db,$_POST['starting_date']);
                $ending_date=mysqli_real_escape_string($db,$_POST['ending_date']);
                $inserted_by=$_SESSION['username'];
                $inserted_on=date('Y-m-d');
        
                $date1=date_create("$inserted_on");
                $date2=date_create("$starting_date");
                $diff=date_diff($date1,$date2);
                
                $date3=date_create("$ending_date");
                $ediff=date_diff($date1,$date3);

                
                if(($diff->format("%R%a"))>0 ){
                    $status="Inactive";
                }
                elseif(($diff->format("%R%a"))<0 && ($ediff->format("%R%a"))<0){
                    $status="Expired";
                }
                else{
                    $status="Active";
                }
                $validdiff=date_diff($date2,$date3);
        
                if(($validdiff->format("%R%a"))<0){
                    ?>
                        <div class="alert alert-danger my-3" role="alert">Check Election Dates!
                        </div>
                    <?php
                }
                    else{
                
                //Updating
                mysqli_query($db,"UPDATE elections SET election_topic='".$election_topic."',no_of_candidates='".$no_of_candidates."',starting_date='".$starting_date."',ending_date='".$ending_date."',status='".$status."',inserted_by='".$inserted_by."',inserted_on='".$inserted_on."' WHERE id='".$id."'")or die(mysqli_error($db));
                ?>
                <script>location.assign("index.php?addElectionPage=1&Updated=1");</script>
            <?php
                }
            }
            
    ?>
        
</div>
</div>
<?php
?>