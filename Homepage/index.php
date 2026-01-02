<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriConnect E-commerce website</title>
    <link rel="stylesheet" href="style.css">
    <script>
                const sellerBtn = document.getElementById('sellerBtn');
        sellerBtn.onclick = function(e) {
            e.preventDefault();
            modal.style.display = 'flex';
            showForm('register');
        };
    </script>
      
    
</head>
<body>
    <nav>
        <div class="nav-left">
            <div class="logo">A</div>
            <div>
                <h3>AgriMarket</h3>
                <p>Connect</p>
            </div>
        </div>
        <div class="nav-center">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Categories</a></li>
                <li><a href="#">Sellers</a></li>
                <li><a href="#">About</a></li>
            </ul>
            <div class="search-box">
                <input type="text" placeholder="Search products...">
            </div>
        </div>
        <div class="nav-right">
            <div class="cart"><img src="images/Cart.jpg" alt="cart"></div>
            <a href="auth.php"><button class="btn-join" id="openModal">Login</button></a>
          
        </div>
    </nav>

    
       


    <main>
        <section class="hp_container">
            <div class="section-1">
                <h2>Connecting Farmers</h2>
                <p>Directly to the Market</p>
                <h4>This is a Premier Multi-vendor platform for agricultural products</h4>
                <form action="" class="form-cont">
                    <input type="text" placeholder="Search for vegetables, fruits, livestock..." required>
                    <button type="submit">Search</button>
                </form>
                <div class="btn">
                    <a href="#">Start Shopping</a>
                    <a href="#">Become a Seller</a>
                </div>
            </div>
        </section>

        <section class="abt_container">
            <div class="section-2">
                <h2>Shop by Category</h2>
                <p>Discover fresh quality agricultural products from verified sellers across different<br>categories</p>
            </div>
            <div class="cont-1">
                <div class="exclusives">
                    <div>
                        <img src="images/pic-4.jpeg" alt="">
                        <span>
                            <h3>Vegetables</h3>
                            <p>Fresh organic vegetables from local farms</p>
                            <a href="#">847 products</a>
                        </span>
                    </div>
                    <div>
                        <img src="images/pic-12.webp" alt="">
                        <span>
                            <h3>Fruit</h3>
                            <p>Seasonal Fruits picked at perfect ripeness</p>
                            <a href="#">234 products</a>
                        </span>
                    </div>
                    <div>
                        <img src="images/pic-5.jpeg" alt="">
                        <span>
                            <h3>Poultry</h3>
                            <p>Fresh-range poultry and fresh eggs</p>
                            <a href="#">456 products</a>
                        </span>
                    </div>
                    <div>
                        <img src="images/pic-11.jpeg" alt="">
                        <span>
                            <h3>Cattle</h3>
                            <p>Grass-field cattle and dairy products</p>
                            <a href="#">134 products</a>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <section class="amt">
            <h2>Featured Products</h2>
            <p>Hand-picked products from our trusted sellers</p>
        </section>

         
             
           <div class="card-container">
                <div class="cards">
                    <div class="deck-1">
                        <img src="images/tomato.jpg" alt="">
                        <p class="pleft">Tomatoes</p>
                        <p class="pright">In Stock</p>
                    </div>
                    <h3>Organic Tomatoes</h3>
                    <img src="images/dp.webp" alt=""  width="20px"> <p>Green Valley Farm</p>
                    <img src="images/nav.jpg" alt="" width="20px"> <p>Limuru, Kenya</p>

                    <p>⭐ 4.8(24)</p>

                    <span>KSH 4.99</span>
                    <p>per unit</p>
                    <div class="btns">
                        <a href="" class="view">View Details</a>
                        <a href="" class="add">Add to Cart</a>
                    </div>
                 </div>

                 <div class="cards">
                    <div class="deck-1">
                        <img src="images/apples.jpg" alt="">
                        <p class="pleft">Apples</p>
                        <p class="pright">In Stock</p>
                    </div>
                    <h3>Organic Apples</h3>
                    <img src="images/dp.webp" alt="" width="20px"> <p>Green Valley Farm</p>
                    <img src="images/nav.jpg" alt="" width="20px"> <p>Weitethie, Kenya</p>

                    <p>⭐ 4.8(24)</p>

                    <span>KSH 4.99</span>
                    <p>per unit</p>
                    <div class="btns">
                        <a href="" class="view">View Details</a>
                        <a href="" class="add">Add to Cart</a>
                    </div>
                 </div>

                 <div class="cards">
                    <div class="deck-1">
                        <img src="images/bananas.jpeg" alt="">
                        <p class="pleft">Bananas</p>
                        <p class="pright">In Stock</p>
                    </div>
                    <h3>Organic Bananas</h3>
                    <img src="images/dp.webp" alt="" width="20px"> <p>Green Valley Farm</p>
                    <img src="images/nav.jpg" alt="" width="20px"> <p>Nanyuki, Kenya</p>

                    <p>⭐ 4.8(24)</p>

                    <span>KSH 4.99</span>
                    <p>per unit</p>
                    <div class="btns">
                        <a href="" class="view">View Details</a>
                        <a href="" class="add">Add to Cart</a>
                    </div>
                 </div>

                  <div class="cards">
                    <div class="deck-1">
                        <img src="images/eggs.jpeg" alt="">
                        <p class="pleft">Eggs</p>
                        <p class="pright">In Stock</p>
                    </div>
                    <h3>Fresh Farm Eggs</h3>
                    <img src="images/dp.webp" alt="" width="20px"> <p>Green Valley Farm</p>
                    <img src="images/nav.jpg" alt="" width="20px"> <p>Kakamega, Kenya</p>

                    <p>⭐ 4.8(24)</p>

                    <span>KSH 4.99</span>
                    <p>per unit</p>
                    <div class="btns">
                        <a href="" class="view">View Details</a>
                        <a href="" class="add">Add to Cart</a>
                    </div>
                 </div>

                  <div class="cards">
                    <div class="deck-1">
                        <img src="images/pic-5.jpeg" alt="">
                        <p class="pleft">Chicken</p>
                        <p class="pright">In Stock</p>
                    </div>
                    <h3>Chicken for sale</h3>
                    <img src="images/dp.webp" alt="" width="20px"> <p>Green Valley Farm</p>
                    <img src="images/nav.jpg" alt="" width="20px"> <p>Busia, Kenya</p>

                    <p>⭐ 4.8(24)</p>

                    <span>KSH 4.99</span>
                    <p>per unit</p>
                    <div class="btns">
                        <a href="" class="view">View Details</a>
                        <a href="" class="add">Add to Cart</a>
                    </div>
                 </div>

                  <div class="cards">
                    <div class="deck-1">
                        <img src="images/carrott.webp" alt="">
                        <p class="pleft">Carrots</p>
                        <p class="pright">In Stock</p>
                    </div>
                    <h3>Fresh organic Carrots</h3>
                    <img src="images/dp.webp" alt="" width="20px"> <p>Green Valley Farm</p>
                    <img src="images/nav.jpg" alt="" width="20px"> <p>Nyandarua, Kenya</p>

                    <p>⭐ 4.8(24)</p>

                    <span>KSH 4.99</span>
                    <p>per unit</p>
                    <div class="btns">
                        <a href="" class="view">View Details</a>
                        <a href="" class="add">Add to Cart</a>
                    </div>
                 </div>

                  <div class="cards">
                    <div class="deck-1">
                        <img src="images/oranges.jpg" alt="">
                        <p class="pleft">Oranges</p>
                        <p class="pright">In Stock</p>
                    </div>
                    <h3>Organic Oranges</h3>
                    <img src="images/dp.webp" alt="" width="20px"> <p>Green Valley Farm</p>
                    <img src="images/nav.jpg" alt="" width="20px"> <p>Muranga, Kenya</p>

                    <p>⭐ 4.8(24)</p>

                    <span>KSH 4.99</span>
                    <p>per unit</p>
                    <div class="btns">
                        <a href="" class="view">View Details</a>
                        <a href="" class="add">Add to Cart</a>
                    </div>
                 </div>

                  <div class="cards">
                    <div class="deck-1">
                        <img src="images/vegges.jpeg" alt="">
                        <p class="pleft">Vegetables</p>
                        <p class="pright">In Stock</p>
                    </div>
                    <h3>Organic Vegetables</h3>
                    <img src="images/dp.webp" alt="" width="20px"> <p>Green Valley Farm</p>
                    <img src="images/nav.jpg" alt="" width="20px"> <p>Kisii, Kenya</p>

                    <p>⭐ 4.8(24)</p>

                    <span>KSH 4.99</span>
                    <p>per unit</p>
                    <div class="btns">
                        <a href="" class="view">View Details</a>
                        <a href="" class="add">Add to Cart</a>
                    </div>
                 </div>



            </div>

            
             
     
    </main>

    <!-- ✅ Testimonial Section -->
<section class="testimonials" id="testimonials">
  <h2>What Our Farmers Say</h2>
  <div class="testimonial-container">
    <div class="testimonial">
      <p>"Agriconnect helped me find buyers for my produce faster than ever before. A true game changer!"</p>
      <h4>- John Mwangi, Farmer</h4>
    </div>
    <div class="testimonial">
      <p>"Thanks to Agriconnect, I can now get real-time market prices and make better selling decisions."</p>
      <h4>- Mary Atieno, Distributor</h4>
    </div>
    <div class="testimonial">
      <p>"The platform connects farmers directly to suppliers. I saved money on fertilizers this season!"</p>
      <h4>- Peter Njoroge, Agripreneur</h4>
    </div>
  </div>
  <div class="testimonial-buttons">
    <button class="prev">&#10094;</button>
    <button class="next">&#10095;</button>
  </div>
</section>

<!-- ✅ Footer -->
<footer class="footer">
  <div class="footer-content">
    <div class="footer-logo">
      <h3>Agriconnect</h3>
      <p>Connecting farmers, buyers, and suppliers for a smarter agricultural future.</p>
    </div>
    <div class="footer-links">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#market">Market</a></li>
        <li><a href="#support">Support</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </div>
    <div class="footer-social">
      <h4>Follow Us</h4>
      <a href="#">🌐 Facebook</a>
      <a href="#">🐦 Twitter</a>
      <a href="#">📸 Instagram</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2025 Agriconnect. All Rights Reserved.</p>
  </div>
</footer>


    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('authModal');
            const loginBtn = document.getElementById('loginBtn');
            const closeBtn = document.querySelector('.close');

            loginBtn.onclick = function(e) {
                e.preventDefault();
                modal.style.display = 'flex';
                showForm('login');
            };

            closeBtn.onclick = function() {
                modal.style.display = 'none';
            };

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };

            window.showForm = function(form) {
                document.getElementById('loginForm').style.display = (form === 'login') ? 'block' : 'none';
                document.getElementById('registerForm').style.display = (form === 'register') ? 'block' : 'none';
                document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
                document.querySelector(`.tab[onclick="showForm('${form}')"]`).classList.add('active');
            };
        });
    </script>
</body>
</html>