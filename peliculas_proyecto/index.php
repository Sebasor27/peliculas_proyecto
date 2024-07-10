<?php
  require('../peliculas_proyecto/includes/essential.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proyecto Películas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Proyecto Películas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="peliculas.php">Películas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="actores.php">Actores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="directores.php">Directores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="generos.php">Géneros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="peliculas_actores.php">Películas y Actores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="peliculas_directores.php">Películas y Directores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="peliculas_generos.php">Películas y Géneros</a>
                    </li>
                </ul>
                <form class="d-flex" method="GET" action="">
                    <select class="form-select me-2" name="tipo">
                        <option value="año">Año</option>
                        <option value="actor">Actor</option>
                        <option value="director">Director</option>
                        <option value="genero">Género</option>
                    </select>
                    <input class="form-control me-2" type="search" name="busqueda" placeholder="Buscar" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Resultados de Búsqueda</h1>
        <hr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<h3>" . $row["titulo"]. "</h3>";
                echo "<p><strong>Año:</strong> " . $row["año"]. "</p>";
                
                if (!empty($row["actores"])) {
                    echo "<p><strong>Actores:</strong> " . $row["actores"]. "</p>";
                }
                
                if (!empty($row["directores"])) {
                    echo "<p><strong>Directores:</strong> " . $row["directores"]. "</p>";
                }
                
                if (!empty($row["generos"])) {
                    echo "<p><strong>Géneros:</strong> " . $row["generos"]. "</p>";
                }
                
                echo "</div>";
                echo "<hr>";
            }
        } else {
            echo "No se encontraron resultados para la búsqueda.";
        }
        $conn->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
