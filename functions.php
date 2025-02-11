<?php

include 'dbconfig.php';


// Function to add shop to the database
function addShop($name, $address, $owner, $mobile, $image) {
    $pdo = getDBConnection();

    if ($pdo === null) {
        return 'Database connection failed';
    }

    // Prepare the SQL query to insert shop data
    $sql = "INSERT INTO shops (name, address, owner, mobile,image) VALUES (:name, :address, :owner, :mobile, :image)";
    
    // Prepare the statement and bind parameters
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':owner', $owner);
    $stmt->bindParam(':mobile', $mobile);
      $stmt->bindParam(':image', $image);

    // Execute the query and return a success message
    if ($stmt->execute()) {
        return 'Shop added successfully!';
    } else {
        return 'Failed to add shop.';
    }
}





// Function to fetch shop data by ID
function getShopById($id) {
    $pdo = getDBConnection();
    
    if ($pdo === null) {
        return null;
    }

    // Prepare the SQL query to fetch shop data by ID
    $sql = "SELECT * FROM shops WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Execute and return the fetched data
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to fetch detailed shop data by ID
function get_shop_details($shop_id) {
    $pdo = getDBConnection();
    
    if ($pdo === null) {
        return null;
    }
 
    $sql = "SELECT * FROM shops WHERE id = :shop_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);

    // Execute the query and return the fetched data
    $stmt->execute();
 
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if any result was returned
    if ($result) {
        return $result; // Return shop details
    } else {
        return 'Shop not found'; // Return a message if no shop is found
    }
}




function get_worker_details($worker_id) {
    
 
    $pdo = getDBConnection();
    if ($pdo === null) return null;
    $sql = "SELECT * FROM worker_list WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $worker_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($result);
    return $result ? $result : 'Worker not found';
}

 

?>
