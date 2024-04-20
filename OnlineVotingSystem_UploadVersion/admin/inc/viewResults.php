<?php
    $election_id=$_GET['viewResults'];

?>

<div class="row my-3">
        <div class="col-12">
            <h3>ELECTION RESULT</h3>
            <?php
                $fetchingActiveElections=mysqli_query($db,"SELECT * FROM elections WHERE id='".$election_id."'")or die(mysqli_error($db));
                $totalActiveElections=mysqli_num_rows($fetchingActiveElections);

                if($totalActiveElections>0)
                {
                    while($data=mysqli_fetch_assoc($fetchingActiveElections)){
                        $election_id=$data['id'];
                        $election_topic=$data['election_topic'];
            ?>
                            <table class="table">
                        <thead>
                        <tr>
                            <th colspan="4" style="background-color:#719EF7;color:white">
                                <h5>ELECTION TOPIC: <?php echo strtoUpper($election_topic);?> </h5>
                            </th>
                        </tr>
                        <tr>
                            <th>Photo</th>
                            <th>Candidate details</th>
                            <th>Votes</th>
                            <th>Result</th>
                            <!--<th>Action</th>-->
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                //Finding candidate with highest votes
                                $fetchingCandidatesdetails=mysqli_query($db,"SELECT * FROM candidate_details where election_id='".$election_id."'") or die(mysqli_error($db));
                                $highest_vote=0;
                                $Candidate_with_highest_vote=0;
                                $candarray=array();    
                                $votesarray=array();   
                                $resultarray=array();       
                                                  
                                while($findingwinner=mysqli_fetch_assoc($fetchingCandidatesdetails)){
                                    $cand_id=$findingwinner['id'];
                                    array_push($candarray,$cand_id);
                                    $fetchingVotes1=mysqli_query($db,"SELECT * FROM vote_table where candidate_id='".$cand_id."'");
                                    
                                    $totalVotes1=mysqli_num_rows($fetchingVotes1);
                                    array_push($votesarray,$totalVotes1);
                                    if($totalVotes1>$highest_vote){
                                        $highest_vote=$totalVotes1;
                                        
                                        //$Candidate_with_highest_vote=$cand_id;
                                    }
                                }
                                
                                
                                $count_of_winners=0;
                                foreach($votesarray as $v){
                                    
                                    if($v==$highest_vote){
                                        array_push($resultarray,"Won");
                                        $count_of_winners=$count_of_winners+1;
                                    }
                                    else{
                                        array_push($resultarray,"Lost");
                                    }
                                }
                                $cand_result=array_combine($candarray,$resultarray);
                                
                                $counter=0;
                                while($count_of_winners>1){
	                                $counter++;
     
                                    foreach($cand_result as $c=>$r){
                                        if($r=="Won"){
                                            $cand_result[$c]="Tied";
                                         
                                        }
                                    }
                                    if($counter<=count($cand_result)){
                                        break;}
                                }
                                
                            
                                
                                //displaying table
                                $fetchingCandidates=mysqli_query($db,"SELECT * FROM candidate_details where election_id='".$election_id."'") or die(mysqli_error($db));
                                
                                while($candidateData=mysqli_fetch_assoc($fetchingCandidates)){
                                    $candidate_id=$candidateData['id'];
                                    $candidate_photo=$candidateData['candidate_photo'];
                                    //Fetching Votes
                                    $fetchingVotes=mysqli_query($db,"SELECT * FROM vote_table where candidate_id='".$candidate_id."'");
                                    $totalVotes=mysqli_num_rows($fetchingVotes);
                                    
                            ?>
                                <tr>
                                    <td><img src="<?php echo $candidate_photo;?>" class="candidate_photo"/></td>
                                    <td><?php echo "<b>" .$candidateData['candidate_name']."</b><br>".$candidateData['candidate_details'];?></td>
                                    <td><?php echo $totalVotes?></td>
                                
                            
                                    <?php
                                    $result_text="";
                                    
                                        foreach($cand_result as $c=>$r){
                                            if($c==$candidate_id){
                                                $result_text=$cand_result[$c];
                                            }

                                        }
                                        if($result_text=="Won"){
                                            ?>
                                            <td><button class="btn btn-success"><?php echo $result_text?></button></td>
                                            <?php
                                        }
                                        if($result_text=="Lost"){
                                            ?>
                                            <td><button class="btn btn-danger"><?php echo $result_text?></button></td>
                                            <?php
                                        }
                                        if($result_text=="Tied"){
                                            ?>
                                            <td><button class="btn btn-warning"><?php echo $result_text?></button></td>
                                            <?php
                                        }
                                        
                                    }
                                    
                                    
                                
                            }

                            
                                ?>

                                </tr>
                                    
                        </tbody>
                        </table>
                    
            <?php
                    }
                
                else
                { 
                    echo "No active elections available";
                }
            ?>    
        </div>
    </div>