<html>
<head>
	<title>Add image</title>
</head>
<body>
<?php
$filename = $_FILES["img"]["name"];
$source = $_FILES["img"]["tmp_name"];
$target = $filename;
move_uploaded_file($source, $target);
exec("ls > images.txt");
$images = fopen("images.txt", "r");
while (!feof($images))
{
	$img = fgets($images);
	if (strcmp($img, "index.php\n") != 0 && strcmp($img, "images.txt\n") != 0 && $img[0] != "")
	{
		echo "Шлях: "."http://77.47.239.14/cursach/res/".$img."<br>";
		echo "<img style = 'height: 50px' src = $img>";
		echo "<br>";
		echo "<br>";
	}
}
?>

<form method = post enctype = "multipart/form-data">
	<input type = "file" name = "img" id = "image">
	<input type = "submit" value = "Send">
</form>
</body>
</html>