<?php
if (!file_exists('datos')) mkdir('datos', 0777, true);
if (!file_exists('assets/fotos')) mkdir('assets/fotos', 0777, true);

$mensaje = '';

if ($_POST) {
    $codigo = trim($_POST['codigo']);
    $foto_url = trim($_POST['foto_url']);
    $tipo = $_POST['tipo'];
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $pais = trim($_POST['pais']);
    $autor = trim($_POST['autor']);
    
    if (empty($codigo) || empty($nombre) || empty($autor)) {
        $mensaje = '<div style="background-color: #ffebee; color: #c62828; padding: 15px; border: 1px solid #c62828; margin-bottom: 20px;">❌ Por favor completa todos los campos obligatorios.</div>';
    } else {
        $obras = [];
        if (file_exists('datos/obras.json')) {
            $obras = json_decode(file_get_contents('datos/obras.json'), true) ?: [];
        }
        
        $codigo_existe = false;
        foreach ($obras as $obra) {
            if ($obra['codigo'] === $codigo) {
                $codigo_existe = true;
                break;
            }
        }
        
        if ($codigo_existe) {
            $mensaje = '<div style="background-color: #ffebee; color: #c62828; padding: 15px; border: 1px solid #c62828; margin-bottom: 20px;">❌ El código ya existe. Por favor usa otro código.</div>';
        } else {
            $nueva_obra = [
                'codigo' => $codigo,
                'foto_url' => $foto_url,
                'tipo' => $tipo,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'pais' => $pais,
                'autor' => $autor,
                'fecha_registro' => date('Y-m-d H:i:s')
            ];
            
            $obras[] = $nueva_obra;
            
            if (file_put_contents('datos/obras.json', json_encode($obras, JSON_PRETTY_PRINT))) {
                $mensaje = '<div style="background-color: #e8f5e8; color: #2e7d32; padding: 15px; border: 1px solid #2e7d32; margin-bottom: 20px;">✅ ¡Obra registrada exitosamente!</div>';
                $_POST = [];
            } else {
                $mensaje = '<div style="background-color: #ffebee; color: #c62828; padding: 15px; border: 1px solid #c62828; margin-bottom: 20px;">❌ Error al guardar la obra.</div>';
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
    <title>Registrar Obra</title>
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
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #007cba;
            outline: none;
        }
        
        .form-group small {
            color: #666;
            font-size: 12px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Registrar Nueva Obra</h1>
            <a href="index.php" class="back-link">← Volver al inicio</a>
        </div>
        
        <div class="form-container">
            <?php echo $mensaje; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="codigo">Código único *</label>
                    <input type="text" id="codigo" name="codigo" value="<?php echo $_POST['codigo'] ?? ''; ?>" required>
                    <small>Ejemplo: SER001, PEL002, LIB003</small>
                </div>

                <div class="form-group">
                    <label for="foto_url">URL de la imagen</label>
                    <input type="url" id="foto_url" name="foto_url" value="<?php echo $_POST['foto_url'] ?? ''; ?>" placeholder="https://ejemplo.com/imagen.jpg">
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo de obra *</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Selecciona un tipo</option>
                        <option value="Serie" <?php echo ($_POST['tipo'] ?? '') === 'Serie' ? 'selected' : ''; ?>>Serie</option>
                        <option value="Película" <?php echo ($_POST['tipo'] ?? '') === 'Película' ? 'selected' : ''; ?>>Película</option>
                        <option value="Otro" <?php echo ($_POST['tipo'] ?? '') === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre de la obra *</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $_POST['nombre'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4"><?php echo $_POST['descripcion'] ?? ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="pais">País de origen</label>
                    <input type="text" id="pais" name="pais" value="<?php echo $_POST['pais'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="autor">Autor/Creador *</label>
                    <input type="text" id="autor" name="autor" value="<?php echo $_POST['autor'] ?? ''; ?>" required>
                </div>

                <button type="submit" class="submit-btn">💾 Registrar Obra</button>
            </form>
        </div>
    </div>
</body>
</html>
