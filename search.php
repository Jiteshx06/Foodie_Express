<?php
session_start();
require_once 'config.php';

$searchTerm = $_GET['query'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results for '<?php echo htmlspecialchars($searchTerm); ?>' | FoodieExpress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
    :root {
      --primary: #ff6b35;
      --primary-dark: #e05a2b;
      --accent: #ffd166;
      --accent-dark: #ffc044;
      --saffron: #FF9933;
      --green: #138808;
      --dark: #1a1a1a;
      --darker: #111;
      --light: #f8f9fa;
      --light-alt: #e9ecef;
      --glass: rgba(255, 255, 255, 0.08);
      --blur: blur(15px);
      --radius: 16px;
      --gradient: linear-gradient(135deg, var(--primary), #ffb347);
      --gradient-alt: linear-gradient(135deg, var(--accent), #ffb347);
      --shadow: 0 12px 30px rgba(0,0,0,0.3);
      --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', 'Outfit', sans-serif;
      background: var(--darker);
      color: var(--light);
      scroll-behavior: smooth;
      overflow-x: hidden;
      background-image: radial-gradient(circle at 10% 20%, rgba(26, 26, 26, 0.8) 0%, rgba(17, 17, 17, 1) 90%);
    }

    section {
      padding: 5rem 0;
    }

    .section-title {
      position: relative;
      margin-bottom: 4rem;
      text-align: center;
    }
    
    .section-title h2 {
      font-size: 2.8rem;
      font-weight: 800;
      margin-bottom: 1.5rem;
      background: var(--gradient-alt);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      display: inline-block;
      position: relative;
    }
    
    .section-title h2:after {
      content: '';
      position: absolute;
      left: 50%;
      bottom: -10px;
      width: 80px;
      height: 4px;
      background: var(--gradient);
      transform: translateX(-50%);
      border-radius: 10px;
    }

    ::-webkit-scrollbar { width: 10px; }
    ::-webkit-scrollbar-track { background: var(--dark); }
    ::-webkit-scrollbar-thumb {
      background: var(--gradient);
      border-radius: 10px;
      border: 2px solid var(--dark);
    }

    /* Navbar */
    .navbar {
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: var(--blur);
      box-shadow: 0 8px 25px rgba(0,0,0,0.6);
      padding: 0.8rem 2rem;
      transition: var(--transition);
    }

    .navbar-brand {
      font-weight: 800;
      font-size: 1.8rem;
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      transition: var(--transition);
      display: flex;
      align-items: center;
    }

    .navbar-brand i {
      margin-right: 8px;
    }

    .navbar-brand:hover {
      transform: scale(1.05);
    }

    .nav-link {
      color: var(--light) !important;
      font-weight: 500;
      margin: 0 0.8rem;
      position: relative;
      transition: var(--transition);
      padding: 0.5rem 1rem !important;
      font-size: 1.05rem;
    }

    .nav-link::after {
      content: "";
      position: absolute;
      left: 50%;
      bottom: 0;
      width: 0;
      height: 2px;
      background: var(--primary);
      transition: var(--transition);
      transform: translateX(-50%);
    }

    .nav-link:hover::after {
      width: 80%;
    }

    .nav-link:hover {
      color: var(--accent) !important;
    }

    .btn-primary {
      background: var(--gradient);
      border: none;
      border-radius: 50px;
      font-weight: 600;
      padding: 0.7rem 2rem;
      transition: var(--transition);
      box-shadow: var(--shadow);
      position: relative;
      overflow: hidden;
      z-index: 1;
    }

    .btn-primary:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 0;
      height: 100%;
      background: linear-gradient(135deg, var(--primary-dark), var(--primary));
      transition: var(--transition);
      z-index: -1;
    }

    .btn-primary:hover:before {
      width: 100%;
    }

    .btn-primary:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(255, 107, 53, 0.4);
    }

    .search-results-container {
      background: linear-gradient(to bottom, var(--dark), var(--darker));
      min-height: 100vh;
      padding-top: 100px;
    }

    .results-title {
      font-size: 2.5rem;
      margin-bottom: 2rem;
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 800;
    }

    .results-subtitle {
      color: var(--light-alt);
      margin-bottom: 3rem;
      font-size: 1.2rem;
    }

    .no-results {
      font-size: 1.8rem;
      color: var(--light-alt);
      text-align: center;
      padding: 5rem 0;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
    }

    .food-card {
      background: var(--glass);
      border-radius: var(--radius);
      overflow: hidden;
      transition: var(--transition);
      box-shadow: var(--shadow);
      backdrop-filter: var(--blur);
      border: 1px solid rgba(255,255,255,0.05);
    }

    .food-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 25px 50px rgba(0,0,0,0.4);
      border-color: rgba(255,255,255,0.1);
    }

    .food-img {
      width: 100%;
      height: 220px;
      position: relative;
      overflow: hidden;
    }

    .food-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: var(--transition);
    }

    .food-card:hover .food-img img {
      transform: scale(1.1);
    }

    .veg-nonveg {
      position: absolute;
      top: 15px;
      right: 15px;
      width: 25px;
      height: 25px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(0,0,0,0.7);
      font-size: 0.8rem;
      z-index: 2;
    }

    .veg {
      color: var(--green);
    }

    .nonveg {
      color: var(--primary);
    }

    .food-details {
      padding: 1.8rem;
    }

    .food-details h5 {
      font-weight: 700;
      font-size: 1.35rem;
      margin-bottom: 0.6rem;
    }

    .food-details p {
      color: var(--light-alt);
      margin-bottom: 1.2rem;
      font-size: 0.95rem;
      min-height: 60px;
    }

    .price-tag {
      background: var(--gradient);
      padding: 0.5rem 1.4rem;
      border-radius: 50px;
      font-weight: 700;
      display: inline-block;
      margin-bottom: 1.2rem;
      color: white;
      font-size: 1.15rem;
      box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }

    .food-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .rating {
      color: var(--accent);
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    /* Floating Cart */
    .cart-floating {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      background: var(--gradient);
      width: 70px;
      height: 70px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 15px 40px rgba(0,0,0,0.5);
      color: #fff;
      cursor: pointer;
      transition: var(--transition);
      z-index: 1000;
      font-size: 1.5rem;
      animation: pulse 2s infinite;
    }

    .cart-floating:hover {
      transform: scale(1.1) rotate(10deg);
      box-shadow: 0 20px 50px rgba(255, 107, 53, 0.6);
    }

    .cart-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: var(--light);
      color: var(--primary);
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.9rem;
      box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }

    /* Custom Message Box */
    .custom-message-box {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background-color: var(--primary);
      color: white;
      padding: 15px 25px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      z-index: 1050;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .custom-message-box.show {
      opacity: 1;
      visibility: visible;
    }

    .custom-message-box .close-btn {
      background: none;
      border: none;
      color: white;
      font-size: 1.2rem;
      cursor: pointer;
      margin-left: 10px;
    }

    /* Animations */
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .results-title { font-size: 2.2rem; }
    }

    @media (max-width: 992px) {
      section { padding: 4rem 0; }
    }

    @media (max-width: 768px) {
      .results-title { font-size: 2rem; }
      .navbar { padding: 0.7rem 1.5rem; }
    }

    @media (max-width: 576px) {
      .results-title { font-size: 1.8rem; }
      .navbar-brand { font-size: 1.5rem; }
    }
  </style>
</head>
<body class="search-results-container">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.html"><i class="fas fa-utensils"></i> FoodieExpress</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Offers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
          <li class="nav-item ms-lg-3 my-2 my-lg-0">
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <a class="btn btn-primary" href="logout.php">Logout <i class="fas fa-sign-out-alt ms-2"></i></a>
            <?php else: ?>
                <a class="btn btn-primary" href="login.html">Login <i class="fas fa-sign-in-alt ms-2"></i></a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
      <h1 class="results-title">Search Results</h1>
      <p class="results-subtitle">Showing results for: <span class="text-accent">'<?php echo htmlspecialchars($searchTerm); ?>'</span></p>
    </div>

    <?php if ($searchTerm):
        $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
        $searchParam = '%' . $searchTerm . '%';
        $stmt->execute([$searchParam, $searchParam]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results): ?>
            <div class="menu-grid">
                <?php foreach ($results as $row): ?>
                    <div class="food-card" data-aos="fade-up">
                        <div class="food-img">
                            <img src="<?php echo htmlspecialchars($row['image'] ?? 'img/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <!-- Veg/Non-Veg indicator would go here if available -->
                        </div>
                        <div class="food-details">
                            <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="price-tag">₹<?php echo htmlspecialchars($row['price']); ?></div>
                            <div class="food-meta">
                                <!-- Add to Cart Button -->
                                <button class="btn btn-sm btn-primary add-to-cart-btn">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results" data-aos="fade-up">
                <i class="fas fa-search mb-3" style="font-size: 3rem;"></i>
                <p>No items found for '<?php echo htmlspecialchars($searchTerm); ?>'</p>
                <a href="index.html" class="btn btn-primary mt-3">Back to Home</a>
            </div>
        <?php endif;
    else: ?>
        <div class="no-results" data-aos="fade-up">
            <i class="fas fa-exclamation-circle mb-3" style="font-size: 3rem;"></i>
            <p>Please enter a search term</p>
            <a href="index.html" class="btn btn-primary mt-3">Back to Home</a>
        </div>
    <?php endif; ?>
  </div>

  <!-- Floating Cart -->
  <div class="cart-floating">
    <i class="fas fa-shopping-cart"></i>
    <div class="cart-badge">0</div>
  </div>

  <!-- Custom Message Box -->
  <div id="customMessageBox" class="custom-message-box">
    <span id="messageText"></span>
    <button class="close-btn" onclick="hideMessageBox()">&times;</button>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    // Initialize animations
    AOS.init({
      duration: 800,
      once: true
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.style.padding = '0.5rem 2rem';
        navbar.style.boxShadow = '0 5px 20px rgba(0,0,0,0.7)';
      } else {
        navbar.style.padding = '0.8rem 2rem';
        navbar.style.boxShadow = '0 8px 25px rgba(0,0,0,0.6)';
      }
    });

    // Message box functions
    function showMessageBox(message, isError = false) {
      const messageBox = document.getElementById('customMessageBox');
      const messageText = document.getElementById('messageText');
      messageText.textContent = message;
      
      if (isError) {
        messageBox.style.backgroundColor = '#dc3545';
      } else {
        messageBox.style.backgroundColor = 'var(--primary)';
      }

      messageBox.classList.add('show');
      setTimeout(() => {
        hideMessageBox();
      }, 3000);
    }

    function hideMessageBox() {
      const messageBox = document.getElementById('customMessageBox');
      messageBox.classList.remove('show');
    }

    // Cart functions
    function updateCartBadge(count) {
      const badge = document.querySelector('.cart-badge');
      badge.textContent = count;
      const cartIcon = document.querySelector('.cart-floating');
      cartIcon.style.animation = 'pulse 0.5s';
      setTimeout(() => {
        cartIcon.style.animation = '';
      }, 500);
    }

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
      button.addEventListener('click', async function() {
        const button = this;
        const originalText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        const productId = button.dataset.productId;
        const productName = button.dataset.productName;
        const productPrice = button.dataset.productPrice;
        const productImage = button.dataset.productImage;

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('product_name', productName);
        formData.append('product_price', productPrice);
        formData.append('product_image', productImage);
        formData.append('quantity', 1);

        try {
          const response = await fetch('php/add_to_cart.php', {
            method: 'POST',
            body: formData
          });
          
          const result = await response.json();

          if (result.success) {
            updateCartBadge(result.cart_count);
            button.innerHTML = '<i class="fas fa-check"></i> Added';
            showMessageBox(`${productName} added to cart!`);
            setTimeout(() => {
              button.innerHTML = originalText;
              button.disabled = false;
            }, 2000);
          } else {
            button.innerHTML = originalText;
            button.disabled = false;
            if (result.message.includes("not logged in")) {
              showMessageBox("Please log in to add items to cart", true);
              setTimeout(() => {
                window.location.href = 'login.html';
              }, 1500);
            } else {
              showMessageBox("Error: " + result.message, true);
            }
          }
        } catch (error) {
          button.innerHTML = originalText;
          button.disabled = false;
          showMessageBox("An error occurred. Please try again.", true);
          console.error("Error:", error);
        }
      });
    });
    
    // Floating cart click
    document.querySelector('.cart-floating').addEventListener('click', function() {
      window.location.href = 'cart.php';
    });

    // Get initial cart count
    async function fetchCartCount() {
      try {
        const response = await fetch('php/get_cart_count.php');
        const result = await response.json();
        if (result.success) {
          updateCartBadge(result.cart_count);
        }
      } catch (error) {
        console.error('Error fetching cart count:', error);
      }
    }

    // Initialize on page load
    window.addEventListener('load', fetchCartCount);
  </script>
</body>
</html>