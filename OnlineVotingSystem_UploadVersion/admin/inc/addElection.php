<?php
    if(isset($_GET['added'])){
    ?>
        <div class="alert alert-success my-3" role="alert">Election has been added successfully!
        </div>
    <?PHP
    }
    elseif(isset($_GET['delete_id'])){
        mysqli_query($db,"DELETE FROM elections WHERE id='".$_GET['delete_id']."'") or die(mysqli_error($db));
        mysqli_query($db,"DELETE FROM candidate_details WHERE election_id='".$_GET['delete_id']."'") or die(mysqli_error($db));
        ?>
        <div class="alert alert-danger my-3" role="alert">Election has been deleted successfully!
        </div>
    <?PHP
    }
    elseif(isset($_GET['Updated'])){
?>
    <div class="alert alert-success my-3" role="alert">Election has been updated successfully!
        </div>
<?php
    }
    elseif((isset($_GET['invaliddates']))){
        ?>
        <div class="alert alert-danger my-3" role="alert">Check Election Dates!
            </div>
    <?php  
    }
?>


<div class="row ">
    <div class="col-4 ">
        <h3>Add Election</h3>
        <form method="POST">
            <div class="form-group m-3">
                <input type="text" name="election_topic" placeholder="Election Topic"  class="form-control"required/>
            </div>
            <div class="form-group m-3">
                <input type="number" name="no_of_candidates" placeholder="Number of Candidates"  class="form-control"required/>
            </div>
            <div class="form-group m-3">
                <input type="text" onfocus="this.type='Date'" name="starting_date" placeholder="Starting Date"  class="form-control"required/>
            </div>
            <div class="form-group m-3">
                <input type="text" onfocus="this.type='Date'" name="ending_date" placeholder="Ending Date"  class="form-control"required/>
            </div>
            <input type="submit" value="Add Election" name="add_election_btn" class="btn btn-success m-3">
        </form>
        

    </div>
    <div class="col-8">
        <h3>Upcoming Election</h3>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Election Name</th>
                    <th scope="col"># Candidates</th>
                    <th scope="col">Starting date</th>
                    <th scope="col">Ending date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $fetchingData=mysqli_query($db,"SELECT * FROM elections") or die(mysqli_error($db));
                        $isAnyElectionsAdded=mysqli_num_rows($fetchingData);

                        if($isAnyElectionsAdded>0){
                            $sno=1;
                            while($row=mysqli_fetch_assoc($fetchingData)){
                                $election_id=$row['id'];
                    ?>
                            <tr>
                                <td><?php echo $sno++;?></td>
                                <td><?php echo $row['election_topic'];?></td>
                                <td><?php echo $row['no_of_candidates'];?></td>
                                <td><?php echo $row['starting_date'];?></td>
                                <td><?php echo $row['ending_date'];?></td>
                                <td><?php echo $row['status'];?></td>
                                <td>
                                    <a href="index.php?editPanel=<?php echo $election_id;?>" class="btn small btn-warning">Edit</a>
                                    <button onclick="DeleteData(<?php echo $election_id;?>)" class="btn small btn-danger">Delete</button>
                                </td>
                            </tr>
                    <?php
                            }
                        }
                        else{
                    ?>
                        <tr><td colspan="7">No Election Available</td></tr>
                    <?php
                        }
                    ?>
                </tbody>

            </table>
    </div>
</div>
<script>
    const DeleteData=(e_id)=>
    {
        let c=confirm("Are you sure you want to delete the election?");

        if(c==true){
            location.assign("index.php?addElectionPage=1&delete_id="+e_id);
        }

    }

</script>

<?php
    if(isset($_POST['add_election_btn'])){
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
            <script>location.assign("index.php?addElectionPage=1&invaliddates=1");</script>
        <?php  
        }
        else{
        //Inserting into db
        mysqli_query($db,"INSERT INTO elections(election_topic,no_of_candidates,starting_date,ending_date,status,inserted_by,inserted_on) 
        VALUES ('".$election_topic."','".$no_of_candidates."','".$starting_date."','".$ending_date."','".$status."','".$inserted_by."','".$inserted_on."')") or die(mysqli_error($db));
        ?>
        <script>location.assign("index.php?addElectionPage=1&added=1");</script>
    <?php
    }
}
?>
