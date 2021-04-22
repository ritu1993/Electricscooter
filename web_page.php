<?php
session_start();
if (!isset($index_loaded)) {
    die('Direct access to this file is forbidden');
}
require_once 'global_defines.php';
/**
 * class web_page is used to output the HTML/CSS web page.
 */
class web_page
{
    public $title = PAGE_DEFAULT_TITLE;
    public $description = PAGE_DEFAULT_DESCRIPTION;
    public $author = PAGE_DEFAULT_AUTHOR;
    public $lang = PAGE_DEFAULT_LANGUAGE;
    public $icon = PAGE_DEFAULT_ICON;

    public $content = '';
    const MAC_CONTENT_SIZE = 3000;

    /**
     * the constructor.
     */
    public function __construct()
    {
    }

    /**
     * displays the web page.
     */
    public function render()
    {
        if ($this->content == '') {
            crash(500, 'sorry we have a problem');

            //error, no content was set
          // http_response_code(500); //internal server error
           // echo 'sorry we have a problem';
            //send email to server admin
            //mail(ADMIN_EMAIL, 'Error in web_page.php', 'No content  \'set in function render(), file web_page.php');
           // die('Sorry we have problem'); //stop program
        }
        if ($this->lang == 'en-CA') {
            require_once 'template.php';
        } elseif ($this->lang == 'fr-CA') {
            require_once 'template_fr.php';
        } else {
            crash(400, 'Language not supported');
        }
        die(); //stop program here
    }

    //end of render()
}//end of class
