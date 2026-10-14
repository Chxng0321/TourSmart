<?php

session_start();



/* =========================
   SEGURIDAD SESION
========================= */

if (!isset($_SESSION["usuario_id"])) {

    header("Location:index.php");
    exit;

}



/* =========================
   TOKEN CSRF
========================= */

if (!isset($_SESSION["csrf_token"])) {

    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

}



/* =========================
   FUNCION SEGURA
========================= */

function escapar($texto){

    return htmlspecialchars(
        $texto,
        ENT_QUOTES,
        "UTF-8"
    );

}



/* =========================
   DATOS PLAYAS
========================= */

$playas = [


"mancora"=>[
"nombre"=>"Máncora",
"region"=>"Piura",
"imagen"=>"img/mancora.jpg",
"descripcion"=>"Una de las playas más conocidas del Perú. Destaca por sus aguas cálidas, clima tropical, actividades acuáticas y una gran oferta turística.",
"actividades"=>[
"🏄 Surf",
"🚤 Paseos en bote",
"🐋 Avistamiento de ballenas",
"🍽️ Restaurantes frente al mar"
],
"ubicacion"=>"Máncora, Talara, Piura, Perú",
"mapa"=>"https://www.google.com/maps?q=Mancora+Piura+Peru&output=embed"
],



"vichayito"=>[
"nombre"=>"Vichayito",
"region"=>"Piura",
"imagen"=>"img/vichayito.jpg",
"descripcion"=>"Playa tranquila con paisajes naturales, ideal para descanso, parejas y actividades frente al océano.",
"actividades"=>[
"🌴 Relajación",
"🏊 Natación",
"🚤 Paseos en bote",
"🌅 Caminatas"
],
"ubicacion"=>"Vichayito, Los Órganos, Piura, Perú",
"mapa"=>"https://www.google.com/maps?q=Vichayito+Piura+Peru&output=embed"
],



"zorritos"=>[
"nombre"=>"Zorritos",
"region"=>"Tumbes",
"imagen"=>"img/zorritos.jpg",
"descripcion"=>"Playa de aguas cálidas ubicada en Tumbes, famosa por sus paisajes tropicales.",
"actividades"=>[
"🏖️ Descanso",
"🌊 Baños de mar",
"🍤 Gastronomía",
"📸 Fotografía"
],
"ubicacion"=>"Zorritos, Tumbes, Perú",
"mapa"=>"https://www.google.com/maps?q=Zorritos+Tumbes+Peru&output=embed"
],



"punta-sal"=>[
"nombre"=>"Punta Sal",
"region"=>"Tumbes",
"imagen"=>"img/punta-sal.jpg",
"descripcion"=>"Una de las playas más hermosas del norte peruano, reconocida por sus aguas cálidas y tranquilidad.",
"actividades"=>[
"🏖️ Descanso",
"🏊 Natación",
"🌅 Caminatas",
"📷 Fotografía"
],
"ubicacion"=>"Punta Sal, Tumbes, Perú",
"mapa"=>"https://www.google.com/maps?q=Punta+Sal+Tumbes+Peru&output=embed"
],



"organos"=>[
"nombre"=>"Los Órganos",
"region"=>"Piura",
"imagen"=>"img/organos.jpg",
"descripcion"=>"Playa tranquila de Piura conocida por sus paisajes naturales y actividades marinas.",
"actividades"=>[
"🐢 Avistamiento marino",
"🏄 Surf",
"🚤 Paseos en bote",
"🌴 Relajación"
],
"ubicacion"=>"Los Órganos, Piura, Perú",
"mapa"=>"https://www.google.com/maps?q=Los+Organos+Piura+Peru&output=embed"
],



"cabo-blanco"=>[
"nombre"=>"Cabo Blanco",
"region"=>"Piura",
"imagen"=>"img/cabo-blanco.jpg",
"descripcion"=>"Famosa playa peruana reconocida por sus grandes olas y pesca deportiva.",
"actividades"=>[
"🏄 Surf",
"🎣 Pesca deportiva",
"📸 Fotografía",
"🌊 Paseos"
],
"ubicacion"=>"Cabo Blanco, Piura, Perú",
"mapa"=>"https://www.google.com/maps?q=Cabo+Blanco+Piura+Peru&output=embed"
],



"colan"=>[
"nombre"=>"Colán",
"region"=>"Piura",
"imagen"=>"img/colan.jpg",
"descripcion"=>"Playa tradicional de Piura con hermosos paisajes y zonas para descansar.",
"actividades"=>[
"🏖️ Descanso",
"🌅 Caminatas",
"🍽️ Gastronomía",
"📷 Fotografía"
],
"ubicacion"=>"Colán, Paita, Piura, Perú",
"mapa"=>"https://www.google.com/maps?q=Colan+Piura+Peru&output=embed"
],



"yacila"=>[
"nombre"=>"Yacila",
"region"=>"Piura",
"imagen"=>"img/yacila.jpg",
"descripcion"=>"Pequeña playa ubicada cerca de Paita, ideal para disfrutar del mar y la tranquilidad.",
"actividades"=>[
"🌊 Baños de mar",
"📸 Fotografía",
"🏖️ Descanso",
"🚶 Caminatas"
],
"ubicacion"=>"Yacila, Paita, Piura, Perú",
"mapa"=>"https://www.google.com/maps?q=Yacila+Piura+Peru&output=embed"
]

];



/* =========================
   PLAYA SELECCIONADA
========================= */


$slug = $_GET["playa"] ?? "mancora";


if(!isset($playas[$slug])){

    $slug="mancora";

}


$playa=$playas[$slug];

?>



<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>

<?php echo escapar($playa["nombre"]); ?> | PlayasPerú

</title>


<link rel="stylesheet" href="css/inicio.css">


</head>



<body>


<div class="detalle-container">



<form id="formFavorito">


<input 
type="hidden"
id="playa_slug"
value="<?php echo escapar($slug); ?>">



<input
type="hidden"
id="csrf_token"
value="<?php echo escapar($_SESSION["csrf_token"]); ?>">



<button 
type="button"
class="btn-favorito">

❤️ Agregar a favoritos

</button>


</form>





<img

class="detalle-imagen"

src="<?php echo escapar($playa["imagen"]); ?>"

alt="<?php echo escapar($playa["nombre"]); ?>"

>




<div class="detalle-info">



<h1>

<?php echo escapar($playa["nombre"]); ?>

</h1>



<h3>

📍 <?php echo escapar($playa["region"]); ?>

</h3>




<p>

<?php echo escapar($playa["descripcion"]); ?>

</p>





<div class="detalle-contenido">



<div class="bloque">


<h2>

Actividades recomendadas

</h2>



<div class="actividades">


<?php foreach($playa["actividades"] as $actividad): ?>


<div>

<?php echo escapar($actividad); ?>

</div>


<?php endforeach; ?>


</div>


</div>





<div class="bloque ubicacion">


<h2>

📍 Ubicación

</h2>



<p class="direccion">

<?php echo escapar($playa["ubicacion"]); ?>

</p>



<iframe

class="mapa"

src="<?php echo escapar($playa["mapa"]); ?>"

loading="lazy"

allowfullscreen>

</iframe>



</div>



</div>




<a 
class="volver"
href="inicio.php">

← Volver a playas

</a>



</div>



</div>




<script>

document.querySelector(".btn-favorito")
.addEventListener("click",function(){


let datos = new FormData();


datos.append(
"playa_slug",
document.getElementById("playa_slug").value
);


datos.append(
"csrf_token",
document.getElementById("csrf_token").value
);



fetch("favorito_accion.php",{

method:"POST",

body:datos

})


.then(res=>res.json())


.then(data=>{

alert(data.mensaje || data.error);

})


.catch(()=>{

alert("Error al guardar favorito");

});


});

</script>



</body>

</html>