<?php

if (!isset($index_loaded)) {
    die('Direct Access to file is forbidden');
}
class products
{
    public function Product_Display()
    {
        $product = ['id' => 0, 'name' => 'black dress', 'Description' => 'Little Black Evening dress', 'price' => '$99.99'];
        $page = new web_page();
        $page->title = 'Product'.$product['name'];
        $page->content = array_HTML_table($product);
        $page->render();
    }

    public function Products_List()
    {
        $DB = new db_pdo();
        $products = $DB->querySelect("select * from products"); //goto pdo_db page
//        $table_html = tableDisplay($products);
        $pageProducts = new web_page();
        $pageProducts->title = 'Products List';
        $pageProducts->content = tableDisplay($products);
        $pageProducts->render();
    }

    public function Products_Catalogue()
    {
        $search='';
        if(isset($_POST['search'])){
            $search=$_POST['search'];
        }
        $DB = new db_pdo();
        $patternChars = '/^[a-zA-Z]/';
        $patternNums = '/^[0-9]/';

        //$products = $DB->querySelect("select * from products where name = '$search' or description like '%$search%'");
        //$products = $DB->querySelect('select * from products where name like"%'.$_POST['search'].'%"');
        if(!$search == '' && preg_match($patternChars, $search)){
            $products = $DB->querySelect("select * from products where name = '$search' or description like '%$search%'");
        }
        else if(!$search == '' && preg_match($patternNums, $search)) {
            $sql_str = 'SELECT * from products where id = :id_input';
            $params = ['id_input' => $search];
            $products = $DB->querySelectParam($sql_str, $params);
            }
        else if($search == '') {
            $products = $DB->querySelect("select * from products ");
        }
        $r = '';
        $r = '<form action="index.php?op=111" method="POST" >';
        $r .= '<input class="form-control" type="text" name="search" requried style="width:300px;"  placeholder="Search" "><br>';
        $r .= ' <input class="btn btn-primary" type="submit" value="Continue" >';
        $r .= '</form>';

        foreach ($products as $key => $value) {
            if ($value['qty_in_stock'] > 0) {
                 $_SESSION['productId'] = $value['id'];
                $r .= '<div class="product">';
                $r .= '<img src="products_images/'.$value['pic'].'" alt="'.$value['description'].'">';
                $r .= '<p class="name">'.$value['name'].'</p>';
                $r .= '<p class="description">'.$value['description'].'</p>';
                $r .= '<p class="price">'.$value['price'].'</p>';
                $r .= '<a href="index.php?op=116&&id='.$value['id'].'">Edit</a>';
                $r .= '</div>';
            }
        }
        $productCataloguePage = new web_page();
        $productCataloguePage->title = 'Products Catalogue';
        $productCataloguePage->content = $r;
        $productCataloguePage->render();
    }

    public function searchDisplay($array)
    {
        $r = '';
        foreach ($array as $key => $value) {
            $r .= '<div class="product">';
            $r .= '<img src="products_images/'.$value['pic'].'" alt="'.$value['description'].'">';
            $r .= '<p class="name">'.$value['name'].'</p>';
            $r .= '<p class="description">'.$value['description'].'</p>';
            $r .= '<p class="price">'.$value['price'].'</p>';
            $r .= '</div>';
        }
        $productCataloguePage = new web_page();
        $productCataloguePage->title = 'Products Catalogue Search';
        $productCataloguePage->content = $r;
        $productCataloguePage->render();
    }

    public function ProductWebService() {
        $DB=new db_pdo();
        $products=$DB->querySelect("select * from products");
        $productsJson=json_encode($products,JSON_PRETTY_PRINT);
        $content_type='Content-Type:application/json;charset=UTF-8';
        header($content_type);
        http_response_code(200);
        
        echo $productsJson;//output the data in JSON format
    }
    
    public function edit($prev_val = [], $err_msg = '')
    {
        $id = $_GET['id'];
        $alert = '';
        if ($prev_val == []) {
            //set initial val , first time display
 
            $DB = new db_pdo();
            $sql_str = 'SELECT * from products where id = '.$id;
            $products = $DB->querySelect($sql_str);
            //var_dump($products);
            $prev_val = $products[0];
        }
        if ($err_msg == '') {
            $alert .= '   <div class="alert alert-primary">'.$err_msg.'</div>';
        } else {
            $alert .= ' <div class="alert alert-danger">'.$err_msg.'</div>';
        }
        $productPage = new web_page();
        $productPage->title = 'products Page';
        $productPage->content = <<< HTML
         <!-- <div class="alert alert-danger">{$err_msg}</div> -->
         {$alert}
        <form action="index.php?op=117" enctype="multipart/form-data" method="POST" style='width:40%' class="form-check">
        <input type="hidden"name="id"value={$id}>
        <fieldset>
            <!-- <input type="hidden" name="op" value="2"> -->
            <legend>Product Form </legend>
            <img src="products_images/{$prev_val['pic']}">
        <p>Add an image(max 500kb jpg,JPG,gif or png)
            <input type="file"  name="user_pic">
        </p>
            <label class="form-check-label" for="name">Name </label>
            <input type="text"  name="name" maxlength="50" requried placeholder="Product Name" class="form-control" value="{$prev_val['name']}"><br>
            <label class="form-check-label" for="description">Description</label>
            <input type="text"  name="description" maxlength="255" width="20"  placeholder="Description" class="form-control" value="{$prev_val['description']}"><br>
            <label class="form-check-label" for="price">Price</label>
            <input type="number" name="price"  min="1.01" step="0.01" placeholder="Price" class="form-control" value="{$prev_val['price']}"><br>
            <label class="form-check-label"  for="qty_in_stock">Quantity in Stock</label>
            <input type="number" name="qty_in_stock"  min="0" placeholder="Quantity" class="form-control" value="{$prev_val['qty_in_stock']}"><br>
            <input class="btn btn-primary" type="submit" value='save'  >
                </fieldset>
        </form>
        HTML;
 
        $productPage->render();
        // $r = 'SELECT email FROM products where id="'.$id.'"';
        // $record = $DB->querySelect($r);
    }
 
    public function save()
    {
        $result = Picture_Save_File('user_pic', 'products_images/');
        $file_name = basename($_FILES['user_pic']['name']);
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $qty_in_stock = $_POST['qty_in_stock'];
        $DB = new db_pdo();
        //$DB->queryUpdateData("update products set name='".$_POST['name']."', description='".$_POST['description'].
        // "', price='".$_POST['price']."', qty_in_stock='".$_POST['qty_in_stock']."' where id=".$id);
        $DB->query("UPDATE products SET name='$name',description='$description',price='$price',pic='$file_name',qty_in_stock='$qty_in_stock' WHERE id=".$_POST['id']);
        $validPage = new web_page();
        $validPage->title = 'List Products';
        $validPage->content = array_HTML_Products($DB->querySelect('SELECT * from products'));
        $validPage->render();
    }
}