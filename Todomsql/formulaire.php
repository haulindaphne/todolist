<?php

function attribution($origine) {
    if (isset($_POST[$origine]) && !empty($_POST[$origine])) {
        return $_POST[$origine];
    }
}

//variables pour accès à la bd
$host = "localhost";
$username = "root";
$password = "root";
$dbname = "todolist";
// connection à la base de données 
try {
    $bd = new PDO("mysql:host=$host;dbname=todolist;charset=utf8", $username, $password);
   //echo "Connected successfully";
    }
catch (Exception $e)
    {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : ' . $e->getMessage());
    }
// rappelle la function 
    $item = attribution('taches');
 // sanitization des données   
    $itemSanit = filter_var($item, FILTER_SANITIZE_STRING);
    $itemSanit = trim($itemSanit);
    echo $item;
    $last = $bd->query('SELECT * from task where id = (select max(id) from task)');//selection des données de la table(!!!) en utilisant l'id
    $lastItems = $last->fetch();

// condition pour vérifier données sanit et voir si != taches
    if (!empty($itemSanit) && $itemSanit != $lastItems['taches']) {
        echo $itemSanit;
        $bd->query('INSERT INTO task(taches, archives) values("'.$itemSanit.'", "false")');//insertion de la nouvelle tâche dans la bd
    }
    $archives = $bd->query('SELECT taches from task where archives = "false"');//selection de la tache pour l'envoyer dans la partie archives (=valeur: false)
    if (isset($_POST['button']) && isset($_POST['list'])){ //quand on appuie sur le bouton ok 
        for ($i = 0 ; $i < count($_POST['list']); $i++){
            $bd->exec('UPDATE task SET archives = "true" WHERE taches = "'.$_POST['list'][$i].'"');//permet d'envoyer dans archives puisque la valeur est devenue true
        }
    }
    if(isset($_POST['supp']) &&  isset($_POST['delete'])) {//quand on appuie sur le bouton supprimer
        for ($a = 0; $a < count($_POST['delete']); $a++) {
            $bd->exec('DELETE from task WHERE taches = "'.$_POST['delete'][$a].'"');//suppression de la tâche de la bd avec la fonction DELETE
        }
    }
    $ok = $bd->query('SELECT taches from task WHERE archives = "false"');//selection des taches quand appuie sur bouton ok pour les envoyer vers archives
    $supprimer = $bd->query('SELECT taches from task WHERE archives = "true"');//selection des taches quand appuie sur supprimer pour les delete

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" media="screen" type="text/css" title="style" href="formulaire.css"/>

        <title>My ToDo List</title>
    </head>

    <body>
        <main>
            <div class="typewriter">
                <h1>ToDo List</h1>
            </div>
                
                <legend><strong>Ajouter une tâche</strong></legend>
                    <form class="" action="" method="post">
                        <label>
                            <input type="text" name="taches" value=""></label>
                            <input class="add" type="submit" name="ajouter" value="Ajouter">
                    </form>
            
                <legend><strong>A faire</strong></legend>
                    <form action="" method="post" name="formafaire">
                        <?php
                            $taskToDo = $ok->fetchAll();
                            foreach ($taskToDo as  $value) {
                            if ($value['archives'] = "false") {
                                echo '<label class="list"><input type="checkbox" name="list[]" value="'.$value['taches'].'">'.$value['taches'].'</label><br/>';
                                }
                            }
                        ?>
                        <input class="button" type="submit" name="button" value="ok" >
                    </form>
                    
                <section class="archives">
                <legend><strong>Archives</strong></legend>
                    <form action="" method="post" name="formchecked">
                        <?php
                            $doneArch = $supprimer->fetchAll();
                            foreach ($doneArch as $value) {
                            if ($value['archives'] = "true") {
                                echo '<label class="line"><input type="checkbox" name="delete[]" value="'.$value['taches'].'">'.$value['taches'].'</label><br/>';
                                }
                            }
                        ?>
                        <input class="supp" type="submit" name="supp" value="Supprimer">
                    </form>
        
                
        </main>
    </body>
</html>
<?php
/*
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
if (isset($_POST['button'])){ 
    $choix=$_POST['tache']; 
       
    for ($init = 0; $init < count($log); $init ++){         
        if (in_array($log[$init]['nomtache'], $choix)){      
                                                    
          $log[$init]['fin'] = true;                
        }
    }
    $json_enc = json_encode($log, JSON_PRETTY_PRINT);                                                                                               
  
    file_put_contents($jsonFichier, $json_enc);      
                                                    
    $log = json_decode($json_enc, true);              
}*/
?>
