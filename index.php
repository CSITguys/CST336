<?php
    require "connections.php";
    function getReleaseDate(){
        global $dbconn;
        
        $sql = "SELECT DISTINCT release_date
                FROM movie_table
                ORDER BY release_date DESC";
        
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute();
        return $stmt->fetchAll();
    }
        function getRatings(){
        global $dbconn;
        
        $sql = "SELECT DISTINCT rating
                FROM movie_table
                ORDER BY rating ASC";
        
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute();
        return $stmt->fetchAll();
    }
    

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>Crimson Cube</title>
		<meta name="description" content="">
		<meta name="author" content="csitguys">

		<meta name="viewport" content="width=device-width; initial-scale=1.0">

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link type="text/css" rel="stylesheet" href="style.css">

	</head>
    <body>
        <?php 
            $dates = getReleaseDate();
            $ratings = getRatings();
        ?>
        <div id="header">
            <div class="logo">
                <a class="logo" href="./">
                    <img src="images/test2.png" width="150px" height="48px" >
                </a>
                <span class="clear"></span>
            </div>
            <div class="search">
                <form class="filterForm">
                    <select name="location">
                        <option value="location_id">location A</option>
                        <option value="location_id">location B</option>
                        <option value="location_id">location C</option>
                    </select>
                    <select name="rating">
<<<<<<< HEAD
                        <option value="rating">G</option>
                        <option value="rating">PG</option>
                        <option value="rating">PG-13</option>
                        <option value="rating">R</option>
=======
                        <?php
                            foreach($ratings as $rating){
                                echo '<option value="' . $rating['rating'] . '">' . $rating['rating'] . '</option>';
                            }
                        ?>
>>>>>>> 48bd739f0cb6ecc3ba4299bff2951cdf3e7d4a1e
                    </select>
                    <select name="year">
                        <?php
                            foreach($dates as $date){
                                echo '<option value="' . $date['release_date'] . '">' . $date['release_date'] . '</option>';
                            }
                        ?>
                    </select>
                </form>
                <form class="searchForm" >
                    <input name="searchBar" type="input" class="searchbar" maxlength="100" size="21">
                    <input type="submit" value="search" class="searchButton">
                    <span class="clear"></span>
                </form>

            </div>
        </div>
        <div id></div>
        <div id="footer">
            <div id="copyright_wrapper">
                <p>site design / logo © 2015  CSIT GUYS</p>
                <span class="clear"></span>
            </div>
            <div id="footer_img_wrapper">
                <img src="images/inverse.png" height="30px" width="100px">
                <span class="clear"></span>
            </div>
        </div>
    </body>
</html>
