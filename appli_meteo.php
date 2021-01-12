<?php
/*
  Plugin Name: appli_meteo
  Description: extension permettant d'accéder à la météo de Prévenchères, magnifique village accueillant le chateau de Neuschwanstein.
  Version: 1.0
  Author: Farfadet30
*/

function SWP_pluginmeteo_btn() {
	
	$info = '<a href="http://api.openweathermap.org/data/2.5/weather?q=Prévenchères&lang=fr&APPID=56549b88c5fd57a3e20c5ec80312cb51
	"target="_blank" class="pluginmeteo">
	</a> ' ;
	echo $info;
	
}
add_action('wp_footer','SWP_pluginmeteo_btn');

// Register style sheet.
add_action('wp_enqueue_scripts','swp_register_plugin_styles');

function swp_register_plugin_styles() {
  wp_register_style('meteo-style',plugins_url('pluginmeteo/meteo.css'));
  wp_enqueue_style('meteo-style');
}
add_action( 'wp_footer', 'meteo' );

function meteo() {
    $curl = curl_init ('http://api.openweathermap.org/data/2.5/weather?q=Prévenchères&lang=fr&APPID=56549b88c5fd57a3e20c5ec80312cb51');
      
    // transmission d'options pour la transmission curl car possibilité de sortir une erreur
    // Va indiquer à curl de ne pas vérifier les connexions (désactiver la vérification SSL)
    // setopt se trouve juste avant l'execution

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Exécuter ce qu'on a mis en curl_init (renvoie vrai ou faux)

    $data = curl_exec($curl);

    // On vérifie si data = false => si c'est false c'est qu'il y a une erreur
    // On va vérifier cette erreur

  if($data === false){
      var_dump(curl_error($curl));
  }
  else{
    $data = json_decode($data,true);
    $weather = $data['weather'][0]['description'];
    $temp = $data['main']['temp']- 273.15;

        echo "
        <div class='meteo'>
            <h2>Météo du moment</h2>
            <h3>Il fait actuellemnt $temp °c</h3>
            <h3>$weather</h3>
            </div>
        ";
  }

    // On ferme la session ce qui permet de libérer la mémoire 

curl_close($curl);
}


?>
