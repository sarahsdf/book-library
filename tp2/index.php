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
                    $_SESSION['username'] = "user";
                    if($row['role'] == "admin"){
                        header("Location: admin.php");  
                    }
                    else{
                        header("Location: login.php");
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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <!-- Styles -->
    <link rel="stylesheet" href="./src/css/bootstrap-3.3.7.min.css">
    <link rel="stylesheet" href="./src/css/style.css">

    
    <!-- Scripts -->
   


    
    <title> Libs </title>
    </head>
    <body data-spy="scroll" data-target=".enter">
        <nav class="navbar navbar-default text-center navbar-fixed-top" id="navv">
            <!--bootstrap navigation from http://www.w3schools.com/bootstrap/bootstrap_navbar.asp-->
            <div class="container-fluid">
                <div class="navbar-header" id="navv">
                    <a class="navbar-brand" href="#" id="judul">Hello, User!</a>
                 </div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Homepage</a></li> 
                    <li><a href="admin.php">Contact</a></li>
                    <li><a href="admin.php">About</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php" id="logout">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="parallax">
            <div class="caption">
                <span class="border text-uppercase">WELCOME TO OUR LIBRARY!</span>
            </div>

            <div class="enter">
                <a href="#loginHere"><span class="border">click to enter <span class="glyphicon glyphicon-chevron-down"></span></span></a>
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

