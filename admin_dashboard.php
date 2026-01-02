<?php
session_start();
// ---------------------- DB CONFIG ----------------------
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'agrimarket',
    'user' => 'root',
    'pass' => ''
];
try {
    $pdo = new PDO("mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4", $dbConfig['user'], $dbConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed: '.$e->getMessage()]);
    exit;
}

// ---------------------- HELPERS ----------------------
function jsonResponse($data){
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function requireAdmin(){
    if(empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
        jsonResponse(['error' => 'Unauthorized']);
    }
}

// ---------------------- API ROUTING ----------------------
$action = isset($_GET['action']) ? $_GET['action'] : '';
if($action){
    switch($action){
        case 'login':
            // JSON login
            $input = json_decode(file_get_contents('php://input'), true);
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if($user && password_verify($password, $user['password']) && $user['role']=='admin' && $user['status']=='active'){
                unset($user['password']);
                $_SESSION['user'] = $user;
                jsonResponse(['ok'=>true,'user'=>$user]);
            } else {
                jsonResponse(['ok'=>false,'error'=>'Invalid credentials or not an active admin.']);
            }
            break;

        case 'logout':
            session_destroy();
            jsonResponse(['ok'=>true]);
            break;

        case 'stats':
            requireAdmin();
            $totalUsers = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
            $totalSellers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='seller'")->fetchColumn();
            $totalProducts = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
            $pendingSellers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='seller' AND status='pending'")->fetchColumn();
            $pendingProducts = $pdo->query("SELECT COUNT(*) FROM products WHERE status='pending'")->fetchColumn();
            $totalOrders = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
            jsonResponse(compact('totalUsers','totalSellers','totalProducts','pendingSellers','pendingProducts','totalOrders'));
            break;

        case 'get_users':
            requireAdmin();
            $q = $_GET['q'] ?? '';
            if($q){
                $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC");
                $stmt->execute(["%$q%","%$q%"]);
                $rows = $stmt->fetchAll();
            } else {
                $stmt = $pdo->query('SELECT * FROM users ORDER BY created_at DESC LIMIT 200');
                $rows = $stmt->fetchAll();
            }
            jsonResponse(['users'=>$rows]);
            break;

        case 'update_user_status':
            requireAdmin();
            $input = json_decode(file_get_contents('php://input'), true);
            $user_id = (int)($input['user_id'] ?? 0);
            $status = $input['status'] ?? 'disabled';
            $stmt = $pdo->prepare('UPDATE users SET status=? WHERE user_id=?');
            $stmt->execute([$status,$user_id]);
            jsonResponse(['ok'=>true]);
            break;

        case 'get_products':
            requireAdmin();
            // Removed category dependency — select only seller name and the product fields
            $stmt = $pdo->query('SELECT p.*, u.name as seller_name FROM products p LEFT JOIN users u ON p.seller_id=u.user_id ORDER BY p.date_added DESC LIMIT 500');
            $rows = $stmt->fetchAll();
            jsonResponse(['products'=>$rows]);
            break;

        case 'update_product_status':
            requireAdmin();
            $input = json_decode(file_get_contents('php://input'), true);
            $product_id = (int)($input['product_id'] ?? 0);
            $status = $input['status'] ?? 'blocked';
            $stmt = $pdo->prepare('UPDATE products SET status=? WHERE product_id=?');
            $stmt->execute([$status,$product_id]);
            jsonResponse(['ok'=>true]);
            break;

        case 'add_product':
            requireAdmin();

            // Accept multipart/form-data POST (FormData from client)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // ensure uploads folder exists
                $uploadDir = __DIR__ . '/uploads';
                if(!is_dir($uploadDir)){
                    if(!mkdir($uploadDir, 0755, true)){
                        jsonResponse(['ok'=>false,'error'=>'Failed to create uploads directory']);
                    }
                }

                $name        = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $price       = trim($_POST['price'] ?? '');
                // quantity treated as varchar; accept it as string
                $quantity    = trim($_POST['quantity'] ?? '');
                $seller_id   = trim($_POST['seller_id'] ?? '');

                if(!$name || $price === '' || $quantity === '' || !$seller_id){
                    jsonResponse(['ok'=>false,'error'=>'All fields are required']);
                }

                // Validate price numeric-ish
                if(!is_numeric($price)){
                    jsonResponse(['ok'=>false,'error'=>'Price must be numeric']);
                }

                // Image handling
                $newName = null;
                if(!empty($_FILES['image']['name'])){
                    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    if(!in_array($ext, ['jpg','jpeg','png'])){
                        jsonResponse(['ok'=>false,'error'=>'Invalid image format (jpg/jpeg/png only)']);
                    }
                    $newName = uniqid('prod_', true) . '.' . $ext;
                    $target = $uploadDir . '/' . $newName;
                    if(!move_uploaded_file($_FILES['image']['tmp_name'], $target)){
                        jsonResponse(['ok'=>false,'error'=>'Failed to upload image']);
                    }
                }

                // Insert into products; note: quantity saved as text (VARCHAR) in DB per your request
                $stmt = $pdo->prepare(
                    'INSERT INTO products (seller_id, name, description, price, quantity, image, status, date_added)
                     VALUES (?, ?, ?, ?, ?, ?, "pending", NOW())'
                );
                $stmt->execute([$seller_id, $name, $description, $price, $quantity, $newName]);

                jsonResponse(['ok'=>true,'message'=>'Product added successfully']);
            } else {
                jsonResponse(['ok'=>false,'error'=>'Invalid request method']);
            }
            break;

        case 'get_orders':
            requireAdmin();
            $stmt = $pdo->query('SELECT o.*, u.name as customer_name FROM orders o LEFT JOIN users u ON o.customer_id=u.user_id ORDER BY o.order_date DESC LIMIT 500');
            $rows = $stmt->fetchAll();
            jsonResponse(['orders'=>$rows]);
            break;

        case 'update_order_status':
            requireAdmin();
            $input = json_decode(file_get_contents('php://input'), true);
            $order_id = (int)($input['order_id'] ?? 0);
            $status = $input['status'] ?? 'processing';
            $stmt = $pdo->prepare('UPDATE orders SET status=? WHERE order_id=?');
            $stmt->execute([$status,$order_id]);
            jsonResponse(['ok'=>true]);
            break;

        default:
            jsonResponse(['error'=>'Unknown action']);
    }
}

// If no action provided, render the SPA HTML below (admin_dashboard.php)
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>AgriMarket Admin Dashboard</title>
  <style>
     :root{
    --bg:#f5f8f4;
    --card:#ffffff;
    --accent:#4caf50;
    --accent-dark:#388e3c;
    --muted:#6b7280;
    --danger:#d32f2f;
    --soil:#8d6e63;
}
*{box-sizing:border-box}
body{
    font-family:'Segoe UI',Roboto,Arial,sans-serif;
    margin:0;
    background:var(--bg) url('https://images.unsplash.com/photo-1524594154905-1f014e8ea1a7?auto=format&fit=crop&w=1600&q=60')
        no-repeat center fixed;
    background-size:cover;
    color:#2e2e2e;
}
body::before{
    content:"";
    position:fixed;
    inset:0;
    background:rgba(245,248,244,0.92);
    pointer-events:none;
    z-index:-1;
}
.app{display:flex;height:100vh}
.sidebar{
    width:260px;
    background:linear-gradient(180deg,var(--accent),var(--accent-dark));
    color:#fff;
    padding:20px;
    display:flex;
    flex-direction:column;
    box-shadow:0 0 12px rgba(0,0,0,0.25);
}
.logo{font-weight:700;font-size:20px;margin-bottom:16px;text-shadow:0 1px 2px rgba(0,0,0,0.2);}
.nav a{display:block;padding:12px;border-radius:8px;color:#fff;text-decoration:none;margin-bottom:8px;transition:background 0.2s;}
.nav a.active,.nav a:hover{background:rgba(255,255,255,0.15);}
.main{flex:1;padding:24px;overflow:auto;backdrop-filter:blur(2px);}
.topbar h2{color:var(--accent-dark)}
.card{background:var(--card);padding:20px;border-radius:12px;flex:1;min-width:220px;box-shadow:0 4px 12px rgba(0,0,0,0.08);border-top:4px solid var(--accent);transition:transform .2s;}
.card:hover{transform:translateY(-3px)}
.card h3{margin:0 0 8px;font-size:15px;color:var(--soil)}
.card p{font-size:26px;margin:0;font-weight:700;color:var(--accent-dark)}
.panel{margin-top:20px;background:var(--card);padding:18px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.05);}
table{width:100%;border-collapse:collapse}
th,td{padding:10px;border-bottom:1px solid #e0e0e0;text-align:left;}
th{background:#f1f8e9;color:var(--accent-dark);}
.btn{display:inline-block;padding:8px 12px;border-radius:6px;border:none;cursor:pointer;font-weight:600;}
.btn.primary{background:var(--accent);color:#fff}
.btn.primary:hover{background:var(--accent-dark)}
.btn.warn{background:#fbc02d;color:#fff}
.btn.danger{background:var(--danger);color:#fff}
.search input[type=text]{padding:8px;border-radius:6px;border:1px solid #cfd8dc;}
.small{font-size:13px;color:#666}
  </style>
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="logo">AgriMarket - Admin</div>
      <nav class="nav">
        <a href="#" data-view="home" class="active">Dashboard Home</a>
        <a href="#" data-view="users">Manage Users</a>
        <a href="#" data-view="products">Manage Products</a>
        <a href="#" data-view="orders">Manage Orders</a>
        <a href="#" data-view="reports">Reports</a>
      </nav>
      <div class="profile" style="margin-top:auto">
        <div id="adminName" style="font-weight:600">Not signed in</div>
        <div class="small">Administrator</div>
        <div style="margin-top:8px"><button id="logoutBtn" class="btn" style="background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.12)">Logout</button></div>
      </div>
    </aside>

    <main class="main">
      <div class="topbar" style="display:flex;justify-content:space-between;align-items:center">
        <div>
          <h2 id="viewTitle">Dashboard Home</h2>
          <div class="small">Welcome to AgriMarket admin panel</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center">
          <div class="small" id="timeNow"></div>
          <button id="refreshBtn" class="btn">Refresh</button>
        </div>
      </div>

      <div id="viewContent">
        <!-- HOME -->
        <div data-page="home">
          <div class="cards" style="display:flex;gap:12px;flex-wrap:wrap">
            <div class="card">
              <h3>Total Users</h3>
              <p id="statUsers">—</p>
            </div>
            <div class="card">
              <h3>Total Sellers</h3>
              <p id="statSellers">—</p>
            </div>
            <div class="card">
              <h3>Total Products</h3>
              <p id="statProducts">—</p>
            </div>
            <div class="card">
              <h3>Pending Sellers</h3>
              <p id="statPendingSellers">—</p>
            </div>
            <div class="card">
              <h3>Pending Products</h3>
              <p id="statPendingProducts">—</p>
            </div>
            <div class="card">
              <h3>Total Orders</h3>
              <p id="statOrders">—</p>
            </div>
          </div>

          <div class="panel">
            <h3 style="margin:0 0 12px">Recent Activity / Quick Actions</h3>
            <div style="display:flex;gap:8px;flex-wrap:wrap">
              <button class="btn primary" id="goUsers">Review Users</button>
              <button class="btn primary" id="goProducts">Review Products</button>
              <button class="btn primary" id="goOrders">View Orders</button>
            </div>
          </div>
        </div>

        <!-- USERS -->
        <div data-page="users" style="display:none">
          <div class="panel">
            <div style="display:flex;justify-content:space-between;align-items:center">
              <h3 style="margin:0">Users</h3>
              <div class="search"><input id="userSearch" type="text" placeholder="Search name or email"><button id="searchUsers" class="btn">Search</button></div>
            </div>
            <div style="margin-top:12px;overflow:auto">
              <table id="usersTable"><thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead><tbody></tbody></table>
            </div>
          </div>
        </div>

        <!-- PRODUCTS -->
<div data-page="products" style="display:none">
  <div class="panel">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <h3 style="margin:0">Products</h3>
      <div class="search">
        <input id="prodSearch" type="text" placeholder="Filter by name">
        <button id="searchProducts" class="btn">Search</button>
      </div>
    </div>

    <!-- ADD PRODUCT FORM -->
    <div style="margin-top:16px;margin-bottom:20px;background:#f9f9f9;padding:16px;border-radius:8px;border:1px solid #ddd;">
      <h4 style="margin:0 0 12px">Add New Product</h4>
      <form id="addProductForm" enctype="multipart/form-data" method="POST" action="?action=add_product" style="display:flex;flex-wrap:wrap;gap:8px">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="number" name="price" placeholder="Price" step="0.01" required>
        <input type="text" name="quantity" placeholder="Quantity (text allowed)" required>

        <!-- Seller Dropdown -->
        <select name="seller_id" required>
            <option value="">-- Select Seller --</option>
            <?php
            $sellers = $pdo->query("SELECT user_id, name FROM users WHERE role='seller' AND status='active' ORDER BY name");
            while ($seller = $sellers->fetch()) {
                echo "<option value='{$seller['user_id']}'>".htmlspecialchars($seller['name'])."</option>";
            }
            ?>
        </select>

        <!-- File input -->
        <input type="file" name="image" accept="image/*" required>

        <button class="btn primary" type="submit">Add Product</button>
      </form>

      <p class="small" style="margin-top:6px;color:#777">
        * Image will be stored in the <b>uploads</b> folder and referenced in the database.
      </p>
    </div>

    <div style="overflow:auto">
      <table id="productsTable">
        <thead>
          <tr>
            <th>#</th><th>Name</th><th>Seller</th><th>Price</th><th>Qty</th><th>Image</th><th>Status</th><th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

        <!-- ORDERS -->
        <div data-page="orders" style="display:none">
          <div class="panel">
            <h3 style="margin:0">Orders</h3>
            <div style="margin-top:12px;overflow:auto">
              <table id="ordersTable"><thead><tr><th>#</th><th>Customer</th><th>Total</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead><tbody></tbody></table>
            </div>
          </div>
        </div>

        <!-- REPORTS -->
        <div data-page="reports" style="display:none">
          <div class="panel">
            <h3 style="margin:0">Reports</h3>
            <div style="margin-top:12px">
              <p class="small">Basic reporting built-in: you can extend to weekly sales, top products, seller performance.</p>
              <div style="margin-top:12px"><button id="downloadReport" class="btn primary">Download CSV (mock)</button></div>
            </div>
          </div>
        </div>

      </div>

    </main>
  </div>

   <script>
    // Simple SPA navigation
    const navLinks = document.querySelectorAll('.nav a');
    const pages = document.querySelectorAll('[data-page]');
    const viewTitle = document.getElementById('viewTitle');
    function showView(name){
      pages.forEach(p=>p.style.display = p.getAttribute('data-page')===name ? '' : 'none');
      navLinks.forEach(a=>a.classList.toggle('active', a.dataset.view===name));
      viewTitle.textContent = ({home:'Dashboard Home',users:'Manage Users',products:'Manage Products',orders:'Manage Orders',reports:'Reports'})[name] || name;
      if(name==='home') loadStats();
      if(name==='users') loadUsers();
      if(name==='products') loadProducts();
      if(name==='orders') loadOrders();
    }
    navLinks.forEach(a=>a.addEventListener('click',e=>{e.preventDefault(); showView(a.dataset.view);}));

    // Quick nav from buttons
    document.getElementById('goUsers').onclick = ()=> showView('users');
    document.getElementById('goProducts').onclick = ()=> showView('products');
    document.getElementById('goOrders').onclick = ()=> showView('orders');

    // Time
    function updateTime(){ document.getElementById('timeNow').textContent = new Date().toLocaleString(); }
    setInterval(updateTime,1000); updateTime();

    // API helper (JSON actions)
    async function api(action, method='GET', body=null){
      const url = `?action=${action}`;
      const opts = {method,headers:{'Accept':'application/json'}};
      if(body!==null){opts.body = JSON.stringify(body); opts.headers['Content-Type']='application/json';}
      const res = await fetch(url,opts);
      const ct = res.headers.get('content-type') || '';
      if(ct.includes('application/json')){
        return res.json();
      } else {
        return {error:'Invalid response from server'};
      }
    }

    // Auth & Stats
    async function loadStats(){
      const r = await api('stats');
      if(r.error){ promptLogin(); return; }
      document.getElementById('statUsers').textContent = r.totalUsers;
      document.getElementById('statSellers').textContent = r.totalSellers;
      document.getElementById('statProducts').textContent = r.totalProducts;
      document.getElementById('statPendingSellers').textContent = r.pendingSellers;
      document.getElementById('statPendingProducts').textContent = r.pendingProducts;
      document.getElementById('statOrders').textContent = r.totalOrders;
    }

    // USERS
    async function loadUsers(q=''){
      const url = q ? `?action=get_users&q=${encodeURIComponent(q)}` : '?action=get_users';
      const res = await fetch(url);
      const data = await res.json();
      if(data.error){ alert('Session expired or error: '+ (data.error||'')); promptLogin(); return; }
      const tbody = document.querySelector('#usersTable tbody'); tbody.innerHTML='';
      data.users.forEach((u,i)=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${i+1}</td><td>${u.name||''}</td><td>${u.email||''}</td><td>${u.role||''}</td><td>${u.status||''}</td><td>${u.created_at||''}</td><td>
          ${u.status!=='active'?`<button class="btn primary" onclick="changeUserStatus(${u.user_id},'active')">Activate</button>`:''}
          ${u.status!=='disabled'?`<button class="btn danger" onclick="changeUserStatus(${u.user_id},'disabled')">Disable</button>`:''}
        </td>`;
        tbody.appendChild(tr);
      });
    }
    window.changeUserStatus = async function(user_id,status){
      if(!confirm('Change status?')) return;
      const res = await api('update_user_status','POST',{user_id,status});
      if(res.ok) loadUsers(); else alert('Error');
    }
    document.getElementById('searchUsers').onclick = ()=> loadUsers(document.getElementById('userSearch').value);

    // PRODUCTS
    async function loadProducts(q=''){
      const res = await api('get_products');
      if(res.error){ alert(res.error); promptLogin(); return; }
      const tbody = document.querySelector('#productsTable tbody'); tbody.innerHTML='';
      res.products.forEach((p,i)=>{
        const imgTag = p.image ? `<img src="uploads/${encodeURIComponent(p.image)}" style="width:60px;height:auto;border-radius:4px">` : '—';
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${i+1}</td>
            <td>${escapeHtml(p.name)}</td>
            <td>${escapeHtml(p.seller_name||'')}</td>
            <td>${p.price}</td>
            <td>${escapeHtml(p.quantity)}</td>
            <td>${imgTag}</td>
            <td>${p.status}</td>
            <td>
              ${p.status!=='approved'?`<button class="btn primary" onclick="changeProductStatus(${p.product_id},'approved')">Approve</button>`:''}
              ${p.status!=='blocked'?`<button class="btn danger" onclick="changeProductStatus(${p.product_id},'blocked')">Block</button>`:''}
            </td>`;
        tbody.appendChild(tr);
      });
    }
    window.changeProductStatus = async function(product_id,status){
      if(!confirm('Change product status?')) return;
      const res = await api('update_product_status','POST',{product_id,status});
      if(res.ok) loadProducts(); else alert('Error');
    }
    document.getElementById('searchProducts').onclick = ()=> loadProducts(document.getElementById('prodSearch').value);

    // Simple html-escape helper
    function escapeHtml(s){
      if(!s) return '';
      return String(s).replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m]; });
    }

    // Add product form: use FormData to send file and fields to ?action=add_product
    document.getElementById('addProductForm').addEventListener('submit', async function(e){
      e.preventDefault();
      const form = this;
      const fd = new FormData(form);
      // Send to same file with action=add_product (server expects multipart)
      const res = await fetch('?action=add_product', { method:'POST', body: fd });
      const data = await res.json();
      if(data.ok){
        alert(data.message || 'Product added');
        form.reset();
        loadProducts();
      } else {
        alert(data.error || 'Failed to add product');
      }
    });

    // ORDERS
    async function loadOrders(){
      const res = await api('get_orders');
      if(res.error){ alert(res.error); promptLogin(); return; }
      const tbody = document.querySelector('#ordersTable tbody'); tbody.innerHTML='';
      res.orders.forEach((o,i)=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${i+1}</td><td>${escapeHtml(o.customer_name||'')}</td><td>${o.total_amount}</td><td>${o.order_date}</td><td>${o.status}</td><td>
          <select onchange="updateOrder(${o.order_id}, this.value)"><option value="processing">Processing</option><option value="shipped">Shipped</option><option value="delivered">Delivered</option><option value="cancelled">Cancelled</option></select>
        </td>`;
        tbody.appendChild(tr);
      });
    }
    window.updateOrder = async function(order_id,status){
      if(!confirm('Update order status to '+status+'?')) return;
      const res = await api('update_order_status','POST',{order_id,status});
      if(res.ok) loadOrders(); else alert('Error');
    }

    // Logout
    document.getElementById('logoutBtn').onclick = async ()=>{
      await api('logout','POST', {});
      promptLogin();
    }

    // Manual refresh
    document.getElementById('refreshBtn').onclick = ()=>{ const v = document.querySelector('.nav a.active').dataset.view; showView(v); };

    // Simple Login modal (browser prompt for demo).
    async function promptLogin(){
      const email = prompt('Admin email:','admin@example.com');
      if(!email) return;
      const password = prompt('Password:');
      if(!password) return;
      const res = await api('login','POST',{email,password});
      if(res.ok){
        document.getElementById('adminName').textContent = res.user.name || res.user.email;
        showView('home');
        loadStats();
      } else {
        alert(res.error || 'Login failed');
        promptLogin();
      }
    }

    // On page load, try stats to check session, else prompt
    (async ()=>{
      const s = await api('stats');
      if(s.error) promptLogin(); else { document.getElementById('adminName').textContent = (s.admin_name||'Admin'); showView('home'); }
    })();

    // Download CSV (mock)
    document.getElementById('downloadReport').onclick = ()=>{
      alert('CSV export placeholder — implement server-side report generation to download.');
    }

  </script>
</body>
</html>
