<?php
session_start();  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>Login</title>
</head>

<body>
    <div class="h-screen flex md:flex-row flex-col">
        <div class="lg:w-3/5 h-screen custom-py-1p lg:block hidden">
            <div class="bus-background bg-cover w-full h-full rounded-r-3xl"></div>
        </div>

        <div class="flex flex-col py-4 md:1/2 lg:w-2/5 w-full items-center">
            <p class="font-bold lg:text-4xl text-2xl w-full text-center text-[#00446b]">Bus Transportation Management System</p>
            <p class="font-semibold lg:text-3xl text-xl text-center mt-10 text-[#00446b]">&lt;Admin&gt;</p>

            <form class="xl:w-4/6 lg:w-5/6 sm:w-2/3 py-4 rounded-3xl shadow-lg shad mt-10 flex flex-col items-center border" action="login.php" method="POST">
                <p class="text-center mb-4 text-xl text-[#00446b]">Sign In</p>
                <hr class="border w-full border-[#00446b]">

                <?php if (isset($_SESSION['error'])): ?>
                <div class="w-full bg-red-100 text-red-700 text-center p-2 rounded-md">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']); 
                    ?>
                </div>
            <?php endif; ?>
                
                <div class="mt-8 w-4/5">
                    <input class="mt-1 block w-full bg-transparent rounded-md border p-2" type="email" name="email" placeholder="Email" required>
                </div>
                <div class="mt-4 w-4/5">
                    <input class="mt-1 block w-full bg-transparent rounded-md border p-2" type="password" name="password" placeholder="Password" required>
                </div>
            
                <div class="w-4/5 flex justify-between mt-4 lg:mb-12 mb-12">
                    <label class="flex items-center">
                        <input 
                        type="checkbox"
                        name="remember"
                        />
                        <span class="text-sm hover:text-gray-300/50 rounded-md text-[#00446b]">Remember me</span>
                    </label>
                    <a class="text-sm hover:text-gray-300/50 rounded-md text-[#00446b]" href="forgot.php">Forgot password?</a>
                
                </div>
            
                <div class="flex items-center mt-4 mb-8 w-4/5">
                    <button type="submit" class="w-full font-medium p-2 rounded-md border bg-[#00446b]">
                        <p class="text-center text-white">Log In</p>
                    </button>   
                </div>
                <a class="text-sm hover:text-gray-300/50 rounded-md text-[#00446b]" href="register.html">Register</a>
          </form>
        </div>
    </div>
</body>

</html>
