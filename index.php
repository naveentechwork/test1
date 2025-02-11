<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="style_app.css">
    <style>
     
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px; /* Space between cards */
            width: 100%;
            max-width: 600px; /* Limit the max width of the container */
            
        }
        .card {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }
        .card img {
            width: 100%;
            max-width: 100px;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1 style="text-align:center;color:black;background-color:white;padding:20px;">Management App</h1>
    
    <div class="container">
       
        <div class="card" onclick="window.location.href='shop_list.php';">
            <img src="images/shop.png" alt="Card Image 2">
            <div class="card-content">
                <h4>Shop</h4>
            </div>
        </div>
        <div class="card" onclick="window.location.href='worker_list.php';">
            <img src="images/worker.png" alt="Card Image 3">
            <div class="card-content">
                <h4>worker</h4>
            </div>
        </div>
       
    </div>

    <?php include 'footer.php'; ?>

    <script src="scripts.js"></script>
</body>
</html>
