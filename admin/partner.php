<?php
  session_start();
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();

    if(isset($_GET['seen']))
    {
        $frm_data = filteration($_GET);

        if($frm_data['seen']=='all'){
            $q = "UPDATE `partner` SET `seen`=?";
            $values = [1];
            if(update($q,$values,'i')){
                alert('success','Marked all as Read!');
               
            }
            else{
                alert('error','Operation Failed!');
               
            }
        }
        else{
            $q = "UPDATE `partner` SET `seen`=? WHERE `sr_no`=?";
            $values = [1,$frm_data['seen']];
            if(update($q,$values,'ii')){
                alert('success','Marked as Read!');
               
            }
            else{
                alert('error','Operation Failed!');
               
                
            }
        
            
        }
    }


    function delete_query($con, $sr_no) 
    {
        $stmt = mysqli_prepare($con, "DELETE FROM `partner` WHERE `sr_no`=?");
        mysqli_stmt_bind_param($stmt, "i", $sr_no);
        if(mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            throw new Exception("Query cannot be executed - Delete");
        }
    }
    function delete_all_queries($con) 
    {
        $stmt = mysqli_prepare($con, "DELETE FROM `partner`");
        if(mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            throw new Exception("Query cannot be executed - Delete All");
        }
    }
    function filter_input_array_recursive($data) {
        foreach($data as $key => $value) {
            if(is_array($value)) {
                $data[$key] = filter_input_array_recursive($value);
            } else {
                $data[$key] = trim(htmlspecialchars($value, ENT_QUOTES));
            }
        }
        return $data;
    }
    
    $con = mysqli_connect($hname,$uname,$pass,$db);

    if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['del']))
     {
        $frm_data = filter_input_array_recursive($_GET);
        if($frm_data['del'] == 'all') {
            try {
                $affected_rows = delete_all_queries($con);
                if($affected_rows > 0) {
                    alert('success', 'All data deleted successfully!');
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    alert('warning', 'No records were deleted.');
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                }
            } catch(Exception $e) {
               alert('danger', 'Error: ' . $e->getMessage());
            }
        } else {
            $sr_no = $frm_data['del'];
            try {
                $affected_rows = delete_query($con, $sr_no);
                if($affected_rows > 0) {
                    alert('success', 'Data deleted successfully!');
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    alert('warning', 'No records were deleted.');
                    header("Location: ".$_SERVER['PHP_SELF']);
                     exit();
                }
            } catch(Exception $e) {
               alert('danger', 'Error: ' . $e->getMessage());
            }
        }
    }
    
    ?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pannel - Partnership Form</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
   <?php
    require('inc/header.php');
   ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">PARTNERSHIP REQUEST</h3>

      <div class="card border-0 shadow mb-4" >
        <div class="card-body">

        <div class="text-end mb-4">
            <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm"><i class="bi bi-check2-all"></i> Mark all Read</a>
            <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm"><i class="bi bi-trash"></i> Delete All</a>
        </div>
           <div class="table-responsive-md" style="height: 450px; overflow-y: scroll;">
           <table class="table table-hover border">
                <thead class="sticky-top">
                    <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Venue Name</th>
                    <th scope="col">E-Mail</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Sports Name</th>
                    <th scope="col" width="40%">Address</th>
                    <th scope="col">Features</th>
                    <th scope="col" width="60%">Message</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = "SELECT * FROM `partner` ORDER BY `sr_no` DESC"; 
                    $data = mysqli_query($con,$q);
                    $i=1;

                    while($row = mysqli_fetch_assoc($data))
                    {
                        $seen='';
                        if($row['seen']!=1){
                            $seen= "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary mb-2'>Mark as Read</a> <br>";
                        }
                        $seen.="<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger' data-id='$row[sr_no]'>Delete</a>";
                        echo<<<query
                        <tr>
                            <td>$i</td>
                            <td>$row[name]</td>
                            <td>$row[vname]</td>
                            <td>$row[email]</td>
                            <td>$row[phone]</td>
                            <td>$row[sname]</td>
                            <td>$row[address]</td>
                            <td>$row[features]</td>
                            <td>$row[message]</td>
                            <td>$row[date]</td>
                            <td>$seen</td>
                        </tr>
                        query;
                        $i++;
                    }
                    ?>
                </tbody>
                </table>
           </div>
        
        
        </div>
        </div>

            </div>
        </div>
    </div>

<?php require('inc/scripts.php'); ?>
</body>
</html>
