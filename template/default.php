<html>
    <head>
        <title>Plantilla Principal</title>
        <style>
            body{
                background: silver;
            }
        </style>
    </head>
    <body>
        <div id="cabeza">
            <h2>Cabeza<h2>
        </div>
        <div id="menu">
            <a href="#">Inicio</a>
            <a href="#">Salir</a>
            <a href="#">Prueba</a>
        </div>
        <div id="contenido">
            <?php echo View::content(); ?>
        </div>
        <div id="pie">
            <p>pie de pagina</p>
        </div>
    </body>
</html>