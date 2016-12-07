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
	
	function bookCover(){
        $conn = connectDB();
        $sql = "SELECT * from book";
        $result = $conn->query($sql);
        return $result;
    }

    function uploadFile() {
        $target_dir = "./src/covers/";
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
            move_uploaded_file($file_tmp, ("./src/covers/".$file_name));

            return "./src/covers/".$file_name;
        }
    }

    function insertBook() {
        $conn = connectDB();
        
        $img_path = uploadFile();
        $title = $_POST['title'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $description = $_POST['description'];
        $quantity = $_POST['quantity'];
        $sql = "INSERT into book (img_path, title, author, publisher, description, quantity) values('$img_path','$title','$author','$publisher','$description','$quantity')";
        
        if($result = mysqli_query($conn, $sql)) {
            echo "New record created successfully <br/>";
            header("Location: viewDetails.php?book_id=" . mysqli_insert_id($conn));
            } else {
            die("Error: $sql");
        }
        mysqli_close($conn);
    }
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if($_POST['command'] === 'insert') {
            insertBook();
        } 
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
                    <?php if(isset($_SESSION['username']) && $_SESSION['role'] === "admin"){?>
                    <li><a href="addBooks.php">Add Book</a></li>
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
			<h1 class="text-center">Collections</h1>
			<div class="row">	
				<?php
					$result = bookCover();
					$cf = 0;

					while($row = $result->fetch_assoc()) {
						 // $bookId = $row['book_id'];
						//$userId = $_SESSION['user_id'];
						$userId = "";
						$cf++;
						echo '<div class="col-xs-4 text-center books" >';
						echo"<img src=\"" .  $row['img_path'] . "\" width=\"150\" class=\"bookDisplay imgEffect\">";
							
						echo "<div><strong>".  $row['title'] . "</strong></div>";
						echo "<div><strong>author</strong>: ". $row['author'] . "</div>";
                        echo "<div><strong>publisher</strong>: ". $row['publisher'] . "</div>";
                        echo "<div><strong>quantity</strong>: ". $row['quantity'] . "</div><br />";
						echo '<div class="row">';
						if(isset($_SESSION['username']) && $_SESSION['role'] === "user" && $row['quantity'] > 0){
							echo '
								<form class="" action="borrowed.php" method="post">
									<input type="hidden" name="command" value="borrow">
									<input type="hidden" name="book_id" value="'.$row['book_id'].'">
									<button type="submit" class="btn btn-default text-center">Borrow this book</button>
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
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script type="text/javascript" src="src/js/script.js"></script>

	</body>
</html>							