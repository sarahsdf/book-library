<?php
    session_start();
    if(!isset($_SESSION['username']) && $_SESSION['role'] != "admin"){
        header("Location: index.php#loginHere");
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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Personal Library</title>
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
                    <li><a href="index.php">Homepage</a></li> 
                    <li><a href="library.php">View Books</a></li>
					<?php if(isset($_SESSION['username']) && $_SESSION['role'] === "user"){?>
                    <li><a href="borrowed.php">My Books</a></li>
                    <?php }?>
                </ul>
                    <p class="navbar-text text-uppercase" id="title">Personal Library</p>
                <ul class="nav navbar-nav navbar-right">
                    <?php if(isset($_SESSION['username']) && $_SESSION['role'] === "admin"){?>
                    <li class="active"><a href="addBooks.php">Add Book</a></li>
                    <?php }?>
                    <?php if(!isset($_SESSION['username'])){?>
                     <li><a href="index.php#loginHere" id="login">Login</a></li>
                     <?php } else{?>
                    <li><a href="logout.php" id="logout">Logout</a></li>
                    <?php }?>
                </ul>
            </div>
        </nav>
        <div class="container">
            
			<h4 class="modal-title" id="insertModalLabel">Add Collection</h4>
			<form action="library.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="img_path" class="text-center text-danger">Select file to upload:</label>
					<input class="form-control" type="file" name="image" id="insert-cover" required />
				</div>
				<div class="form-group">
					<label for="title">Title </label>
					<input type="text" class="form-control" id="insert-title" name="title" placeholder="insert title of the book" required>
				</div>
				<div class="form-group">
					<label for="author">Author</label>
					<input type="text" class="form-control" id="insert-author" name="author" placeholder="author of the book" required>
				</div>
				<div class="form-group">
					<label for="publisher">Publisher</label>
					<input type="text" class="form-control" id="insert-publisher" name="publisher" placeholder="publisher of the book" required>
				</div>
				<div class="form-group">
					<label for="description">Description</label>
					<input type="text" class="form-control" id="insert-description" name="description" placeholder="description of the book" required>
				</div>
				<div class="form-group">
					<label for="quantity">Quantity</label>
					<input type="number" class="form-control" id="insert-quantity" name="quantity" placeholder="Quantity of the book" required>
				</div>
				<input type="hidden" id="insert-command" name="command" value="insert">
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
        </div>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>                         