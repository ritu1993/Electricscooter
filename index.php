<?php

$index_loaded = true;
require_once 'web_page.php';
require_once 'tools.php';
require_once 'products.php';
require_once 'users.php';

$_SESSION['login_count'] = 0;
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = 0;
}
switch ($op) {
    case 0:
        homePage();
        break;
    case 7:
        //download word document test
        header('Content-type:application/ms-word');
        header('Content-Disposition: attachment;filename=HTTP_protocol.docx');
        // no break

    case 1:
            if ($_SESSION['login_count'] > 2 and isset($_SESSION['login_count'])) {
                $validPage = new web_page();
                $validPage->content = '<h2>Max login limit reached!!! Try after some time</h2>';
                $validPage->render();
                break;
            } else {
                $userObj = new users();
                $userObj->LoginPageDisplay();
                break;
            }

            // no break
    case 2:
        $user = new users();
        $user->LoginPageVerify();
        break;

    case 3:
        $new_user = new users();
        $new_user->RegisterFormDisplay();
        break;

    case 4:
        $new_user = new users();
        $new_user->RegisterFormVerify();
        break;

    case 5:
        $user = new users();
        $user->logout();
        break;
    case 50:
        DisplayServerErrorLogs();
        break;

    case 51:
        $DB = new db_pdo();
        $Users = $DB->querySelect('SELECT * from users');
        $HTML_table = table_display($Users); //ref tools.php
        $page = new web_page();
        $page->content = $HTML_table;
        $page->render();
        break;

    case 100:
        $prod = new products();
        $prod->Product_Display();
        break;

    case 110:
        $prod = new products();
        $prod->Products_List();
        break;

    case 111:
        $prod = new products();
        $prod->Products_Catalogue();
        break;
    
    case 115:
        $prod=new products();
        $prod->ProductWebService();
        break;
    case 116:
            $prod=new products();
            $prod->edit();
            break;  
                
    case 117:
            $prod=new products();
            $prod->save();
            break;

     

    default:
        //http_response_code(400);
      //  header('HTTP/1.0 400 Invalid op code in index.php');
        //echo 'Invalid op Code in index.php';
        //die();
        //header('location:http://www.isi-mtl.com');
       // homePage();
       crash(400, 'Invalid op cod in index.php');
        break;
}
function Product_Display()
{
    $product = ['id' => 0, 'name' => 'black dress', 'Description' => 'Little Black Evening dress', 'price' => '$99.99'];
    $page = new web_page();
    $page->title = 'Product'.$product['name'];
    $page->content = array_HTML_table($product);
    $page->render();
}
function homePage()
{
    $home_page = new web_page();
    $home_page->title = 'ElectronicScooter.com Home- Welcome';
    $home_page->content = '<h1>Welcome to home page</h1>';
    $home_page->render();
}

function DisplayServerErrorLogs()
{
    $page = new web_page();
    $page->title = 'Server error logs';
    $page->content = '';
    //get file content
    $page->content = file_get_contents('logs/errors.log');

    $page->render();
}

/*function DisplayInformation()
{
    $users = [
        ['id' => 0, 'email' => 'ritu@gmail.com', 'password' => '123456'],
        ['id' => 1, 'email' => 'ritzie@gmail.com', 'password' => '123456'],
    ];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if ($users['email'] == $_POST['email'] && $users['password'] == $_POST['password']) {
        echo'you have logged successfully.';
    } else {
        header('location:index.php?op=1');
    }
    //var_dump($_POST);
    //echo $email.'<br>';
    // echo $password.'<br>';
}
*/

//array_display($product);
//echo '<br>';
//$winning_numbers = [33, 46, 23, 11, 67];
//array_display($winning_numbers);

        //display home page
