<?php

if (isset($_POST['ajouter'])) {
    $add_tache = trim($_POST['ajouter']);
    if (!empty($add_tache)) {
        $sanitize = filter_var($add_tache, FILTER_SANITIZE_STRING);
        echo $sanitize;
    }
    else{
        echo "Veuillez ajouter une nouvelle tâche !";
    }
}
$jsonFichier ="todo.json"; //variable correspondant au fichier json
$jsonReceived = file_get_contents($jsonFichier);//variable reception données

$log = json_decode($jsonReceived, true); //decodage des données

if (isset($_POST['ajouter']) AND end($log)['nomtache'] != $_POST['tache']){ 
    $add_tache = $_POST['tache']; 
    $array_tache = array("nomtache" => $add_tache, 
                         "fin" => false);

    $log[] = $array_tache; 
    $json_enc = json_encode($log, JSON_PRETTY_PRINT); 

    file_put_contents($jsonFichier, $json_enc); 
    $log = json_decode($json_enc, true); 
   
}
$jsonFile = "todo2.json";
file_get_contents($jsonFile);

if (isset($_POST['button'])){ 
    $choix=$_POST['tache']; 
       
    for ($init = 0; $init < count($log); $init ++){         
        if (in_array($log[$init]['nomtache'], $choix)){      
                                                    
          $log[$init]['fin'] = true;                
        }
    }
    $json_enc = json_encode($log, JSON_PRETTY_PRINT);       
                                                                                                 
    file_put_contents($jsonFile, $json_enc);      
                                                    
    $log = json_decode($json_enc, true);              
}
/*
if (isset($_POST['supp']))
    
  if (!unlink($jsonFile))
    {
    echo ("Error deleting $jsonFile");
    }
        else
        {
        echo ("Deleted $file");
        }
  */  

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="todo.css" type="text/css" media="screen" charset="utf-8">    
        <title>My ToDo List</title>
    </head>
    <body>
        <main>
            <header>
                <h1>My ToDo List</h1>
            </header>
                <ul>
                    <li>
                    <legend><strong>Ajouter une tâche</strong></legend>
                    <form class="" action="formulaire.php" method="post">

                        <input type="text" name="tache" value="">
                        <input class="add" type="submit" name="ajouter" value="Ajouter">
                    </form>

                    <legend><strong>A faire</strong></legend>
                    <form action="formulaire.php" method="post" name="formafaire">
                        <?php
                            foreach ($log as $key => $value){
                                                               
                                if ($value["fin"] == false){  
                                    echo "<input type='checkbox' name='tache[]' value='".$value["nomtache"]."'/>
                                       <label for='choix'>".$value["nomtache"]."</label><br />"; 
                                }                                                                 
                            }                                                        
                        ?>
                        <input class="button" type="submit" name="button" value="ok" >
                    </form>

                    <section class="archive">
                    <legend><strong>Archive</strong></legend>
                    <form action="formulaire.php" method="post" name="formchecked">
                        <?php
                            foreach ($log as $key => $value){
                                if ($value["fin"] == true){
                                    echo "<input type='checkbox' name='tache[]' value='".$value."'checked/>
                                        <label for='choix'>".$value["nomtache"]."</label><br />";
                                }
                            }
                        ?>
                            <input class="supp" type="submit" name="supp" value="Supprimer">
                    </form>
                </li>
            </ul>
        </main>
    </body>
</html>