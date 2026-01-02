<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../auth.php?error=Unauthorized");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller's | Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="/Homepage/style.css">
    <link rel="stylesheet" href="seller.css">
</head>
<body>

     <nav class="flex-div">

        <div class="nav-left flex-div">

            <img src="images/menu.png" alt="" class="menu-icon">
            <div class="nav-left">

                    <div class="logo">A</div>
                    <div>
                        <h3>AgriMarket</h3>
                        <p>Connect</p>
                    </div>
                    
                </div>
        </div>
        <div class="nav-middle flex-div">
             <p>Seller's Dashboard</p>
           
            

        </div>


        <div class="nav-right flex-div">
            <img src="images/Cart.jpg" alt="">
            <img src="images/more.png" alt="">
            <img src="images/notification.png" alt="">
            <img src="images/Jack.png" alt="" class="user-icon">
        </div>



     </nav>

     <!-- Sidebar -->
     <div class="sidebar">
        <div class="shortcut-links">
            <a href="javascript:void(0);" onclick="showSection('profile')" id="btn-profile"><img src="images/dp-1.jpg" alt=""><p>Profile Settings</p></a>
            <a href="javascript:void(0);" onclick="showSection('products')" id="btn-products"><img src="images/products-icon.png" alt=""><p>Post a Product</p></a>
            <a href="javascript:void(0);" onclick="showSection('cart')" id="btn-cart"><img src="images/Cart.jpg" alt=""><p>Orders</p></a>
            <a href="javascript:void(0);" onclick="showSection('checkout')" id="btn-checkout"><img src="images/payment-icon.png" alt=""><p>Payment Credentials</p></a>
            <a href="javascript:void(0);" onclick="showSection('track')" id="btn-track"><img src="images/Movt.png" alt=""><p>Delivery</p></a>
            <a href="javascript:void(0);" onclick="showSection('sell')" id="btn-sell"><img src="images/sell.png" alt=""><p>Bought Items</p></a>
            <a href="javascript:void(0);" onclick="showSection('contact')" id="btn-contact"><img src="images/contact.png" alt=""><p>Contact</p></a>
            <a href="javascript:void(0);" onclick="showSection('history')" id="btn-history"><img src="images/history2.png" alt=""><p>View Order History</p></a>
            <a href="javascript:void(0);" onclick="showSection('logout')" id="btn-logout"><img src="images/logout.png" alt=""><p>Logout</p></a>
            <hr>
        </div>
     </div>

     <!-- Main -->
     <div class="container">
        <!-- Profile Section -->
        <div class="content-section" id="profile" style="display:block;">
            <h2>Profile Settings</h2>
            <p>Manage your profile information here.</p>
                    <section class="aura-box">
        <h2>👤 Profile Settings</h2>
        <form>
            <label>Name
            <input class="aura-input" type="text" placeholder="Your Name">
            </label><br>
            <label>Email
            <input class="aura-input" type="email" placeholder="you@email.com">
            </label><br>
            <label>Password
            <input class="aura-input" type="password" placeholder="New Password">
            </label><br>
            <button class="aura-btn">Save Changes</button>
        </form>
        </section>





        </div>
        <!-- Products Section -->
         <div class="content-section" id="products" style="display:none;">
            <h2>Post Products</h2>
            <br>
            <p>View Products Section.</p>

            <br>
            <div class="nav-middle flex-div">
            <div class="search-box flex-div">
                <input type="text" placeholder="Search For Products">
                <img src="images/search.png" alt=""  >
            </div>
           
            

        </div>
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
        </div>

        

  
         
  

       
        <!-- Cart Section -->
        <div class="content-section" id="cart" style="display:none;">
            <h2>Orders</h2>
            <p>View items added to your cart.</p>
                <section class="aura-box">
                <h2>🛒 Your Cart</h2>
                <table class="aura-table">
                    <thead>
                    <tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                    <tr><td>Maize (50kg)</td><td>2</td><td>KSh 3,000</td><td>KSh 6,000</td></tr>
                    <tr><td>Beans (20kg)</td><td>1</td><td>KSh 1,500</td><td>KSh 1,500</td></tr>
                    </tbody>
                </table>
                <p><strong>Subtotal:</strong> KSh 7,500</p>
                <button class="aura-btn">Proceed to Checkout</button>
            </section>

        </div>
        <!-- Checkout Section -->
        <div class="content-section" id="checkout" style="display:none;">
            <h2>Check Out</h2>
            <p>Proceed to payment and confirm your order.</p>
            <section class="aura-box">
                <h2>💳 Checkout</h2>
                <form>
                    <label>Delivery Address
                    <textarea class="aura-textarea" rows="3" placeholder="Enter delivery details"></textarea>
                    </label><br>
                    <label>Payment Method
                    <select class="aura-select">
                        <option>Mobile Money (M-Pesa)</option>
                        <option>Credit/Debit Card</option>
                        <option>Bank Transfer</option>
                    </select>
                    </label><br>
                    <button class="aura-btn">Complete Payment</button>
                </form>
                </section>

        </div>
        <!-- Track Movement Section -->
        <div class="content-section" id="track" style="display:none;">
            <h2>Bought items</h2>
            <p>Track your order delivery status.</p>
            <section class="aura-box">
                <h2>📍 Track Order Movement</h2>
                <div class="tracking">
                    <p><strong>Order ID:</strong> #AG12345</p>
                    <div class="tracking-steps">
                    <span style="color:var(--accent-dark)">✔ Order Placed</span> →
                    <span style="color:var(--accent-dark)">✔ Payment Confirmed</span> →
                    <span style="color:#ffa000">🚚 On Transit</span> →
                    <span>📦 Delivered</span>
                    </div>
                </div>
                </section>

        </div>
        <!-- Want to Sell Section -->
        <div class="content-section" id="sell" style="display:none;">
            <h2>Payment methods</h2>
            <p>Register your products for sale.</p>
            <section class="aura-box">
                <h2>💼 Sell Your Produce</h2>
                <form>
                    <label>Product Name
                    <input class="aura-input" type="text" placeholder="e.g. Fresh Tomatoes">
                    </label><br>
                    <label>Quantity (Kg)
                    <input class="aura-input" type="number">
                    </label><br>
                    <label>Price per Kg (KSh)
                    <input class="aura-input" type="number">
                    </label><br>
                    <button class="aura-btn">List Product</button>
                </form>
                </section>

        </div>
        <!-- Contact Section -->
        <div class="content-section" id="contact" style="display:none;">
            <h2>Contact</h2>
            <p>Contact support or send us a message.</p>
            <section class="aura-box">
            <h2>📧 Contact Support</h2>
            <form>
                <label>Subject
                <input class="aura-input" type="text" placeholder="Order Issue, Payment, etc.">
                </label><br>
                <label>Message
                <textarea class="aura-textarea" rows="4" placeholder="Describe your issue..."></textarea>
                </label><br>
                <button class="aura-btn">Send Message</button>
            </form>
            </section>

        </div>
        <!-- Order History Section -->
        <div class="content-section" id="history" style="display:none;">
            <h2>Order History</h2>
            <p>View your past orders.</p>
            <section class="aura-box">
            <h2>📑 Order History</h2>
            <table class="aura-table">
                <thead>
                <tr><th>Order ID</th><th>Date</th><th>Status</th><th>Total</th></tr>
                </thead>
                <tbody>
                <tr><td>#AG12345</td><td>12 Sept 2025</td><td><span style="color:var(--accent-dark)">Delivered</span></td><td>KSh 7,500</td></tr>
                <tr><td>#AG12410</td><td>05 Sept 2025</td><td><span style="color:#ffa000">On Transit</span></td><td>KSh 4,200</td></tr>
                </tbody>
            </table>
            </section>

        </div>
        <!-- Logout Section -->
        <div class="content-section logout" id="logout" style="display:none;">
            <h2>Logout</h2>
            <p>You have been logged out.</p>
            <section class="aura-box" style="text-align:center;">
            <h2>🚪 Logout</h2>
            <p>You have successfully logged out.</p>
            <a href="login.html" class="aura-btn">Log In Again</a>
            </section>

        </div>
     </div> 


    






    <script src="seller.js"></script>
 
</body>
</html>