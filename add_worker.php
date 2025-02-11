<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Worker</title>
  <link rel="stylesheet" href="style_app.css">
</head>
<body>
  <div class="container1" style="padding:40px;">
    <h1>Add a New Worker</h1>
    <form id="addWorkerForm" method="POST" enctype="multipart/form-data">
      <label for="workerName">Worker Name</label>
      <input type="text" id="workerName" name="workerName" required>

      <label for="workerAddress">Worker Address</label>
      <input type="text" id="workerAddress" name="workerAddress" required>

      <label for="workerMobile">Mobile</label>
      <input type="text" id="workerMobile" name="workerMobile" required>

      <label for="workerImage">Worker Image (Optional)</label>
      <input type="file" id="workerImage" name="workerImage" accept="image/*">

      <!-- Preview container for image -->
      <div id="imagePreviewContainer" style="margin-top: 10px;">
        <img id="imagePreview" style="max-width: 100%; display: none;" />
      </div>

      <button type="submit">Add Worker</button>
    </form>

    <div id="responseMessage" style="margin-top: 20px;"></div>
  </div>

  <script>
    // Display image preview when a file is selected
    document.getElementById('workerImage').addEventListener('change', function(event) {
      const file = event.target.files[0];
      const preview = document.getElementById('imagePreview');

      if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block'; // Show the image
        };

        reader.readAsDataURL(file);
      } else {
        preview.style.display = 'none'; // Hide the image if no file is selected
      }
    });

    // Form submission handling
    document.getElementById('addWorkerForm').onsubmit = function(event) {
      event.preventDefault();

      // Get form data
      const formData = new FormData(this);

      // Send the form data via AJAX (fetch)
      fetch('addworkerserver.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        const responseMessage = document.getElementById('responseMessage');
        if (data.status === 'success') {
          window.location.href = "worker_list.php"; // Redirect to worker list after success
        } else {
          responseMessage.innerHTML = `<p style="color: red;">${data.message}</p>`;
        }
      })
      .catch(error => {
        const responseMessage = document.getElementById('responseMessage');
        responseMessage.innerHTML = `<p style="color: red;">An error occurred: ${error.message}</p>`;
      });
    };
  </script>

  <style>
    /* Ensure the file input is visible and clickable */
    input[type="file"] {
      visibility: visible;
      opacity: 1;
      position: static;
      margin-top: 10px;
    }
    /* Optional: You can add styles for the form, input fields, and button here */
    input[type="text"], input[type="file"] {
      margin-bottom: 10px;
      padding: 10px;
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background-color: #45a049;
    }

    /* Optional: Style for the image preview */
    #imagePreview {
      max-width: 100%;
      height: auto;
      border: 1px solid #ccc;
      margin-top: 10px;
    }
  </style>
</body>
</html>
