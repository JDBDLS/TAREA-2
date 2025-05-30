<?php
if (!file_exists('datos')) mkdir('datos', 0777, true);
if (!file_exists('assets/fotos')) mkdir('assets/fotos', 0777, true);

$mensaje = '';
$obras = [];

if (file_exists('datos/obras.json')) {
    $obras = json_decode(file_get_contents('datos/obras.json'), true) ?: [];
}

if ($_POST) {
    $cedula = trim($_POST['cedula']);
    $foto_url = trim($_POST['foto_url']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $habilidades = trim($_POST['habilidades']);
    $comida_favorita = trim($_POST['comida_favorita']);
    $codigo_obra = $_POST['codigo_obra'];
    
    if (empty($cedula) || empty($nombre) || empty($apellido) || empty($codigo_obra)) {
        $mensaje = '<div style="background-color: #ffebee; color: #c62828; padding: 15px; border: 1px solid #c62828; margin-bottom: 20px;">❌ Por favor completa todos los campos obligatorios.</div>';
    } else {
        $personajes = [];
        if (file_exists('datos/personajes.json')) {
            $personajes = json_decode(file_get_contents('datos/personajes.json'), true) ?: [];
        }
        
        $cedula_existe = false;
        foreach ($personajes as $personaje) {
            if ($personaje['cedula'] === $cedula) {
                $cedula_existe = true;
                break;
            }
        }
        
        if ($cedula_existe) {
            $mensaje = '<div style="background-color: #ffebee; color: #c62828; padding: 15px; border: 1px solid #c62828; margin-bottom: 20px;">❌ La cédula ya existe. Por favor usa otra cédula.</div>';
        } else {
            $nuevo_personaje = [
                'cedula' => $cedula,
                'foto_url' => $foto_url,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'fecha_nacimiento' => $fecha_nacimiento,
                'sexo' => $sexo,
                'habilidades' => $habilidades,
                'comida_favorita' => $comida_favorita,
                'codigo_obra' => $codigo_obra,
                'fecha_registro' => date('Y-m-d H:i:s')
            ];
            
            $personajes[] = $nuevo_personaje;
            
            if (file_put_contents('datos/personajes.json', json_encode($personajes, JSON_PRETTY_PRINT))) {
                $mensaje = '<div style="background-color: #e8f5e8; color: #2e7d32; padding: 15px; border: 1px solid #2e7d32; margin-bottom: 20px;">✅ ¡Personaje agregado exitosamente!</div>';
                $_POST = [];
            } else {
                $mensaje = '<div style="background-color: #ffebee; color: #c62828; padding: 15px; border: 1px solid #c62828; margin-bottom: 20px;">❌ Error al guardar el personaje.</div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Personaje</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .header {
            background-color: white;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .back-link {
            color: #007cba;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .form-container {
            background-color: white;
            padding: 30px;
            border: 1px solid #ddd;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            border-color: #007cba;
            outline: none;
        }
        
        .submit-btn {
            width: 100%;
            background-color: #007cba;
            color: white;
            padding: 15px;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
        
        .submit-btn:hover {
            background-color: #005a87;
        }
        
        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👤 Agregar Nuevo Personaje</h1>
            <a href="index.php" class="back-link">← Volver al inicio</a>
        </div>
        
        <div class="form-container">
            <?php echo $mensaje; ?>

            <?php if (empty($obras)): ?>
                <div style="background-color: #fff3cd; color: #856404; padding: 15px; border: 1px solid #856404; margin-bottom: 20px;">
                    ⚠️ No hay obras registradas. <a href="registrar_obra.php" style="color: #856404;">Registra una obra primero</a>.
                </div>
            <?php else: ?>

            <form method="POST">
                <div class="form-group">
                    <label for="codigo_obra">Obra *</label>
                    <select id="codigo_obra" name="codigo_obra" required>
                        <option value="">Selecciona una obra</option>
                        <?php foreach ($obras as $obra): ?>
                            <option value="<?php echo $obra['codigo']; ?>" <?php echo ($_POST['codigo_obra'] ?? '') === $obra['codigo'] ? 'selected' : ''; ?>>
                                <?php echo $obra['nombre'] . ' (' . $obra['codigo'] . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cedula">Cédula *</label>
                    <input type="text" id="cedula" name="cedula" value="<?php echo $_POST['cedula'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="foto_url">URL de la imagen del personaje</label>
                    <input type="url" id="foto_url" name="foto_url" value="<?php echo $_POST['foto_url'] ?? ''; ?>" placeholder="https://ejemplo.com/personaje.jpg">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre *</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $_POST['nombre'] ?? ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido *</label>
                        <input type="text" id="apellido" name="apellido" value="<?php echo $_POST['apellido'] ?? ''; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $_POST['fecha_nacimiento'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="sexo">Sexo</label>
                        <select id="sexo" name="sexo">
                            <option value="">Seleccionar</option>
                            <option value="Masculino" <?php echo ($_POST['sexo'] ?? '') === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                            <option value="Femenino" <?php echo ($_POST['sexo'] ?? '') === 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="habilidades">Habilidades</label>
                    <input type="text" id="habilidades" name="habilidades" value="<?php echo $_POST['habilidades'] ?? ''; ?>" placeholder="Separadas por comas: volar, fuerza, telepatía">
                </div>

                <div class="form-group">
                    <label for="comida_favorita">Comida favorita</label>
                    <input type="text" id="comida_favorita" name="comida_favorita" value="<?php echo $_POST['comida_favorita'] ?? ''; ?>">
                </div>

                <button type="submit" class="submit-btn">👤 Agregar Personaje</button>
            </form>

            <?php endif; ?>
        </div>
    </div>
</body>
</html>
