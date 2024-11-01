<?php
/*
Plugin Name: Tungle.me WordPress Plugin
Plugin URI: http://wordpress.org/extend/plugins/tungleme-official-widget/
Description: This WordPress plugin displays the Tungle.me widget on your Wordpress site.
Author: Tungle Corporation
Version: 1.0.1
Author URI: http://www.tungle.me
*/

/*
* Installation instruction
* 1. Put this file in your wp-content/plugins directory
* 2. Go into wordpress admin->plugins and activate it
* 3. A yellow banner will confirm that the plug-in is activated but needs your tungle.me username to continue, click on the link provided in the yellow banner
* 4. Drag & drop the Tungle.me widget to a sidebar on the right hand side. 
* 5. Open the properties of the widget by clicking the arrow and enter your tungle.me username
* 6. You can customize the text that will appear before the tungle.me widget
* 7. Save and you are done!
*/


function tungleme_widget() {
	//This function renders the widget inside a wordpress blog sidebar.
	$data = get_option('tungleme_widget');
	if (!isset($data["heading"]))
		$data["heading"] = "Tungle.me";
	if (!isset($data["label"]))
		$data["label"] = "Book a meeting with me";
	if (isset($data["username"]))
	{
		echo '<li id="tunglemeWidget" class="widget widget_tungleme">';
		echo '<h3 class="widget-title">' . $data["heading"] . '</h3>';
		echo '<p>' . $data["label"] . '</p>';
		echo '<img src="https://www.tungle.me/public/' . $data["username"] . '/busyicon" class="tungle-me" teml="' . $data["username"] . '"/>';
		echo '</li>';
	}
}
 

function control_tunglemeWidget(){
	//This function provides what is needed to render the options pane in the Wordpress widget control panel
  $data = get_option('tungleme_widget');
	if (!isset($data["label"]))	//setting the default value
		$data["label"] = "Book a meeting with me";
	if (!isset($data["heading"]))
		$data["heading"] = "Tungle.me";
  ?>
  <p><label>Tungle.me Username<br><input name="tungleme_widget_username" type="text" value="<?php echo $data['username']; ?>" /></label></p>
  <p><label>Heading<br><input name="tungleme_widget_heading" type="text" value="<?php echo $data['heading']; ?>" /></label></p>
  <p><label>Label<br><input name="tungleme_widget_label" type="text" value="<?php echo $data['label']; ?>" /></label></p>
  <?php
   if (isset($_POST['tungleme_widget_username'])){
    $data['username'] = attribute_escape($_REQUEST['tungleme_widget_username']);
    $data['heading'] = attribute_escape($_REQUEST['tungleme_widget_heading']);
    $data['label'] = attribute_escape($_REQUEST['tungleme_widget_label']);
	//save the new options
    update_option('tungleme_widget', $data);
  }


}

function init_tunglemeWidget(){
	//Tell wordpress this plug-in is a widget
	register_sidebar_widget("Tungle.me", "tungleme_widget");     
	//Tell wordpress this plug-in has a control in the widget_control panel.
    register_widget_control('Tungle.me', 'control_tunglemeWidget');
	//Tell wordpress to load this JS in the queue..
	wp_enqueue_script("tunglemeWidget","https://www.tungle.me/portal/js/plugins/tungle.mwmWidget.js");

	  $data = get_option('tungleme_widget');
	if (!isset($data['username']) || $data["username"] == "") {
		function tungle_warning() {
			echo "
			<div id='tungle-warning' class='updated fade'><p><strong>".__('Tungle.me widget is almost ready.')."</strong> ".sprintf(__('You must  <a href="%1$s">enter your Tungle.me username</a> and add it to a sidebar for it to work. The widget will not appear unless you have completed this step.'), "widgets.php")."</p></div>
			";
		}
		add_action('admin_notices', 'tungle_warning');
	}

}

//When all plug-in are loaded, register various components used by this plug-in. 
add_action("plugins_loaded", "init_tunglemeWidget");
 
?>