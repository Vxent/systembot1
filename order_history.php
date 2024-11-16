<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch the user's order history from the database
$userId = $_SESSION['user_id'];
$query = "
    SELECT o.id, p.name AS product_name, p.price AS product_price, p.image_url AS product_image, o.order_date, o.status
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC
";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 p-6">
<nav class="bg-black shadow-md top-0 left-0 w-full z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-2">
            <div class="flex-1 flex justify-start">
                <div class="hidden md:flex space-x-4 p-2">
                    <a href="index.php" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Home</a>
                    
                    <a href="#about" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">About</a>
                    <a href="#threats" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Services</a>
                </div>
            </div>
            <div class="flex-1 flex justify-center">
                <div class="text-center">
                    <img src="images/logo1.png" alt="" width="200px" class="h-20">  
                </div>
            </div>
            <div class="flex-1 flex justify-end">
                <div class="hidden md:flex space-x-4 p-2">
                    
                    <a href="apparelShop.php" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Shop</a>
                    <a href="order_history.php" class=" px-2 py-1 font-abhaya-libre uppercase text-white tracking-wider px-4 xl:px-8 py-2 text-sm hover:underline">Order History </a>
                    <a href="logout.php"><button type="submit" class="py-2 px-4 border-2 border-white bg-gradient-to-r from-yellow-500 to-orange-600 text-white py-2 rounded-md shadow-lg hover:from-yellow-600  hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">LOGOUT</button></a>
                </div>
            </div>
            <!-- Hamburger Button for Mobile View -->
            <div class="md:hidden flex items-center">
                <button id="navbar-toggle" class="text-white focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div id="navbar-menu" class="navbar-menu md:hidden hidden">
        <a href="#Main" class="block px-4 py-2 text-white hover:bg-gray-700">Home</a>
        <a href="#varieties" class="block px-4 py-2 text-white hover:bg-gray-700">Sports</a>
        <a href="#about" class="block px-4 py-2 text-white hover:bg-gray-700">About</a>
        <a href="contacts.html" class="block px-4 py-2 text-white hover:bg-gray-700">Contacts</a>
    </div>
</nav>

<!-- Navbar Script -->
<script>
    document.getElementById('navbar-toggle').addEventListener('click', function() {
        var menu = document.getElementById('navbar-menu');
        menu.classList.toggle('hidden'); // Toggle the 'hidden' class
    });
</script>

    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-4">Your Order History</h2>
       

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-800">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="px-4 py-2 border-b">Order ID</th>
                        <th class="px-4 py-2 border-b">Product Image</th>
                        <th class="px-4 py-2 border-b">Product Name</th>
                        <th class="px-4 py-2 border-b">Price</th>
                        <th class="px-4 py-2 border-b">Order Date</th>
                        <th class="px-4 py-2 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="px-4 py-2 border-b">
                                    <img src="<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" class="w-16 h-16 object-cover">
                                </td>
                                <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($row['product_price']); ?></td>
                                <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($row['order_date']); ?></td>
                                <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="px-4 py-2 border-b text-center">No orders found.</td>
                        </tr>
                        
                    <?php } ?>
                </tbody>
                
            </table>
     <div class="pt-5 text-center">
     <a href="customizedOrderHistory.php" class="inline-block px-6 py-2 text-white font-bold bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300 uppercase">
  Link to customized orders
</a>

     </div>

        </div>
    </div>

    <?php $stmt->close(); $db->close(); ?>
</body>
</html>