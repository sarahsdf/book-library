<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<head>
    <title>Validation</title>
     <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="libs/bootstrap/dist/css/bootstrap.min.css">
    <!-- insert more css file here -->
    <link rel="stylesheet" href="src/css/style.css">
</head>
<body>
<?php if (! $_POST) {echo "400 Bad Request"; die();}
session_start();
$target_dir = "./covers/";
$uploadStatus = true;


//mengecek apakah terdapat image pada superglobal var $_FILES
if(isset($_FILES['image'])){
	//mendapatkan nama file
	$file_name = $_FILES['image']['name'];

	//mendapatkan ukuran file
	$file_size = $_FILES['image']['size'];

	//mendapatkan nama file sementara yang dismpan oleh server
	$file_tmp = $_FILES['image']['tmp_name'];

	//mendapatkan tipe file
	$file_type = $_FILES['image']['type'];

	$time = date_create();

	//mendapatkan file extension
	$file_ext = pathinfo($file_name,PATHINFO_EXTENSION);

	//menindahkan file dari tempat yang sementara pada server ke directory yang kita inginkan
	move_uploaded_file($file_tmp, ("./covers/".$file_name));
	
	$_SESSION['message'] = "The file ". basename($file_name). " has been uploaded!" ;

	if($file_size < 2097152 && ($file_ext == "jpg" || $file_ext == "png")){
		echo $_SESSION['message'];
		$uploadStatus = true;
	}

	//mengecek file size
	if($file_size > 2097152 ){
		echo "Sorry, the file is too large!";
		$uploadStatus = false;
	}

	//mengecek file ekstensi
	if($file_ext != "jpg" && $file_ext != "png"){
		echo "Sorry, only JPG and PNG files are allowed";
		$uploadStatus = false;
	}

	if($uploadStatus == true){
		$rowData = array("./src/covers/".$file_name, $file_name, $time);
		if($_SESSION['rowData']){
			$tableData = $_SESSION['rowData'];
			array_push($tableData, $rowData);
			$_SESSION['rowData'] = $tableData;
		}
		else{
			$_SESSION['rowData'] = array($rowData);
		}
		header("Location: admin.php");
	}
}
?>
<a href="admin.php" alt="back to index"><button class="btn btn-warning pull-right" id="button">Upload Another File</button></a>
</body>
</html>