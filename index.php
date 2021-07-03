<?php
include 'theme/dbConection.php';
session_start();
$array = array();
if (isset($_POST['search-term'])) {
      $postsTitle = "You searched for '" . $_POST['search-term'] . "'";
      $sql = "SELECT * FROM product WHERE name LIKE '%".$_POST['search-term']."%'";
      } else {
      $sql = "SELECT * FROM product";
}
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
      $array[] = array(
            'id_product' => $row['id_product'],
            'name' => $row['name'],
            'price' => $row['price'],
            'description' => $row['description'],
            'image' => $row['image']
      );
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
      
      <link href="/assets/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
      <link href="/assets/vendor/fontawesome/css/solid.css" rel="stylesheet">

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
                                  <a class="nav-link active text-light" aria-current="page" href="/index.php">Home</a>
                                </li>
                                <?php if (isset($_SESSION["position"]) && $_SESSION["position"] == 1){
                                ?>
                                <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Product
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="/theme/all-product.php">All Products</a></li>
                                    <li><a class="dropdown-item" href="/theme/add-product.php">Add Products</a></li>
                                  </ul>
                                </li>
                                <?php } ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      User
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownUser">
                                      <li><?php if (isset($_SESSION["name"]) && isset($_SESSION["position"])){ ?>
                                          <a class="dropdown-item" href="/theme/logout.php">Logout</a>
                                      <?php }else{ ?>
                                          <a class="dropdown-item" href="/theme/login-form.php">Login</a>
                                      <?php } ?></li>
                                      <li><a class="dropdown-item" href="/theme/register-form.php">Register</a></li>
                                    </ul>
                                </li>
                              </ul>
                              <form action="index.php" method="post" class="d-flex">
                                <input class="form-control me-2" type="text" name="search-term" placeholder="Type something here" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
                              </form>
                            </div>
                  </div>
            </div>
      </header>

      <section id="banner">
            <div class="page-banner"></div>
      </section>

      <section id="main" class="main">
            <div class="container-fluid px-3">
                  <div class="row">
                        <?php foreach ($array as $value) { ?>

                        <div class="col">
                              <div class="card border" style="width: 300px;">
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode( $value['image'] ); ?>" class="card-img-top" alt="Macbook Air 2019 Silver">
                                    <div class="card-body">
                                      <h5 class="card-title"><?php echo $value['name'] ?> </h5>
                                      <h4 class="card-text text-danger"><?php echo number_format($value['price'], 0, '', '.'); ?> VNĐ</h4>
                                      <p class="card-text mb-3"><?php echo $value['description'] ?></p>
                                      <a href="/theme/detail-product.php?id=<?php echo $value['id_product'] ?>" class="btn btn-outline-success">Get it</a>
                                    </div>
                              </div>
                        </div>

                        <?php } ?>
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
