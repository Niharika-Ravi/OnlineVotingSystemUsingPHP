<?php
    require_once("../../admin/inc/config.php");
    date_default_timezone_set("Asia/Calcutta");
    $vote_date=date('Y-m-d');
    $vote_time=date('h-i-s A',time());
    
    if(isset($_POST['e_id'])AND isset($_POST['c_id'])AND isset($_POST['v_id'])){
        mysqli_query($db,"INSERT INTO vote_table(election_id,voters_id,candidate_id,vote_date,vote_timing) VALUES('".$_POST['e_id']."','".$_POST['v_id']."','".$_POST['c_id']."','".$vote_date."','".$vote_time."')") or die(mysqli_error($db));

        echo "Success";
    }
?>