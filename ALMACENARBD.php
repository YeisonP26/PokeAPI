<?php
set_time_limit(0);

// Cambiando los detalles de la conexión a la base de datos
$serverName = "localhost";
$userName = "root";
$passWord = "";
$databaseName = "pokedex";

$connection = new mysqli($serverName, $userName, $passWord, $databaseName);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

function fetchPokemonData($id)
{
    $url = "https://pokeapi.co/api/v2/pokemon/$id";
    $response = file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }
    return json_decode($response, true);
}

$startId = 1;
$endId = 50;

for ($pokemonId = $startId; $pokemonId <= $endId; $pokemonId++) {
    $pokemonData = fetchPokemonData($pokemonId);

    if ($pokemonData) {
        $id = $pokemonData['id'];
        $name = $pokemonData['name'];
        $imageUrl = $pokemonData['sprites']['other']['official-artwork']['front_default'];

        // Insertando o actualizando datos del pokemon
        $sql = "INSERT INTO pokemon (id, name, image) VALUES ($id, '$name', '$imageUrl')
                ON DUPLICATE KEY UPDATE name=VALUES(name), image=VALUES(image)";
        $connection->query($sql);

        foreach ($pokemonData['types'] as $typeData) {
            $typeName = $typeData['type']['name'];

            // Verificando si el tipo ya existe
            $typeQuery = "SELECT id FROM type WHERE name='$typeName'";
            $typeResult = $connection->query($typeQuery);
            if ($typeResult->num_rows == 0) {
                // Insertando nuevo tipo
                $insertTypeSql = "INSERT INTO type (name) VALUES ('$typeName')";
                $connection->query($insertTypeSql);
            }

            // Obteniendo el ID del tipo
            $typeIdQuery = "SELECT id FROM type WHERE name='$typeName'";
            $typeIdResult = $connection->query($typeIdQuery);
            $typeIdRow = $typeIdResult->fetch_assoc();
            $typeId = $typeIdRow['id'];

            // Verificando si la relación existe
            $relationQuery = "SELECT * FROM potyrelation WHERE pokemon_id=$id AND type_id=$typeId";
            $relationResult = $connection->query($relationQuery);
            if ($relationResult->num_rows == 0) {
                // Insertando nueva relación
                $insertRelationSql = "INSERT INTO potyrelation (pokemon_id, type_id) VALUES ($id, $typeId)";
                $connection->query($insertRelationSql);
            }
        }
    } else {
        echo "Fallo al obtener datos para el Pokémon con ID $pokemonId<br>";
    }
    sleep(1); // Esperando un segundo entre las solicitudes para evitar exceder los límites de la API
}

// Redireccionando al archivo index.php después de completar el proceso
header('Location: index.php');

$connection->close();
?>