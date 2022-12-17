<?php
    session_start();
    if(!isset($_SESSION["sess_user"])){
        header("location:login.php");    
    }
?>
<!doctype html>
<html>
<head>
    <title>Search</title>
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
        }

        .content form button {
        background: #002000;
        vertical-align: middle;
        width: 65px;
        height: 50px;
        border-radius: 10px;
        object-fit: contain;
        padding: 5px;
        }


        .content form button:hover {
        background: #003500;
        padding: 5px;
        }

        .content movie-list {
        margin: 10px 20px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        min-height: 100px;
        }

        .content movie-list md {
        background-color: #909090;
        margin: 5px;
        border: 2px solid #005000;
        float: left;
        width: 300px;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        }

        .content movie-list md input[type="submit"] {
            background-color: #005000;
            border: none;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: medium;
            margin: 10px 2px;
            border-radius: 12px;
            cursor: pointer;
        }

        .content movie-list md input[type="submit"]:hover {
            background-color: #009000;
            border: none;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: medium;
            margin: 10px 2px;
            border-radius: 12px;
            cursor: pointer;
        }

        .content movie-list md:hover {
        border: 2px solid BLACK;
        background-color: #f1f1f1;
        }

        .content movie-list img {
        width: 100%;
        height: auto;
        border-radius: 3px;
        }

        .content movie-list md movinf {
        font-size: large;
        font-weight: bold;
        color:#005000;
        }
        .content movie-list md movdesc {
        display: none;
        }
        .content movie-list md:hover movdesc {
        display: block;
        font-size:smaller;
        text-align: left;
        }

        .content lh {
        background-color: #002000;
        border-radius: 12px;
        margin: 10px;
        padding: 6px;
        font-size: 20px;
        font-weight: bold;
        color: #70a090;
        }

        .footer {
        background-color: #f1f1f1;
        padding: 10px;
        }


    </style>
</head>
<body>
    <div class="topnav">
        <l><img src="img/logo.png" style="width:80px"></l>
        <a href="logout.php">Logout</a>
        <a href="movie-search.php">Search for Movies</a>
        <a href="user-home.php">Your Profile</a>
        <a href="recommendations.php">Get Recommendations</a>
    </div>

    <div class="content-head">
        <h2>See what other people are watching.</h2> 
        <p>Search for a username to find all of their favorited movies, and favorite them yourself!</p>  
    </div> 
    
    <div class="content">
        <form action="" method="GET">
                <input type="text" name="search">
                <button type="submit" name="submit"><img src="img/search.png" style="width:30px"/></button>
        </form>
            <?php

                if(isset($_GET["submit"])){
                    if(!empty($_GET["search"])){
                        $con = mysqli_connect("localhost","root","","website_data");
                        $query = mysqli_query($con, 'SELECT * FROM favorite_movies WHERE username=\''.$_GET["search"].'\'');
                    
                        if(mysqli_num_rows($query) > 0){
                            echo "<lh>Here's what ".$_GET["search"]." is watching:</lh>";
                            echo "<movie-list>";
                            while($element = mysqli_fetch_array($query)){
                                $curl = curl_init();
                                curl_setopt_array($curl, [
                                    CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/title/get-overview-details?tconst=".$element["film_id"],
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_ENCODING => "",
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 30,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => "GET",
                                    CURLOPT_HTTPHEADER => [
                                        "X-RapidAPI-Host: online-movie-database.p.rapidapi.com",
                                        "X-RapidAPI-Key: d08be8bb7bmsh76d8d1528512f30p1ffaffjsnb28c61cc2409"
                                    ],
                                ]);

                                $results = json_decode(curl_exec($curl));
                                $err = curl_error($curl);
                                curl_close($curl);
                                if ($err) {
                                    echo "cURL Error #:" . $err;
                                } 

                                
                                echo "<md>";
                                echo '<img src="'.$results->title->image->url.'">';
                                echo "<movinf>".$results->title->title." (".$results->title->year.")</movinf>";
                                echo '<form action="" method="POST"><input type="submit" value="Add to Favorites" name="add_fav'.$element["film_id"].'" /></form>';
                                echo "<movdesc>Rating: ".$results->ratings->rating."</movdesc>";
                                echo "<movdesc>".$results->plotSummary->text."</movdesc>";
                                echo "</md>";
                                if(isset($_POST["add_fav".$element["film_id"]])){
                                    try{
                                        mysqli_query($con, 'INSERT INTO favorite_movies VALUES("'.$element["film_id"].'", "'.$_SESSION['sess_user'].'")');
                                    }catch(mysqli_sql_exception $e){
                                        echo "<script>alert('Already in favorites!');</script>";
                                    }
                                }
                            }
                            echo "</movie-list>";
                        }
                        else{
                            echo 'No results for username "'.$_GET["search"].'"';
                        }
                    }
                }
            ?>
    </div>

    <div class="footer">
        Thanks for visiting! Signed in as <b><?=$_SESSION["sess_user"]?></b>.
    </div>
</body>
</html>