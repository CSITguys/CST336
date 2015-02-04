<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: signon.php");
    }
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
    function getGenres(){
        global $dbconn;
        $sql = "SELECT movie_category,
                COUNT(*) AS amount
                FROM movie_table
                GROUP BY movie_category
                ORDER BY amount DESC";
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute();
        return $stmt->fetchAll();
    }
    function getMovieNames(){
        global $dbconn;
        
		$sql = "SELECT rating, movie_category, release_date, movie_title, movie_id
				FROM movie_table
				ORDER BY movie_title";
        
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute();
        return $stmt->fetchAll();
	}
    function getLocations(){
        global $dbconn;
        $sql = "SELECT name, location_id
                FROM locations
                ORDER BY name";
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute();
        return $stmt->fetchAll();
    }
    $searchResults = "";
    if(isset($_GET['searchBar'])){
        $searchinput = $_GET['searchBar'];
        $sql = "SELECT *
                FROM movie_table
                WHERE movie_title
                LIKE :search";
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':search'=>('%'. $searchinput . '%')));
        $searchResults = $stmt->fetchAll();
    }
    $category = "";
    if(isset($_GET['genre'])){
        $sql = "SELECT *
                FROM movie_table
                WHERE movie_category=:movie_category
                ORDER BY movie_title";
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':movie_category'=>$_GET['genre']));
        $category = $stmt->fetchAll();
        
    }
	function getrentedmovies ($customer_id) {
	if (isset ($_SESSION['user_id'])) {
	
		$sql = "SELECT transaction_id, date, returned*
				FROM transactions
				WHERE customer_Id = :customer_id, returned = 0";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(':customer_id'=>$_SESSION['user_id']));
		$stadiumInfo = $stmt -> fetch();
	}
	}
	if (isset($_POST['update'])) { //checks whether we're coming from "save data" form

	$sql = "UPDATE transactions
			SET returned = 1,
			WHERE transaction_id = :transaction_id";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(":returned"=> 1,)); 

	echo "Movie Has Been Returned!! <br> <br>"; 
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<style>
			h1 {
				text-align: center;
			}
			.menuitem {
				text-align: center;
			}
		</style>
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
        <link type="text/css" rel="stylesheet" href="mystyles1.css">
        <script>
            function confirmRental(movie_title, location) {
                var remove = confirm("Do you really want to rent " + movie_title + "From" + location + "?");
                if (!remove) {
                    event.preventDefault();
                }
            }
            function confirmLogout(event) {
                var logout = confirm("Do you really want to log out?");
                if (!logout) {
                    event.preventDefault();
                }
            }
        </script>
	</head>
    <body>
        <?php
            $dates = getReleaseDate();
            $ratings = getRatings();
            $genres = getGenres();
            $locations = getLocations();
        ?>
        <div id="header">
            <div class="logo">
                <a class="logo" href="index.php">
                    <img src="images/test2.png" width="150px" height="48px" >
                </a>
                <span class="clear"></span>
            </div>
            <div class="search">
                <div id="filterwrapper">
                    <form class="filterForm">
                        <select name="location" class="filterinput left" >
                            <option class="placeholder" value="" disabled selected>Location</option>
                            <?php
                            foreach($locations as $location){
                                echo '<option value="'. $location['location_id'] . '">' . $location['name'] . '</option>';
                            }
                            ?>
                        </select>
                        <select name="rating" class="filterinput">
                            <option class="placeholder" value="" disabled selected>rating</option>
                            <?php
                                foreach($ratings as $rating){
                                    echo '<option value="' . $rating['rating'] . '">' . $rating['rating'] . '</option>';
                                }
                            ?>
                        </select>
                        <select name="year" class="filterinput right">
                            <option class="placeholder" value="" disabled selected>Date</option>
                            <?php
                                foreach($dates as $date){
                                    echo '<option value="' . $date['release_date'] . '">' . $date['release_date'] . '</option>';
                                }
                            ?>
                        </select>
                        <br />
                        <input class="searchButton" type="reset" value="Clear Filters" style="width:50%; border-top-right-radius: 0px 0px;
    border-bottom-right-radius: 5px 5px; border-right: thin solid #fff ">
                        <input class="searchButton" type="submit" value="Filter" style="width:50%; border-bottom-left-radius: 5px 5px; border-top-right-radius: 0 0 ; border-bottom-right-radius: 0 0;">
                    </form>
                </div>
                <div id="searchwrapper">
                    <form class="searchForm" >
                        <input name="searchBar" type="input" class="searchbar" maxlength="100" size="21">
                        <input type="submit" value="search" class="searchButton">
                        <span class="clear"></span>
                    </form>
                </div>

            </div>
            <span class="clear"></span>
        </div>
        
        <div id=main>
            <div id="navbar">
                <div id="links">
                    Sign In<br>
                    <a href = "http://hosting.otterlabs.org/powellphillipl/CST336/Group%20Project/viewallMovies.php">View All Movies</a><br>View All Movies<br>
                    Return A Movie<br>
                    Manage My Account<br>
                <form method="post" action="signout.php" onsubmit="confirmLogout()">
                    <input type="submit" value="Sign Out" />
                </form>
                </div>
                <ul>
                <?php
                    foreach($genres as $genre){
                        echo '<li><a href="index.php?genre='.$genre['movie_category'].'">' . $genre['movie_category'] . ' (' . $genre['amount'] . ')</a></li>';
                    }
                ?>
                </ul>
                <span class="clear"></span>
            </div>
            <div id="mainpage">
                <h1>Return A Movie</h1>
                <p>Here is a list of all movies you currently have rented out.  Click "Return Now" to return any movie.</p>
                <?php
		if (isset($_POST['customer_id'])) {
			$moviesrented = getrentedmovies($_POST['customer_id']);
				echo "<table border = \"1\">";
                		echo "<tr>";
						?>
                			<td id = "title"><strong>Transaction ID</strong></td>
                			<td id = "title"><strong>Date</strong></td>
                			<td id = "title"><strong>Movie Name</strong></td>
                			<td id = "title"><strong>Please Return</strong></td>
					<?php
                		echo "</tr>";
						foreach ($moviesrented as $movie) {
							echo "<tr>";
							echo "<td>";
								echo "<option value='" . $movie['transaction_id'] . "' >" . $movie['transaction_id']. "</option>";
							echo "</td>";
							echo "<td>";
								echo "<option value='" . $movie['date'] . "' >" . $movie['date']. "</option>";
							echo "</td>";
							echo "<td>";
								echo "<option value='" . $movie['inventory_id'] . "' >" . $movie['inventory_id']. "</option>";
							echo "</td>";
							echo "<td>";
								echo "<option value='" . $transaction['location_id'] . "' >" . $transaction['location_id']. "</option>";
							echo "</td>";
							echo "<td>";
								echo "<option value='" . $transaction['returned'] . "' >" . $transaction['returned']. "</option>";
							echo "</td>";
							?>
							<form method = "post">
							<input type = "hidden" name = "transaction_id" value = "<?=$movie['transaction_id']?>">
							<input type = "submit" name = "update" value = "Please Return">
							</form>
							<?php
							}
					echo "</table>";	
					}?>
            </div>
        </div>
        
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
