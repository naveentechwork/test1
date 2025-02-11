<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Shop</title>
  <link rel="stylesheet" href="style_app.css">
</head>
<body>
  <div class="container1" style="padding:40px;">
    <h1>Add a New Shop</h1>
    <form id="addShopForm" enctype="multipart/form-data">
      <label for="shopName">Shop Name</label>
      <input type="text" id="shopName" name="shopName" required>

      <label for="shopAddress">Shop Address</label>
      <input type="text" id="shopAddress" name="shopAddress" required>
      
      <label for="shopOwner">Owner</label>
      <input type="text" id="shopOwner" name="shopOwner" required>
      
      <label for="shopMobile">Mobile</label>
      <input type="text" id="shopMobile" name="shopMobile" required>

      <!-- Image Upload Section -->
      <label for="shopImage">Shop Image</label>
      <input type="file" id="shopImage" name="shopImage" accept="image/*">

      <button type="submit">Add Shop</button>
    </form>

    <div id="responseMessage" style="margin-top: 20px;"></div>
  </div>

  <script>
    document.getElementById('addShopForm').onsubmit = function(event) {
      event.preventDefault();

      // Get form values
      const name = document.getElementById('shopName').value;
      const address = document.getElementById('shopAddress').value;
      const owner = document.getElementById('shopOwner').value;
      const mobile = document.getElementById('shopMobile').value;
      const image = document.getElementById('shopImage').files[0]; // Get the selected image

      // Check if all required fields are filled
      if (name && address && owner && mobile) {
        const formData = new FormData();
        formData.append('shopName', name);
        formData.append('shopAddress', address);
        formData.append('shopOwner', owner);
        formData.append('shopMobile', mobile);
        if (image) formData.append('image', image); // Append the image file if selected

        // Send the form data using fetch (AJAX)
        fetch('add-shop.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          // Show response message
          const responseMessage = document.getElementById('responseMessage');
          if (data.status === 'success') {
            window.location.href = "shop_list.php";
          } else {
            responseMessage.innerHTML = `<p style="color: red;">${data.message}</p>`;
          }
        })
        .catch(error => {
          const responseMessage = document.getElementById('responseMessage');
          responseMessage.innerHTML = `<p style="color: red;">An error occurred: ${error.message}</p>`;
        });
      } else {
        alert('Please fill out all fields.');
      }
    };
  </script>
</body>
</html>
