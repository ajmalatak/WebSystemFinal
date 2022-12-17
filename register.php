<!doctype html>
<html>
<head>    
    <title>Register</title>  
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
        <a href="login.php">Log In</a>
    </div>
    <div class="content-head">
        <h2>Welcome to Social Media Squared.</h2> 
        <p>Register below to explore movies and TV.</p>  
    </div> 
    <div class="content">
        <form action="" method="POST">   
                <input type="text" name="user" placeholder="username"><br />    
                <input type="password" name="pass" placeholder="password"><br />   
                <button type="submit" name="submit">register</button>
        </form>
        <?php    
            if(isset($_POST["submit"])){    
                if(!empty($_POST['user']) && !empty($_POST['pass'])) {    
                    $user=$_POST['user'];    
                    $pass=$_POST['pass'];    
                    $con=mysqli_connect('localhost','root','','website_data') or die(mysqli_error());
                    
                    $query=mysqli_query($con,"SELECT * FROM login WHERE username='".$user."'");    
                    $numrows=mysqli_num_rows($query);    
                    if($numrows==0)    
                    {    
                    $sql="INSERT INTO login(username,password) VALUES('$user','$pass')";    
                    
                    $result=mysqli_query($con,$sql);    
                        if($result){    
                    echo "Account Successfully Created";    
                    } else {    
                    echo "Failure!";    
                    }    
                    
                    } else {    
                    echo "That username already exists! Please try again with another.";    
                    }    
                    
                } else {    
                    echo "All fields are required!";    
                }    
            }    
        ?>    
    </div>
</body>    
</html> 