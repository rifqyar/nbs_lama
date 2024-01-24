<?php
require_once "smtp/Mail/Mail.php"; // PEAR Mail package
require_once ('smtp/Mail/mime.php'); // PEAR Mail_Mime packge

	$from = "Ayu damayanti <damayanti@indonesiaport.co.id>";
	$to = "Ayu damayanti <damayanti@indonesiaport.co.id>,Ayu damayanti <ayudamayanti.21@gmail.com>, donaldabek@gmail.com";
	$subject = 'Test mime message with an attachment';

	
	$headers = array ('From' => $from,'To' => $to, 'Subject' => $subject);

	$text = 'Text version of email';// text and html versions of email.
	$html = '<html><body>HTML version of email. <strong>This should be bold</strong></body></html>';

	$attachment = $_FILES['kirim_email']['tmp_name']; // attachment
	$name = basename($_FILES['kirim_email']['name']);
	$crlf = "\n";

	$mime = new Mail_mime($crlf);
	$mime->setTXTBody($text);
	$mime->setHTMLBody($html);
	//$mime->addAttachment($file, 'xls');
	
	$mime->addAttachment($attachment,'application/x-xls',$name,true,'base64'); 

	//do not ever try to call these lines in reverse order
	$body = $mime->get();
	$headers = $mime->headers($headers);

	$host = "mail.indonesiaport.co.id";
	$username = "damayanti@indonesiaport.co.id";
	$password = "damayanti";

	$smtp = Mail::factory('smtp', array ('host' => $host, 'auth' => true,
	 'username' => $username,'password' => $password));

	$mail = $smtp->send($to, $headers, $body);
	
	echo "dama";
	
	if (PEAR::isError($mail)) {
	  echo 'NOT OK';
	}
	else {
	 //echo $_FILES['kirim_email']['tmp_name'];
	  echo 'OK';
		
	}

	?>