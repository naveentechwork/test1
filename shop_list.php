<?php 
// Include database connection functions
include 'functions.php';

// Fetch the shop list from the database
$pdo = getDBConnection();

// Prepare SQL query without search functionality
$sql = "SELECT * FROM shops ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$shops = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total shop count
$totalShops = count($shops);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop List</title>
  <link rel="stylesheet" href="style_app.css">
  <style>
 
  /* Style for each shop item */
.shop-item {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  padding: 10px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease;
}

.shop-item:hover {
  transform: scale(1.05); /* Slight hover effect */
}

/* Container for the image and text */
.shop-content {
  display: flex;
  align-items: center;
  width: 100%;
}

/* Shop image styling */
.shop-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  margin-right: 20px; /* Space between image and text */
  border-radius: 50%; /* Optional: makes image circular */
}

/* Text container for shop details */
.shop-details {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

/* Style for the shop name */
.name {
  font-size: 18px;
  font-weight: bold;
  color: #333;
}

/* Style for the shop address */
.address {
  font-size: 14px;
  color: #777;
}

  
 
  </style>
</head>
<body>
  <div class="">
    <!-- Header Section -->
    <h1 style="display: flex; justify-content: space-between; align-items: center;padding-left:30px;">
      Shop List 
      <a href="add_shop.php" style="margin-left: auto; padding: 5px 15px; font-size: 20px; background-color: #4CAF50; color: white; border: none; border-radius: 50%; cursor: pointer;margin-right:10px;text-decoration: none;">+</a>
    </h1>

    <!-- Search Section -->
    <div class="header" style="padding:10px;">
      <input type="text" id="searchInput" placeholder="Search all fields..." class="search-input" />
    </div>

 <div class="total-shops">
        <p style="margin-left:20px;">Total Shops: <span id="shopCount"><?php echo $totalShops; ?></span></p>
      </div>

    <div style="background-color:lightgrey;border-radius: 5px;padding:15px;">
      <!-- Total Shops Display -->
     

      <!-- Shop List -->
      <div id="shopList" class="shop-list">
        <?php foreach ($shops as $shop): ?>
          <a style="text-decoration: none;" href="shop-details.php?id=<?php echo $shop['id']; ?>" class="shop-link">
            <div class="shop-item" data-name="<?php echo htmlspecialchars($shop['name']); ?>" data-address="<?php echo htmlspecialchars($shop['address']); ?>">
              <div class="shop-content">
                <img src="<?php echo !empty($shop['image']) ? 'uploads/'.htmlspecialchars($shop['image']) : 'images/shop.png'; ?>" alt="Shop image" style="padding:5px;">
                <div class="shop-details">
                  <div class="name"><?php echo htmlspecialchars($shop['name']); ?></div>
                  <div class="address"><?php echo htmlspecialchars($shop['address']); ?></div>
                </div>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    // JavaScript to filter the shops based on the search input
    document.getElementById('searchInput').addEventListener('input', function(event) {
      const query = event.target.value.toLowerCase();
      const shopItems = document.querySelectorAll('.shop-item');
      
      shopItems.forEach(shop => {
        const shopName = shop.getAttribute('data-name').toLowerCase();
        const shopAddress = shop.getAttribute('data-address').toLowerCase();
        
        if (shopName.includes(query) || shopAddress.includes(query)) {
          shop.style.display = '';
        } else {
          shop.style.display = 'none';
        }
      });
      
      // Update the shop count based on the visible shops
      const visibleShops = document.querySelectorAll('.shop-item[style="display: none;"]');
      document.getElementById('shopCount').textContent = shopItems.length - visibleShops.length;
    });
  </script>
</body>
</html>
