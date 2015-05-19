<?php
function dbConnect()
{
	mysql_connect('127.0.0.1', 'root', '787898');
	mysql_select_db('acm');
	mysql_query ('set character_set_client="utf8"');
	mysql_query ('set character_set_results="utf8"');
	mysql_query ('set collation_connection="utf8_general_ci"');
}

function dbDisconnect()
{
	
}

?>