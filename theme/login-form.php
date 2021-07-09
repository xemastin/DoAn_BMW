<?php 
include 'dbConection.php';
session_start();
if ( isset($_SESSION['wrongTime']) && $_SESSION['wrongTime'] > 5 ) $_SESSION['blockTime'] = time();
if ( isset($_SESSION['blockTime']) && time() - $_SESSION['blockTime'] < 3600 ) {
      echo "Stop brute-force right now!!!";
      die();
}
if ( isset($_SESSION['blockTime']) && time() - $_SESSION['blockTime'] > 3600 ) {
      unset($_SESSION['blockTime']);
      unset($_SESSION['wrongTime']);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
      if (isset($_POST['username']))
            if (isset($_POST['password'])){
                  $username = hash("sha256",$_POST['username']);
                  $password = hash("sha256",$_POST['password']);

                  $sql = "SELECT * FROM user WHERE password='". $password ."' AND (SHA2(username,256)='".$username."' OR SHA2(email,256)='". $username ."');";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $_SESSION["name"] = $row["fullName"];
                        $_SESSION["position"] = $row["position"];
                        header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
                        die();
                  }else{
                        unset($_SESSION["name"]);
                        unset($_SESSION["position"]);
                        if ( isset($_SESSION['wrongTime']) ){
                              $_SESSION['wrongTime'] += 1;
                        }else{
                              $_SESSION['wrongTime'] = 1;
                        }
                  }
            }
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
                                <?php if (isset($_SESSION["position"]) && $_SESSION["position"] == 1){
                                ?>
                                <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Product
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="/theme/all-product.php">All Products</a></li>
                                    <li><a class="dropdown-item" href="/theme/add-product.php">Add Products</a></li>
                                    <li><a class="dropdown-item" href="/theme/detail-product.php">Details Product</a></li>
                                  </ul>
                                </li>
                                <?php } ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link active dropdown-toggle text-light" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      User
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownUser">
                                      <li><a class="dropdown-item disabled" href="/theme/login-form.php">Login</a></li>
                                      <li><a class="dropdown-item" href="/theme/register-form.php">Register</a></li>
                                    </ul>
                                </li>
                              </ul>
                            </div>
                  </div>
            </div>
      </header>

      <section id="main-login-form" class="main-login-form"> 
            <div class="login-form">
                  <div class="login-form-logo">
                        <img src="/assets/image/logo-site.png" width="80px" height="80px">
                        <p class="ms-3 text-uppercase fs-3">Laptop Store</p>
                  </div>
                  <div class="login-form-title">
                        <p>Sign into your account</p>
                  </div>
                  <form method="POST">
                        <input name="username" class="form-control mb-4" type="text" placeholder="Username/Email Address">
                        <input name="password" class="form-control mb-4" type="password" placeholder="Password"> 
                        
                        <button type="submit" class="btn btn-outline-success text-uppercase fw-bold w-100">Login</button>
                  </form>
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