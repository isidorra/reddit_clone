
<?php 
    require_once("inc/header.php");
    require_once("app/models/User.php");

    if($user->is_logged()) {
        header("Location: index.php");
        exit();
    }

    

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $email = $_POST['email'];

        if(!$user->is_unique($username)) {
            $username_error_message = "This username is already taken. Please try again."; 
        } else {
            $created = $user->create($username, $password, $email);

            if(!$created) {
                header("Location: register.php");
                exit();
    
            }
    
            header("Location: login.php");
            exit();
        }

        
        
    }
?>



<form action="" method="POST" class="form-container" id="form">
    <h1 class="text-3xl font-medium mb-9 mt-10">New Account</h1>

    <label for="username" class="uppercase font-bold text-sm px-2">Username</label>
    <input name="username" type="text" required placeholder="Username" id="username"
           class="block bg-gray outline-none w-full rounded-full py-3 px-4 mt-1 mb-3"/>
    <?php if(isset($username_error_message)): ?>
        <p class="text-red"> <?php echo $username_error_message ?> </p>
    <?php endif; ?>

    <label for="email" class="uppercase font-bold text-sm px-2">Email Address</label>
    <input name="email" type="email" required placeholder="Email Address" id="email"
           class="block bg-gray outline-none w-full rounded-full py-3 px-4 mt-1 mb-3"/>
    <p class="text-red" id="email_error"></p>

    <label for="password" class="uppercase font-bold text-sm px-2">Password</label>
    <input name="password" type="password" required placeholder="Password" id="password"
           class="block bg-gray outline-none w-full rounded-full py-3 px-4 mt-1 mb-3"/>
    <p class="text-red" id="password_error"></p>

    <label for="confirm_password" class="uppercase font-bold text-sm px-2">Confirm Password</label>
    <input name="confirm_password" type="password" required placeholder="Confirm Password" id="confirm_password"
           class="block bg-gray outline-none w-full rounded-full py-3 px-4 mt-1 mb-3"/>
    <p class="text-red" id="match_error"></p>
    <button type="submit" 
            class="bg-primary w-full py-3 rounded-full mt-3 mb-4 hover:bg-blue duration-100 ease-in">
        Register
    </button>

    <p>Already have an account? 
        <a href="login.php" class="text-primary hover:text-blue duration-100 ease-in">
            Log In
        </a>
    </p>

</form>




<?php require_once("inc/footer.php"); ?>
<script src="public/js/auth-validation.js"></script>