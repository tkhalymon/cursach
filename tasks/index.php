<?php
$root = "..";
include $root.'/common/db.php';
dbConnect();
$tmp = mysql_query('SELECT count(*) FROM tasks');
$task_num = mysql_fetch_array($tmp)[0];
?>
<html>
<head>
	<link rel="icon" type="image/x-icon" href="<?php echo $root;?>/ACM_logo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/menu.css">
	<meta http-equiv = "content-type" content = "text/html" charset = "utf-8">
	<title>Задачі</title>
</head>
<body>
<?php
include "$root/common/menu.php";
?>
	<div class = "content">
<?php
function outp_form()
{
echo "\n".'<form method = "POST" action = "?t='.$_GET['t'].'" enctype = "multipart/form-data">
	<input type = "file" name = "sol_file" id = "sol_file">
	<input type = "submit" value = "Перевірити">
</form>';
echo "\n".'<table>
	<tr><td>Вхідні дані</td><td>stdin</td></tr>
	<tr><td>Вихідні дані</td><td>stdout</td></tr>
	<tr><td>Ліміт часу</td><td>1 секунда</td></tr>
</table>';
}
if (!isset($_GET["sort"]))
{
	$_GET["sort"] = "diff";
}
if (isset($_GET["rm"]))
{
	mysql_query("DELETE FROM tasks WHERE id = ".$_GET["rm"]);
}
if (isset($_GET['t']))
{
	$tmp = mysql_query('SELECT * FROM tasks WHERE id = '.$_GET['t']);
	$task = mysql_fetch_array($tmp);
	echo '<h1>'.$task['name']."</h1>\n";
	echo '<p>'.$task['prob']."</p>\n";
	echo "<table>\n";
	echo "<td colspan = '2'>\n";
	echo "Приклади вхідних даних\n";
	echo "</td>\n";
	echo "<tr>\n";
	echo "<td>Вхідні дані</td>\n";
	echo "<td>Вихідні дані</td>\n";
	echo "</tr>\n";
	$exin = fopen("exin.txt", "w");
	fwrite($exin, $task["example_in"]);
	$exout = fopen("exout.txt", "w");
	fwrite($exout, $task["example_out"]);
	exec ("./split exin.txt exi ./");
	$num = exec ("./split exout.txt exo ./");
	fwrite($exin, $task["example_in"]);
	for ($i = 1; $i <= $num; $i++)
	{
		echo "<tr>\n";
		echo '<td>'.file_get_contents("exi$i")."</td>\n";
		echo '<td>'.file_get_contents("exo$i")."</td>\n";
		echo "</tr>\n";
	}
	exec("rm exi* exo*");
	// echo "<tr>\n";
	// // echo '<td>'.$task['input2']."</td>\n";
	// // echo '<td>'.$task['output2']."</td>\n";
	// echo "</tr>\n";
	echo "</table>\n";
	$tmp = mysql_query('SELECT name FROM topics WHERE id = '.$task['topic']);
	$topic_name = mysql_fetch_array($tmp)[0];
	echo '<p>Ця задача належить до теми: ';
	echo '<a href = "../theory/?t='.$task['topic'].'">'.$topic_name.'</a></p>';
	if (empty($_FILES["sol_file"]["name"]))
	{
		// echo "<a href='http://hometasks.zz.mu/correct.cpp'>правильно<br></a>";
		// echo "<a href='http://hometasks.zz.mu/incorrect.cpp'>не правильно<br></a>";
		// echo "<a href='http://hometasks.zz.mu/timeout.cpp'>ліміт часу<br></a>";
		outp_form();
	}
	else
	{
		$filename = $_FILES["sol_file"]["name"];
		$source = $_FILES["sol_file"]["tmp_name"];
		$target = "uploads/".$filename;
		move_uploaded_file($source, $target);
		echo '<p>';
		if (file_exists($target))
		{
			exec ("g++ $target");
			if (file_exists("a.out"))
			{
				// get test file from database
				// get solution cpp file from database
				$tmp = mysql_query('SELECT * FROM tasks WHERE id = '.$_GET['t']);
				$task = mysql_fetch_array($tmp);
				$solfile = fopen("sol.cpp", "w");
				fwrite($solfile, $task['solution']);
				exec("g++ sol.cpp -o sol");
				// read test from database
				exec("echo '1 4' > test.txt");
				exec("./check_sol.sh ".$task['timelimit']);
				if (file_get_contents("timelimit.txt") != '')
				{
					exec("cat test.txt | ./sol > sol_res.txt");
					exec("rm -f result.txt");
					exec("echo 'user_res.txt sol_res.txt' | ./cmp > result.txt");
					if (file_get_contents("result.txt") != '')
					{
						echo 'Відповідь не правильна';
						outp_form();
						echo "todo: add info";
					}
					else
					{
						echo 'Відповідь правильна';
					}
					exec ("rm -f sol_res.txt test.txt user_res.txt");
				}
				else
				{
					echo 'Час виконання завеликий';
					outp_form();
				}
				exec("rm -f timelimit.txt");
			}
			else
			{
				echo 'Помилка компіляції';
				outp_form();
				echo "<pre>\tРезультат виконання:\n".file_get_contents("result.txt").'</pre>';
			}
			// exec("rm -f a.out sol timelimit.txt sol_res.txt user_res.txt test.txt sol.cpp result.txt");
			exec("rm -f a.out result.txt sol sol.cpp");
			unlink($target);
		}
		echo '</p>';
	}
}
else
{
	switch ($_GET["sort"])
	{
	case 'name':
		echo '<h1>Завдання відсортовані за іменем</h1>';
		echo '<p>Ви можете швидко знайти конкретну задачу за її назвою</p>';
	break;
	case 'diff':
		echo '<h1>Завдання відсортовані за складністю</h1>';
		echo '<p>Ви можете збільшуючи складність поступово підвищувати свій рівень</p>';
		break;
	case 'topic':
		echo '<h1>Завдання відсортовані за категоріями</h1>';
		echo '<p>Ви можете покращити свої навички розв’язання задач певних видів</p>';
		break;
	}
	echo '<ul>';
	for ($i = 0; $i < $task_num; $i++)
	{
		echo '<li>';
		$tmp = mysql_query('SELECT name FROM tasks WHERE id = '.($i + 1));
		// echo "string";
		$task = mysql_fetch_array($tmp);
		if (isset($_GET['sort']))
			echo ($i + 1).'. <a href = "'.$root.'/tasks/?t='.($i + 1).'&sort='.$_GET['sort'].'">'.$task['name'].'</a>';
		else
			echo ($i + 1).'. <a href = "'.$root.'/tasks/?t='.($i + 1).'">'.$task['name'].'</a>';
		echo '</li>';
	}
	echo '</ul>';
}
?>

</div>
</body>
</html>