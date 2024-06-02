<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
</head>

<body>
    <h1>PokéDex</h1>
    <div class="navigation">

        <form action="" method="GET">
            <div>
                <a class="text-link" href="?content=pokemon">Pokémones</a>
            </div>
            <input type="hidden" name="content" value="pokemon">
            <input class="text-link" type="text" name="search" placeholder="Buscar pokemon...">
            <select class="text-link" name="order_by">
                <option value="id_asc">Inferior</option>
                <option value="id_desc">Superior</option>
                <option value="name_asc">Nombre A-Z</option>
                <option value="name_desc">Nombre Z-A</option>
            </select>

            <button class="text-link" type="submit">Buscar</button>
            <a class="text-link" href="?content=trainers">Entrenadores</a>
        </form>
    </div>

    <div class="pokedex">
        <?php
        if (isset($_GET['content'])) {
            $selectedContent = $_GET['content'];
            if ($selectedContent === 'pokemon') {
                include 'pokemon.php';
            } elseif ($selectedContent === 'trainers') {
                include 'entrenadores.php';
            } else {
                echo 'Contenido no válido';
            }
        } else {
            include 'pokemon.php';
        }
        ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const images = document.querySelectorAll(".pokemon-image img");

            const options = {
                root: null,
                rootMargin: "0px",
                threshold: 0.1
            };

            const observer = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.dataset.src;
                        img.src = src;
                        observer.unobserve(img);
                    }
                });
            }, options);

            images.forEach(image => {
                observer.observe(image);
            });
        });
    </script>
</body>

</html>