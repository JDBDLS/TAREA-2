<?php
$codigo = $_GET['codigo'] ?? '';
$obra = null;
$personajes_obra = [];

if (empty($codigo)) {
    header('Location: ver_obras.php');
    exit;
}

if (file_exists('datos/obras.json')) {
    $obras = json_decode(file_get_contents('datos/obras.json'), true) ?: [];
    foreach ($obras as $o) {
        if ($o['codigo'] === $codigo) {
            $obra = $o;
            break;
        }
    }
}

if (!$obra) {
    header('Location: ver_obras.php');
    exit;
}

if (file_exists('datos/personajes.json')) {
    $personajes = json_decode(file_get_contents('datos/personajes.json'), true) ?: [];
    foreach ($personajes as $personaje) {
        if ($personaje['codigo_obra'] === $codigo) {
            $personajes_obra[] = $personaje;
        }
    }
}

function calcularEdad($fecha_nacimiento) {
    if (empty($fecha_nacimiento)) return 'No especificada';
    $fecha_nac = new DateTime($fecha_nacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha_nac);
    return $edad->y . ' años';
}

function calcularSignoZodiacal($fecha_nacimiento) {
    if (empty($fecha_nacimiento)) return 'No especificado';
    
    $fecha = new DateTime($fecha_nacimiento);
    $dia = (int)$fecha->format('d');
    $mes = (int)$fecha->format('m');
    
    $signos = [
        ['Capricornio', 12, 22], ['Acuario', 1, 20], ['Piscis', 2, 19], ['Aries', 3, 21],
        ['Tauro', 4, 20], ['Géminis', 5, 21], ['Cáncer', 6, 22], ['Leo', 7, 23],
        ['Virgo', 8, 23], ['Libra', 9, 23], ['Escorpio', 10, 23], ['Sagitario', 11, 22]
    ];
    
    foreach ($signos as $signo) {
        if (($mes == $signo[1] && $dia >= $signo[2]) || 
            ($mes == ($signo[1] % 12) + 1 && $dia < $signo[2])) {
            return $signo[0];
        }
    }
    
    return 'Capricornio';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle - <?php echo htmlspecialchars($obra['nombre']); ?></title>
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
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .header h1 {
            color: #333;
            margin: 0;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .back-link {
            color: #007cba;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .btn-print {
            background-color: #007cba;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        
        .btn-print:hover {
            background-color: #005a87;
        }
        
        .obra-detalle {
            background-color: white;
            padding: 30px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .obra-header {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }
        
        .obra-imagen-grande {
            width: 200px;
            height: 280px;
            object-fit: cover;
        }
        
        .obra-imagen-placeholder {
            width: 200px;
            height: 280px;
            background-color: #007cba;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
        }
        
        .obra-datos {
            flex: 1;
        }
        
        .obra-datos h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .obra-datos p {
            margin-bottom: 10px;
            line-height: 1.5;
        }
        
        .obra-datos strong {
            color: #007cba;
        }
        
        .personajes-section {
            background-color: white;
            padding: 30px;
            border: 1px solid #ddd;
        }
        
        .personajes-section h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
        }
        
        .personajes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .personaje-card {
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #f9f9f9;
        }
        
        .personaje-header {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .personaje-imagen {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .personaje-imagen-placeholder {
            width: 60px;
            height: 60px;
            background-color: #007cba;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .personaje-info h4 {
            color: #333;
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        
        .personaje-info .cedula {
            color: #666;
            font-size: 14px;
        }
        
        .personaje-details p {
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .personaje-details strong {
            color: #007cba;
        }
        
        .no-personajes {
            text-align: center;
            color: #666;
            padding: 40px;
        }
        
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .container { max-width: none; margin: 0; }
            .header, .obra-detalle, .personajes-section { 
                border: none;
                margin: 0 0 20px 0;
            }
        }
        
        @media (max-width: 600px) {
            .header { 
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .obra-header { 
                flex-direction: column;
                text-align: center;
            }
            .obra-imagen-grande, .obra-imagen-placeholder {
                width: 100%;
                max-width: 250px;
                margin: 0 auto;
            }
            .personajes-grid { 
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header no-print">
            <h1>📖 Detalle de la Obra</h1>
            <div class="header-actions">
                <a href="ver_obras.php" class="back-link">← Volver a obras</a>
                <button onclick="window.print()" class="btn-print">🖨️ Imprimir</button>
            </div>
        </div>

        <div class="obra-detalle">
            <div class="obra-header">
                <?php if (!empty($obra['foto_url'])): ?>
                    <img src="<?php echo htmlspecialchars($obra['foto_url']); ?>" alt="<?php echo htmlspecialchars($obra['nombre']); ?>" class="obra-imagen-grande">
                <?php else: ?>
                    <div class="obra-imagen-placeholder">📚</div>
                <?php endif; ?>
                
                <div class="obra-datos">
                    <h2><?php echo htmlspecialchars($obra['nombre']); ?></h2>
                    
                    <p><strong>Código:</strong> <?php echo htmlspecialchars($obra['codigo']); ?></p>
                    <p><strong>Tipo:</strong> <?php echo htmlspecialchars($obra['tipo']); ?></p>
                    <p><strong>País:</strong> <?php echo htmlspecialchars($obra['pais'] ?: 'No especificado'); ?></p>
                    <p><strong>Autor:</strong> <?php echo htmlspecialchars($obra['autor']); ?></p>
                    
                    <?php if (!empty($obra['descripcion'])): ?>
                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($obra['descripcion']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="personajes-section">
            <h3>👥 Personajes Registrados (<?php echo count($personajes_obra); ?>)</h3>
            
            <?php if (empty($personajes_obra)): ?>
                <div class="no-personajes">
                    <p>Esta obra no tiene personajes registrados.</p>
                </div>
            <?php else: ?>
                <div class="personajes-grid">
                    <?php foreach ($personajes_obra as $personaje): ?>
                        <div class="personaje-card">
                            <div class="personaje-header">
                                <?php if (!empty($personaje['foto_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($personaje['foto_url']); ?>" alt="<?php echo htmlspecialchars($personaje['nombre']); ?>" class="personaje-imagen">
                                <?php else: ?>
                                    <div class="personaje-imagen-placeholder">👤</div>
                                <?php endif; ?>
                                
                                <div class="personaje-info">
                                    <h4><?php echo htmlspecialchars($personaje['nombre'] . ' ' . $personaje['apellido']); ?></h4>
                                    <div class="cedula">Cédula: <?php echo htmlspecialchars($personaje['cedula']); ?></div>
                                </div>
                            </div>
                            
                            <div class="personaje-details">
                                <p><strong>Edad:</strong> <?php echo calcularEdad($personaje['fecha_nacimiento']); ?></p>
                                <p><strong>Signo Zodiacal:</strong> <?php echo calcularSignoZodiacal($personaje['fecha_nacimiento']); ?></p>
                                
                                <?php if (!empty($personaje['sexo'])): ?>
                                    <p><strong>Sexo:</strong> <?php echo htmlspecialchars($personaje['sexo']); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($personaje['habilidades'])): ?>
                                    <p><strong>Habilidades:</strong> <?php echo htmlspecialchars($personaje['habilidades']); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($personaje['comida_favorita'])): ?>
                                    <p><strong>Comida favorita:</strong> <?php echo htmlspecialchars($personaje['comida_favorita']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
