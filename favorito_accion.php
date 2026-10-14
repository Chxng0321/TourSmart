<?php

session_start();


header("Content-Type: application/json; charset=utf-8");



/* =========================
   VALIDAR SESION
========================= */


if(!isset($_SESSION["usuario_id"])){

    http_response_code(401);

    echo json_encode([

        "error"=>"Sesión no iniciada"

    ]);

    exit;

}



/* =========================
   VALIDAR METODO
========================= */


if($_SERVER["REQUEST_METHOD"] !== "POST"){

    http_response_code(405);

    echo json_encode([

        "error"=>"Método no permitido"

    ]);

    exit;

}



/* =========================
   VALIDAR CSRF
========================= */


$token = $_POST["csrf_token"] ?? "";


if(
    empty($token) ||
    !isset($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
){

    http_response_code(403);

    echo json_encode([

        "error"=>"Solicitud no válida"

    ]);

    exit;

}



/* =========================
   PLAYAS PERMITIDAS
========================= */


$playasPermitidas=[

    "mancora",
    "vichayito",
    "zorritos",
    "punta-sal",
    "organos",
    "cabo-blanco",
    "colan",
    "yacila"

];



$slug = $_POST["playa_slug"] ?? "";



if(!in_array($slug,$playasPermitidas,true)){


    http_response_code(400);


    echo json_encode([

        "error"=>"Playa inválida"

    ]);


    exit;

}





/* =========================
   CONEXION BD
========================= */


require_once __DIR__."/config/conexion.php";



$usuarioId = (int)$_SESSION["usuario_id"];





try{


    /* BUSCAR FAVORITO */


    $buscar=$conexion->prepare(

        "SELECT id 
         FROM favoritos
         WHERE usuario_id = ?
         AND playa_slug = ?"

    );


    $buscar->execute([

        $usuarioId,
        $slug

    ]);




    if($buscar->fetch()){



        /* ELIMINAR */


        $eliminar=$conexion->prepare(

            "DELETE FROM favoritos
             WHERE usuario_id = ?
             AND playa_slug = ?"

        );


        $eliminar->execute([

            $usuarioId,
            $slug

        ]);



        echo json_encode([

            "favorito"=>false,

            "mensaje"=>"Playa eliminada de favoritos"

        ]);



    }else{



        /* INSERTAR */


        $guardar=$conexion->prepare(

            "INSERT INTO favoritos
            (usuario_id,playa_slug)
            VALUES(?,?)"

        );


        $guardar->execute([

            $usuarioId,
            $slug

        ]);



        echo json_encode([

            "favorito"=>true,

            "mensaje"=>"Playa agregada a favoritos"

        ]);



    }



}catch(PDOException $e){



    error_log($e->getMessage());


    http_response_code(500);


    echo json_encode([

        "error"=>"Error al guardar favorito"

    ]);

}


?>