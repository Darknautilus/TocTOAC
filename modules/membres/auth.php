<?php

if(!isLogged()) {
  echo $twig->render("membres_auth.html", array("message" => "It works" ));
}
else {
  echo $twig->render("index_show.html", array());
}