<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dropdown Menu</title>
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
      background-color: #465672;
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
    .menu-item img {
      height: 20px;
      width: 30px;
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
    <div class="menu-item dropdown-toggle">
      Patient Registration
      <img src="../../images/chevron-down-solid.svg" alt="dropdown"/>
    </div>
    <div class="dropdown-menu">
      <a href="#">Register new Patient</a>
      <a href="#">Manage Patient Pregistration</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Triage
      <img src="../../images/chevron-down-solid.svg" alt="dropdown"/>
    </div>
    <div class="dropdown-menu">
      <a href="#">Add Triage</a>
      <a href="#">Manage Triage</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Consultation
      <img src="../../images/chevron-down-solid.svg" alt="dropdown"/>
    </div>
    <div class="dropdown-menu">
      <a href="#">Add Consultation Details</a>
      <a href="#">Manage Consultation</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Laboratory
      <img src="../../images/chevron-down-solid.svg" alt="dropdown"/>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Pharmacy
      <img src="../../images/chevron-down-solid.svg" alt="dropdown"/>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Inventory
      <img src="../../images/chevron-down-solid.svg" alt="dropdown"/>
    </div>
    <div class="dropdown-menu">
      <a href="#">Sub-item 1</a>
      <a href="#">Sub-item 2</a>
    </div>

    <div class="menu-item dropdown-toggle">
      Billing
      <img src="../../images/chevron-down-solid.svg" alt="dropdown"/>
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

