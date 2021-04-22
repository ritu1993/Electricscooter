<!DOCTYPE html>
<html lang="<?=$this->lang; ?>">

<head>
    <meta charset="utf-8">
    <title> <?=$this->title; ?>
    </title>
    <meta name="descriptiom" value="<?=$this->description; ?>">
    <meta name="author" value="<?=$this->author; ?>">

    <link rel="icon" href="<?=$this->icon; ?>">
    <link rel="stylesheet" href="css/global.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
</head>

<body>
    <header>
        <img src="<?=$this->icon; ?>"
            alt="<?=COMPANY_NAME; ?>" />
        <?=WEB_SITE_NAME; ?>

    </header>
    <nav>
        <ul class=nav>
            <li><a class="navdata" href="index.php">Home</a></li>
            <li><a class="navdata" href="index.php?op=110">Product List</a></li>
            <li><a class="navdata" href="index.php?op=111">Product Catalogue</a></li>

            <li><a class="navdata" href="index.php?op=3">Register</a></li>
            <?php
            if (isset($_SESSION['level'])) {
                if ($_SESSION['level'] == 'employee') {
                    echo' <li><a class="navdata" href="index.php?op=50">Error Logs</a></li>';
                }
            }
            if (isset($_SESSION['user_name'])) {
                echo"<li><a  href='index.php?op=5'>Logout</a></li>";
                echo'<li style="color:white;padding:10px; position:absolute;right:40px;text-transform: uppercase;">'.$_SESSION['user_name'].'<li>';
            } else {
                echo'  <li><a class="navdata" href="index.php?op=1">Login</a></li>';
            } ?>
        </ul>
    </nav>
    <main>
        <?= $this->content; ?>
    </main>
    <footer>
        THIS IS THE FOOTER
        <a href="index.php?op=7">Download Word Doc
    </footer>

</body>


</html>