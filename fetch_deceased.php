<?php
// fetch_deceased.php
include 'db_connection.php'; // Include your DB connection script

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT d.id, d.name, d.birth_date, d.death_date, g.grave_id, g.latitude_start, g.longitude_start
        FROM deceased d
        JOIN cemetery_graves g ON d.grave_id = g.grave_id
        WHERE d.name LIKE ? OR g.grave_id LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $searchQuery . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
