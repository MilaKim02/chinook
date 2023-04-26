<?php
function sanitize_sql($input)
{
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitize_sql($value);
        }
    } else {
        // Remove any non-alphanumeric characters except for spaces
        $input = preg_replace("/[^äöa-zA-Z0-9 ]+/", "", $input);
        // Escape single quotes
        $input = str_replace("'", "\'", $input);
        // Escape double quotes
        $input = str_replace('"', '\"', $input);
    }
    return $input;
}

require "dbconnection.php";
$dbcon = createDbConnection();

/*Tiedosto lisää uuden artistin, sekä lisäksi artistille albumin ja albumin kappaleet.
Kaikki tarvittavat tiedot saadaan joko POST- tai JSON-muodossa parametreina. Voit
aloittaa luomalla vain artistin ja albumin.*/

$body = file_get_contents("php://input");
$artist_object = json_decode($body);
$artist_name = sanitize_sql($artist_object->artist);
$sql = "INSERT INTO artists (Name) VALUES (\"$artist_name\")";
$dbcon->exec($sql);
$artist_id = $dbcon->lastInsertId();
foreach ($artist_object->albums as $album_object) {
    $album_title = sanitize_sql($album_object->title);
    $sql = "INSERT INTO albums (Title, ArtistId) VALUES (\"$album_title\", $artist_id)";
    $dbcon->exec($sql);
    $album_id = $dbcon->lastInsertId();
    foreach ($album_object->tracks as $track_object) {
            $track_name = sanitize_sql($track_object);
            $sql = "INSERT INTO tracks (Name, AlbumId, MediaTypeId) VALUES (\"$track_name\", $album_id, 1)";
            $dbcon->exec($sql);
    }
}