<?php
# Script Name	: miniature.php
# Description	: Donne une div miniature à partir d'un id vidéo ($id)
# Author      : Léo Delobel
    function printMiniature($id_video){
      include("init_sql.php");
      $statement = $DATABASE->prepare("SELECT * FROM video WHERE id_video = ?");
      $statement->execute(array($id_video));

      $video = $statement->fetchAll()[0];

      $statement = $DATABASE->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
      $statement->execute(array($video["id_utilisateur"]));

      $auteur = $statement->fetchAll()[0];

      ?>

      <a href="watch.php?id_video=  <?php echo $video['id_video'] ?>">
        <span class="miniature">
          <img class="miniature_img" src="res/miniatures/<?php echo $video["id_video"] ?>.jpg">
          <div class="miniature_meta">
            <p class="miniature_titre"> <?php echo $video["titre"] ?></p>
            <p class="miniature_vues"> <?php echo $video["nombre_vues"] ?> vues</p>
            <p class="miniature_auteur"> <?php echo $auteur["pseudonyme"] ?></p>
          </div>
        </span>
      </a>

      <?php
    }
 ?>