<?php
include 'common/db.php';
dbConnect();
$tmp = mysql_query('SELECT count(*) FROM tasks');
$task_num = mysql_fetch_array($tmp)[0];
?>
<html>
<head>
	<link rel = 'icon' type = 'image/x-icon' href = 'ACM_logo.png'>
	<link rel = 'stylesheet' type = 'text/css' href = 'style.css'>
	<link rel = 'stylesheet' type = 'text/css' href = 'menu.css'>
	<meta http-equiv = 'content-type' content = 'text/html' charset = 'utf-8'>
	<title>Головна</title>
</head>
<body>
	<?php
	$root = '.';
	include 'common/menu.php';
	?>
	<div class = 'content'>
		<h1>Головна сторінка</h1>
		<p>
		Даний сайт спеціально створений для підготовки до міжнародної олімпіади з програмування ACM (Association for Computing Machinery). Завдання розподілені за категоріями - групами алгоритмів для їх вирішення. Для кожної категорії представлено теоретичні відомості та приклади реалізацій алгоритмів. Сайт постійно оновлюється з додаванням нових задач, на даний момент ми налічуємо близько <?php echo ($task_num - $task_num % 5 + 5); ?> задач.
		</p>
	</div>
</body>
</html>