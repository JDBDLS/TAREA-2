<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Obras y Personajes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .header {
            background-color: white;
            padding: 30px;
            text-align: center;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            margin: 0;
        }
        
        .menu {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .menu-item {
            background-color: white;
            border: 1px solid #ddd;
            padding: 20px;
            text-decoration: none;
            color: #333;
            flex: 1;
            min-width: 200px;
            text-align: center;
        }
        
        .menu-item:hover {
            background-color: #f5f5f5;
        }
        
        .menu-item h3 {
            margin: 10px 0;
            color: #007cba;
        }
        
        .menu-item p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        
        .stats {
            background-color: white;
            padding: 30px;
            border: 1px solid #ddd;
        }
        
        .stats h2 {
            margin-top: 0;
            color: #333;
        }
        
        .stats-row {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }
        
        .stat-box {
            text-align: center;
            padding: 20px;
            background-color: #007cba;
            color: white;
            flex: 1;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            display: block;
        }
        
        .stat-label {
            margin-top: 10px;
        }
        
        @media (max-width: 600px) {
            .menu {
                flex-direction: column;
            }
            
            .stats-row {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Gestor de Obras y Personajes</h1>
            <p>Sistema para gestionar contenido multimedia y sus personajes</p>
        </div>

        <div class="menu">
            <a href="registrar_obra.php" class="menu-item">
                <div style="font-size: 30px;">📝</div>
                <h3>Registrar Obra</h3>
                <p>Añadir nuevas series, películas o libros</p>
            </a>
            
            <a href="agregar_personaje.php" class="menu-item">
                <div style="font-size: 30px;">👤</div>
                <h3>Agregar Personaje</h3>
                <p>Crear personajes para las obras</p>
            </a>
            
            <a href="ver_obras.php" class="menu-item">
                <div style="font-size: 30px;">📋</div>
                <h3>Ver Obras</h3>
                <p>Ver todas las obras registradas</p>
            </a>
        </div>

        <div class="stats">
            <h2>Estadísticas</h2>
            <?php
            if (!file_exists('datos')) mkdir('datos', 0777, true);
            if (!file_exists('assets/fotos')) mkdir('assets/fotos', 0777, true);
            
            $obras = [];
            $personajes = [];
            
            if (file_exists('datos/obras.json')) {
                $obras = json_decode(file_get_contents('datos/obras.json'), true) ?: [];
            }
            
            if (file_exists('datos/personajes.json')) {
                $personajes = json_decode(file_get_contents('datos/personajes.json'), true) ?: [];
            }
            ?>
            
            <div class="stats-row">
                <div class="stat-box">
                    <span class="stat-number"><?php echo count($obras); ?></span>
                    <div class="stat-label">Obras Registradas</div>
                </div>
                
                <div class="stat-box">
                    <span class="stat-number"><?php echo count($personajes); ?></span>
                    <div class="stat-label">Personajes Creados</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
