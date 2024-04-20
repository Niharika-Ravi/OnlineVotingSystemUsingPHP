<?php
    if(isset($_GET['added'])){
    ?>
        <div class="alert alert-success my-3" role="alert">Election has been added successfully!
        </div>
    <?PHP
    }
?>

<div class="row ">
    
    <div class="col-12">
        <h3>Election</h3>
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
                                    <a href="index.php?viewResults=<?php echo $election_id?>" class="btn small btn-success">View Results</a>
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


