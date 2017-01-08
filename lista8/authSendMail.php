<?php

require_once("query.php");

$message = <<<EOT
To: krymeer@gmail.com
Subject: Odzyskiwanie utraconego hasła
From: Administrator mytest.bank
Content-type: text/html;charset=UTF-8
MIME-Version: 1.0

Otrzymujesz tę wiadomość, ponieważ osoba znająca Twój adres e-mail zażądała zresetowania hasła do strony <b>mytest.bank</b>. Aby dokonać zmiany hasła, przejdź pod link:<br/>
	http://mytest.bank/lostPassChange.php?k={{KEY}}<br/>
	Link ten jest ważny przez <b>10 minut</b> od momentu wysłania.<br/>
	Jeżeli nigdy nie podejmowałeś próby zmiany hasła, zignoruj tę wiadomość bądź skontaktuj się z <a href="mailto:krzysztof.radoslaw.osada@gmail.com">administratorem</a>.
EOT;

function generateRandomString($length = 8) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

if (isset($_POST["email"]) && !empty($_POST["email"])) {
	if (ifExists($_POST["email"], "ggl") == 1) {
		$str = generateRandomString();
		setcookie("exists", "1", time()+1);
		setcookie("timeout", "1", time()+600);
		$_SESSION["key"] = password_hash($str, PASSWORD_DEFAULT);
		$_SESSION["email"] = $_POST["email"];
		$message = str_replace("{{KEY}}", $str, $message);
		file_put_contents("mail.txt", $message);
		exec("/usr/sbin/ssmtp -t < mail.txt");
		unlink("mail.txt");
		header("location: http://" .$_SERVER["HTTP_HOST"]. "/lostPass.php");
		exit;
	} else {
		setcookie("notExists", "1", time()+1);
		header("location: http://" .$_SERVER["HTTP_HOST"]. "/lostPass.php");
		exit;
	}
} else {
	header("location: http://" .$_SERVER["HTTP_HOST"]. "/lostPass.php");
	exit;
}

?>
