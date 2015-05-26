<?php
$admin = true;
$root = "..";
include $root.'/common/db.php';
dbConnect();
$tmp = mysql_query('SELECT * FROM tasks ORDER BY id DESC LIMIT 0, 1');
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
	echo "<a href = '?sort='".$_GET["sort"].">Return</a>";
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
		outp_form();
	}
	else
	{
		$filename = $_FILES["sol_file"]["name"];
		$source = $_FILES["sol_file"]["tmp_name"];
		$target = "uploads/".$filename;
		move_uploaded_file($source, $target);
		echo '<p>';
		$correct = true;
		$timelimit = true;
		$compile = true;
		if (file_exists($target))
		{
			exec ("g++ $target");
			if (file_exists("a.out"))
			{
				// get test file from database
				// get solution cpp file from database
				$tmp = mysql_query('SELECT * FROM tasks WHERE id = '.$_GET['t']);
				$task = mysql_fetch_array($tmp);
				// $solfile = fopen("sol.cpp", "w");
				// fwrite($solfile, $task['solution']);
				// exec("g++ sol.cpp -o sol");
				// read test from database

				$tin = fopen("tin.txt", "w");
				fwrite($tin, $task['test_in']);
				$tout = fopen("tout.txt", "w");
				fwrite($tout, $task['test_out']);

				exec ("./split tin.txt tin ./");
				$num = exec ("./split tout.txt tout ./");

				for ($i = 1; $i <= $num; $i++)
				{	
					exec("./check_sol.sh tin$i ".$task['timelimit']);
					if (file_get_contents("timelimit.txt") != '')
					{
						exec("rm -f result.txt");
						exec("echo 'user_res.txt tout$i' | ./cmp > result.txt");
						if (file_get_contents("result.txt") != '')
						{
							$correct = false;
							break;
						}
					}
					else
					{
						$timelimit = false;
						break;
					}
					exec("rm -f timelimit.txt");
				}
			}
			else
			{
				$compile = false;
			}
			if ($compile == false)
			{
				echo "Помилка компіляції";
				echo "<pre>\tРезультат виконання:\n".file_get_contents("result.txt").'</pre>';
				outp_form();
			}
			else if ($timelimit == false)
			{
				echo "Час виконання завеликий";
				outp_form();
			}
			else if ($correct == false)
			{
				echo "Відповідь не правильна (тест $i)";
				outp_form();
			}
			else
			{
				echo "Відповідь правильна";
			}
			exec("rm -f a.out timelimit.txt user_res.txt result.txt tin* tout*");
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
	case 'topic':
		if (isset($_GET["topic"]))
		{
			$tmp = mysql_query('SELECT name FROM topics WHERE id = '.$_GET["topic"]);
			$topic = mysql_fetch_array($tmp);
			$id = 1;
			echo $topic['name'];
			echo '<ul>';
			for ($i = 0; $i < $task_num; $i++)
			{
				$tmp = mysql_query('SELECT name, topic FROM tasks WHERE id = '.($i + 1));
				$task = mysql_fetch_array($tmp);
				if ($task['name'] != '' && $task['topic'] == $_GET['topic'])
				{
					echo '<li>';
					if (isset($_GET['sort']))
					{
						if ($admin == true)
						{
							echo "<a href = '?rm=".($i + 1)."'><img src = '$root/del.png'></a> <a href = 'edittask?t=".($i + 1)."'><img src = '$root/edit.png'></a> ";
						}
						echo ($id).'. <a href = "'.$root.'/tasks/?t='.($i + 1).'&sort='.$_GET['sort'].'">'.$task['name'].'</a>';
					}
					else
					{
						if ($admin == true)
						{
							echo "<a href = '?rm=".($i + 1)."'><img src = '$root/del.png'></a> <a href = 'edittask?t=".($i + 1)."'><img src = '$root/edit.png'></a> ";
						}
						echo ($id).'. <a href = "'.$root.'/tasks/?t='.($i + 1).'>'.$task['name'].'</a>';
					}
					echo '</li>';
					$id++;
				}
			}
			echo '</ul>';
		}
		else
		{
			$tmp = mysql_query("SELECT COUNT(*) FROM topics");
			$topic_num = mysql_fetch_array($tmp)[0];
			echo '<ul>';
			for ($j = 1; $j <= $topic_num; $j++)
			{
				$tmp = mysql_query('SELECT name FROM topics WHERE id = '.$j);
				$topic = mysql_fetch_array($tmp);
				echo '<li style = "padding-left: 50px; font-size: 30px;">';
				echo "<a href = '?sort=topic&topic=$j'>".$topic['name']."</a>";
				echo '</li>';
			}
			echo '</ul>';
		}
		break;
	default:
		echo '<ul>';
		$id = 1;
		for ($i = 0; $i < $task_num; $i++)
		{
			$tmp = mysql_query('SELECT name FROM tasks WHERE id = '.($i + 1));
			$task = mysql_fetch_array($tmp);
			if ($task['name'] != '')
			{
				echo '<li>';
				if (isset($_GET['sort']))
				{
					if ($admin == true)
					{
						echo "<a href = '?rm=".($i + 1)."'><img src = '$root/del.png'></a> <a href = 'edittask?t=".($i + 1)."'><img src = '$root/edit.png'></a> ";
					}
					echo ($id).'. <a href = "'.$root.'/tasks/?t='.($i + 1).'&sort='.$_GET['sort'].'">'.$task['name'].'</a>';
				}
				else
				{
					if ($admin == true)
					{
						echo "<a href = '?rm=".($i + 1)."'><img src = '$root/del.png'></a> <a href = 'edittask?t=".($i + 1)."'><img src = '$root/edit.png'></a> ";
					}
					echo ($id).'. <a href = "'.$root.'/tasks/?t='.($i + 1).'>'.$task['name'].'</a>';
				}
				echo '</li>';
				$id++;
			}
		}
		echo '</ul>';
	}
}
?>

</div>
</body>
</html>