<?php

$mail_to  = "portfoliomiguel92@gmail.com";  // replace it with your email

$email    = $_POST['email'];

$subject = "Newsletter Mail From: " . $email;

$body   = "New Subscribtion From: " . "<$email>" . "\n\n";
$body   .= "----------------" . "\n\n";
$body   .= "Email: " . "$email" . "\n\n";
$body   .= "subject: " . "$subject" . "\n\n";

$from  = "From: " . "<$email>";

if (mail($mail_to, $subject, $body, $from)) {
  echo "Thanks for your subscribtion!";
} else {
  echo "error!";
}