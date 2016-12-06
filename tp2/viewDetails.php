<?php
	session_start();
	$bookid = $_GET['book_id'];
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

	function deleteBook($id) {
        $conn = connectDB();
        
        $sql = "DELETE FROM book WHERE id = $bookid";
        
        if($result = mysqli_query($conn, $sql)) {
            echo "New record created successfully <br/>";
            header("Location: viewDetails.php");
            } else {
            die("Error: $sql");
        }
        mysqli_close($conn);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if($_POST['command'] === 'delete') {
			deleteBook($_POST['bookid']);
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

        </div>
		<div class="container">
			<div class="row">
				<div class="col-xs-6">
					<?php 
						echo "<h2>".  $_GET['title'] . "</h2>";
						echo"<img src=\"" .  $_GET['img_path'] . "\" width=\"250\" class=\"bookDisplay\">";
					?>
				</div>
				<div class="col-xs-6" id="bookDetails">
					<?php
							echo "<div class=\"row\"><strong>Author: </strong>". $_GET['author'] . "</div><br/>";
							echo "<div class=\"row\"><strong>Publisher: </strong>". $_GET['publisher'] . "</div><br/>";
							echo "<div class=\"row\"><strong>Description: </strong>". $_GET['description'] . "</div><br/>";
							echo "<div class=\"row\"><strong>Quantity: </strong>". $_GET['quantity'] . "</div><br/>";
							echo '<div class="row">';
							if(isset($_SESSION['username']) && $_SESSION['role'] === "user"){
								echo '
								<form class="col-xs-8" action="library.php?book_id='.$_GET['book_id'].' method="post">
									<button type="submit" class="btn btn-default text-center">Borrow this book</button>
								</form>
								<input type="hidden" name="title" value="'.$_GET['book_id'].'"/>
								<input type="hidden" name="img_path" value="'.$_SESSION['user_id'].'"/>
								';
							}
							if(isset($_SESSION['username']) && $_SESSION['role'] === "admin"){
								echo '
	                                <form action="viewDetails.php" method="post" class="col-xs-6">
	                                    <input type="hidden" id="delete-bookid" name="bookid" value="'.$bookid.'">
	                                    <input type="hidden" id="delete-command" name="command" value="delete">
	                                    <button type="submit" class="btn btn-danger">Delete</button>
	                                </form>';
							}
								echo '
								<form class="col-xs-4 pull-right" action="library.php" method="post">
									<button type="submit" class="btn btn-default text-center">Back to Collections</button>
								</form>
								';
							echo "</div>";
					?>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>							