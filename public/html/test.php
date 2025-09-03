<?php
// Configuración
$directus_url = "https://strapi.grupobd.mx"; // Cambia a tu URL
$collection = "pagina";

// Inicializamos cURL
$ch = curl_init();

// Configuramos la URL y opciones
curl_setopt($ch, CURLOPT_URL, "$directus_url/items/$collection");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // opcional: timeout de 10 segundos

// Ejecutamos la petición
$response = curl_exec($ch);

// Verificamos errores
if(curl_errno($ch)) {
    echo 'Error en cURL: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

// Cerramos cURL
curl_close($ch);

// Decodificamos JSON
$data = json_decode($response, true);

// Tomamos el primer registro
$page = $data['data'][0];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page['titulo']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        img { max-width: 100%; height: auto; }
        h1 { color: #333; }
    </style>
</head>
<body>
    
<p>Contenido descargado de: <?= "$directus_url/items/$collection"; ?></p>
<pre><?= json_encode($data); ?></pre>

<h1><?php echo htmlspecialchars($page['titulo']); ?></h1>
<p><?php echo nl2br(htmlspecialchars($page['contenido'])); ?></p>

<?php if(!empty($page['imagen'])): ?>
    <img src="<?php echo $directus_url . '/assets/' . $page['imagen']; ?>" alt="Imagen">
<?php endif; ?>

</body>
</html>