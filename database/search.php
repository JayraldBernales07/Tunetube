<?php
include('db/dbconfig.php');

// Get search term if it exists
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($connection, $_GET['search']) : '';

// Check if the search term is provided
$searchQuery = "";
if (!empty($searchTerm)) {
    $searchQuery = " WHERE songs.Title LIKE '%$searchTerm%' OR artists.Name LIKE '%$searchTerm%'";
}

// Query to fetch songs with sorting and optional search filter
$query = "SELECT songs.*, artists.Name AS ArtistName 
          FROM songs 
          LEFT JOIN artists ON songs.ArtistID = artists.ArtistID 
          $searchQuery";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    $counter = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='item d-flex justify-content-between align-items-center mb-4 song-row' 
              data-title='" . htmlspecialchars($row['Title']) . "' 
              data-artist='" . htmlspecialchars($row['ArtistName'] ? $row['ArtistName'] : 'Unknown') . "' 
              data-file='" . htmlspecialchars($row['FilePath']) . "' 
              data-song-id='" . $row['SongID'] . "'>";

        echo "<div class='info1 d-flex align-items-center gap-3'>";
        echo "<p class='fw-bold'>" . $counter++ . "</p>";
        echo "<img src='admin/img/song-bg.png' class='img-fluid' style='width: 40px; height: 40px;'>";
        echo "<div class='details'>";
        echo "<p class='mb-0 text'>" . htmlspecialchars($row['Title']) . "</p>";
        echo "<h5 class='mb-0'>" . htmlspecialchars($row['ArtistName'] ? $row['ArtistName'] : 'Unknown') . "</h5>";
        echo "</div>";
        echo "</div>";

        echo "<div class='d-flex align-items-center'>";
        echo "<p class='fw-bold mb-0'>" . htmlspecialchars($row['Duration']) . "</p>";
        echo "<button class='btn dropdown-toggle' type='button' id='songDropdown" . $row['SongID'] . "' data-bs-toggle='dropdown' aria-expanded='false' title='Options'>";
        echo "<i class='bx bx-dots-vertical-rounded text-white'></i>";
        echo "</button>";
        echo "<ul class='dropdown-menu dropdown-menu-end' aria-labelledby='songDropdown" . $row['SongID'] . "'>";
        echo "<li><button class='dropdown-item add-to-favorite' data-song-id='" . $row['SongID'] . "'>Add to Favorite</button></li>";
        echo "<li><a class='dropdown-item' href='songs-edit.php?id=" . $row['SongID'] . "'>Edit song</a></li>";
        echo "<li><a class='dropdown-item' href='database/songs-delete.php?id=" . $row['SongID'] . "'>Delete song</a></li>";
        echo "<li><a class='dropdown-item' href='#'>Share</a></li>";
        echo "</ul>";
        echo "</div>";
        
        echo "</div>";
    }
} else {
    echo "No songs found.";
}
mysqli_close($connection);
?>
