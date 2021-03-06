<?php
	session_start();
	
	if(!isset($_SESSION['username']) || $_SESSION['role'] == 'admin'){
		header("Location: library.php");
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
	
	function bookCover(){
        $conn = connectDB();
        $sql = "SELECT * from book, loan WHERE loan.book_id = book.book_id AND loan.user_id = ".$_SESSION['user_id'];
        $result = $conn->query($sql);
        return $result;
    }
	
	function borrowBook() {
        $conn = connectDB();
        
        $book_id = $_POST['book_id'];
        $user_id = $_SESSION['user_id'];
        $sql = "INSERT into loan (book_id, user_id) values($book_id,$user_id)";
        
        if($result = mysqli_query($conn, $sql)) {
            } else {
            die("Error: $sql");
        }
		
		$sql = "UPDATE book SET quantity = quantity - 1 WHERE book_id = ".$_POST['book_id']."";
        
        if($result = mysqli_query($conn, $sql)) {
        } else {
            die("Error: $sql");
        }
        mysqli_close($conn);
    }
	
	function returnBook() {
        $conn = connectDB();
        
        $book_id = $_POST['book_id'];
        $user_id = $_SESSION['user_id'];
        $sql = "DELETE FROM loan WHERE book_id = $book_id AND user_id = $user_id";
        
        if($result = mysqli_query($conn, $sql)) {
            } else {
            die("Error: $sql");
        }
		
		$sql = "UPDATE book SET quantity = quantity + 1 WHERE book_id = ".$_POST['book_id'];
        
        if($result = mysqli_query($conn, $sql)) {
        } else {
            die("Error: $sql");
        }
        mysqli_close($conn);
    }
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if($_POST['command'] == 'return') {
			returnBook();
		} else {
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
        <nav class="navbar text-center navbar-default navbar-fixed-top" id="navv">
            <!--bootstrap navigation from http://www.w3schools.com/bootstrap/bootstrap_navbar.asp-->
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Homepage</a></li> 
                    <li><a href="library.php">View Books</a></li>
					<li class="active"><a href="borrowed.php">My Books</a></li>
                </ul>
                    <p class="navbar-text text-uppercase" id="title">Personal Library</p>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="about.php">About</a></li>
                    <?php if(!isset($_SESSION['username'])){?>
                     <li><a href="index.php#loginHere" id="login">Login</a></li>
                     <?php } else{?>
                    <li><a href="index.php" id="logout">Logout</a></li>
                    <?php }?>
                </ul>
            </div>
        </nav>

		<div class="container">
			<h1 class="text-center">My Books</h1>

			<div class="row">	
				<?php
					$result = bookCover();
					$cf = 0;

					while($row = $result->fetch_assoc()) {
						 // $bookId = $row['book_id'];
						//$userId = $_SESSION['user_id'];
						$userId = "";
						$cf++;
						echo '<div class="col-xs-4 text-center books">';
						echo"<img src=\"" .  $row['img_path'] . "\" width=\"150\" class=\"bookDisplay imgEffect\">";
							
						echo "<div><strong>".  $row['title'] . "</strong></div>";
						echo "<div><strong>author</strong>: ". $row['author'] . "</div>";
                        echo "<div><strong>publisher</strong>: ". $row['publisher'] . "</div>";
                        echo "<div><strong>quantity</strong>: ". $row['quantity'] . "</div><br />";
						echo '<div class="row">';
						if(isset($_SESSION['username']) && $_SESSION['role'] === "user"){
							echo '
								<form class="" action="borrowed.php" method="post">
									<input type="hidden" name="command" value="return">
									<input type="hidden" name="book_id" value="'.$row['book_id'].'">
									<button type="submit" class="btn btn-default text-center">Return</button>
								</form>
								';
						}
						
                        ?><?php
						echo '
						<a href="viewDetails.php?book_id='.$row['book_id'].'" class="btn btn-warning text-center">view details</a>
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
			<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="updateModalLabel">Update Collection</h4>
                        </div>
                        <div class="modal-body">
                            <form action="library.php" method="post">
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
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script type="text/javascript" src="src/js/script.js"></script>

		<script>
            function setUpdateData(book_id, img_path, title, author, publisher, description, quantity) {
                $("#update-bookid").val(book_id);
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