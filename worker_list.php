<?php 
// Include database connection functions
include 'functions.php';

// Fetch the worker list from the database
$pdo = getDBConnection();

// Prepare SQL query without search functionality
$sql = "SELECT * FROM worker_list ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$workers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total worker count
$totalWorkers = count($workers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Worker List</title>
  <link rel="stylesheet" href="style_app.css">
  <style>
    /* Style for each worker item */
    .worker-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      padding: 10px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease;
    }

    .worker-item:hover {
      transform: scale(1.05); /* Slight hover effect */
    }

    /* Container for the image and text */
    .worker-content {
      display: flex;
      align-items: center;
      width: 100%;
    }

    /* Worker icon styling */
    .worker-icon {
      width: 50px;
      height: 50px;
      object-fit: cover;
      margin-right: 20px; /* Space between image and text */
      border-radius: 50%; /* Optional: makes image circular */
    }

    /* Text container for worker details */
    .worker-details {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    /* Style for the worker name */
    .name {
      font-size: 18px;
      font-weight: bold;
      color: #333;
    }

    /* Style for the worker address */
    .address {
      font-size: 14px;
      color: #777;
    }
  </style>
</head>
<body>
  <div class="">
    <!-- Header Section -->
    <h1 style="display: flex; justify-content: space-between; align-items: center; padding-left:30px;">
      Worker List 
      <a href="add_worker.php" style="margin-left: auto; padding: 5px 15px; font-size: 20px; background-color: #4CAF50; color: white; border: none; border-radius: 50%; cursor: pointer; margin-right:10px; text-decoration: none;">+</a>
    </h1>

    <!-- Search Section -->
    <div class="header" style="padding:10px;">
      <input type="text" id="searchInput" placeholder="Search all fields..." class="search-input" />
    </div>

    <div class="total-workers">
      <p style="margin-left:20px;">Total Worker: <span id="workerCount"><?php echo $totalWorkers; ?></span></p>
    </div>

    <div style="background-color:lightgrey; border-radius: 5px; padding:15px;">
      <!-- Total workers Display -->
      <div id="workerList" class="worker-list">
        <?php foreach ($workers as $worker): ?>
          <a style="text-decoration: none;" href="worker_details.php?id=<?php echo $worker['id']; ?>" class="worker-link">
            <div class="worker-item" data-id="w<?php echo htmlspecialchars($worker['id']); ?>" data-name="<?php echo htmlspecialchars($worker['name']); ?>" data-address="<?php echo htmlspecialchars($worker['address']); ?>">
              <div class="worker-content">
                <img src="<?php echo !empty($worker['image']) ? htmlspecialchars($worker['image']) : 'images/worker.png'; ?>" alt="worker Icon" style="padding:5px;width:100px;">
                <div class="worker-details">
                  <div class="name">ID: w<?php echo htmlspecialchars($worker['id']); ?></div>
                  <div class="name"><?php echo htmlspecialchars($worker['name']); ?></div>
                  <div class="address"><?php echo htmlspecialchars($worker['address']); ?></div>
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
    // JavaScript to filter the workers based on the search input
    document.getElementById('searchInput').addEventListener('input', function(event) {
      const query = event.target.value.toLowerCase();
      const workerItems = document.querySelectorAll('.worker-item');
      
      let visibleWorkerCount = 0;  // To track visible workers
    
      workerItems.forEach(worker => {
        const WorkerID = worker.getAttribute('data-id').toLowerCase();  // Get worker ID
        const WorkerName = worker.getAttribute('data-name').toLowerCase();  // Get worker name
        const WorkerAddress = worker.getAttribute('data-address').toLowerCase();  // Get worker address
        
        // If query matches worker ID, name, or address
        if (WorkerID.includes(query) || WorkerName.includes(query) || WorkerAddress.includes(query)) {
          worker.style.display = '';  // Show worker
          visibleWorkerCount++;
        } else {
          worker.style.display = 'none';  // Hide worker
        }
      });
      
      // Update the worker count based on the visible workers
      document.getElementById('workerCount').textContent = visibleWorkerCount;
    });
  </script>
</body>
</html>
