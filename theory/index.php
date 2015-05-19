<?php
$root = "..";
include $root.'/common/db.php';
dbConnect();
$tmp = mysql_query('SELECT count(*) FROM topics');
$topic_num = mysql_fetch_array($tmp)[0];
?>
<html>
<head>
	<link rel="icon" type="image/x-icon" href="<?php echo $root;?>/ACM_logo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/menu.css">
	<meta http-equiv = "content-type" content = "text/html" charset = "utf-8">
	<title>ACM Online Cource</title>
</head>
<body>
	<?php include $root.'/common/menu.php'; ?>
	<div class = "content">
		<?php
		if (isset($_GET['t']))
		{
			$tmp = mysql_query('SELECT * FROM topics WHERE id = '.$_GET['t']);
			$topic = mysql_fetch_array($tmp);
			echo '<h1>'.$topic['name'].'</h1>';
			echo '<p>';
			echo $topic['theory'];
			echo '</p>';
		}
		else
		{
			echo '<p>Тут ви можете знайти теоретичні відомості та приклади алгоритмів вирішення задач на різні теми.</p>';
			echo '<ul>';
			for ($i = 0; $i < $topic_num; $i++)
			{
				echo '<li>';
				$tmp = mysql_query('SELECT * FROM topics WHERE id = '.($i + 1));
				$topic = mysql_fetch_array($tmp);
				echo ($i + 1).'. <a href = "'.$root.'/theory/?t='.($i + 1).'">'.$topic['name'].'</a>';
				echo '</li>';
			}
			echo '</ul>';
		}
		?>
	</div>
</body>
</html>