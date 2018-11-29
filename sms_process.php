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
?>


<?php
function view($data,$flag=0)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	if($flag=1)
	die;
}
$data= array(
'content'	=> $_POST['content'],
);
	
//view($data,1);
	
	if($data['content'])
	{
	
		$_objSmsProtocolGsm = new Com("ActiveXperts.SmsProtocolGsm");
		
		//create the nessecairy com objects
		$objMessage   = new Com ("ActiveXperts.SmsMessage");
		$objConstants = new Com ("ActiveXperts.SmsConstants");
			
		$device       = "GlobeTrotter GI4xx - Modem Interface";
		$speed        = "Default";
		$pincode      = "";
		$unicode      = "";
				
		//configure a logfile
		$_objSmsProtocolGsm->Logfile = "C:\SMSMMSToolLog.txt";
	
		foreach($data['content'] as $key=>$row)
		{
			$cont= explode('break_sms',$row);
			$sms_table_id 	=	$cont[0];
			$mobile_no 		=	$cont[1];
			$message 		=	$cont[2];
			
			
			$recipient    = $mobile_no;
			$message      = $message;
			
			
			//Clear the messageobject first
			$objMessage->Clear();
			
			//fill in the recipient
			if( $recipient == "" ) die("No recipient address filled in."); 
			$objMessage->Recipient = $recipient;
			
			//fill in the messageformat
			if( $unicode != "" ) $objMessage->Format = $objConstants->asMESSAGEFORMAT_UNICODE_MULTIPART;
			
			//fill in the message body
			$objMessage->Data = $message;
			
			//clear the gsm object
			$_objSmsProtocolGsm->Clear();
			
			//fill in the devicename
			$_objSmsProtocolGsm->Device = $device;
			
			//fill in the devicespeed
			if( $speed == "Default" ) $_objSmsProtocolGsm->DeviceSpeed = 0;
			if( $speed != "Default" ) $_objSmsProtocolGsm->DeviceSpeed = $speed;
			
			//fill in the pincode
			if( $pincode != "" ) $_objSmsProtocolGsm->EnterPin( $pincode );
			
			//send the message
			if( $_objSmsProtocolGsm->LastError == 0 ){
				$_objSmsProtocolGsm->Send( $objMessage );
				
				//Update SMS_TABLE
				$sql = "UPDATE sms_table SET status='1' WHERE sms_table_id=$sms_table_id";
				$result = $conn->query($sql);
				
			}
			
			//get the results
			$LastError        = $_objSmsProtocolGsm->LastError;
			$ErrorDescription = $_objSmsProtocolGsm->GetErrorDescription( $LastError );
			
						
			//view($LastError);
			//view($ErrorDescription,1);
			
			//Send only 5 SMS
			if($key == 4)
			break;
		}
	}
	
	$conn->close();
	header('location:sms_design.php?success="Your messages has been sent."');
	
	

?>