<?php
require "dbconnection.php";
$dbcon = createDbConnection();

// Lisää koodiin muuttaja nimeltä artist_id. Tiedosto hakee ja palauttaa JSONmuodossa artistin nimen ja sekä artistin albumit ja albumien kappaleet. Esimerkki vastauksessa kuvassa 2.

$artist_id = 90;
header('Content-type: application/json');
$sql = "SELECT artists.Name from artists where ArtistId = $artist_id";
$statement = $dbcon->prepare($sql);
$statement->execute();
$result = $statement -> fetchAll(PDO::FETCH_COLUMN);
$resp = new stdClass();
$resp->artist = $result[0];
$resp->albums = Array();
$sql = "SELECT albums.Title, albums.AlbumId from albums where ArtistId = $artist_id";
$statement = $dbcon->prepare($sql);
$statement->execute();
$result = $statement -> fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $r)
{
    $album = new stdClass();
    $album->title = $r["Title"];
    $album_id = $r["AlbumId"];
    $sql = "SELECT tracks.Name from tracks where AlbumId = $album_id";
    $statement = $dbcon->prepare($sql);
    $statement->execute();
    $album->tracks = $statement -> fetchAll(PDO::FETCH_COLUMN);
    $resp->albums[] = $album;
}
echo json_encode($resp);