<?php 
session_start();
// if(!empty($_SESSION['nRol'])){
$datos = array();
$cliente = new SoapClient(null, array(
    'uri' => 'http://localhost/',
    'location' => 'https://deliverylions.000webhostapp.com/serviciosweb/serviciosweb.php'
));
// include '../../../serviciosweb/clsservicios.php';
// $cliente = new clsservicios();
if(!isset($_GET["search"])){
    $datosC = $cliente->listaC();
}else{
    $datosC = $cliente->Busqueda($_GET["search"]);
}
$datosAFI = $cliente->listaAfiliados();
$datosCategoria = $cliente->listarComidas();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glider-js@1.7.8/glider.min.css">
    <link rel="stylesheet" href="../../css/client_styles/inicio.css">
    
</head>
<body>
    <?php include "../components/nav.php"; ?>
    <section class="content">
        <div class="content-text">
            <h1>BIENVENIDO DELIVERY LIONS</h1>
        </div>
        <div class="content-text">
            <h3>Vendemos Cualquier comida</h3>
        </div>
    </section>
    <main>
        <div class="content-text">
            <h3>Categoría</h3>
        </div>
        <div class="carrusel">
                <div class="carrusel-list" id="carrusel-list">
                    <button class="carrusel-arrow carrusel-prev" id="button-prev" data-button="button-prev"
                        onclick="">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-left"
                            class="svg-inline--fa fa-chevron-left fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 320 512">
                            <path fill="currentColor"
                                d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z">
                            </path>
                        </svg>
                    </button>
                    <div class="carrusel-track" id="track">
                        <?php foreach($datosCategoria as $categoria){
                        ?>
                        <div class="carrusel-item">
                            <div >
                                <a href="<?php echo $categoria['ID']?>">
                                    <picture>
                                        <img src="<?php echo $categoria['IMAGEN']?>" alt="Comida rápida">
                                    </picture>
                                </a>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <button class="carrusel-arrow carrusel-next" id="button-next" data-button="button-next"
                    onclick="">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right"
                        class="svg-inline--fa fa-chevron-right fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 320 512">
                        <path fill="currentColor"
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                        </path>
                    </svg>
                </button>
                </div>
                <div role="tablist" class="carousel__indicadores"></div>
            </div>
        <form action = "" method="get">
            <div class="search-content">
                <div class="search">
                    <input type="text"  name="search" placeholder="Buscar">
                    <button type="submit" class="fa-solid fa-magnifying-glass"></button>
                </div>
            </div>
        </form>
        <div class="main-content">
            <div class="afiliados-container">
            <h2>Vendedores</h2>
             <?php 
                foreach ($datosAFI as $afi){
                ?>
                <div class="afiliados-content">
                    <div class="img-content"><img src="<?php echo $afi['img_AFI']?>" alt="imagen afiliados"></div>
                    <div class="text-content">
                        <h3><?php echo $afi['N_AFI']?></h3>
                        <p><?php echo $afi['DesAFI']?></p>
                    </div>
                </div>
             <?php  }
            ?>
        </div>
            <div class="comida-container">
                 <div class="title-food">
                    <h2>Comida</h2>
                </div>
            <?php 
                foreach ($datosC as $comida){
            ?>
                    <div class="comida-content">
                        <div class="img-content"><img src="<?php echo $comida['img']?>" alt="imagen comida"></div>
                        <div class="info-container">
                            <div class="title">
                                
                                <h3><?php echo $comida['N_Menu']?></h3>
                            </div>
                            <div class="text-content">
                                <p><?php echo $comida['DesMenu']?></p>
                            </div>
                            <div class="btn-content"><button type="button" id="<?php echo $comida['ID'] ?>" onclick="detallePro(<?php echo $comida['ID'] ?>,'<?php echo sha1($comida['ID']) ?>')">Más información</button></div>
                        </div>
                    </div>
             <?php   }
            ?>
            <!--<div class="comida-content">-->
            <!--    <div class="img-content"><img src="../../img/comida.webp" alt="imagen comida"></div>-->
            <!--    <div class="info-container">-->
            <!--        <div class="title">-->
            <!--            <h3>Comida</h3>-->
            <!--        </div>-->
            <!--        <div class="text-content">-->
            <!--            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequuntur, quidem.</p>-->
            <!--        </div>-->
            <!--        <div class="btn-content"><button>Más información</button></div>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
        </div>
        
    </main>
    <?php include_once '../components/footer.php' ?>
</body>
<script src="https://kit.fontawesome.com/907d8454bc.js" crossorigin="anonymous"></script>
<script>
    function detallePro(ID, token){
        window.location.replace("detallsproduct.php?ID="+ID+"&token="+token);
    }
    
</script>

</html>


<?php
// }else{
//     echo "
//     <script>
//         window.location.href = 'https://deliverylions.000webhostapp.com/sitioweb/inicioPrincipal/inicioPrincipal.php';
//     </script>";
// }

 ?>