<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Tareas</title>
    <link rel="stylesheet" href="estilos/index.css">
</head>
<body>

<header>
    <h1>Lista de Tareas</h1>
    <div class="nav">
        <a href="agregar.php" class="button">Agregar Nueva Tarea</a>
        <a href="logout.php" class="button">Cerrar Sesión</a>
    </div>
</header>

<main>
    <?php if (!empty($tareas)) : ?>
        <div class="task-table">
            <table>
                <thead>
                    <tr>
                        <th>Tarea</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tareas as $tarea) : ?>
                        <tr>
                            <td class="task-name"><?php echo $tarea->getNombre(); ?></td>
                            <td class="task-actions">
                                <form action="eliminar.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $tarea->getId(); ?>">
                                    <button type="submit" class="delete-button" onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea?');">Eliminar</button>
                                </form>
                                <form action="editar.php" method="GET" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $tarea->getId(); ?>">
                                    <button type="submit" class="edit-button">Editar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <p class="no-tasks">No hay tareas disponibles.</p>
    <?php endif; ?>
</main>

</body>
</html>
