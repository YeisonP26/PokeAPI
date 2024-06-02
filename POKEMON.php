<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokedex";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT p.id, p.name, p.image, GROUP_CONCAT(t.name) AS types
        FROM pokemon p
        LEFT JOIN potyrelation pt ON p.id = pt.pokemon_id
        LEFT JOIN type t ON pt.type_id = t.id";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql .= " WHERE p.name LIKE '%$search%'";
}

$sql .= " GROUP BY p.id";

$order_by = 'name_asc';

if (isset($_GET['order_by'])) {
    $order_by = $_GET['order_by'];
}

switch ($order_by) {
    case 'name_asc':
        $sql .= " ORDER BY p.name ASC";
        break;
    case 'name_desc':
        $sql .= " ORDER BY p.name DESC";
        break;
    case 'id_asc':
        $sql .= " ORDER BY p.id ASC";
        break;
    case 'id_desc':
        $sql .= " ORDER BY p.id DESC";
        break;
    default:
        $sql .= " ORDER BY p.name ASC";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="pokemon-card">';
        echo '<div class="pokemon-name">' . $row["name"] . '</div>';
        echo '<div class="pokemon-id badge">#' . $row["id"] . '</div>';
        echo '<div class="pokemon-image"><img data-src="' . $row["image"] . '" style="filter: drop-shadow(0 0 0.65rem yellow);" alt="' . $row["name"] . '"></div>';
        
        $types = explode(',', $row["types"]);
        echo '<div class="pokemon-type">';
        foreach ($types as $type) {
            echo '<div class="' . strtolower($type) . '-type">' . $type . '</div>';
        }
        echo '</div>';

        echo '</div>';
    }
} else {
    echo "No se encontraron Pokémon.";
}

$conn->close();
?>