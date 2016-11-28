<?php
	session_start();
	if(!isset($_SESSION['username'])){
		header("Location: index.php");
	}
	function connectDB() {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "library";
		
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		
		// Check connection
		if (!$conn) {
			die("Connection failed: " + mysqli_connect_error());
		}
		return $conn;
	}
	
	function insertBook() {
		$conn = connectDB();
		
		$img_path = $_POST['img_path'];
		$title = $_POST['title'];
		$author = $_POST['author'];
		$publisher = $_POST['publisher'];
		$description = $_POST['description'];
		$quantity = $_POST['quantity'];
		$sql = "INSERT into book (img_path, title, author, publisher, description, quantity) values('$img_path','$title','$author','$publisher','$description','$quantity')";
		
		if($result = mysqli_query($conn, $sql)) {
			echo "New record created successfully <br/>";
			header("Location: admin.php");
			} else {
			die("Error: $sql");
		}
		mysqli_close($conn);
	}
	
	function updateBook($id) {
		$conn = connectDB();
		
		$img_path = $_POST['img_path'];
		$title = $_POST['title'];
		$author = $_POST['author'];
		$publisher = $_POST['publisher'];
		$description = $_POST['description'];
		$quantity = $_POST['quantity'];
		$sql = "UPDATE book SET img_path='$img_path', title='$title', author='$author', publisher='$publisher', description='$description', quantity='$quantity'
			WHERE id=$id";
		
		if($result = mysqli_query($conn, $sql)) {
			echo "New record created successfully <br/>";
			header("Location: admin.php");
			} else {
			die("Error: $sql");
		}
		mysqli_close($conn);
	}
	
	function deleteBook($id) {
		$conn = connectDB();
		
		$sql = "DELETE FROM book WHERE id=$id";
		
		if($result = mysqli_query($conn, $sql)) {
			echo "New record created successfully <br/>";
			header("Location: admin.php");
			} else {
			die("Error: $sql");
		}
		mysqli_close($conn);
	}
	
	function selectAllFromTable($table) {
		$conn = connectDB();
		
		$sql = "SELECT book_id, img_path, title, author, publisher, description, quantity FROM $table";
		
		if(!$result = mysqli_query($conn, $sql)) {
			die("Error: $sql");
		}
		mysqli_close($conn);
		return $result;
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if($_POST['command'] === 'insert') {
			insertPaket();
			} else if($_POST['command'] === 'update') {
			updatePaket($_POST['paketid']);
			} else if($_POST['command'] === 'delete') {
			deletePaket($_POST['paketid']);
		}
	}
	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Library</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="./src/css/library-style.css">
	</head>
	<body>
		<nav class="navbar navbar-default text-center navbar-fixed-top" id="navv">
			<!--bootstrap navigation from http://www.w3schools.com/bootstrap/bootstrap_navbar.asp-->
			<div class="container-fluid">
    			<div class="navbar-header" id="navv">
     				<a class="navbar-brand" href="#" id="judul">Hello, user!</a>
   				 </div>
    			<ul class="nav navbar-nav">
    				<li class="active"><a href="admin.php">Homepage</a></li> 
					<li><a href="admin.php">Contact</a></li>
					<li><a href="admin.php">About</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a class="btn btn-info navbar-btn" href="logout.php" id="logout">Logout</a></li>
				</ul>
 			</div>
		</nav>
		<div class="container">
			<!--h1 class="text-center">Latihan Lab 8</h1-->
			
			<div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="insertModalLabel">Add Collection</h4>
						</div>
						<div class="modal-body">
							<form action="admin.php" method="post">
								<div class="form-group">
                          			<label for="img_path" class="text-center text-danger">Select file to upload:</label>
                            		<input class="form-control" type="file" name="img_path" id="insert-cover"/>
                            	</div>
								<div class="form-group">
									<label for="title">Title </label>
									<input type="text" class="form-control" id="insert-title" name="title" placeholder="insert title of the book">
								</div>
								<div class="form-group">
									<label for="author">Author</label>
									<input type="text" class="form-control" id="insert-author" name="author" placeholder="author of the book">
								</div>
								<div class="form-group">
									<label for="publisher">Publisher</label>
									<input type="text" class="form-control" id="insert-publisher" name="publisher" placeholder="publisher of the book">
								</div>
								<div class="form-group">
									<label for="description">Description</label>
									<input type="text" class="form-control" id="insert-description" name="description" placeholder="description of the book">
								</div>
								<div class="form-group">
									<label for="quantity">Quantity</label>
									<input type="text" class="form-control" id="insert-quantity" name="quantity" placeholder="Quantity of the book">
								</div>
								<input type="hidden" id="insert-command" name="command" value="insert">
								<button type="submit" class="btn btn-primary">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class='table'>
					<thead> <tr> <th>ID</th> <th>Covers</th> <th>Title</th> <th>Author</th> <th>Publisher</th> <th>Description</th> <th>Quantity</th> </tr> </thead>
					<tbody>
						<?php
							$img_path = selectAllFromTable("book");
							while ($row = mysqli_fetch_row($img_path)) {
								echo "<tr>";
								foreach($row as $key => $value) {
									echo "<td>$value</td>";
								}
								echo '<td>
								<button type="button" class="btn btn-default" data-toggle="modal" data-target="#updateModal" onclick="setUpdateData(\''.$row[0].'\',\''.$row[1].'\',\''.$row[2].'\',\''.$row[3].'\',\''.$row[4].'\')">
								Edit
								</button>
								</td>';
								echo '<td>
								<form action="admin.php" method="post">
									<input type="hidden" id="delete-bookid" name="bookid" value="'.$row[0].'">
									<input type="hidden" id="delete-command" name="command" value="delete">
									<button type="submit" class="btn btn-danger">Delete</button>
								</form>
								</td>';
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="updateModalLabel">Update Collection</h4>
						</div>
						<div class="modal-body">
							<form action="admin.php" method="post">
								<div class="form-group">
                          			<label for="img_path" class="text-center text-danger">Select file to upload:</label>
                            		<input class="form-control" type="file" name="img_path" id="update-cover"/>
                            	</div>
								<div class="form-group">
									<label for="title">Title </label>
									<input type="text" class="form-control" id="update-title" name="title" placeholder="insert title of the book">
								</div>
								<div class="form-group">
									<label for="author">Author</label>
									<input type="text" class="form-control" id="update-author" name="author" placeholder="author of the book">
								</div>
								<div class="form-group">
									<label for="publisher">Publisher</label>
									<input type="text" class="form-control" id="update-publisher" name="publisher" placeholder="publisher of the book">
								</div>
								<div class="form-group">
									<label for="description">Description</label>
									<input type="text" class="form-control" id="update-description" name="description" placeholder="description of the book">
								</div>
								<div class="form-group">
									<label for="quantity">Quantity</label>
									<input type="text" class="form-control" id="update-quantity" name="quantity" placeholder="Quantity of the book">
								</div>
								<input type="hidden" id="update-bookid" name="bookid">
								<input type="hidden" id="update-command" name="command" value="update">
								<button type="submit" class="btn btn-primary">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!--h4>Paket</h4-->
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#insertModal">
				Add Collection
			</button>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script>
			function setUpdateData(id, \img_path, tujuan, fitur, harga) {
				$("#update-bookid").val(id);
				$("#update-cover").val(img_path);
				$("#update-title").val(title);
				$("#update-author").val(author);
				$("#update-publisher").val(publisher);
				$("#update-description").val(description);
				$("#update-quantity").val(quantity);
			}
		</script>
	</body>
</html>							