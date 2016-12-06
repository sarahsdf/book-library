<?php
	session_start();
	$variable = (isset($_GET['variable'])) ? $_GET['variable'] : "";
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
				<div class="col-xs-5">
					<?php 
						echo "<h2>".  $_GET['title'] . "</h2>";
						echo"<img src=\"" .  $_GET['img_path'] . "\" width=\"250\" class=\"bookDisplay\">";
					?>
				</div>
				<div class="col-xs-7">
					<?php
							echo "<div class=\"row\""."<em>". $_GET['author'] . "</em></div>";
							echo "<div class=\"row\""."<em>". $_GET['publisher'] . "</em></div>";
							echo "<div class=\"row\""."<em>". $_GET['description'] . "</em></div>";
							echo "<div class=\"row\""."<em>". $_GET['quantity'] . "</em></div>";
							echo '<div class="row">';
								echo '
								<form class="col-xs-2 col-xs-offset-2" action="library.php" method="post">
									<button type="submit" class="btn btn-default text-center">Borrow</button>
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