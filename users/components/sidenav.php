<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dropdown Menu</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .sidebar {
      display: flex;
      flex-direction: column;
      justify-content: start;
      align-items: start;
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      width: 15rem;
      background-color: #800000;
      overflow-y: auto; /* Added to handle overflow */
    }
    .menu-item {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid white;
      width: 100%;
      color: white;
      padding: 1rem;
      font-size: 20px;
      text-decoration: none;
      cursor: pointer;
      box-sizing: border-box;
    }
    .menu-item i {
      font-size: 20px;
    }
    .dropdown-menu {
      display: none;
      flex-direction: column;
      background-color: #57687a;
      width: 100%;
    }
    .dropdown-menu a {
      text-decoration: none;
      padding: 1rem;
      color: white;
      display: block;
      box-sizing: border-box;
      border-top: 1px solid #465672;
    }
    .dropdown-menu a:hover {
      background-color: #465672;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="menu-item dropdown-toggle" style="margin-top: 2rem;">
      Patient Registration
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Triage
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Consultation
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Laboratory
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Pharmacy
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Inventory
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Billing
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>
  </div>
  <script>
    document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
      toggle.addEventListener('click', function(event) {
        event.preventDefault();
        var dropdown = this.nextElementSibling;
        if (dropdown.style.display === 'flex') {
          dropdown.style.display = 'none';
        } else {
          dropdown.style.display = 'flex';
        }
      });
    });
  </script>
</body>
</html>
