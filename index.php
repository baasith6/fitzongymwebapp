<?php
// Include database configuration file
include("dbconfig.php");

$successMessage = ""; // Default empty

// Show message if redirected after successful submission
if (isset($_GET['submitted']) && $_GET['submitted'] === "true") {
  $successMessage = "Thank you! Your message has been sent successfully.";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input values
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare SQL query to insert the data into the "questions" table
    $query = "INSERT INTO questions (name, email, message) VALUES (?, ?, ?)";

    // Prepare statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters to the SQL query
        $stmt->bind_param("sss", $name, $email, $message);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to #contact section with flag
            header("Location: index.php?submitted=true#contact");
            exit();
        } else {
            // If query execution fails
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // If preparation of the SQL query fails
        echo "Error preparing the SQL query: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitZone Fitness Center</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>

  <!-- Header Section -->
  <header>
    <nav class="navbar">
      <div class="logo">
        <h1>FitZone </h1>
      </div>
      <ul class="menu" id="navMenu">
        <li><a href="#about">About Us</a></li>
        <li><a href="#classes">Classes</a></li>
        <li><a href="#trainers">Trainers</a></li>
        <li><a href="#membership">Membership</a></li>
        <li><a href="#blog">Blog</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="#contact">Contact Us</a></li>
      </ul>
      <div class="toggle" id="menuToggle">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero-slider">
  <div class="slide slide1"></div>
  <div class="slide slide2"></div>

  <div class="hero-content container">
    <h1>Welcome to FitZone Fitness </h1>
    <p>Your fitness journey starts here. Achieve your goals with the best trainers and facilities.</p>
    <a href="register.php" class="btn cta-button">Join Now</a>
    <div class="scroll-down"></div>
  </div>
</section>

  <!-- About Section -->
  <section id="about" class="section about-section">
  <div class="container">
    
    <!-- Left Side: Image -->
    <div class="about-image">
      <img src="./images/about-us.jpg" alt="About FitZone">
    </div>

    <!-- Right Side: Text -->
    <div class="about-content">
      <h2>About FitZone</h2>
      <p>
        FitZone Fitness Center in Kurunegala is your ultimate destination for health and wellness. 
        Our facility combines cutting-edge equipment, experienced trainers, and a motivational environment.
        <br><br>
        Whether you're looking to burn fat, build strength, or improve flexibility, our diverse programs like cardio, strength training, yoga, and personalized coaching are designed for all fitness levels. 
        We also offer expert nutrition guidance to complement your training.
      </p>
      <a href="register.php" class="btn cta-button">Learn More</a>
    </div>

  </div>
</section>


  <!-- Classes Section -->
  <section id="classes" class="section classes-section">
  <div class="container">
    <h2>Our Classes</h2>
    <div class="classes-grid">

      <a href="register.php" class="class-card">
        <img src="./images/classes/cardio.jpg" alt="Cardio Training">
        <h3>Cardio</h3>
        <p>Boost your heart health with intense cardio workouts.</p>
      </a>

      <a href="register.php" class="class-card">
        <img src="./images/classes/class-4.jpg" alt="Strength Training">
        <h3>Strength Training</h3>
        <p>Build muscle and strength with our specialized programs.</p>
      </a>

      <a href="register.php" class="class-card">
        <img src="./images/classes/yoga.jpg" alt="Yoga Session">
        <h3>Yoga</h3>
        <p>Enhance flexibility and mindfulness with our yoga sessions.</p>
      </a>

      <a href="register.php" class="class-card">
        <img src="./images/classes/zumba.jpg" alt="Zumba Dance">
        <h3>Zumba</h3>
        <p>Get fit with fun, dance-based Zumba sessions.</p>
      </a>

      <a href="register.php" class="class-card">
        <img src="./images/classes/class-3.jpg" alt="HIIT Training">
        <h3>HIIT</h3>
        <p>Push your limits with high-intensity interval training.</p>
      </a>

      <a href="register.php" class="class-card">
        <img src="./images/classes/crossfit.jpg" alt="CrossFit">
        <h3>CrossFit</h3>
        <p>Experience powerful functional fitness with CrossFit training.</p>
      </a>
      <a href="register.php" class="btn cta-button">Join Now</a>

    </div>
  </div>
</section>


  <!-- Trainers Section -->
<section id="trainers" class="section trainers-section">
  <div class="container">
    <h2>Meet Our Trainers</h2>
    <div class="trainers-list">

      <!-- Trainer Card 1 -->
      <div class="trainer-card">
        <img src="trainer1.jpg" alt="Albert Einstein - Strength Trainer">
        <h3>Albert Einstein</h3>
        <p>Specialty: Strength Training</p>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

      <!-- Trainer Card 2 -->
      <div class="trainer-card">
        <img src="trainer2.jpg" alt="Isaac Newton - Yoga Trainer">
        <h3>Isaac Newton</h3>
        <p>Specialty: Yoga & HITT</p>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

      <!-- Trainer Card 3 -->
      <div class="trainer-card">
        <img src="trainer3.jpg" alt="Nikola Tesla - Pilates Instructor">
        <h3>Nikola Tesla</h3>
        <p>Specialty: Zumba & CrossFit</p>
        <div class="social-icons">
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

    </div>
    <a href="register.php" class="btn cta-button">Join Now</a>
  </div>
</section>


<section id="membership" class="section membership-section">
  <div class="container">
    <h2>Membership Plans</h2>
    <div class="membership-plans">

      <!-- Basic Plan -->
      <div class="plan-card">
        <h3>Basic Plan</h3>
        <ul class="features">
          <li>Access to all gym equipment</li>
          <li>Group fitness classes</li>
        </ul>
        <p class="price">LKR 2000</p>
        <a href="register.php" class="btn">Choose Plan</a>
      </div>

      <!-- Standard Plan -->
      <div class="plan-card">
        <h3>Standard Plan</h3>
        <ul class="features">
          <li>All gym facilities</li>
          <li>Yoga, Zumba, Cardio sessions</li>
          <li>Locker & shower access</li>
        </ul>
        <p class="price">LKR 3500</p>
        <a href="register.php" class="btn">Choose Plan</a>
      </div>

      <!-- Premium Plan - Recommended -->
      <div class="plan-card featured">
        <h3>Premium Plan</h3>
        <ul class="features">
          <li>Unlimited gym & class access</li>
          <li>Personal coaching</li>
          <li>Nutrition guidance</li>
          <li>Priority booking</li>
        </ul>
        <p class="price">LKR 5000</p>
        <a href="register.php" class="btn">Choose Plan</a>
      </div>

      <!-- Student Plan -->
      <div class="plan-card">
        <h3>Student Plan</h3>
        <ul class="features">
          <li>Gym access after 3PM</li>
          <li>Evening group sessions</li>
          <li>Student-only rates</li>
        </ul>
        <p class="price">LKR 3000</p>
        <a href="register.php" class="btn">Choose Plan</a>
      </div>

      <!-- Online Plan -->
      <div class="plan-card">
        <h3>Online Plan</h3>
        <ul class="features">
          <li>Live virtual sessions</li>
          <li>Custom workouts</li>
          <li>Remote nutrition tracking</li>
        </ul>
        <p class="price">LKR 1500</p>
        <a href="register.php" class="btn">Choose Plan</a>
      </div>

    </div>
  </div>
</section>



  <!-- Blog Section -->
<section id="blog" class="section blog-section">
  <div class="container">
    <h2>Latest Blog Posts</h2>
    <div class="blog-grid">

      <!-- Blog Post 1 -->
      <div class="post-card">
        <img src="./images/blog/blog-4.jpg" alt="Workout Tips for Beginners">
        <div class="post-content">
          <h3>Workout Tips for Beginners</h3>
          <p>Learn how to start your fitness journey with simple, effective workout routines and beginner-friendly strategies.</p>
          <a href="blog-post1.html" class="read-more">Read More</a>
        </div>
      </div>

      <!-- Blog Post 2 -->
      <div class="post-card">
        <img src="./images/blog/meal.jpg" alt="Healthy Meal Plans">
        <div class="post-content">
          <h3>Healthy Meal Plans</h3>
          <p>Fuel your body with nutrient-rich meals that support your fitness goals and enhance recovery and performance.</p>
          <a href="blog-post2.html" class="read-more">Read More</a>
        </div>
      </div>

      <!-- Blog Post 3 -->
      <div class="post-card">
        <img src="./images/blog/stretch.jpg" alt="Importance of Stretching">
        <div class="post-content">
          <h3>Importance of Stretching</h3>
          <p>Understand how stretching improves flexibility, reduces injury risk, and accelerates muscle recovery.</p>
          <a href="blog-post3.html" class="read-more">Read More</a>
        </div>
      </div>
      
    </div>
    <a href="register.php" class="btn cta-button">Join Now</a>

  </div>
</section>


  <!-- Contact Us Section -->
  <section id="contact" class="section contact-section">
  <div class="container contact-container">
    
    <!-- Contact Form -->
    <div class="contact-form">
      <h2>Contact Us</h2>
      <!-- ✅ SUCCESS MESSAGE HERE -->
      <?php if (!empty($successMessage)): ?>
        <p class="form-success"><?php echo $successMessage; ?></p>
      <?php endif; ?>
      <p>Have questions? Reach out to us for more information or to get started with your fitness journey!</p>
      <form action="index.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Your Name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="your@email.com" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" placeholder="Your message..." required></textarea>

        <button type="submit" class="cta-button btn">Send Message</button>
      </form>
      
    </div>

    <!-- Contact Info + Map -->
    <div class="contact-info">
      <h2>Get in Touch</h2>
      <p>123 Fitness Street, Kurunegala, Sri Lanka</p>
      <p>Email: <a href="mailto:info@fitzone.com">info@fitzone.com</a></p>
      <p>Phone: +94 123 456 789</p>

      <!-- Embedded Google Map -->
      <div style="margin-top: 20px; border-radius: 5px; overflow: hidden;">
        <iframe
          src="https://www.google.com/maps?q=Kurunegala,%20Sri%20Lanka&output=embed"
          width="100%"
          height="200"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>
</section>

  \


  
  <!-- Couples Offer Modal -->
<div class="modal" id="couple-offer-modal">
  <div class="modal-content">
    <span class="modal-close" id="close-offer-modal">&times;</span>
    <img src="images/testimonial/testimonial-2.jpg" alt="Couple Offer" class="modal-img">
    <h2>Couple’s Premium Plan Offer 🎉</h2>
    <p>Join FitZone with your partner and enjoy our <strong>Premium Plan</strong> at just <strong>LKR 8000</strong> for both!</p>
    <p>That’s a 20% discount from the regular price. Let's get fit together!</p>
    <a href="register.php" class="btn">Join Now</a>
  </div>
</div>

  <!-- Whatsapp ICON -->
  <a href="https://wa.me/94777353481" class="whatsapp-float" target="_blank" title="Chat with us on WhatsApp">
  <img src="https://img.icons8.com/color/48/000000/whatsapp--v1.png" alt="WhatsApp" />
  </a>


  <!-- JavaScript for Mobile Menu Toggle and Modal -->
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      // Navbar scroll shrink
      const navbar = document.querySelector('.navbar');
      window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }

        // Back to top button show/hide
        const backToTopButton = document.getElementById("backToTop");
        if (backToTopButton) {
          backToTopButton.style.display = window.scrollY > 300 ? "block" : "none";
        }
      });

      // Mobile menu toggle
      const toggle = document.querySelector('.toggle');
      const menu = document.querySelector('.menu');
      if (toggle && menu) {
        toggle.addEventListener('click', () => {
          toggle.classList.toggle('active');
          menu.classList.toggle('open');
        });
      }

      // Back to top button scroll behavior
      const backToTopButton = document.getElementById("backToTop");
      if (backToTopButton) {
        backToTopButton.addEventListener("click", () => {
          window.scrollTo({ top: 0, behavior: "smooth" });
        });
      }

      // Modal functionality
      window.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('couple-offer-modal');
        const modalClose = document.getElementById('close-offer-modal');

        // ✅ Ensure modal and close button exist
        if (!modal || !modalClose) {
          console.warn("Modal or close button not found.");
          return;
        }

        // ✅ Show modal after 3 seconds if not already shown in session
        if (!sessionStorage.getItem('modalShown')) {
          setTimeout(() => {
            modal.classList.add('active');
            sessionStorage.setItem('modalShown', 'true');
          }, 3000);
        }

        // ✅ Close modal when X is clicked
        modalClose.addEventListener('click', () => {
          modal.classList.remove('active');
        });
      });
    });
  </script>

</body>
</html>
