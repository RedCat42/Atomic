<?php
# Script Name	: watch.php
# Description	: Affichage d'une vidéo et de ses informations
# Author      : Léo Delobel
# URL         : http://176.166.235.56/watch.php
  include("header.php");
  include("php/init_sql.php");
 ?>

 <div class="video-container">
   <div class="video">
     <?php
      $statement = $DATABASE->prepare("SELECT * FROM video WHERE id_video = ?");
      $statement->execute(array($_GET["id_video"]));

      $video = $statement->fetchAll()[0];
      # La vidéo se trouve dans res/videos/[ID_VIDEO].mp4

      $statement = $DATABASE->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
      $statement->execute(array($video["id_utilisateur"]));

      $auteur = $statement->fetchAll()[0];
      ?>

      <video controls>
        <source src="res/videos/<?php echo $video["id_video"];?>.mp4" type="video/mp4">
      </video>
   </div>

   <div class="video-meta">
     <p>
       <?php
       echo $video["nombre_vues"] . ' vues  •  ';
       echo $video["date_publication"] . '  •  👍';
       echo $video["nombre_likes"];?></p>
     <hr>
     <p>Posté par <?php echo $auteur["pseudonyme"];?></p>
     <hr>
     <h4>Description</h4>
     <p><?php echo $video["description"];?></p>
   </div>

   <div class="commentaires">
   </div>
 </div>