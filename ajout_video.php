<?php
# Script Name	: ajout_video.php
# Description	: Page d'ajout de video
# Author      : Léo Delobel
# URL         : http://176.166.235.56/ajout_video.php
 ?>
<link rel="stylesheet" href="css/style_ajout_video.css"/>
 <?php
// On affiche le header
require 'header.php';
require_once("php/class_categorie.php");
require_once("php/class_video.php");


if(isset($_POST["ajout"])){
  // Demande d'ajout de vidéo
  $dir_miniatures = "/var/www/atomic/res/miniatures/";
  $dir_videos = "/var/www/atomic/res/videos/";
  $img_type = substr($_FILES['miniature']['type'], 6);
  $vid_type = substr($_FILES['video']['type'], 6);

  if(
    $_SESSION["auth"] && // Si l'utilisateur est connecté
    is_numeric($_POST["categorie"]) && // Si l'id catégorie est un nombre
    in_array($_POST["categorie"], CategorieManager::GetIDs()) // Et fait partie des id existants
  ){
    // AddVideo($id_utilisateur, $id_categorie, $titre, $description)
    $SQL = VideoManager::AddVideo(
      $_SESSION["id_utilisateur"],
      $_POST["categorie"],
      $_POST["titre"],
      $_POST["description"],
      $img_type,
      $vid_type);
    if($SQL["success"]){
        // Si l'ajout SQL s'est bien passé
        if($_FILES['miniature']['size'] <= (1024 * 1024 * 12)
        && $_FILES['video']['size'] <= (1024 * 1024 * 64)){
          // Si la miniature fait moins de 12Mo
          // Et la vidéo moins de 64Mo
          $path_miniature = $dir_miniatures . $SQL["id_video"] . '.' . $img_type;
          $path_video = $dir_videos . $SQL["id_video"] . '.' . $vid_type;
          if (move_uploaded_file($_FILES['miniature']['tmp_name'], $path_miniature)
          && move_uploaded_file($_FILES['video']['tmp_name'], $path_video)) {
            echo "La miniature a été téléchargée avec succès\n";
          } else {
            echo "Erreur de téléchargement :\n";
            print_r($_FILES);
            // Si ca ne marche pas, on supprime la vidéo
            VideoManager::RemoveVideo($SQL["id_video"]);
          }
        } else {
          // Si ca ne marche pas, on supprime la vidéo
          VideoManager::RemoveVideo($SQL["id_video"]);
        }
      }
  }
}

 // Si l'utilisateur est connecté
 if($_SESSION["auth"]){
  ?>

<form enctype="multipart/form-data" action="" method="post" class="uploadform">
    <h3>Téléverser une vidéo</h3>
  <input type="text" name="titre" class="inputtext" placeholder="Titre" required>


  <textarea type="text" name="description" class="inputtext" placeholder="Description" required></textarea>

  <select name="categorie" class="inputtext">
    <?php
      foreach(CategorieManager::GetAll() as $c){
        echo '<option value="' . $c->id_categorie . '">' . $c->description . '</option>';
      }
     ?>
  </select>

  <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
  <p>Miniature (JPG, JPEG, GIF, PNG) :</p>
  <input name="miniature" type="file" class="fichier"/>
  <p>Video (MP4 de préférence) :</p>
  <input name="video" type="file" class="fichier"/>

  <input type="submit" name="ajout" value="Upload" class="televerser"/>
</form>

  <?php
} else {
  // Sinon
 ?>

<p>Vous n'êtes pas connecté !</p>

 <?php
}
  ?>
