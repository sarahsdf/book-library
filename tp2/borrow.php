<?php
	$variable = (isset($_GET['variable'])) ? $_GET['variable'] : "";
	$query = mysql_query("
		INSERT INTO loan ('book_id','user_id')
		VALUES (".$_GET['book_id'].",".$_GET['user_id'].")"
		);

?>