<?php
// Include database connection functions
include 'functions.php';
  $workerId = $_GET['id'];
// Fetch the worker details from the database based on the ID
if (isset($_GET['id'])) {
  

    // Connect to the database
    $pdo = getDBConnection();

    // Prepare SQL query to fetch worker details by ID
    $sql = "SELECT * FROM worker_list WHERE id = :workerId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':workerId', $workerId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the worker details
    $worker = $stmt->fetch(PDO::FETCH_ASSOC);
    
   

    if (!$worker) {
        // If the worker doesn't exist, show an error message
        echo "<p>Worker not found.</p>";
        exit;
    }
} else {
    // If the ID is not passed, show an error message
    echo "<p>Worker ID not provided.</p>";
    exit;
}
$pdo_visit = getDBConnection();
$sql_visit = "SELECT * FROM tbl_visit WHERE visited_by = :visited_by";
$stmt_visit = $pdo_visit->prepare($sql_visit);
$stmt_visit->bindParam(':visited_by', $workerId, PDO::PARAM_INT);
$stmt_visit->execute();
$visit_list = $stmt_visit->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Worker Details</title>
  <link rel="stylesheet" href="style_app.css">
  <style>
    /* Optional: Additional styling for the worker details */
    .worker-details-container {
      padding: 30px;
      background-color: #f9f9f9;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin: 20px auto;
      max-width: 800px;
    }

    .worker-details {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .worker-details img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      margin-right: 20px;
    }

    .worker-details div {
      flex: 1;
    }

    .worker-details h2 {
      margin: 0;
      font-size: 24px;
    }

    .worker-details p {
      margin: 5px 0;
      font-size: 16px;
    }

    /* Style the back link */
    .back-link {
      display: inline-block;
      margin-top: 20px;
      font-size: 16px;
      color: #007BFF;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="worker-details-container">
    <h1>Worker Details</h1>

    <div class="worker-details">
      <!-- Display worker image -->
      <img src="<?php echo !empty($worker['image']) ? htmlspecialchars($worker['image']) : 'images/worker.png'; ?>" alt="Worker Image">

      <div>
        <h2>ID: w<?php echo htmlspecialchars($worker['id']); ?></h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($worker['name']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($worker['address']); ?></p>
        <p><strong>Mobile:</strong> <?php echo htmlspecialchars($worker['mobile']); ?></p>
        <!-- Display other worker details if necessary -->
      </div>
    </div>



    <a href="worker_list.php" class="back-link">Backt</a>
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
            $shop_det = get_shop_details($activity['shop_id']);
            
            // Alternate background color based on counter
            $bg_color = ($counter % 2 == 0) ? 'lightblue' : 'lightgreen';
            $counter++;
        ?>
        
        <!-- Shop Visit Row -->
        <div class="visitRow" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; background-color: <?= $bg_color; ?>;">
          <p style="flex: 1;">
            <a href="shop-details.php?id=<?= $activity['shop_id']; ?>" style="color:black;"><?= $shop_det['name']; ?></a>
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
