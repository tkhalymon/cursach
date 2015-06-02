<html>
<head>
	<title>RUN</title>
</head>
<body>
	<pre>
<?php
if (isset($_POST['query']))
{
	if (isset($_POST['cont']))
	{
		$mode = "a";
	}
	else
	{
	}
		$mode = "w";
	$infile = fopen("input.txt", $mode);
	fwrite($infile, $_POST['query']."\n");
	fclose($infile);
	exec("cp input.txt in.txt");
	$infil = fopen("in.txt", "a");
	fclose($infil);
	// cd /var/www/html/rgr/cource/run/
	// echo exec("bash -c './ex <<< cat in.txt' && killall ex");
	echo exec("./run");
	echo file_get_contents("f.t");

	// echo file_get_contents("output.txt");
	echo "string";
}
	echo "
	<form action = '#' method = 'post'>
		<input type = 'text' name = 'query'/>";
if (isset($_POST['query']))
{
	echo "
		<input type = 'hidden' name = 'cont' value = 1/>";
}
	echo "
		<input type = 'submit' value = 'Send'/>
	</form>";
?>
	</pre>
</body>
</html>