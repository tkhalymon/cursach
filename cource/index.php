<?php
$root = "..";
?>
<html>
<head>
	<link rel="icon" type="image/x-icon" href="<?php echo $root;?>/ACM_logo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/menu.css">
	<meta http-equiv = "content-type" content = "text/html" charset = "utf-8">
	<title>Онлайн курс</title>
</head>
<body>
	<?php
	include "$root/common/menu.php";
	?>
	<div class = "content">
		<a href = './'>Return</a><br>
		<h1>Онлайн курс ACM</h1>
<?php
dbConnect();
if (isset($_GET['t']))
{
	$tmp = mysql_query("SELECT * FROM cource WHERE id = ".$_GET['t']);
	$topic = mysql_fetch_array($tmp);
	echo $topic['theory'];
	$tmp = mysql_query("SELECT name FROM topics WHERE id = ".$_GET['t']);
	$topic_name = mysql_fetch_array($tmp)['name'];
	$source = fopen("example".$_GET['t'].".cpp", "w");
	fwrite($source, $topic['example']);
	fclose($source);
	echo "<br><a download href = 'example".$_GET['t'].".cpp'>Код вирішення прикладу<a>";
}
else
{
	$tmp = mysql_query('SELECT * FROM topics ORDER BY id DESC LIMIT 0, 1');
	$task_num = mysql_fetch_array($tmp)[0];
	for ($i = 1; $i <= $task_num; $i++)
	{
		$tmp = mysql_query("SELECT name FROM topics WHERE id = $i");
		$topic = mysql_fetch_array($tmp);
		echo "<a href = '?t=$i'>".$topic["name"]."</a><br>";
	}
}
?>

	</div>
</body>
</html>