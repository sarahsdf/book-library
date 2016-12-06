<?php
    session_start();
    if(!isset($_SESSION['username'])){
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

            return "./src/covers/".$file_name;

            // if($uploadStatus == true){
            //     $rowData = array("./src/covers/".$file_name, $file_name, $time);
            //     if($_SESSION['rowData']){
            //         $tableData = $_SESSION['rowData'];
            //         array_push($tableData, $rowData);
            //         $_SESSION['rowData'] = $tableData;
            //     }
            //     else{
            //         $_SESSION['rowData'] = array($rowData);
            //     }
            //     header("Location: admin.php");
            // }
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
        
        $sql = "DELETE FROM book WHERE book_id=$id";
        
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
            insertBook();
            } else if($_POST['command'] === 'update') {
            updateBook($_POST['bookid']);
            } else if($_POST['command'] === 'delete') {
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
                    <li ><a href="index.php">Homepage</a></li> 
                    <li><a href="library.php">Collection</a></li>
                </ul>
                    <p class="navbar-text text-uppercase" id="title">Welcome to our library!</p>
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="addBooks.php">Add Collection</a></li>
                    <li><a href="logout.php" id="logout">Logout</a></li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <h1 class="text-center">Collections</h1>

             <button type="button" class="btn btn-info" id="addBook">
                    Add Collection
                </button>
            
            <div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="insertModalLabel">Add Collection</h4>
                        </div>
                        <div class="modal-body">
                            <form action="admin.php" method="post" enctype="multipart/form-data">
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
                           $result = bookCover();
                            while($row = $result->fetch_assoc()) {
                                $_GET['title'] = $row['title'];
                                $_GET['img_path'] = $row['img_path'];
                                $_GET['author'] = $row['author'];
                                $_GET['publisher'] = $row['publisher'];
                                $_GET['quantity'] = $row['quantity'];
                                echo "<tr>";
                                echo "<td>" .  $row['book_id'] . "</td>".
                                     "<td><img src=\"" .  $row['img_path'] . "\" width=\"128\"></td>".
                                     "<td>" .  $row['title'] . "</td>".
                                     "<td>" .  $row['author'] . "</td>".
                                     "<td>" .  $row['publisher'] . "</td>".
                                     "<td>" .  $row['description'] . "</td>".
                                     "<td>" .  $row['quantity'] . "</td>";
                            
                                echo '<td>
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#updateModal" onclick="setUpdateData(\''.$row['book_id'].'\',\''.$row['img_path'].'\',\''.$row['title'].'\',\''.$row['author'].'\',\''.$row['publisher'].'\',\''.$row['description'].'\',\''.$row['quantity'].'\')">
                                Edit
                                </button>
                                </td>';
                                echo '<td>
                                <form action="admin.php" method="post">
                                    <input type="hidden" id="delete-bookid" name="bookid" value="'.$row['book_id'].'">
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
               
        </div>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
        <script src="src/js/script.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script>
            function setUpdateData(id, img_path, title, author, publisher, description, quantity) {
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