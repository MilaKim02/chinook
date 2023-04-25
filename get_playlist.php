<?php

require "dbconnection.php";
$dbcon = createDbConnection();

// Lisää koodiin muuttaja nimeltä playlist_id. Tiedosto hakee kyseisen soittolistan
//kappaleiden nimet ja kappaleiden säveltäjät ja tulostaa ne echolla. Kuvassa 1
// esimerkki. Voit helpottaa tehtävää tulostamalla aluksi yhden kappaleen tiedot. 

$playlist_id = 1;

$sql = " SELECT Name, Composer FROM tracks WHERE GenreId=$playlist_id";
$statement = $dbcon->prepare($sql);
$statement->execute();

$tracks = $statement -> fetchAll(PDO::FETCH_ASSOC);
foreach($tracks as $track){
    echo "<h2>".$track ["Name"]."</h2>";
    echo "(".$track ["Composer"].")";
    };



 
