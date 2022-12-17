<!doctype html>
<html>
<head>    
    <title>Login</title>  
    <style>
        * {
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
        }

        body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        }

        .topnav {
        overflow: hidden;
        background-color: #005000;
        }

        .topnav a {
        float: right;
        display: block;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        border-left: 2px solid #678867;
        border-bottom-left-radius: 18px;
        }


        .topnav a:hover {
        background-color: #ddd;
        color: black;
        }

        .content-head {
        background-image: url("img/home.jpg");
        background-repeat: no-repeat;
        background-size: cover;
        box-shadow: inset 0 0 0 1000px rgba(0,200,0,.2);
        padding: 120px 50px;
        color: WHITE;
        }


        .content {
        background-color: #ddd;
        padding: 10px;
        height: auto;
        }



        .content form {
        border-radius: 20px;
        text-align: center;
        padding: 10px;
        margin: 5px;
        vertical-align: center;
        }

        .content form input[type="text"] {
        border-radius: 10px;
        vertical-align: middle;
        border: 4px solid #658065;
        text-align: left;
        font-size: 30px;
        padding: 5px;
        margin: 5px;
        }

        .content form input[type="password"] {
        border-radius: 10px;
        vertical-align: middle;
        border: 4px solid #658065;
        text-align: left;
        font-size: 30px;
        padding: 5px;
        }

        .content form button {
        background: #002000;
        width: 300px;
        height: 50px;
        border-radius: 10px;
        padding: 5px;
        margin: 5px;
        color: #70a090;
        font-size: 25px;
        font-weight: bold;
        }


        .content form button:hover {
        background: #003500;
        padding: 5px;
        }




        .footer {
        background-color: #f1f1f1;
        padding: 10px;
        }


    </style>
</head>  
<body>    
    <div class="topnav">
        <a href="register.php">Register</a>
    </div>
    <div class="content-head">
        <h2>Welcome to Social Media Squared.</h2> 
        <p>Log in below to explore movies and TV.</p>  
    </div> 
    <div class="content">
        <form action="" method="GET">   
                <input type="text" name="user" placeholder="username"><br />    
                <input type="password" name="pass" placeholder="password"><br />   
                <button type="submit" name="submit">log in</button>
        </form>
        <?php
        if(isset($_GET["submit"])){    
            if(!empty($_GET['user']) && !empty($_GET['pass'])) {    
                $user=$_GET['user'];    
                $pass=$_GET['pass'];    
                $con=mysqli_connect('localhost','root','','website_data') or die(mysqli_error());
                
                $query=mysqli_query($con, "SELECT * FROM login WHERE username='".$user."' AND password='".$pass."'");    
                $numrows=mysqli_num_rows($query);    
                if($numrows!=0)    
                {    
                    $row = mysqli_fetch_array($query);
                    session_start();    
                    $_SESSION["sess_user"] = $user;
                    $_SESSION["genres"] = array();
                    header("location:user-home.php");
                    exit();
                } else {    
                    echo "That username/password does not exist! Please try again with another.";    
                }    
                
            } else {    
                echo "All fields are required!";    
            }    
        }    
        ?>
    </div>
</body>  
</html>