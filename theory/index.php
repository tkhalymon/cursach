<?php
$root = "..";
?>
<html>
<head>
	<link rel="icon" type="image/x-icon" href="<?php echo $root;?>/ACM_logo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/menu.css">
	<meta http-equiv = "content-type" content = "text/html" charset = "utf-8">
	<title>Теорія</title>
</head>
<body>
<?php
include $root.'/common/menu.php';
dbConnect();
$tmp = mysql_query('SELECT count(*) FROM topics');
$topic_num = mysql_fetch_array($tmp)[0];
?>
	<div class = "content">
		<?php
		if (isset($_GET['t']))
		{
			echo "<a href = './'>Return</a>";
			if (isset($_POST['name']))
			{
				mysql_query("UPDATE topics SET name = '".$_POST['name']."', theory = '".$_POST['theory']."' WHERE id = ".$_GET['t']);
			}
			$tmp = mysql_query('SELECT * FROM topics WHERE id = '.$_GET['t']);
			$topic = mysql_fetch_array($tmp);
			echo '<h1>'.$topic['name'].'</h1>';
			echo '<p>';
			echo $topic['theory'];
			echo '</p>';
		}
		else if (isset($_GET['edit']))
		{
			$tmp = mysql_query('SELECT * FROM topics WHERE id = '.$_GET['edit']);
			$topic = mysql_fetch_array($tmp);
			echo '<form action = ?t='.$_GET['edit'].' method = "post">';
			echo '<input type = "text" style = "width: 1000px" name = "name" value = '.$topic['name'].'>';
			echo '<p>';
			echo "<textarea name = 'theory' style = 'width: 100%; height: 500px;' placeholder = 'Теорія'>".$topic['theory']."</textarea>";
			echo '</p>';
			echo "<input type = 'submit' value = 'Відправити'>";
			echo '</form>';
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
				// echo "<a href = '?edit=".($i + 1)."'><img src = '$root/edit.png'></a> ";
				echo ($i + 1).'. <a href = "'.$root.'/theory/?t='.($i + 1).'">'.$topic['name'].'</a>';
				echo '</li>';
			}
			echo '</ul>';
		}
		?>
	</div>
</body>
</html>