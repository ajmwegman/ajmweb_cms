<?php
@session_start();

// Set error reporting
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

// Include required files
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . "/system/database.php");
require_once($path . "/admin/src/database.class.php");

$db = new database($pdo);

// Define success and error messages
$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

$site_id = $_SERVER['group_id'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['checkboxes'])) {

        // Delete existing language links for the site
        $delete_languages = $db->deletedata("language_link", "site_id", $site_id);

        // Get the selected checkboxes
        $languages = $_POST['checkboxes'];

        // Insert the selected languages into the database
        foreach ($languages as $language) {

            $values = array(
                'site_id' => $site_id,
                'locale' => $language
            );

            $go = $db->insertdata("language_link", $values);

            if ($go == true) {
                echo $success;
            } else {
                echo $error;
            }
        }
    } else {
        // If no checkboxes were selected
        echo $error;
    }

    echo $success;
}
?>