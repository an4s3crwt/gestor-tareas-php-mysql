<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="estilos/agregar.css">
</head>
<body>
    <div class="container">
        <h1>Editar Tarea</h1>
        
        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="editar.php" method="POST" class="form">
            <input type="hidden" name="id" value="<?php echo $tarea->getId(); ?>">
            
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $tarea->getNombre(); ?>" required>
            
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required><?php echo $tarea->getDescripcion(); ?></textarea>
            
            <label for="prioridad">Prioridad:</label>
            <input type="text" name="prioridad" value="<?php echo $tarea->getPrioridad(); ?>" required>
            
            <label for="fecha_limite">Fecha Límite:</label>
            <input type="date" name="fecha_limite" value="<?php echo $tarea->getFechalimite(); ?>" required>
            
            <button type="submit" class="btn">Guardar Cambios</button>
        </form>

        <a href="index.php" class="link">Volver a la Lista de Tareas</a>
    </div>
</body>
</html>
