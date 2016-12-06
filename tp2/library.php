<?php
	session_start();
	// if(!isset($_SESSION['username'])){
	// 	header("Location: index.php#loginHere");
	// }
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

	function bookCover(){
		$conn = connectDB();
		$sql = "SELECT * from book";
		$result = $conn->query($sql);
		return $result;
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
			header("Location: library.php");
			} else {
			die("Error: $sql");
		}
		mysqli_close($conn);
	}

	function borrowBook() {
		$conn = connectDB();
		
		$book_id = $_POST['book_id'];
		$title = $_POST['title'];
		$author = $_POST['author'];
		$publisher = $_POST['publisher'];
		$description = $_POST['description'];
		$quantity = $_POST['quantity'];
		$sql = "INSERT into loan (loan_id, book_id, user_id) SELECT * from book";
		
		if($result = mysqli_query($conn, $sql)) {
			echo "New record created successfully <br/>";
			header("Location: library.php");
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
			header("Location: library.php");
			} else {
			die("Error: $sql");
		}
		mysqli_close($conn);
	}
	
	function deleteBook($id) {
		$conn = connectDB();
		
		$sql = "DELETE FROM book WHERE book_id=$id";
		
		if($result = mysqli_query($conn, $sql)) {
			echo "New record created successfully <br/>";
			header("Location: library.php");
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
			insertBook();
		} else if($_POST['command'] === 'update') {
			updateBook($_POST['bookid']);
		} else if($_POST['command'] === 'delete') {
			deleteBook($_POST['bookid']);
		} else if($_POST['command'] === 'borrow') {
			borrowBook();
		}

	}
	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Library</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="./src/css/bootstrap-3.3.7.min.css">
        <link rel="stylesheet" href="./src/css/library-style.css">
	</head>
	<body>
		<body data-spy="scroll" data-target=".enter">
        <nav class="navbar text-center navbar-default navbar-fixed-top" id="navv">
            <!--bootstrap navigation from http://www.w3schools.com/bootstrap/bootstrap_navbar.asp-->
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php">Homepage</a></li> 
                    <li><a href="library.php">Collection</a></li>
                </ul>
                    <p class="navbar-text text-uppercase" id="title">Welcome to our library!</p>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="about.php">About</a></li>
                    <li><a href="logout.php" id="logout">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="parallax">
            <div class="enter">
                <a href="#loginHere"><span class="border">enter <!--span class="glyphicon glyphicon-chevron-down"></span--></span></a>
            </div>

        </div>
		<div class="container">
			<div class="row">
				<?php
					$result = bookCover();
					$cf = 0;

					while($row = $result->fetch_assoc()) {
						$bookId = $row['book_id'];
						//$userId = $_SESSION['user_id'];
						$userId = "";
						$cf++;
						echo '<div class="col-xs-4 text-center">';
						echo"<img src=\"" .  $row['img_path'] . "\" width=\"150\" class=\"bookDisplay\">";
							
						echo "<div><strong>".  $row['title'] . "</strong></div>";
						echo "<em>". $row['author'] . "</em>";
						echo '<div class="row">';
						if(isset($_SESSION['username'])){
							echo '
							<form class="col-xs-2 col-xs-offset-2" action="library.php" method="post">
								<button type="submit" class="btn btn-default text-center">Borrow</button>
							</form>
							';
						}
						echo '
						<form class="col-xs-6" action="viewDetails.php?book_id='.$bookId.' method="post">
							<input type="hidden" name="title" value="'.$row['title'].'"/>
							<input type="hidden" name="img_path" value="'.$row['img_path'].'"/>
							<input type="hidden" name="author" value="'.$row['author'].'"/>
							<input type="hidden" name="publisher" value="'.$row['publisher'].'"/>
							<input type="hidden" name="quantity" value="'.$row['quantity'].'"/>
							<input type="hidden" name="description" value="'.$row['description'].'"/>
							<button type="submit" class="btn btn-danger text-center">view details</button>
						</form>
						';
						echo '</div>';
						echo "</div>";
						if ($cf == 3) {
							echo "<div class=\"clearfix\"></div>";
							$cf = 0;
						}
					}
				?>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>							