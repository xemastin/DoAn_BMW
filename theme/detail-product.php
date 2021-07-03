<?php
include 'dbConection.php';
session_start();
$array = array();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['id']) && is_numeric($_GET['id']) == 1) {
        $sql = "SELECT * FROM product WHERE id_product=" . $_GET['id'] . "";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            //Lấy thông tin của sản phẩm
            $row = $result->fetch_assoc();
            $array[] = array(
                'id_product' => htmlspecialchars($row['id_product']),
                'name' => htmlspecialchars($row['name']),
                'price' => htmlspecialchars($row['price']),
                'description' => htmlspecialchars($row['description']),
                'image' => $row['image']
            );

            //Đọc comment của sản phẩm đang xem
            $sql = "SELECT * FROM comment WHERE idProduct=" . $_GET['id'] . " ORDER BY `idComment` DESC";
            $result =  mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
            $arrayComment[] = array(
                  'fullName' => htmlspecialchars($row['fullName']),
                  'contentComment' => htmlspecialchars($row['contentComment']),
                  'timeComment' => htmlspecialchars($row['timeComment'])
            );}

        } else {
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php");
            die();
        }
    } else {
        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php");
        die();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (empty($_POST['name'])){
        die();
    }
    if (empty($_POST['idProduct'])){
        die();
    }
    if (empty($_POST['comment'])){
        $_POST['comment'] = "Say nothing.";
    }
    $idProduct = $_POST['idProduct'];
    $fullName = $_POST['name'];
    $contentComment = $_POST['comment'];
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $timeComment = date('Y-m-d H:i:s');
    $sql = "insert into comment(idProduct,fullName,contentComment,timeComment)
       values(?,?,?,?)";

    $stmt = mysqli_prepare($conn,$sql);
    mysqli_stmt_bind_param($stmt, "ssss",$idProduct,$fullName,$contentComment,$timeComment);
    mysqli_stmt_execute($stmt);

    $check = mysqli_stmt_affected_rows($stmt);
    if($check==1){
        header("Location: http://".$_SERVER['HTTP_HOST']."/theme/detail-product.php?id=".$idProduct);
        die();
    }else{
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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
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
                            <a class="nav-link dropdown-toggle text-light active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Product
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php if (isset($_SESSION["position"]) && $_SESSION["position"] == 1) { ?>
                                    <li><a class="dropdown-item" href="/theme/all-product.php">All Products</a></li>
                                    <li><a class="dropdown-item" href="/theme/add-product.php">Add Products</a></li>
                                <?php } ?>
                                <li><a class="dropdown-item disabled" href="/theme/detail-product.php">Details Product</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                User
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownUser">
                                <li><?php if (isset($_SESSION["name"]) && isset($_SESSION["position"])) { ?>
                                        <a class="dropdown-item" href="/theme/logout.php">Logout</a>
                                    <?php } else { ?>
                                        <a class="dropdown-item" href="/theme/login-form.php">Login</a>
                                    <?php } ?>
                                </li>
                                <li><a class="dropdown-item" href="/theme/register-form.php">Register</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <section id="contentDetailProduct" class="content-detail-product">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-6">
                    <div class="product-images">
                        <!-- Product Large Image -->
                        <div class="tab-content product-preview-images order-2">
                            <div id="image1" class="tab-pane fade show active" role="tabpane" aria-labelledby="thumbnail1">
                                <img class="img-fluid" src="data:image/jpeg;base64,<?php echo base64_encode($array[0]['image']); ?>" alt="">
                            </div>
                            <div id="image2" class="tab-pane fade" role="tabpane" aria-labelledby="thumbnail2">
                                <img class="img-fluid" src="/assets/image/products-thumbnail/macbook-air-2018-13-inch-gray-thumbnail-01.png" alt="">
                            </div>
                            <div id="image3" class="tab-pane fade" role="tabpane" aria-labelledby="thumbnail3">
                                <img class="img-fluid" src="/assets/image/products-thumbnail/macbook-air-2018-13-inch-gray-thumbnail-02.png" alt="">
                            </div>
                        </div>
                        <!-- end Product Large Image -->
                        <!-- Product Thumbnail Image -->
                        <div class="product-thumbnails order-1">
                            <ul class="nav d-flex flex-column" role="tablist">
                                <li role="presentation">
                                    <a id="thumbnail1" class="product-thumbnail-item active d-block" href="#image1" data-bs-toggle="tab" data-bs-target="#image1" type="button" role="tab" aria-controls="image1" aria-selected="true">
                                        <img class="img-fluid" src="data:image/jpeg;base64,<?php echo base64_encode($array[0]['image']); ?>" alt="Product Thumbnail">
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a id="thumbnail2" class="product-thumbnail-item d-block" href="#image2" data-bs-toggle="tab" data-bs-target="#image2" type="button" role="tab" aria-controls="image2" aria-selected="false">
                                        <img class="img-fluid" src="/assets/image/products-thumbnail/macbook-air-2018-13-inch-gray-thumbnail-01.png" alt="Product Thumbnail">
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a id="thumbnail3" class="product-thumbnail-item d-block" href="#image3" data-bs-toggle="tab" data-bs-target="#image3" type="button" role="tab" aria-controls="image3" aria-selected="false">
                                        <img class="img-fluid" src="/assets/image/products-thumbnail/macbook-air-2018-13-inch-gray-thumbnail-02.png" alt="Product Thumbnail">
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- end Product Thumbnail Image List -->
                    </div>
                    <!-- end Product Image Preview -->
                </div>

                <!-- Product Information -->
                <div class="col-12 col-sm-12 col-lg-6">
                    <!-- Product Brand -->
                    <p class="fst-italic mb-1">Laptop Store</p>
                    <!-- Product Title -->
                    <h3 class="fw-bold fs-3 text-uppercase">
                        <a href="#"><?php echo $array[0]['name'] ?></a>
                    </h3>
                    <!-- Product Description -->
                    <div id="collapseParents" class="card-group-custom" role="tablist" aria-multiselectable="false">
                        <!-- Bootstrap Card -->
                        <article class="card card-custom card-corporate border-bottom">
                            <div class="card-header p-0 fs-5" role="tab">
                                <div class="card-title">
                                    <a id="cardHeadDescription" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseDescription" data-bs-parent="#collapseParents" role="button" aria-expanded="true" aria-controls="collapseDescription">
                                        Description
                                        <div class="card-arrow"></div>
                                    </a>
                                </div>
                            </div>
                            <div id="collapseDescription" class="collapse mt-3 pb-3" data-bs-parent="#collapseParents" role="tabpanel" aria-labelledby="cardHeadDelivery">
                                <div class="card-body p-0">
                                    <?php echo $array[0]['description'] ?>
                                </div>
                            </div>
                        </article>
                        <!-- Bootstrap card -->
                        <div class="card card-custom card-corporate border-bottom">
                            <div class="card-header p-0" role="tab">
                                <div class="card-title fs-5">
                                    <a id="cardHeadDelivery" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseDelivery" data-bs-parent="#collapseParents" role="button" aria-expand="false" aria-controls="collapseDelivery">
                                        Delivery
                                        <div class="card-arrow"></div>
                                    </a>
                                </div>
                                <div id="collapseDelivery" class="collapse mt-1 pb-3" data-bs-parent="#collapseParents" role="tabpanel" aria-labelledby="cardHeadDescription">
                                    <div class="card-body p-0">
                                        We deliver our goods worldwide. No matter where you live, your order will be
                                        shipped in time and delivered right to your door or to any other location you
                                        have stated.
                                        The packages are handled with utmost care, so the ordered products will be
                                        handed to you safe and sound, just like you expect them to be.
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Product Price -->
                    <div class="product-price mt-4">
                        <span class="product-price-new fw-bold text-content fs-2 text-danger"><?php echo number_format($array[0]['price'], 0, '', '.') ?> VNĐ</span>
                        <span class="product-price-old fs-5 text-secondary text-decoration-line-through"><?php echo number_format($array[0]['price'] + 2000000, 0, '', '.') ?>
                            VNĐ</span>
                    </div>
                    <!-- Product Sale -->
                    <div class="product-sale d-flex mt-4 align-items-center">
                        <div class="form-group d-flex align-items-center me-4">
                            <label class="me-3 text-uppercase fw-bold">Quantities:</label>
                            <div style="width:70px; height: 38px;">
                                <select class="w-100 h-100 fs-5 ps-1">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                </select>
                            </div>
                        </div>
                        <!-- Add to cart -->
                        <div class="button-field">
                            <button class="btn btn-outline-success text-uppercase fw-bold py-2">
                                <i class="fa fa-shopping-cart"></i>
                                Add to cart
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </section>

    <section class="content-item" id="comments">
        <div class="container">
            <div class="row bootstrap snippets bootdeys">
                <div class="col-md-8 col-sm-12">
                    <div class="comment-wrapper">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Comment panel
                            </div>
                            <div class="panel-body">
                                <form action="" method="post">
                                    <input type="text" hidden name="name" value="<?php if (isset($_SESSION["name"])) echo $_SESSION["name"];else echo "Anonymous" ?>">
                                    <input type="hidden" name="idProduct" value="<?php echo $_GET['id'] ?>">
                                    <textarea class="form-control" name="comment" placeholder="Write a comment..." rows="3"></textarea>
                                    <br>
                                    <button type="submit" class="btn btn-info pull-right">Post</button>
                                </form>
                                <div class="clearfix"></div>
                                <hr>
                                <ul class="media-list">
                                <?php if (isset($arrayComment)) foreach ($arrayComment as $comment) { ?>
                                    <div class="media">
                                        <img class="align-self-start mr-3" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_17a64e3cd9a%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_17a64e3cd9a%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.8359375%22%20y%3D%2236.5609375%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <h5 class="mt-0"><?php echo $comment["fullName"] ?> <span style="color: gray;font-size: 10px;">- <?php $date = new DateTime($comment["timeComment"]);echo $date->format("d/m/Y H:i:s") ?></span></h5>
                                            <p><?php echo $comment["contentComment"] ?></p>
                                            
                                        </div>
                                    </div>
                                <?php } ?>
    
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
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
