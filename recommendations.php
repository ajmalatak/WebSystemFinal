<?php
    session_start();
    if(!isset($_SESSION["sess_user"])){
        header("location:login.php");    
    }
?>
<!doctype html>
<html>
<head>
    <title>Recommendations</title>
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

        .content movie-list {
        margin: 10px 20px;
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
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
        background-color: #558055;
        border-radius: 12px;
        margin: 10px;
        padding: 6px;
        font-size: 20px;
        font-weight: bold;
        color: WHITE;
        }

        .footer {
        background-color: #f1f1f1;
        padding: 10px;
        }


    </style>
</head>
<body>
    <div class="topnav">
        <a href="logout.php">Logout</a>
        <a href="movie-search.php">Search for Movies</a>
        <a href="user-home.php">Your Profile</a>
        <a href="user-search.php">Search Other Users</a>
    </div>

    <div class="content-head">
        <h2>Movies and TV just for you, <?=$_SESSION['sess_user'];?>.</h2> 
        <p>See recommendations based on your favorite movies below, along with their ratings and descriptions.</p>  
    </div> 

    <div class="content">
        <?php
            if(count($_SESSION['genres']) > 0){
                foreach($_SESSION['genres'] as $key=>$value){
                    // Temporary minimum of 3 genres, to avoid going over movie API limits
                    if($key >= 3) break;

                    
                    $con = mysqli_connect("localhost","root","","website_data");

                    echo "<lh>Because you like ".$value.":</lh>";
                    echo "<movie-list>";

                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/title/v2/get-popular-movies-by-genre?genre=".$value."&limit=100",
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

                    $genre_results = json_decode(curl_exec($curl));
                    $err = curl_error($curl);
                    curl_close($curl);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } 


                    $movie_recs = array();
                    while(count($movie_recs) < 4){
                        $i = rand(0, 99);
                        if(!in_array(rtrim(substr($genre_results[$i], 7)), $movie_recs)){
                            array_push($movie_recs, rtrim(substr($genre_results[$i], 7), "/"));
                        }
                    }

                    foreach($movie_recs as $value){
                        $curl = curl_init();
                        curl_setopt_array($curl, [
                            CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/title/get-overview-details?tconst=".$value,
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
                        echo '<form action="" method="POST"><input type="submit" value="Add to Favorites" name="add_fav'.$value.'" /></form>';
                        if(property_exists($results, "ratings") && property_exists($results->ratings, "rating")) echo "<movdesc>Rating: ".$results->ratings->rating."</movdesc>";
                        if(property_exists($results, "plotSummary") && property_exists($results->plotSummary, "text")) echo "<movdesc>".$results->plotSummary->text."</movdesc>";
                        echo "</md>";
                        if(isset($_POST["add_fav".$value])){
                            try{
                                mysqli_query($con, 'INSERT INTO favorite_movies VALUES("'.$value.'", "'.$_SESSION['sess_user'].'")');
                            }catch(mysqli_sql_exception $e){
                                echo "<script>alert('Already in favorites!');</script>";
                            }
                        }
                    }
                    echo "</movie-list>";
                }
            }else{
                echo "Add movies to your favorites to get recommendations!";
            }
        ?>
    </div>

    <div class="footer">
        Thanks for visiting! Signed in as <b><?=$_SESSION["sess_user"]?></b>.
    </div>
</body>
</html>