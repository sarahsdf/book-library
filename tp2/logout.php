<?php
	session_start();

	if( isset($_SESSION['username']) ){
		$username = $_SESSION['username']; //Want to re-instate this after we destroy the session.
	}   

	unset($_SESSION);
	session_destroy();

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
        <nav class="navbar text-center navbar-default navbar-fixed-top" id="navv">
            <!--bootstrap navigation from http://www.w3schools.com/bootstrap/bootstrap_navbar.asp-->
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Homepage</a></li> 
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
