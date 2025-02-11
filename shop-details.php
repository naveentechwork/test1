<?php 
 
include 'functions.php';

$id = $_GET['id'];

// Fetch the shop list from the database
$pdo = getDBConnection();
$sql = "SELECT * FROM shops WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$shops = $stmt->fetchAll(PDO::FETCH_ASSOC);
 

// Check if a shop exists
if (count($shops) == 0) {
    die("Shop not found.");
}

// Fetch the visit list from the database
$pdo_visit = getDBConnection();
$sql_visit = "SELECT * FROM tbl_visit WHERE shop_id = :shop_id";
$stmt_visit = $pdo_visit->prepare($sql_visit);
$stmt_visit->bindParam(':shop_id', $id, PDO::PARAM_INT);
$stmt_visit->execute();
$visit_list = $stmt_visit->fetchAll(PDO::FETCH_ASSOC);
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop Details</title>
   <link rel="stylesheet" href="style_app.css">
  <style>
    /* Style for the container and header */
    .container {
      padding: 20px;
    }

    h1 {
      text-align: center;
    }

    /* Style for the shop detail card */
    .shop-card {
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: relative;
      max-width: 400px;
      margin: 20px auto;
      text-align: center;
    }

    /* Style for the edit button */
    .edit-button {
      position: absolute;
      top: 10px;
      right: 10px;
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    .edit-button:hover {
      background-color: #0056b3;
    }

    /* Style for the activity cards */
    .activity-card {
      border: 1px solid #ddd;
      padding: 15px;
      margin: 10px 0;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-align: left;
    }

    .activity-card h4 {
      margin-bottom: 10px;
    }

    .activity-card p {
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <div class="container1">
    <h1>Shop Details<button class="edit-button" style="margin-top: 15px;font-size: 13px; background-color: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer; text-align: center;  " onclick="window.location.href='edit_shop.php?id=<?=$id?>';">Edit</button></h1>
<div id="shopDetails" style="display: flex; justify-content: center; padding: 20px; background-color: #f8f8f8;  ">
    
    
     
     
     
  <div class="shop-card" style="display: flex; align-items: center; background-color: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); max-width: 600px; width: 100%; margin: 10px;">
    <img src="<?php echo !empty($shops[0]['image']) ? 'uploads/'.htmlspecialchars($shops[0]['image']) : 'images/shop.png'; ?>" style="width: 80px; height: 80px; border-radius: 5%; margin-right: 10px;padding:5px;" alt="<?php echo htmlspecialchars($shops[0]['name']); ?>">
    <div class="shop-info" style="display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;text-align:left;">
      <h2 style="margin: 0; font-size: 22px; font-weight: bold; color: #333; padding-bottom: 10px;"><?php echo $shops[0]['name']; ?></h2>
      <p style="margin: 5px 0; font-size: 16px; color: #555;"><?php echo $shops[0]['address']; ?></p>
      <p style="margin: 5px 0; font-size: 16px; color: #555;"><?php echo $shops[0]['mobile']; ?></p>
     
    </div>
  </div>
</div>

 
    
    
  </div>
 
<div class="container1"> 
  <div class="shop-visits">
    <h3>Shop Visits</h3>
    <div id="shopvisits">
      
      <!-- Header Row with clickable Date Column -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <p style="flex: 1;">
          <strong>Shop</strong> 
        </p>
        <p style="flex: 0 0 auto; text-align: right;" id="dateHeader" onclick="sortVisitsByDate()">
          <strong>Date</strong> 
        </p>
      </div>
      
      <div id="visitList">
        <?php
        // Initial sorting by date_time
        usort($visit_list, function($a, $b) {
            return strtotime($a['date_time']) - strtotime($b['date_time']);
        });

        $counter = 0; // To alternate background colors
        foreach ($visit_list as $activity) {
            
            $worker_det = get_worker_details($activity['visited_by']);
           
           prin_r($worker_det);
            
            // Alternate background color based on counter
            $bg_color = ($counter % 2 == 0) ? 'lightblue' : 'lightgreen';
            $counter++;
        ?>
        
        <!-- Shop Visit Row -->
        <div class="visitRow" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; background-color: <?= $bg_color; ?>;">
          <p style="flex: 1;">
            <a href="shop-details.php?id=<?= $worker_det['shop_id']; ?>" style="color:black;"><?= $worker_det['name']; ?></a>
          </p>
          <p class="dateCell" style="flex: 0 0 auto; text-align: right;">
            <?= $activity['date_time']; ?>
          </p>
        </div>
        
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>

<script>
let sortAsc = true; // Boolean to track ascending/descending order

function sortVisitsByDate() {
    const rows = Array.from(document.querySelectorAll('.visitRow')); // Get all visit rows
    const visitList = document.getElementById('visitList');
    
    // Sort rows by date_time in ascending or descending order
    rows.sort((a, b) => {
        const dateA = new Date(a.querySelector('.dateCell').textContent);
        const dateB = new Date(b.querySelector('.dateCell').textContent);
        
        // Sort based on current order
        return sortAsc ? dateA - dateB : dateB - dateA;
    });
    
    // Re-attach sorted rows to the parent container
    rows.forEach(row => visitList.appendChild(row));
    
    // Toggle sorting order for next click
    sortAsc = !sortAsc;
    
    // Optional: Change the appearance of the Date column to show sort direction
    document.getElementById('dateHeader').textContent = `Date ${sortAsc ? '↑' : '↓'}`;
}
</script>

  
  <?php include 'footer.php';?>
</body>
</html>
