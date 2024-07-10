<?php
include 'includes/db.php';

$sql_peliculas = "SELECT id_pelicula, titulo FROM peliculas";
$result_peliculas = $conn->query($sql_peliculas);

$sql_generos = "SELECT id_genero, nombre_genero FROM generos";
$result_generos = $conn->query($sql_generos);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"])) {
    if ($_GET["action"] == "delete" && isset($_GET["id"])) {
        $id_pelicula_genero = $_GET["id"];
        $sql_delete = "DELETE FROM Peliculas_Generos WHERE id_pelicula_genero='$id_pelicula_genero'";
        
        if ($conn->query($sql_delete) === TRUE) {
            echo 'Registro eliminado exitosamente';
        } else {
            echo 'Error al eliminar registro: ' . $conn->error;
        }
        
        $id_pelicula_genero = '';
    } elseif ($_GET["action"] == "edit" && isset($_GET["id"])) {
        $id_pelicula_genero = $_GET["id"];
        $sql_edit = "SELECT * FROM Peliculas_Generos WHERE id_pelicula_genero='$id_pelicula_genero'";
        $result_edit = $conn->query($sql_edit);

        if ($result_edit->num_rows > 0) {
            $row_edit = $result_edit->fetch_assoc();
            $id_pelicula = $row_edit["id_pelicula"];
            $id_genero = $row_edit["id_genero"];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pelicula_genero = $_POST["id_pelicula_genero"];
    $id_pelicula = $_POST["id_pelicula"];
    $id_genero = $_POST["id_genero"];

    if (!empty($id_pelicula_genero)) {
        $sql_update = "UPDATE Peliculas_Generos SET id_pelicula='$id_pelicula', id_genero='$id_genero' WHERE id_pelicula_genero='$id_pelicula_genero'";

        if ($conn->query($sql_update) === TRUE) {
            echo 'Registro actualizado exitosamente';
        } else {
            echo 'Error al actualizar registro: ' . $conn->error;
        }
    } else {
        $sql_insert = "INSERT INTO Peliculas_Generos (id_pelicula, id_genero) VALUES ('$id_pelicula', '$id_genero')";

        if ($conn->query($sql_insert) === TRUE) {
            echo 'Nuevo registro agregado exitosamente';
        } else {
            echo 'Error al agregar nuevo registro: ' . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Peliculas_generos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <a href="index.php">Volver a la página principal</a>
    <h1>Peliculas_generos</h1>

    <form action="peliculas_generos.php" method="post">
        <input type="hidden" id="id_pelicula_genero" name="id_pelicula_genero" value="<?php echo isset($id_pelicula_genero) ? $id_pelicula_genero : ''; ?>">

        <label for="id_pelicula">Pelicula:</label>
        <select id="id_pelicula" name="id_pelicula" required>
            <?php
            // Mostrar opciones de películas
            while ($row = $result_peliculas->fetch_assoc()) {
                $selected = isset($id_pelicula) && $id_pelicula == $row['id_pelicula'] ? 'selected' : '';
                echo "<option value='" . $row['id_pelicula'] . "' $selected>" . $row['titulo'] . "</option>";
            }
            ?>
        </select>

        <label for="id_genero">Género:</label>
        <select id="id_genero" name="id_genero" required>
            <?php
            // Mostrar opciones de géneros
            while ($row = $result_generos->fetch_assoc()) {
                $selected = isset($id_genero) && $id_genero == $row['id_genero'] ? 'selected' : '';
                echo "<option value='" . $row['id_genero'] . "' $selected>" . $row['nombre_genero'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" value="<?php echo isset($id_pelicula_genero) ? 'Actualizar' : 'Agregar'; ?>">
    </form>

    <h2>Lista de Peliculas_generos</h2>
    <table>
        <tr>
            <th>Id pelicula genero</th>
            <th>Pelicula</th>
            <th>Género</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT pg.id_pelicula_genero, p.titulo AS titulo_pelicula, pg.id_pelicula, g.nombre_genero, pg.id_genero 
                FROM Peliculas_Generos pg
                INNER JOIN peliculas p ON pg.id_pelicula = p.id_pelicula
                INNER JOIN generos g ON pg.id_genero = g.id_genero";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_pelicula_genero"] . "</td>";
                echo "<td>" . $row["titulo_pelicula"] . "</td>";
                echo "<td>" . $row["nombre_genero"] . "</td>";
                echo "<td>
                        <a href='peliculas_generos.php?action=edit&id=" . $row["id_pelicula_genero"] . "'>Editar</a>
                        <a href='peliculas_generos.php?action=delete&id=" . $row["id_pelicula_genero"] . "' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?')\">Eliminar</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay registros</td></tr>";
        }
        ?>
    </table>
</body>

</html>
