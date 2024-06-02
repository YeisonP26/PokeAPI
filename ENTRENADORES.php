<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokedex";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM trainer";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="trainer-card">';
        echo '<div class="trainer-name">' . $row["name"] . '</div>';
        echo '<div class="trainer-age">' . $row["age"] . ' años</div>';
        echo '<div class="trainer-region">' . $row["region"] . ' - Región</div>';

        echo '<div class="trainer-pokemon">';

        $trainer_id = $row["id"];
        $pokemon_query = "SELECT p.name
                          FROM pokemon p
                          INNER JOIN potrarelation pr ON p.id = pr.pokemon_id
                          WHERE pr.trainer_id = $trainer_id
                          LIMIT 6";
        $pokemon_result = $conn->query($pokemon_query);
        if ($pokemon_result->num_rows > 0) {
            echo '<div class="pokemon-list">';
            while ($pokemon_row = $pokemon_result->fetch_assoc()) {
                echo '<div class="pokemon">' . $pokemon_row["name"] . '</div>';
            }
            echo '</div>';
        } else {
            echo 'Este entrenador no ha capturado ningun Pokémon.';
        }

        echo '</div>';
        echo '</div>';
    }
} else {
    echo "No se encontraron entrenadores.";
}

$conn->close();
?>