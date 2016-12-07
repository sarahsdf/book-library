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

	function writeReview(){
		$conn = connectDB();
        
        $book_id = $_POST['book_id'];
        $user_id = $_POST['user_id'];
        $date = $_POST['date'];
        $content = $_POST['content'];
        $sql = "INSERT into review (book_id, user_id, date, content) values('$book_id','$user_id','$date','$content')";
        
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
                    <li class="active"><a href="library.php">View Books</a></li>
					<?php if(isset($_SESSION['username']) && $_SESSION['role'] === "user"){?>
                    <li><a href="borrowed.php">My Books</a></li>
                    <?php }?>
                </ul>
                    <p class="navbar-text text-uppercase" id="title">Personal Library</p>
                <ul class="nav navbar-nav navbar-right">
                   <?php if(!isset($_SESSION['username'])){?>
                     <li><a href="index.php#loginHere" id="login">Login</a></li>
                     <?php } else{?>
                    <li><a href="logout.php" id="logout">Logout</a></li>
                    <?php }?>
                </ul>
            </div>
        </nav>

        </div>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<?php 
						$conn = connectDB();
						$sql = "SELECT * FROM book WHERE book_id='" . $_GET['book_id'] . "'";
						$result = $conn->query($sql);

						$row = $result->fetch_assoc();
						echo "<h2>".  $row['title'] . "</h2>";
					?>
				</div>
				<br/><br/><br/><br/><br/>
				<div class="col-xs-4">
					<?php
						echo"<img src=\"" .  $row['img_path'] . "\" width=\"250\" class=\"bookDisplay imgEffect\">";
						if(isset($_SESSION['username']) && $_SESSION['role'] === "user"){
							if ($row['quantity'] > 0) {
							echo '
								<form class="" action="borrowed.php" method="post">
									<input type="hidden" name="command" value="borrow">
									<input type="hidden" name="book_id" value="'.$row['book_id'].'">
									<button type="submit" class="btn btn-default text-center">Borrow this book</button>
								</form>
								';
							}
							echo '
								<div><button type="submit" class="btn btn-info text-center" data-toggle="modal" data-target="#reviewModal">Write a review</button></div>
							';
						}
					?>
				</div>
				
				<div class="col-xs-8" id="bookDetails">
					<?php
							echo "<div class=\"row\"><p><strong>Author: </strong></p><p>". $row['author'] . "</p></div><br/>";
							echo "<div class=\"row\"><p><strong>Publisher: </strong></p><p>". $row['publisher'] . "</p></div><br/>";
							echo '<div class="row"><p><strong>Description: </strong></p><p class="text-justify">'. $row['description'] . "</p></div><br/>";
							echo "<div class=\"row\"><p><strong>Quantity: </strong></p>". $row['quantity'] . "</div><br/>";
							echo '<div class="row">';

							echo "</div></br>";
							
					?>
				</div>
				<div class="col-xs-8" id="bookDetails">
					<div class="row"><strong>Reviews:</strong></div><br/>
					<div class="row" id="reviews" class="col-xs-8">
						
					</div>
				</div>
			</div>

			<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="reviewModalLabel">Write a Review</h4>
                        </div>
                        <div class="modal-body">
                            <form id="writeReviewForm" action="writeReviewAPI.php" method="post">
                                
                                <div class="form-group">
                                    <label for="description">Review</label>
	                                <textarea name="bookReview" id="bookReview" class="form-control"></textarea>
                               </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script>
			$("#writeReviewForm").submit(function() {
				$.ajax({
					url: "writeReviewAPI.php",
					data: {
						book_id: <?= $_GET['book_id'] ?>,
						command: "writeReview",
						review: $("#bookReview").val()
					},
					success: function(data) {
						$('#reviewModal').modal('hide');
						fetchReview();
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log("jqXHR, textStatus, errorThrown:");
						console.log(jqXHR);
						console.log(textStatus);
						console.log(errorThrown);
					},
					method: "POST"
				});
				return false;
			});
			
			function fetchReview() {
				$.ajax({
					url: "writeReviewAPI.php",
					data: {
						book_id: <?= $_GET['book_id'] ?>,
						command: "showReview"
					},
					success: function(data) {
						showReview(data);
					},
					error: function(data) {
						console.log(data);
					},
					dataType: "json",
					method: "POST"
				});
			}
			
			function showReview(data) {
				var reviews = "";
				$.each(data, function(index, value) {
					reviews += "<hr/>";
					reviews += "<strong><span>" + value.username + "</strong> wrote:</span>" + '<span class="pull-right text-muted">' + value.date + "</span></br></br>";
					reviews += "<p>" + value.content + "</p>";
					reviews += "<hr/>";
				});
				$("#reviews").html(reviews);
			}
			
			fetchReview();
		</script>
	</body>
</html>							