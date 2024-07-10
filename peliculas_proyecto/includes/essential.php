<?php
include 'includes/db.php'; // Incluye la conexión a la base de datos

if (isset($_GET['tipo']) && isset($_GET['busqueda'])) {
    $tipo = $_GET['tipo'];
    $busqueda = $_GET['busqueda'];

    // Consulta base para obtener todas las películas con sus relaciones
    $sql = "SELECT p.id_pelicula, p.titulo, p.año,
                   GROUP_CONCAT(DISTINCT a.nombre_actor SEPARATOR ', ') AS actores,
                   GROUP_CONCAT(DISTINCT d.nombre_director SEPARATOR ', ') AS directores,
                   GROUP_CONCAT(DISTINCT g.nombre_genero SEPARATOR ', ') AS generos
            FROM peliculas p
            LEFT JOIN peliculas_actores pa ON p.id_pelicula = pa.id_pelicula
            LEFT JOIN actores a ON pa.id_actor = a.id_actor
            LEFT JOIN peliculas_directores pd ON p.id_pelicula = pd.id_pelicula
            LEFT JOIN directores d ON pd.id_director = d.id_director
            LEFT JOIN peliculas_generos pg ON p.id_pelicula = pg.id_pelicula
            LEFT JOIN generos g ON pg.id_genero = g.id_genero";

    // Agregar condiciones según el tipo de búsqueda
    switch ($tipo) {
        case 'año':
            $sql .= " WHERE p.año LIKE '%$busqueda%'";
            break;
        case 'actor':
            $sql .= " WHERE a.nombre_actor LIKE '%$busqueda%'";
            break;
        case 'director':
            $sql .= " WHERE d.nombre_director LIKE '%$busqueda%'";
            break;
        case 'genero':
            $sql .= " WHERE g.nombre_genero LIKE '%$busqueda%'";
            break;
        default:
            // Si no se selecciona un tipo válido, mostrar todas las películas
            break;
    }

    $sql .= " GROUP BY p.id_pelicula";
    
    $result = $conn->query($sql);
} else {
    // Si no se proporcionan parámetros de búsqueda válidos, mostrar todas las películas
    $sql = "SELECT p.id_pelicula, p.titulo, p.año,
                   GROUP_CONCAT(DISTINCT a.nombre_actor SEPARATOR ', ') AS actores,
                   GROUP_CONCAT(DISTINCT d.nombre_director SEPARATOR ', ') AS directores,
                   GROUP_CONCAT(DISTINCT g.nombre_genero SEPARATOR ', ') AS generos
            FROM peliculas p
            LEFT JOIN peliculas_actores pa ON p.id_pelicula = pa.id_pelicula
            LEFT JOIN actores a ON pa.id_actor = a.id_actor
            LEFT JOIN peliculas_directores pd ON p.id_pelicula = pd.id_pelicula
            LEFT JOIN directores d ON pd.id_director = d.id_director
            LEFT JOIN peliculas_generos pg ON p.id_pelicula = pg.id_pelicula
            LEFT JOIN generos g ON pg.id_genero = g.id_genero
            GROUP BY p.id_pelicula";

    $result = $conn->query($sql);
}
?>