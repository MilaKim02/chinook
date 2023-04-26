<?php
require "dbconnection.php";
$dbcon = createDbConnection();

//Tiedostossa on parametreina artist_id. Poista kyseinen artisti ja kaikki siihen liittyvät
//tiedot kannasta transaktiona. Huom! Tutki kannan rakennetta ja poista
//riippuvuudet oikeassa järjestyksessä.(artists/albums/tracks/invoice_items). 

$artist_id = 1;

try {
    $dbcon->beginTransaction();
    //delete invoice_items
    $statement = $dbcon->prepare("DELETE FROM invoice_items WHERE TrackId IN (SELECT TrackId FROM tracks WHERE AlbumId IN ( SELECT AlbumId from albums WHERE ArtistId = $artist_id))");
    $statement->execute();

    //delete playlist_track
    $statement = $dbcon->prepare("DELETE FROM playlist_track WHERE TrackId IN (SELECT TrackId FROM tracks WHERE AlbumId IN (SELECT AlbumId from albums where ArtistId = $artist_id))");
    $statement->execute();

    //poista Tracks
    $statement = $dbcon->prepare("DELETE FROM tracks WHERE AlbumID IN (SELECT AlbumId from albums where ArtistId = $artist_id)");
    $statement->execute();

    //delete albums
    $statement = $dbcon->prepare("DELETE FROM albums WHERE ArtistId = $artist_id ");
    $statement->execute();
    $statement = $dbcon->prepare("DELETE FROM artists WHERE ArtistId = $artist_id ");
    $statement->execute();

    if ($dbcon->commit())
        echo " artisti poistettu onnistuneesti!";
    else
        echo " jotain meni pieleen!";
    
} catch (Exception $e) {

    $dbcon->rollBack();
    echo $e->getMessage();
}
