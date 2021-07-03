<?php 
include 'dbConection.php';
session_start();
if (!(isset($_SESSION["position"]) && $_SESSION["position"] < 2)){
      header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
}
$array = array();
$Title="All proudcts";
if (isset($_POST['search-term'])) {
      $Title = "You searched for '" . $_POST['search-term'] . "'";
      $sql = "SELECT * FROM product WHERE name LIKE '%".$_POST['search-term']."%'";
      } else {
      $sql = "SELECT * FROM product";
}
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
      $array[] = array(
            'id_product' => htmlspecialchars($row['id_product']),
            'name' => htmlspecialchars($row['name']),
            'price' => htmlspecialchars($row['price']),
            'description' => htmlspecialchars($row['description']),
            'image' => $row['image']
      );
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['idProduct']) && is_numeric($_POST['idProduct']) == 1){
            $sql = "DELETE FROM product WHERE id_product=". $_POST['idProduct'].";";
            if ($conn->query($sql) === TRUE) {
                  $myObj = array("action"=>"delete", "status"=>"OK");
            }else{
                  $myObj = array("action"=>"delete", "status"=>"ERROR","error"=>$conn->error);
            }
            echo json_encode($myObj);
            die();
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
      
      <link href="/assets/vendor/fontawesome/css/fontawesome.css" rel="stylesheet">
      <link href="/assets/vendor/fontawesome/css/solid.css" rel="stylesheet">

      <!-- Main Files CSS -->
      <link href="/assets/css/style.css" rel="stylesheet">
      <!-- Main Files JS -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="/assets/js/main.js"></script>
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
                                  <a class="nav-link active dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Product
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item disabled" href="/theme/all-product.php">All Products</a></li>
                                    <li><a class="dropdown-item" href="/theme/add-product.php">Add Products</a></li>
                                  </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      User
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownUser">
                                      <li><a class="dropdown-item" href="/theme/login-form.php">Login</a></li>
                                      <li><a class="dropdown-item" href="/theme/register-form.php">Register</a></li>
                                    </ul>
                                </li>
                              </ul>
                              <form action="all-product.php" method="post" class="d-flex">
                                <input class="form-control me-2" type="text" name="search-term" placeholder="Type something here" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
                              </form>
                            </div>
                  </div>
            </div>
      </header>

      <section id="main-all-product" class="main-all-product">
            <div class="container">
                  <h3 class="text-uppercase"><?php echo $Title ?></h3>
                  <div class="products-list">
                        <table class="table table-striped table-hover">
                              <thead>
                                    <tr class="table-dark text-uppercase">
                                          <td scope="col">#</td>
                                          <td scope="col">Name</td>
                                          <td scope="col">Image</td>
                                          <td scope="col">Description</td>
                                          <td scope="col"></td>     
                                    </tr>
                              </thead>
                              <tbody>
                                    <?php foreach ($array as $value) { ?>
                                    <tr id="laptop<?php echo $value['id_product']; ?>">
                                          <td><?php echo $value['id_product']; ?></td>
                                          <td><?php echo $value['name']; ?></td>
                                          <td>
                                                <img src="data:image/jpeg;base64,<?php echo base64_encode( $value['image'] ); ?>" width="100px" height="100px">
                                          </td>
                                          <td>
                                                <?php echo $value['description']; ?>
                                          </td>
                                          <td>
                                                <button onclick="Delete(<?php echo $value['id_product']; ?>)" class="btn btn-outline-warning text-uppercase me-2 mb-2 mb-md-0">Remove</button>
                                                <a href="./edit-product.php?id=<?php echo $value['id_product']; ?>"><button class="btn btn-outline-info text-uppercase">Edit</button></a>
                                          </td>
                                    </tr>
                                    <?php } ?>
                              </tbody>

                        </table>
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
