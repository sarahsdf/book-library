<?php
    session_start();

    $servername = "localhost";
    $usernamedb = "root";
    $passworddb = "";
    $dbname = "library";

    $usernameValid = false;
    $passwordValid = false;

    if (isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Create connection
        $conn = mysqli_connect($servername, $usernamedb, $passworddb, $dbname); 
        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);
        
        // Check connection
        if (!$conn) {
            die("Connection failed: " .  mysqli_connect_error());
        }

        if ($result->num_rows > 0) {
            // output data of each row 
            while($row = $result->fetch_assoc()) {
                if($row['username'] == $username && $row['password'] == $password ){
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['user_id'] = $row['user_id'];
                    if($row['role'] == "admin" || $row['role'] == "user"){
                        header("Location: library.php");  
                    }
                    break;
                }
            }
        }

        if (preg_match("/^[a-zA-Z ]{1,255}$/", $_POST['username'])){
            $usernameValid = true;
        }
        else{
            $usernameValid = false;
        }

        if (preg_match("/^\w{3,20}$/", $_POST['password'])){
            $passwordValid = true;
        }
        else{
            $passwordValid = false;
        }

        $conn->close();
    }
?>
 <!-- pagination belom!!!!! -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <!-- Styles -->
    <link rel="stylesheet" href="./src/css/bootstrap-3.3.7.min.css">
    <link rel="stylesheet" href="./src/css/style.css">   

    
    <title> Libs </title>
    </head>
    <body data-spy="scroll" data-target=".enter">
        <nav class="navbar text-center navbar-default navbar-fixed-top" id="navv">
            <!--bootstrap navigation from http://www.w3schools.com/bootstrap/bootstrap_navbar.asp-->
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Homepage</a></li> 
                    <li><a href="library.php">View Books</a></li>
                </ul>
                    <p class="navbar-text text-uppercase" id="title">Personal Library</p>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="about.php">About</a></li>
                    <?php if(!isset($_SESSION['username'])){?>
                     <li><a href="index.php#loginHere" id="login">Login</a></li>
                     <?php }?>
                </ul>
            </div>
        </nav>

        <div class="parallax">
            <div class="enter">
                <a href="#loginHere"><span class="border">enter</span></a>
            </div>

        </div>

        <div id="loginHere">
            <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="text-center text-success text-uppercase" id="formTitle">Login</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <form action="index.php" method="post">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input class="form-control" type="text" name="username" placeholder="username" autofocus />
                        </div>
                        <div class="form-group">
                            <label for="password">Password: </label>
                            <input class="form-control" type="password" name="password" placeholder="password" />
                        </div>
                        <?php
                            if (isset($_POST['login'])){
                                if($usernameValid == false){
                                    echo '<p class="text-danger">username TIDAK sesuai dengan ketentuan<br></p>';
                                }
                                if($passwordValid == false){
                                    echo '<p class="text-danger">password should be 3 to 20 characters long</p>';
                                }
                            }
                            
                        ?>
                        <input type="submit" name="login" value="login" class="pull-right btn btn-lg" id="login" />
                    </form>
                   
                </div>
            </div>
        </div>
        </div>


    </body>
</html>

