
<?php 
    require_once("inc/header.php");
    require_once("app/models/User.php");

    if($user->is_logged()) {
        header("Location: index.php");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $result = $user->login($username, $password);
        if(!$result) {
            header("Location: login.php");
            exit();
        }

        header("Location: index.php");
        exit();
    }

?>

<!-- TO-DO: Input validation!!!!!!!!!! -->
<form action="" method="POST" class="form-container">
    <h1 class="text-3xl font-medium mb-9 mt-10">My Account</h1>

    <label for="username" class="uppercase font-bold text-sm px-2">Username</label>
    <input name="username" type="text" required placeholder="Username" 
           class="block bg-gray outline-none w-full rounded-full py-3 px-4 mt-1 mb-3"/>

    <label for="password" class="uppercase font-bold text-sm px-2">Password</label>
    <input name="password" type="password" required placeholder="Password" 
           class="block bg-gray outline-none w-full rounded-full py-3 px-4 mt-1 mb-3"/>

    <button type="submit" 
            class="bg-primary w-full py-3 rounded-full mt-3 mb-4 hover:bg-blue duration-100 ease-in">
        Log in
    </button>

    <p>Don't have an account? 
        <a href="register.php" class="text-primary hover:text-blue duration-100 ease-in">
            Register
        </a>
    </p>

</form>




<?php require_once("inc/footer.php"); ?>