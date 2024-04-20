<?php
    require_once("inc/header.php");
    require_once("inc/navigation.php");
    

?>


<div class="row my-3">
        
        <div class="col-11">
            <h3>Voters Panel</h3>
            <center>
            <?php
                $fetchingActiveElections=mysqli_query($db,"SELECT * FROM elections WHERE status='Active'")or die(mysqli_error($db));
                $totalActiveElections=mysqli_num_rows($fetchingActiveElections);

                if($totalActiveElections>0)
                {
                    while($data=mysqli_fetch_assoc($fetchingActiveElections)){
                        $election_id=$data['id'];
                        $election_topic=$data['election_topic'];
            ?>
                        
                            <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3" style="background-color:#719EF7;color:white;width=100%">
                                <h5>ELECTION TOPIC: <?php echo strtoUpper($election_topic);?> </h5>
                            </th>
                        </tr>
                        <tr>
                            <th>Photo</th>
                            <th>Candidate details</th>
                            
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                $fetchingCandidates=mysqli_query($db,"SELECT * FROM candidate_details where election_id='".$election_id."'") or die(mysqli_error($db));

                                while($candidateData=mysqli_fetch_assoc($fetchingCandidates)){
                                    $candidate_id=$candidateData['id'];
                                    $candidate_photo=$candidateData['candidate_photo'];
                                    

                            ?>
                                <tr>
                                    <td><img src="<?php echo $candidate_photo;?>" class="candidate_photo"/></td>
                                    <td><?php echo "<b>" .$candidateData['candidate_name']."</b><br>".$candidateData['candidate_details'];?></td>
                                    
                                    <?php
                                        $checkIfVoteCasted=mysqli_query($db,"SELECT * FROM vote_table where voters_id='".$_SESSION['user_id']."' AND election_id='".$election_id."'") or die(mysqli_error($db));
                                        $isVoteCasted=mysqli_num_rows($checkIfVoteCasted);
                                        if($isVoteCasted >0){
                                            $voteCastedData=mysqli_fetch_assoc($checkIfVoteCasted);
                                            $voteGivenTo=$voteCastedData['candidate_id'];
                                            if($voteGivenTo==$candidate_id){
                                    ?>
                                        <td><button class="btn btn-danger" > Already Voted</button></td>
                                </tr>
                                    <?php
                                            }
                                        }
                                        else{
                                    ?>
                                    <td><button class="btn btn-success" onclick="CastVote(<?php echo $election_id;?>,<?php echo $candidate_id;?>,<?php echo $_SESSION['user_id'];?>)"> Vote</button></td>
                                </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                    
                        </tbody>
                        </table>
                    
            <?php
                    }
                }
                else
                { 
                    echo "No active elections available";
                }
            ?>    
        </div>
    </div>
    <script>
            const CastVote=(election_id,candidate_id,voters_id)=>
            {
                $.ajax({
                    type:"POST",
                    url:"inc/ajaxCalls.php",
                    data:"e_id="+election_id+"&c_id="+candidate_id+"&v_id="+voters_id,
                    success:function(response){
                        console.log(response);
                        if(response=="Success"){
                            location.assign("index.php?VoteCasted=1");
                        }
                        else{
                            location.assign("index.php?VoteNotCasted=1");
                        }
                    }
                });
            }
        </script>
<?php
    
    require_once("inc/footer.php");
?>
    
