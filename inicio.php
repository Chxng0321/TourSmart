<?php

session_start();



/* =========================
   VALIDAR SESION
========================= */


if(!isset($_SESSION["usuario_id"])){

    header("Location:index.php");
    exit;

}



/* =========================
   CONEXION
========================= */


require_once __DIR__."/config/conexion.php";



/* =========================
   TOKEN CSRF
========================= */


if(empty($_SESSION["csrf_token"])){

    $_SESSION["csrf_token"] = bin2hex(
        random_bytes(32)
    );

}



/* =========================
   FUNCION SEGURA
========================= */


function escapar($texto){

    return htmlspecialchars(
        (string)$texto,
        ENT_QUOTES,
        "UTF-8"
    );

}



/* =========================
   USUARIO
========================= */


$usuarioId = (int)$_SESSION["usuario_id"];

$nombre = $_SESSION["usuario_nombre"] ?? "Usuario";



/* =========================
   FAVORITOS
========================= */


$consulta = $conexion->prepare(

    "SELECT playa_slug 
     FROM favoritos 
     WHERE usuario_id = ?"

);


$consulta->execute([

    $usuarioId

]);


$favoritos = $consulta->fetchAll(
    PDO::FETCH_COLUMN
);



/* =========================
   CATALOGO PLAYAS
========================= */


$playas = [


[
"slug"=>"mancora",
"nombre"=>"Máncora",
"region"=>"Piura",
"imagen"=>"mancora.jpg",
"presupuesto"=>"medio",
"tipos"=>[
"relajacion",
"surf",
"aventura"
]
],



[
"slug"=>"vichayito",
"nombre"=>"Vichayito",
"region"=>"Piura",
"imagen"=>"vichayito.jpg",
"presupuesto"=>"alto",
"tipos"=>[
"relajacion",
"familiar"
]
],



[
"slug"=>"zorritos",
"nombre"=>"Zorritos",
"region"=>"Tumbes",
"imagen"=>"zorritos.jpg",
"presupuesto"=>"medio",
"tipos"=>[
"relajacion",
"familiar"
]
],



[
"slug"=>"punta-sal",
"nombre"=>"Punta Sal",
"region"=>"Tumbes",
"imagen"=>"punta-sal.jpg",
"presupuesto"=>"alto",
"tipos"=>[
"relajacion",
"familiar"
]
],



[
"slug"=>"organos",
"nombre"=>"Los Órganos",
"region"=>"Piura",
"imagen"=>"organos.jpg",
"presupuesto"=>"medio",
"tipos"=>[
"relajacion",
"aventura"
]
],



[
"slug"=>"cabo-blanco",
"nombre"=>"Cabo Blanco",
"region"=>"Piura",
"imagen"=>"cabo-blanco.jpg",
"presupuesto"=>"bajo",
"tipos"=>[
"surf",
"aventura"
]
],



[
"slug"=>"colan",
"nombre"=>"Colán",
"region"=>"Piura",
"imagen"=>"colan.jpg",
"presupuesto"=>"medio",
"tipos"=>[
"relajacion",
"familiar"
]
],



[
"slug"=>"yacila",
"nombre"=>"Yacila",
"region"=>"Piura",
"imagen"=>"yacila.jpg",
"presupuesto"=>"bajo",
"tipos"=>[
"familiar",
"aventura"
]
]


];



$soloFavoritos = (

    ($_GET["vista"] ?? "") === "favoritos"

);


?>
<!-- =========================
     LISTADO DE PLAYAS
========================= -->


<div class="encabezado-playas">


<h2>
Playas destacadas
</h2>


<span id="contadorPlayas">

<?php echo count($playas); ?> playas

</span>


</div>




<div class="grid-playas">



<?php foreach($playas as $playa): ?>


<?php


/* FAVORITO ACTIVO */

$estaGuardado = in_array(
    $playa["slug"],
    $favoritos,
    true
);


?>



<div class="tarjeta">



<!-- FAVORITO -->

<button

class="btn-favorito <?php echo $estaGuardado ? 'guardado':''; ?>"

data-playa="<?php echo escapar($playa["slug"]); ?>"

>

<?php echo $estaGuardado ? "❤️":"♡"; ?>

</button>





<!-- LINK COMPLETO DE PLAYA -->

<a

href="detalle.php?playa=<?php echo escapar($playa["slug"]); ?>"

class="link-playa"

>





<div class="foto-placeholder">


<img

src="img/<?php echo escapar($playa["imagen"]); ?>"

alt="Playa <?php echo escapar($playa["nombre"]); ?>"

loading="lazy"


>


</div>







<div class="tarjeta-info">



<h3>

<?php echo escapar($playa["nombre"]); ?>

</h3>



<p>

📍 <?php echo escapar($playa["region"]); ?>

</p>



</div>




</a>





</div>



<?php endforeach; ?>



</div>
<script>


/* =========================
   FAVORITOS AJAX
========================= */


document.querySelectorAll(".btn-favorito")
.forEach(boton=>{


    boton.addEventListener("click",function(e){


        e.preventDefault();


        let playa = this.dataset.playa;



        let datos = new FormData();


        datos.append(
            "playa_slug",
            playa
        );


        datos.append(
            "csrf_token",
            "<?php echo $_SESSION["csrf_token"]; ?>"
        );





        fetch("favorito_accion.php",{

            method:"POST",

            body:datos

        })



        .then(res=>res.json())



        .then(data=>{


            if(data.favorito){


                this.classList.add("guardado");

                this.innerHTML="❤️";


            }else{


                this.classList.remove("guardado");

                this.innerHTML="♡";


            }



        })



        .catch(()=>{


            alert(
                "Error al guardar favorito"
            );


        });



    });



});





/* =========================
   BUSCADOR
========================= */


const buscador = document.querySelector("#buscador");


if(buscador){


buscador.addEventListener(
"keyup",
function(){



let texto = this.value.toLowerCase();



document.querySelectorAll(".tarjeta")
.forEach(tarjeta=>{


    let nombre = tarjeta
    .innerText
    .toLowerCase();



    if(nombre.includes(texto)){


        tarjeta.style.display="block";


    }else{


        tarjeta.style.display="none";


    }



});



});


}





/* =========================
   FILTRO REGION
========================= */


const filtroRegion =
document.querySelector("#region");



if(filtroRegion){


filtroRegion.addEventListener(
"change",
function(){


let region =
this.value.toLowerCase();



document.querySelectorAll(".tarjeta")
.forEach(tarjeta=>{


let texto =
tarjeta.innerText.toLowerCase();



if(
region=="" ||
texto.includes(region)
){


tarjeta.style.display="block";


}else{


tarjeta.style.display="none";


}



});


});


}





/* =========================
   LIMPIAR FILTROS
========================= */


const limpiar =
document.querySelector(".btn-limpiar");



if(limpiar){


limpiar.addEventListener(
"click",
function(){



document.querySelectorAll(".tarjeta")
.forEach(tarjeta=>{


tarjeta.style.display="block";


});



if(buscador){

buscador.value="";

}


if(filtroRegion){

filtroRegion.value="";

}



});


}



</script>
</body>
</html>