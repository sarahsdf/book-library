<?php
	session_start();

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
	
	function writeReview() {
        $conn = connectDB();
        
        $book_id = $_POST['book_id'];
        $user_id = $_SESSION['user_id'];
		$date = date("Y-m-d");
		$content = $_POST["review"];
        $sql = "INSERT into review (book_id, user_id, date, content) values($book_id,$user_id,'$date','$content')";
        
        if($result = mysqli_query($conn, $sql)) {
            } else {
            die("Error: $sql");
        }
        mysqli_close($conn);
    }
	
	function showReview() {
        $conn = connectDB();
        
        $book_id = $_POST['book_id'];
        $user_id = $_SESSION['user_id'];
		$date = date("Y-m-d");
        $sql = "SELECT * FROM review, user WHERE user.user_id = review.user_id AND book_id = $book_id";
        
        if($result = mysqli_query($conn, $sql)) {
			echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        } else {
            die("Error: $sql");
        }
        mysqli_close($conn);
    }
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if($_POST['command'] == 'writeReview') {
			writeReview();
		} else {
			showReview();
		}
	}
	
?>