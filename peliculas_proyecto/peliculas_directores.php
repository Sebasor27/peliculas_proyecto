<?php
include 'includes/db.php';

$sql_peliculas = "SELECT id_pelicula, titulo FROM peliculas";
$result_peliculas = $conn->query($sql_peliculas);

$sql_directores = "SELECT id_director, nombre_director FROM directores";
$result_directores = $conn->query($sql_directores);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"])) {
    if ($_GET["action"] == "delete" && isset($_GET["id"])) {
        
        $id_pelicula_director = $_GET["id"];
        $sql_delete = "DELETE FROM Peliculas_Directores WHERE id_pelicula_director='$id_pelicula_director'";
        
        if ($conn->query($sql_delete) === TRUE) {
            echo 'Registro eliminado exitosamente';
        } else {
            echo 'Error al eliminar registro: ' . $conn->error;
        }
        
        $id_pelicula_director = '';
    } elseif ($_GET["action"] == "edit" && isset($_GET["id"])) {
       
        $id_pelicula_director = $_GET["id"];
        $sql_edit = "SELECT * FROM Peliculas_Directores WHERE id_pelicula_director='$id_pelicula_director'";
        $result_edit = $conn->query($sql_edit);

        if ($result_edit->num_rows > 0) {
            $row_edit = $result_edit->fetch_assoc();
            $id_pelicula = $row_edit["id_pelicula"];
            $id_director = $row_edit["id_director"];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pelicula_director = $_POST["id_pelicula_director"];
    $id_pelicula = $_POST["id_pelicula"];
    $id_director = $_POST["id_director"];

    if (!empty($id_pelicula_director)) {
        $sql_update = "UPDATE Peliculas_Directores SET id_pelicula='$id_pelicula', id_director='$id_director' WHERE id_pelicula_director='$id_pelicula_director'";

        if ($conn->query($sql_update) === TRUE) {
            echo 'Registro actualizado exitosamente';
        } else {
            echo 'Error al actualizar registro: ' . $conn->error;
        }
    } else {
        $sql_insert = "INSERT INTO Peliculas_Directores (id_pelicula, id_director) VALUES ('$id_pelicula', '$id_director')";

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
    <title>Peliculas_directores</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <a href="index.php">Volver a la página principal</a>
    <h1>Peliculas_directores</h1>

    <form action="peliculas_directores.php" method="post">
        <input type="hidden" id="id_pelicula_director" name="id_pelicula_director" value="<?php echo isset($id_pelicula_director) ? $id_pelicula_director : ''; ?>">

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

        <label for="id_director">Director:</label>
        <select id="id_director" name="id_director" required>
            <?php
            // Mostrar opciones de directores
            while ($row = $result_directores->fetch_assoc()) {
                $selected = isset($id_director) && $id_director == $row['id_director'] ? 'selected' : '';
                echo "<option value='" . $row['id_director'] . "' $selected>" . $row['nombre_director'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" value="<?php echo isset($id_pelicula_director) ? 'Actualizar' : 'Agregar'; ?>">
    </form>

    <h2>Lista de Peliculas_directores</h2>
    <table>
        <tr>
            <th>Id pelicula director</th>
            <th>Pelicula</th>
            <th>Director</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT pd.id_pelicula_director, p.titulo AS titulo_pelicula, pd.id_pelicula, d.nombre_director, pd.id_director 
                FROM Peliculas_Directores pd
                INNER JOIN peliculas p ON pd.id_pelicula = p.id_pelicula
                INNER JOIN directores d ON pd.id_director = d.id_director";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_pelicula_director"] . "</td>";
                echo "<td>" . $row["titulo_pelicula"] . "</td>";
                echo "<td>" . $row["nombre_director"] . "</td>";
                echo "<td>
                        <a href='peliculas_directores.php?action=edit&id=" . $row["id_pelicula_director"] . "'>Editar</a>
                        <a href='peliculas_directores.php?action=delete&id=" . $row["id_pelicula_director"] . "' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?')\">Eliminar</a>
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
