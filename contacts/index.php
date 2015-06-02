<?php
$root = "..";
?>
<html>
<head>
	<link rel="icon" type="image/x-icon" href="<?php echo $root;?>/ACM_logo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $root;?>/menu.css">
	<meta http-equiv = "content-type" content = "text/html" charset = "utf-8">
	<title>Автори</title>
	<style>
	td.contacts
	{
		padding: 5px;
		padding-right: 15px;
		width: auto;
		border: none;
	}
	th
	{
		width: auto;
		text-align: left;
		font-size: 24px;
	}
	</style>
</head>
<body>
	<?php include "$root/common/menu.php"; ?>
	<div class = "content">
		<h1>Контакти</h1>
		<table>
		<p>
			Курсова робота на тему "Тренажер олімпіадних задач з візуалізацією алгоритмів". Створена студентами першого курсу ФІОТ НТУУ "КПІ":
		</p>
		<tr>
			<th class = "contacts">ПІБ</th>
			<th class = "contacts">група</th>
			<th class = "contacts">e-mail</th>
			<th class = "contacts">телефон</th>
		</tr>
		<tr>
			<td class = "contacts">Ботвинко Дар’я Петрівна</td>
			<td class = "contacts">IC-4104</td>
			<td class = "contacts">darya.botvynko@gmail.com</td>
			<td class = "contacts">0937187799</td>
		</tr>
		<tr>
			<td class = "contacts">Сердюк Євгеній Олександрович</td>
			<td class = "contacts">IC-4124</td>
			<td class = "contacts">zheka2797@gmail.com</td>
			<td class = "contacts">0634769167</td>
		</tr>
		<tr>
			<td class = "contacts">Халимон Тарас Юрійович</td>
			<td class = "contacts">IC-4128</td>
			<td class = "contacts">taras.khalymon@gmail.com</td>
			<td class = "contacts">0979624765</td>
		</tr>
		</table>
	</div>
</body>
</html>