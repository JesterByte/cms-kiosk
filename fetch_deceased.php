<?php
// fetch_deceased.php
include 'db_connection.php'; // Include your DB connection script

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT d.id, d.full_name, d.birth_date, d.death_date, g.lot_id, g.latitude_start, g.longitude_start
        FROM deceased d
        JOIN cemetery_lots g ON d.location = g.lot_id
        WHERE d.full_name LIKE ? OR g.lot_id LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $searchQuery . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $row["birth_date"] = displayDate($row["birth_date"]);
    $row["death_date"] = displayDate($row["death_date"]);
    $row["lot_id"] = displayPhaseLocation($row["lot_id"]);
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

function displayPhaseLocation($location) {
    // Use a regular expression to parse the input
    if (preg_match('/^P(\d+)-C(\d+)L(\d+)$/', $location, $matches)) {
      $phase = $matches[1];
      $column = $matches[2];
      $lot = $matches[3];
      return "Phase $phase, Column $column, Lot $lot";
    } else {
      return "Invalid location format";
    }
}

function displayDate($date) {
  return date("F j, Y", strtotime($date));
}
