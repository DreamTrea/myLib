<?php
function send_mail($from_email,$from_name,$to_email,$subject,$body,$file=''){
    if (strlen($to_email)==0) return 0;
    $mailheaders .= "From: $from_name<$from_email> \r\n";
    $mailheaders .= "Reply-To: $from_name<$from_email>\r\n";
    $mailheaders .= "Return-Path: $from_name<$from_email>\r\n";

    $boundary = uniqid("part");
    if (strlen($file[type])==0) $file[type] = "application/octet-stream";

    $mailheaders .= "MIME-Version: 1.0\r\n";
    $mailheaders .= "Content-Type: Multipart/mixed; boundary = \"".$boundary."\"";

    $bodytext = "This is a multi-part message in MIME format.\r\n\r\n";
    $bodytext .= "--".$boundary."\r\n";
    $bodytext .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
    $bodytext .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $bodytext .= chunk_split(base64_encode($body))."\r\n\r\n";

    if(is_array($file)){
      $bodytext .= "--".$boundary."\r\n";
      $bodytext .= "Content-Type: ".$file[type]."; name=\"".$file[name]."\"\r\n";
      $bodytext .= "Content-Transfer-Encoding: base64\r\n";
      $bodytext .= "Content-Disposition: attachment; filename=\"".$file[name]."\"\r\n\r\n";
      $bodytext .= chunk_split(base64_encode($file[data]))."\r\n\r\n";
    }

    $bodytext .= "--".$boundary."--";

    if(!mail($to_email,$subject,$bodytext,$mailheaders)) {return 0;}
    return 1;
}

?>
