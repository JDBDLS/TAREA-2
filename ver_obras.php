<?php
$obras = [];
$personajes = [];

if (file_exists('datos/obras.json')) {
    $obras = json_decode(file_get_contents('datos/obras.json'), true) ?: [];
}

if (file_exists('datos/personajes.json')) {
    $personajes = json_decode(file_get_contents('datos/personajes.json'), true) ?: [];
}

function contarPersonajes($codigo_obra, $personajes) {
    $count = 0;
    foreach ($personajes as $personaje) {
        if ($personaje['codigo_obra'] === $codigo_obra) {
            $count++;
        }
    }
    return $count;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Obras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        
        .container {
            max-width: 1000px;
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
        
        .obras-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .obra-card {
            background-color: white;
            border: 1px solid #ddd;
            padding: 0;
            overflow: hidden;
        }
        
        .obra-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .obra-imagen {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .obra-imagen-placeholder {
            width: 100%;
            height: 180px;
            background-color: #007cba;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: white;
        }
        
        .obra-content {
            padding: 20px;
        }
        
        .obra-titulo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .obra-info {
            margin-bottom: 8px;
            font-size: 14px;
            color: #666;
        }
        
        .obra-info strong {
            color: #333;
        }
        
        .obra-tipo {
            display: inline-block;
            background-color: #007cba;
            color: white;
            padding: 4px 8px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .btn-detalle {
            display: inline-block;
            background-color: #007cba;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            margin-top: 10px;
        }
        
        .btn-detalle:hover {
            background-color: #005a87;
        }
        
        .no-obras {
            background-color: white;
            padding: 40px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        .no-obras h2 {
            color: #666;
            margin-bottom: 15px;
        }
        
        .no-obras a {
            display: inline-block;
            background-color: #007cba;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            margin-top: 15px;
        }
        
        .no-obras a:hover {
            background-color: #005a87;
        }
        
        @media (max-width: 600px) {
            .obras-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Todas las Obras Registradas</h1>
            <a href="index.php" class="back-link">← Volver al inicio</a>
        </div>

        <?php if (empty($obras)): ?>
            <div class="no-obras">
                <h2>No hay obras registradas</h2>
                <p>Comienza agregando tu primera obra</p>
                <a href="registrar_obra.php">Registrar primera obra</a>
            </div>
        <?php else: ?>

        <div class="obras-grid">
            <?php foreach ($obras as $obra): ?>
                <div class="obra-card">
                    <?php if (!empty($obra['foto_url'])): ?>
                        <img src="<?php echo htmlspecialchars($obra['foto_url']); ?>" alt="<?php echo htmlspecialchars($obra['nombre']); ?>" class="obra-imagen">
                    <?php else: ?>
                        <div class="obra-imagen-placeholder">📚</div>
                    <?php endif; ?>
                    
                    <div class="obra-content">
                        <div class="obra-tipo"><?php echo htmlspecialchars($obra['tipo']); ?></div>
                        <h3 class="obra-titulo"><?php echo htmlspecialchars($obra['nombre']); ?></h3>
                        
                        <div class="obra-info">
                            <strong>País:</strong> <?php echo htmlspecialchars($obra['pais'] ?: 'No especificado'); ?>
                        </div>
                        
                        <div class="obra-info">
                            <strong>Personajes:</strong> <?php echo contarPersonajes($obra['codigo'], $personajes); ?>
                        </div>
                        
                        <a href="detalle.php?codigo=<?php echo urlencode($obra['codigo']); ?>" class="btn-detalle">Ver Detalle</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>
</body>
</html>
