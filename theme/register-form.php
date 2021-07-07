<?php
include 'dbConection.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["fullName"])) {
            echo "Error";
            die();
      }
      if (empty($_POST["username"])) {
            echo "Error";
            die();
      }
      if (empty($_POST["email"])) {
            echo "Error";
            die();
      }
      if (empty($_POST["password"])) {
            echo "Error";
            die();
      }
      if (empty($_POST["re_password"]) || $_POST["password"] !== $_POST["re_password"]) {
            echo "Error";
            die();
      }
      
      $sql = "SELECT * FROM user WHERE email=? OR username=?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "ss",$_POST["email"], $_POST["username"]);
      mysqli_stmt_execute($stmt);
      if (mysqli_stmt_num_rows($stmt) != 0) 
      {
        $message = "Username hoặc Email đã có người sử dụng";
      }
      else 
      {
            $sql = "insert into user(fullName,email,username,password,position) values(?,?,?,?,2)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $_POST["fullName"], $_POST["email"], $_POST["username"], hash("sha256", $_POST['password']));
            mysqli_stmt_execute($stmt);
            $check = mysqli_stmt_affected_rows($stmt);

            if ($check === 1) {
                  $_SESSION["name"] = $_POST["fullName"];
                  $_SESSION["position"] = 2;
                  header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php");
                  die();
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
      <!--valid confirm password-->
      <script src="/assets/vendor/jquery/jquery.min.js"></script>
      <script src="/assets/vendor/jquery/jquery.validate.min.js" type="text/javascript"></script>

      <script>
            $().ready(function() {
                  $("#register").validate({

                        rules: {
                              fullName: {
                                    required: true,
                                    minlength: 5
                              },
                              email: {
                                    required: true,
                                    email: true
                              },
                              username: {
                                    required: true,
                                    minlength: 5
                              },
                              password: {
                                    required: true,
                                    minlength: 5
                              },
                              re_password: {
                                    required: true,
                                    minlength: 5,
                                    equalTo: "#password"
                              }
                        },
                  })
            })
      </script>
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
                                    <?php if (isset($_SESSION["position"]) && $_SESSION["position"] == 1) {
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
                                                <li><a class="dropdown-item" href="/theme/login-form.php">Login</a></li>
                                                <li><a class="dropdown-item disabled" href="/theme/register-form.php">Register</a></li>
                                          </ul>
                                    </li>
                              </ul>
                        </div>
                  </div>
            </div>
      </header>

      <section id="main-register-form" class="main-register-form">
            <div class="register-form">
                  <div class="register-form-logo">
                        <img src="/assets/image/logo-site.png" width="80px" height="80px">
                        <p class="ms-3 text-uppercase fs-3">Laptop Store</p>
                  </div>

                  <div class="register-form-title">
                        <p>Create your account</p>
                  </div>

                  <form id="register" method="POST" action="" onsubmit="return stopSubmit()">
                        <input class="form-control mb-3" type="text" name="fullName" placeholder="Fullname">
                        <input class="form-control mb-3" type="email" id="email" name="email" placeholder="Email Address">
                        <input class="form-control mb-3" type="text" name="username" placeholder="Username">
                        <input class="form-control mb-3" name="password" id="password" type="password" placeholder="Password">
                        <input class="form-control mb-3" name="re_password" id="re_password" type="password" placeholder="Repeat Password">
                        <?php if (isset($message)) { ?> <div class="alert alert-danger" role="alert"><?php echo $message ?></div><?php } ?>
                        <button class="btn btn-outline-success text-uppercase fw-bold w-100">Sign Up</button>
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