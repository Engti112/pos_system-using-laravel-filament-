<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
<h1>Laravel + Filament</h1>

<h1>🚀 Laravel POS System - Setup Guide</h1>
<h2>1. Clone the Repository</h2>
<pre><code>git clone https://github.com/Engti112/pos_system-using-laravel-filament-.git
cd pos_system-using-laravel-filament-
</code></pre>
<h2>2. Install Dependencies</h2>
<p>Ensure <strong>Composer</strong> is installed, then run:</p>
    <pre><code>composer install</code></pre>
<h2>3. Configure Environment</h2>
    <p>Create a <code>.env</code> file by copying <code>.env.example</code>:</p>
    <pre><code>cp .env.example .env</code></pre>
    <P>Download Postgres if you don't have</P>
    <pre><code>https://www.enterprisedb.com/downloads/postgres-postgresql-downloads</code></pre>
<p>Then, update the database configuration in <code>.env</code>:</p>
    <pre><code>DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pos_system
DB_USERNAME=your_pg_user
DB_PASSWORD=your_pg_password
</code></pre>

<h2>4. Run Migrations </h2>
    <pre><code>php artisan migrate</code></pre>
   

 <h2>5. Check Migrations</h2>
 <p>Check migration status:</p>
    <pre><code>php artisan migrate:status</code></pre>


<p>If you want a fresh database:</p>
    <pre><code>php artisan migrate:fresh</code></pre>

<h2>6. Seed Database</h2>
    <p>To seed the database with test users:</p>
    <pre><code>php artisan db:seed --class=UserSeeder</code></pre>
     <p>Or migrate and seed together:</p>
    <pre><code>php artisan migrate:fresh --seed</code></pre>
     <p>note that </p>
    <pre><code>'email' => admin@gmail.com</code></pre>
    <pre><code>'password' => password</code></pre>

 <h2>7. Start the Development Server</h2>
    <pre><code>php artisan serve</code></pre>
    <p>Your application will be available at <strong>http://127.0.0.1:8000</strong></p>
<p>go to <strong>http://127.0.0.1:8000/admin</strong></p>
<h2>8. Run Tinker for Testing</h2>
    <p>To interact with the database via Laravel Tinker:</p>
    <pre><code>php artisan tinker</code></pre>
    <p>Create a Product (If Not Already Created)</p>
    <pre><code> $product = \App\Models\Product::create([
    'name' => 'Test Product',
    'barcode' => '123456789',
    'description' => 'Sample product for testing',
]);
 </code></pre>
    <p>Add a Stock Entry with an Expiry Date</p>
    <pre><code>  $stock = \App\Models\StockEntry::create([
    'product_id' => $product->id,
    'quantity' => 20,
    'purchase_price' => 50,
    'selling_price' => 75,
    'expiry_date' => '2025-04-01',
]);
</code></pre>
    <p>Add a Low Stock Alert</p>
    <pre><code> use App\Models\LowStockAlert;
use App\Models\Product;
    $product = Product::first();
    LowStockAlert::create([
    'product_id' => $product->id,
    'threshold' => 10, // Example threshold
    'current_stock' => 5, // Below threshold
    'alert_sent' => false,
]);</code></pre>
    

<p>For example, retrieving all sales items:</p>
<pre><code>use App\Models\SaleItem;
SaleItem::all();
</code></pre>


