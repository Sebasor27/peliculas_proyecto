<?php
include 'includes/db.php';

$sql_peliculas = "SELECT id_pelicula, titulo FROM peliculas";
$result_peliculas = $conn->query($sql_peliculas);

$sql_actores = "SELECT id_actor, nombre_actor FROM actores";
$result_actores = $conn->query($sql_actores);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"])) {
    if ($_GET["action"] == "delete" && isset($_GET["id"])) {
        
        $id_pelicula_actor = $_GET["id"];
        $sql_delete = "DELETE FROM Peliculas_Actores WHERE id_pelicula_actor='$id_pelicula_actor'";
        
        if ($conn->query($sql_delete) === TRUE) {
            echo 'Registro eliminado exitosamente';
        } else {
            echo 'Error al eliminar registro: ' . $conn->error;
        }
        
       
        $id_pelicula_actor = '';
    } elseif ($_GET["action"] == "edit" && isset($_GET["id"])) {
       
        $id_pelicula_actor = $_GET["id"];
        $sql_edit = "SELECT * FROM Peliculas_Actores WHERE id_pelicula_actor='$id_pelicula_actor'";
        $result_edit = $conn->query($sql_edit);

        if ($result_edit->num_rows > 0) {
            $row_edit = $result_edit->fetch_assoc();
            $id_pelicula = $row_edit["id_pelicula"];
            $id_actor = $row_edit["id_actor"];
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pelicula_actor = $_POST["id_pelicula_actor"];
    $id_pelicula = $_POST["id_pelicula"];
    $id_actor = $_POST["id_actor"];

    if (!empty($id_pelicula_actor)) {

        $sql_update = "UPDATE Peliculas_Actores SET id_pelicula='$id_pelicula', id_actor='$id_actor' WHERE id_pelicula_actor='$id_pelicula_actor'";

        if ($conn->query($sql_update) === TRUE) {
            echo 'Registro actualizado exitosamente';
        } else {
            echo 'Error al actualizar registro: ' . $conn->error;
        }
    } else {

        $sql_insert = "INSERT INTO Peliculas_Actores (id_pelicula, id_actor) VALUES ('$id_pelicula', '$id_actor')";

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
    <title>Peliculas_actores</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <a href="index.php">Volver a la página principal</a>
    <h1>Peliculas_actores</h1>

    <form action="peliculas_actores.php" method="post">
    <input type="hidden" id="id_pelicula_actor" name="id_pelicula_actor" value="<?php echo isset($id_pelicula_actor) ? $id_pelicula_actor : ''; ?>">



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

        <label for="id_actor">Actor:</label>
        <select id="id_actor" name="id_actor" required>
            <?php
            // Mostrar opciones de actores
            while ($row = $result_actores->fetch_assoc()) {
                $selected = isset($id_actor) && $id_actor == $row['id_actor'] ? 'selected' : '';
                echo "<option value='" . $row['id_actor'] . "' $selected>" . $row['nombre_actor'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" value="<?php echo isset($id_pelicula_actor) ? 'Actualizar' : 'Agregar'; ?>">
    </form>

    <h2>Lista de Peliculas_actores</h2>
    <table>
        <tr>
            <th>Id pelicula actor</th>
            <th>Pelicula</th>
            <th>Actor</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT pa.id_pelicula_actor, p.titulo AS titulo_pelicula, pa.id_pelicula, a.nombre_actor, pa.id_actor 
                FROM Peliculas_Actores pa
                INNER JOIN peliculas p ON pa.id_pelicula = p.id_pelicula
                INNER JOIN actores a ON pa.id_actor = a.id_actor";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_pelicula_actor"] . "</td>";
                echo "<td>" . $row["titulo_pelicula"] . "</td>";
                echo "<td>" . $row["nombre_actor"] . "</td>";
                echo "<td>
                        <a href='peliculas_actores.php?action=edit&id=" . $row["id_pelicula_actor"] . "'>Editar</a>
                        <a href='peliculas_actores.php?action=delete&id=" . $row["id_pelicula_actor"] . "' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?')\">Eliminar</a>
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