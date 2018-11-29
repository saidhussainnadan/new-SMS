<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT sms_table_id,mobile_no, message, status FROM sms_table where status=0";
$result = $conn->query($sql);
$contents=array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
			$contentArray['sms_table_id'] 	= 	$row['sms_table_id'];
			$contentArray['mobile_no'] 		= 	$row['mobile_no'];
			$contentArray['message'] 		= 	$row['message'];
			$contentArray['status'] 		=	$row['status'];
			array_push($contents,$contentArray);
    }
}
$conn->close();


//echo "<pre>";print_r($contents);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

  </head>

  <body>
  <form action="sms_process.php" method='post'  name="send_form">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 main">
          <h1 class="page-header">SMS APP
		  
		  <span style="float:right;"><input type='submit' value='Send'></span></h1>

		  <?php if(isset($_GET['success'])) { ?>
		  <div class="alert alert-success"><?php echo $_GET['success']; ?></div>
		  <?php } ?>
		  
		  
     <?php if(!empty($contents)) { ?>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th><input type="checkbox" id="checkAll" ></th>
                  <th>#</th>
                  <th>Mobile#</th>
                  <th>Message</th>
                </tr>
              </thead>
              <tbody>
			  <?php foreach($contents as $key=>$row) { ?>
                <tr>
				
				<?php $val=$row['sms_table_id']."break_sms".$row['mobile_no']."break_sms".$row['message']; ?>
				
                  <th><input type="checkbox" name="content[]" value="<?php echo $val; ?>" checked="checked" ></th>
                  <td><?php echo $row['sms_table_id']; ?></td>
                  <td>
				  <?php echo $row['mobile_no']; ?></td>
                  <td><?php echo $row['message']; ?></td>
                </tr>
			<?php } ?>	
			
              </tbody>
            </table>
          </div>
		  <?php }else { ?>
		  
		  <div class="alert alert-danger">No PENDING SMS Found.</div>
		  <?php } ?>
        </div>
      </div>
    </div>
	
</form>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap-3.3.7/dist/js/bootstrap.min.js"></script>
	
	<script>
	$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
	
window.onload=function(){
    window.setTimeout(function() { document.send_form.submit(); }, 10000);
};
	
	</script>
  </body>
</html>
