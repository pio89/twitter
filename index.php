<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
	</head>
	<body>
		<?php
		require 'auxiliar.php';

    $errores = array();
		$con = conectar();





    /* CREAR DOS SESIONE EL ID Y EL NICK*/
		$_SESSION['id'] = 1; 

		if (isset($_SESSION['id']))
    {
      $id_usuario = (int) trim($_SESSION['id']);
    }

		$res = pg_query($con, "select * from usuarios where id= $id_usuario");

		if (pg_num_rows($res) > 0)
    {
    	$fila = pg_fetch_assoc($res, 0);
    	$_SESSION['nick'] = $fila['nick'];
  	}	
/********************************************************/
/*CABECERA CON EL USUARIO CONECTADO */
    ?>


      <form action="index.php" method="get">
      <p align="right">Usuario: <strong><?= $_SESSION['nick'] ?></strong>
        <input type="submit" name="salir" value="Salir" />
      </p>
    </form>
    
    <hr/>


  <?php
    /***********************************************/




/* FUNCIONES */


    function comprobar_tuit_insertado($res)
    {
      global $errores;

      if ($res == FALSE || pg_affected_rows($res) != 1)
      {
        $errores[] = "Error, no se ha podido insertar el tuit";
        comprobar_errores();
      }
    }




    function comprobar_errores()
    {
      global $errores;
      
      if (!empty($errores))
      {
        throw new Exception();
      }
    }



    function validarTuit($tuit)
    {
      global $errores;

      if (strlen($tuit) >140 || $tuit == "" || strlen($tuit) < 1)
      {
        $errores[] = "El tuit no es valido";
      }
     
    }




    function mostrarTuit($id_usuario)
      {
        global $con;
        $res = pg_query($con, "select t.id, u.nick, t.mensaje, t.fecha from usuarios u, tuit t where u.id = t.id_usuarios and u.id= $id_usuario");

     
        if (pg_num_rows($res) > 0)
        {
        

       
       
        $cols = array_keys(pg_fetch_assoc($res, 0)); 


        ?>
        
        </br>
          <div>
          <h3>Tus tuits</h3><?php
          for ($i = 0; $i < pg_num_rows($res); $i++)
          {
               $fila = pg_fetch_array($res, $i);
            ?>
            <div style="padding-left: 20px; ">
   
    
          
          <p><span style="font-weight:bold;"><?= $fila['nick'] ?></span>&nbsp&nbsp<?= $fila['fecha'] ?></p>
          <?= $fila['mensaje'] ?> 
          <form method="post" action="index.php">
          <input type="hidden" name="id" value="<?= $fila['id'] ?>" />
          <input type="submit" value="Borrar" />
          </form>
        
          </div>
          <br/><br/><br/>

          <?php
          }
        
          ?>
           </div>          
                    
                 
        <?php
       
        }
        else
        { 

          echo 'No tienes ningun tuis';
        }
  
      }


      function borrar_tuit($id)
      {
          global $con;
           $res = pg_query($con, "delete from tuit where id::text='$id'");
      }

























    if(isset($_GET['tuit']))
    {
      $tuit = trim($_GET['tuit']);

      /* comprobaciones*/

      try {
    
        
        validarTuit($tuit);

        comprobar_errores();

        $con   = conectar();
        $res   = pg_query($con, "begin");
        $res   = pg_query($con, "lock table tuit in share mode");
        $res = pg_query($con, "insert into tuit(mensaje, id_usuarios) values
                                ('$tuit', $id_usuario )");
        comprobar_tuit_insertado($res); 
        } catch (Exception $e) {
        foreach ($errores as $error): ?>
          <p>Error: <?= $error ?></p><?php
        endforeach;
       }finally {
        $res = pg_query($con, "commit");
        }
    
    }
    
      else
      {
        $tuit = "";
      }

      


      ?>

   




  	
		

  


    


		
		

		<center>
		<h1>TWITTER</h1>
		<h3>Perfil de: <?= $_SESSION['nick'] ?></h3>
		<form method="get" action="index.php">
			<textarea cols="50" rows="10" name="tuit" value="<?= $tuit ?>" maxlength="140" placeholder="Escribir el tuit..."></textarea>

			<input type="submit" value="Twittear">
		</form>
		</center>

		


      <?php
    

     

      if(isset($_POST['id']))
      {
        $id = trim($_POST['id']);
        borrar_tuit($id);
      }


       mostrarTuit($id_usuario);



      



        
        


      ?>

		

		  
	





	</body>
</html>