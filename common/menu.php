<?php
include 'db.php';
dbConnect();
$addr = $_SERVER['PHP_SELF'];
$getused = false;
if (isset($_GET['t']))
{
	$getused = true;
	$addr = $addr."?t=".$_GET['t'];
}
if (isset($_GET['sort']))
{
	if ($getused)
	{
		$addr = $addr."&";
	}
	else
	{
		$addr = $addr."?";
		$getused = true;
	}
	$addr = $addr."sort=".$_GET['sort'];
}
if ($_SERVER['REMOTE_ADDR'] != '192.168.1.1')
{
	mysql_query("INSERT INTO visitors (ip, time, location) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".date("d.m.y H:i:s")."', '".$addr."')");
}
mysql_close();
echo '<div class = "logo">
	<img src="'.$root.'/ACM_logo.png">';
echo "<div style='display: inline-block;'>Тренажер олімпіадних задач</div>
<p style = 'margin-bottom: -10px; margin-top: -30px; padding: -10px;'>(відповідно до стандартів ACM)</p>";
	
echo '
</div>
<ul class="menu">
	<li><a href = "'.$root.'/">Головна</a></li>
	<li><a href = "'.$root.'/tasks'; if (isset($_GET['sort'])) echo '/?sort='.$_GET['sort'];echo'">Завдання</a>
		<ul class="submenu">
			<li><a href = "'.$root.'/tasks/?sort=name">Всі задачі</a></li>
			<li><a href = "'.$root.'/tasks/?sort=topic">Сортувати за темами</a></li>';
			if ($admin == true)
			{
				echo '<li><a href = "'.$root.'/tasks/addtask">Додати задачу</a></li>';
			}
		echo '</ul>
	</li>
	<li><a href = "'.$root.'/theory">Теорія</a></li>
	<li><a href = "'.$root.'/cource">Онлайн курс</a></li>
	<li><a href = "'.$root.'/contacts">Контакти</a></li>
</ul>';
?>