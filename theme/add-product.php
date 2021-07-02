<?php 
include 'dbConection.php';
session_start();
if (!(isset($_SESSION["position"]) && $_SESSION["position"] == 2)){
      header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["nameProduct"])){
            header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
      }
      if (empty($_POST["priceProduct"])){
            header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
      }
      if (empty($_POST["descriptionProduct"])){
            header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
      }
      $name = $_POST["nameProduct"];
      $price = $_POST["priceProduct"];
      $description = $_POST["descriptionProduct"];
      $image_name = $_FILES['imageProduct']['name'];

      if (!file_exists(dirname(getcwd(),1)."\\assets\\Uploads")){
            mkdir(dirname(getcwd(),1)."\\assets\\Uploads", 0777);
      }

      
      move_uploaded_file($_FILES['imageProduct']['tmp_name'], dirname(getcwd(),1)."\\assets\\Uploads\\".$_FILES['imageProduct']['name']);

      if (exif_imagetype(dirname(getcwd(),1)."\\assets\\Uploads\\".$_FILES['imageProduct']['name']) == false){
            delete_files(dirname(getcwd(),1)."\\assets\\Uploads");
            header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            die();
      }

      $image_file = file_get_contents(dirname(getcwd(),1)."\\assets\\Uploads\\".$_FILES['imageProduct']['name']);
      $sql = "insert into product(name,price,description,nameImage,image)
       values(?,?,?,?,?)";

      $stmt = mysqli_prepare($conn,$sql);
      mysqli_stmt_bind_param($stmt, "sisss",$name,$price,$description,$image_name,$image_file);
      mysqli_stmt_execute($stmt);

      $check = mysqli_stmt_affected_rows($stmt);
      if($check==1){
            delete_files(dirname(getcwd(),1)."\\assets\\Uploads");
            header("Location: http://".$_SERVER['HTTP_HOST']."/theme/all-product.php");
            die();
      }else{
            delete_files(dirname(getcwd(),1)."\\assets\\Uploads");
            $msg = $stmt->error;
      }
      echo $msg;
      die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <title>LAPTOP STORE</title>
      <link href="/assets/image/favicon-16x16.png" rel="icon">

      <!-- Google Fonts -->
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet"> 
      
      <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="/assets/vendor/icofont/icofont.min.css" rel="stylesheet">

      <!-- Main Files CSS -->
      <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
      <header id="header">
            <div class="navbar navbar-expand-lg nabar-dark bg-dark px-3">
                  <div class="container-fluid">
                        <a class="navbar-brand d-block text-uppercase text-light text-center fs-4" href="/index.php">
                              <img src="/assets/image/favicon.png" class="img-fluid" width="40px" height="40px"> 
                              Laptop Store
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="icofont-navigation-menu" style="color: white;"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                              <ul class="navbar-nav mb-lg-0 ms-auto me-5 text-uppercase">
                                <li class="nav-item">
                                  <a class="nav-link text-light" aria-current="page" href="/index.php">Home</a>
                                </li>
                                <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Product
                                  </a>
                                  <ul class="dropdown-menu active" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="/theme/all-product.php">All Products</a></li>
                                    <li><a class="dropdown-item disabled" href="/theme/add-product.php" >Add Products</a></li>
                                  
                                  </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      User
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownUser">
                                      <li><?php if (isset($_SESSION["position"]) && isset($_SESSION["name"])){ ?>
                                          <a class="dropdown-item" href="/theme/logout.php">Logout</a>
                                      <?php }else{ ?>
                                          <a class="dropdown-item" href="/theme/login-form.php">Login</a>
                                      <?php } ?></li>
                                      <li><a class="dropdown-item" href="/theme/register-form.php">Register</a></li>
                                    </ul>
                                </li>
                              </ul>
                              <form class="d-flex">
                                <input class="form-control me-2" type="search" placeholder="Type something here" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit">Search</button>
                              </form>
                        </div>
                  </div>
            </div>
      </header>

      <section id="main-add-product" class="main-add-product">
            <div class="container">
                  <h3 class="text-uppercase">Add Product</h3>
                  <div class="add-product-form">
                        <form action="" method="POST" enctype="multipart/form-data">
                              <div class="row mb-3">
                                    <div class="col-2 col-sm-2">
                                          <label class="form-label text-uppercase fw-bold">Name</label>
                                    </div>
                                    <div class="col-10 col-sm-10">
                                          <input type="text" name="nameProduct" class="form-control"/>
                                    </div>
                              </div> 
                              
                              <div class="row mb-3">
                                    <div class="col-2 col-sm-2">
                                          <label class="form-label text-uppercase fw-bold">Price</label>
                                    </div>
                                    <div class="col-10 col-sm-10">
                                          <input type="text" name="priceProduct" class="form-control"></input>
                                    </div>
                              </div>

                              <div class="row mb-3">
                                    <div class="col-2 col-sm-2">
                                          <label class="form-label text-uppercase fw-bold">Image</label>
                                    </div>
                                    <div class="col-10 col-sm-10">
                                          <input type="file" name="imageProduct" class="btn btn-secondary text-uppercase"></input>
                                          <span class="fst-italic ms-3"></span>
                                    </div>
                              </div>

                              <div class="row mb-3">
                                    <div class="col-2 col-sm-2">
                                          <label class="form-label text-uppercase fw-bold">Description</label>
                                    </div>
                                    <div class="col-10 col-sm-10">
                                          <textarea class="form-control" name="descriptionProduct" rows="3"></textarea>
                                    </div>
                              </div>
                              <button type="submit" class="btn btn-secondary">Upload Product</button>
                        </form>
                  </div>
            </div>
      </section>
      
      <footer id="footer" class="bg-dark">
            <div class="container-fluid">
                  <div class="d-flex p-3 align-items-center">
                        <div class="footer-logo-site">
                              <img src="/assets/image/logo-site.png" class="img-fluid">
                        </div>
                        <div class="footer-title-site">
                              <p class="main-title">Laptop Store</p>
                              <p class="sub-title">The best quality laptop.</p>
                        </div>
                  </div>
            </div>
      </footer>

      <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>           
</body>
</html>