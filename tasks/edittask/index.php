<?php
$root = "../..";
?>
<html>
<head>
	<link rel="icon" type="image/x-icon" href="<?php echo $root;?>/ACM_logo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/menu.css">
	<meta http-equiv = "content-type" content = "text/html" charset = "utf-8">
	<title>Змінити завдання</title>

<script>
var countExamples = <?php echo $exnum;?>; // Текущее число полей
var curExampleId = <?php echo $exnum;?>; // Уникальное значение для атрибута name
var exampleLimit = 5; // Максимальное число возможных полей
function deleteExample(ex)
{
	if (countExamples > 1)
	{
		// Получаем доступ к ДИВу, содержащему поле
		var contDiv = ex.parentNode.parentNode;
		// Удаляем этот ДИВ из DOM-дерева
		contDiv.parentNode.removeChild(contDiv);
		// Уменьшаем значение текущего числа полей
		countExamples--;
	}
	changeExNum();
	// Возвращаем false, чтобы не было перехода по сслыке
	return false;
}
function addExample()
{
	// Проверяем, не достигло ли число полей максимума
	if (countExamples >= exampleLimit) 
	{
		return false;
	}
	// Увеличиваем текущее значение числа полей
	countExamples++;
	// Увеличиваем ID
	curExampleId++;
	// Создаем элемент ДИВ
	var ex = document.createElement("tr");
	// Добавляем HTML-контент с пом. свойства innerHTML
	ex.innerHTML = " <tr><td class = 'fname'></td><td style = 'vertical-align:top; width: 500px;' class = 'fvalue'><textarea name = 'ein" + curExampleId + "' style = \"width:247px; height:50px; margin-top:5px;\"></textarea>\n<textarea name = 'eout" + curExampleId + "' style = \"width:247px; height:50px; margin-top:5px;\"></textarea>\n</td><td class = 'fdel'><a style='text-decoration:none;' onclick='return deleteExample(this)' href = '#'><img src = '<?php echo $root; ?>/del.png'></a></td></tr>";
	// Добавляем новый узел в конец списка полей
	document.getElementById("examples").appendChild(ex);
	changeExNum();
	// Возвращаем false, чтобы не было перехода по сслыке
	return false;
}
var countTests = <?php echo $tnum;?>; // Текущее число полей
var curTestId = <?php echo $tnum;?>; // Уникальное значение для атрибута name
var testLimit = 50; // Максимальное число возможных полей
function deleteTest(tst)
{
	if (countTests > 1)
	{
		// Получаем доступ к ДИВу, содержащему поле
		var contDiv = tst.parentNode.parentNode;
		// Удаляем этот ДИВ из DOM-дерева
		contDiv.parentNode.removeChild(contDiv);
		// Уменьшаем значение текущего числа полей
		countTests--;
	}
	changeTsNum();
	// Возвращаем false, чтобы не было перехода по сслыке
	return false;
}
function addTest()
{
	// Проверяем, не достигло ли число полей максимума
	if (countTests >= testLimit) 
	{
		return false;
	}
	// Увеличиваем текущее значение числа полей
	countTests++;
	// Увеличиваем ID
	curTestId++;
	// Создаем элемент ДИВ
	var tst = document.createElement("tr");
	// Добавляем HTML-контент с пом. свойства innerHTML
	tst.innerHTML = " <tr><td class = 'fname'></td><td style = 'vertical-align:top; width: 500px;' class = 'fvalue'><textarea name = 'tin" + curTestId + "' style = \"width:247px; height:50px; margin-top:5px;\"></textarea>\n<textarea name = 'tout" + curTestId + "' style = \"width:247px; height:50px; margin-top:5px;\"></textarea>\n</td><td class = 'fdel'><a style='text-decoration:none;' onclick='return deleteTest(this)' href = '#'><img src = '<?php echo $root; ?>/del.png'></a></td></tr>";
	// Добавляем новый узел в конец списка полей
	document.getElementById("tests").appendChild(tst);
	// document.getElementById("tests").createElement(tst);
	changeTsNum();
	// Возвращаем false, чтобы не было перехода по сслыке
	return false;
}

function changeExNum()
{
	var num = document.getElementById("eNum");
	num.innerHTML = "<input type = hidden name = e_num value = " + curExampleId + ">";
}

function changeTsNum()
{
	var num = document.getElementById("tNum");
	num.innerHTML = "<input type = hidden name = t_num value = " + curTestId + ">";
}
</script>	
</head>
<body>
<?php
include "$root/common/menu.php";
dbConnect();
$tmp = mysql_query('SELECT * FROM tasks WHERE id = '.$_GET['t']);
$task = mysql_fetch_array($tmp);
$ein = fopen("ein.txt", "w");
fwrite($ein, $task['example_in']);
$eout = fopen("eout.txt", "w");
fwrite($eout, $task['example_out']);
exec ("./split ein.txt ein ./");
$exnum = exec ("./split eout.txt eout ./");
$tin = fopen("tin.txt", "w");
fwrite($tin, $task['test_in']);
$tout = fopen("tout.txt", "w");
fwrite($tout, $task['test_out']);
exec ("./split tin.txt tin ./");
$tnum = exec ("./split tout.txt tout ./");
mysql_close();
?>
<div class = 'content'>
<?php
if (!isset($_POST['e_num']))
{
	echo "
	<form action = '#' method = 'post'>
	<table style = 'border: none;' id = 'parentId'>
	<tr>
		<td class = 'fname'>
			Назва
		</td>
		<td class = 'fvalue' colspan = 2>
			<input type = 'text' name = 'name' value = '".$task['name']."' style = 'width: 100%' placeholder = 'Назва задачі'>
		</td>
	</tr>
	<tr>
		<td style = 'padding-top: 15px;' class = 'fname'>
			Ліміт часу
		</td>
		<td class = 'fvalue' colspan = 2>
			<input type = 'int' name = 'tlimit' value = '".$task['timelimit']."'>
		</td>
	</tr>
	<tr>
		<td class = 'fname'>
			Тема
		</td>
	<td class = 'fvalue' colspan = 2>
	<select name = 'topic'>
	<option value = '0'>Виберіть тему</option>";
	dbConnect();
	$tmp = mysql_query('SELECT count(*) FROM topics');
	$topic_num = mysql_fetch_array($tmp)[0];
	for ($i = 1; $i <= $topic_num; $i++)
	{
		$tmp = mysql_query('SELECT id, name FROM topics WHERE id = '.$i);
		$topic = mysql_fetch_array($tmp);
		if ($task['timelimit'] == $i)
		{
			echo '<option selected value = '.$i.'>'.$topic["name"]."</option>\n";
		}
		else
		{
			echo '<option value = '.$i.'>'.$topic["name"]."</option>\n";
		}
	}
	mysql_close();
	echo "
	</select>
		</td>
	</tr>
	<tr>
		<td class = 'fname'>
			Текст
		</td>
		<td class = 'fvalue' colspan = 2>
			<textarea name = 'prob' style = 'width: 100%; height: 200px;' placeholder = 'Текст задачі'>".$task['prob']."</textarea>
		</td>
	</tr>
	</table>
	Приклади до завдання
	<div id = 'eNum'>
		<input type = 'hidden' name = 'e_num' value = $exnum>
	</div>
	<table id = 'examples'>
	";
	for ($i = 1; $i <= $exnum; $i++)
	{
		echo "
		<tr>
			<td class = 'fname'></td>
			<td class = 'fvalue'  style = 'vertical-align: middle; width: 500px;'>
				<textarea name = 'ein$i' type='text' style='width:247px; height:50px; margin-top:5px;'>".file_get_contents("ein$i")."</textarea>
				<textarea name = 'eout$i' type='text' style='width:247px; height:50px; margin-top:5px;'>".file_get_contents("eout$i")."</textarea>
			</td>
			<td class = 'fdel'>
				<a style = 'text-decoration:none;' onclick='return deleteExample(this)' href='#'>
					<img style = 'padding-top: 0px' src = '".$root."/del.png'>
				</a>
			</td>
		</tr>";
	}
	echo "
	</table>
	<div style = 'margin-left: 220px; width: 300px; vertical-align: middle;'>
		<center>
			<a onclick='return addExample()' href = '#'><img style = 'height: 30px' src = '".$root."/add.png'></a><br><br>
		</center>
	</div>
	Тести
	<div id = 'tNum'>
		<input type = 'hidden' name = 't_num' value = $tnum>
	</div>
	<table id = 'tests'>";



	for ($i = 1; $i <= $tnum; $i++)
	{
		echo "
		<tr>
			<td class = 'fname'></td>
			<td class = 'fvalue'  style = 'vertical-align: middle; width: 500px;'>
				<textarea name = 'tin$i' type = 'text' style = 'width:247px; height:50px; margin-top:5px;'>".file_get_contents("tin$i")."</textarea>
				<textarea name = 'tout$i' type = 'text' style = 'width:247px; height:50px; margin-top:5px;'>".file_get_contents("tout$i")."</textarea>
			</td>
			<td class = 'fdel'>
				<a style = 'text-decoration:none;' onclick='return deleteTest(this)' href='#'>
					<img style = 'padding-top: 0px' src = '".$root."/del.png'>
				</a>
			</td>
		</tr>";
	}
	echo "
	</table>
	<div style = 'margin-left: 220px; width: 300px; vertical-align: middle;'>
		<center>
			<a onclick='return addTest()' href='#'><img style = 'height: 30px' src = '".$root."/add.png'></a><br><br>
			<input type = 'submit' value = 'Відправити'>
		</center>
	</div>
	</form>";
	exec("rm ein* eout*");
}
else
{
	$inputOk = true;
	if ($_POST['name'] == "") $inputOk = false;
	if ($inputOk == false)
	{
		echo "Incorrect input";
	}
	else
	{
		$name = $_POST['name'];
		$prob = $_POST['prob'];
		$ex_in = fopen("ex_in.txt", "w");
		$ex_out = fopen("ex_out.txt", "w");
		$first = true;
		for ($i = 0; $i <= $_POST['e_num']; $i++)
		{
			if (isset($_POST["ein$i"]))
			{
				if ($first == false)
				{
					fwrite($ex_in, "\n||\n");
					fwrite($ex_out, "\n||\n");
				}
				$first = false;
				fwrite($ex_in, $_POST["ein$i"]);
				fwrite($ex_out, $_POST["eout$i"]);
			}
		}
		fclose($ex_in);
		fclose($ex_out);
		$ein = file_get_contents("ex_in.txt");
		$eout = file_get_contents("ex_out.txt");
		$ts_in = fopen("t_in.txt", "w");
		$ts_out = fopen("t_out.txt", "w");
		$first = true;
		for ($i = 0; $i <= $_POST['t_num']; $i++)
		{
			if (isset($_POST["tin$i"]))
			{
				if ($first == false)
				{
					fwrite($ts_in, "\n||\n");
					fwrite($ts_out, "\n||\n");
				}
				$first = false;
				fwrite($ts_in, $_POST["tin$i"]);
				fwrite($ts_out, $_POST["tout$i"]);
			}
		}
		fclose($ts_in);
		fclose($ts_out);
		$t_in = file_get_contents("t_in.txt");
		$t_out = file_get_contents("t_out.txt");
		exec("rm ex_in.txt ex_out.txt t_in.txt t_out.txt");
		$topic = $_POST['topic'];
		$tlimit = $_POST['tlimit'];
		dbConnect();
		mysql_query("UPDATE tasks SET name = '$name', prob = '$prob', example_in = '$ein', example_out = '$eout', test_in = '$t_in', test_out = '$t_out', topic = '$topic', timelimit = '$tlimit' WHERE id = ".$_GET['t']);
		mysql_close();
		echo "Задача оновлена";
	}
}
exec("rm ein* eout* tin* tout*");
?>
</div>
</body>
</html>