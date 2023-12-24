<?php

    require_once("inc/header.php");
    require_once("app/models/Discussion.php");
    require_once("inc/converts/datetime.php");

    $current_user = new User();
    $logged_user = new User();

    if($user->is_logged()) {
       
        $current_user = $user->get_by_id($_GET["user_id"]);
        $logged_user_id = $_SESSION["user_id"];
 
        if($current_user["user_id"] === $logged_user_id) 
            $isUsersProfile = true;
        else 
            $isUsersProfile = false;
    } else {
        header("Location: login.php");
        exit();
    }

    $discussions = new Discussion();
    $discussions = $discussions->get_by_host($current_user['user_id']);

    $success = false;
    $incorrect_old = false;
    $incorrect_match = false;
    $incorrect_length = false;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        $user = new User();
        $result = $user->change_password($old_password, $new_password, $confirm_password, $current_user["user_id"]);

        if($result == 14) {
            header('Location: profile.php?user_id=<?php echo $_SESSION["user_id"] ?>');
            exit();
        } else if($result == 13) {
            $success = true;
        } else if($result == 12){
            $incorrect_old = true;
        } else if($result == 11) {
            $incorrect_match = true;
        } else {
            $incorrect_length = true;
        }
    }

?>

<div class="max-container">
    <div class="flex flex-col gap-10 md:flex-row px-10">
        <!-- Profile Info -->
        <div class="mt-10 border-b border-gray pb-10 md:border-r md:border-gray md:pr-10 md:border-b-0">
                <div class="text-center">
                    <img src="public/assets/profile-photos/<?php echo $current_user['profile_photo']?>"
                        class="w-24 md:w-36 mx-auto"/>
                </div>



                <div>
                    <p class="uppercase opacity-80 text-blue font-thin text-sm mt-5">Username</p> 
                    <p class="text-xl"><?php echo $current_user['username'] ?></p>
                </div>

 
       
            <p class="uppercase opacity-80 text-blue font-thin text-sm mt-3">Email Address</p> 
            <p class="text-xl"><?php echo $current_user['email'] ?></p>
         
            <p class="text-blue text-sm font-thin mt-5">Joined: <?php echo $current_user['created_at'] ?></p>

            <button id="show-password-form" class="bg-bgColor border border-blue rounded-lg py-1 px-1 text-base font-thin 
                                        mx-auto text-center hover:bg-blue flex items-center gap-2 mt-5 w-full justify-center">
                    <img src="public/assets/icons/key.svg" alt="Key"/>
                    Change Password
            </button>

            <?php if($success): ?>
                        <p class="text-white">Successfully changed password.</p>
            <?php endif; ?>

            <form action="" method="POST" class="mt-9 hidden w-11/12 mx-auto" id="password-form">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-base">Change Password</h3>
                        <img src="public/assets/icons/x-mark.svg" alt="Close" id="close-password-form"/>
                    </div>

                    <input name="old_password" type="password" required placeholder="Old Password" 
                            class="block bg-gray outline-none w-full rounded-full py-3 px-4 mt-1 mb-3"/>
                    <?php if($incorrect_old): ?>
                        <p class="text-red" id="incorrect_error">Incorrect old password.</p>
                    <?php endif; ?>

                    <input name="new_password" type="password" required placeholder="New Password" 
                            class="block bg-gray outline-none w-full rounded-full py-3 px-4 mt-1 mb-3"/>
                    <?php if($incorrect_length): ?>
                        <p class="text-red" id="length_error">Password must contain at least 7 characters.</p>
                    <?php endif; ?>

                    <input name="confirm_password" type="password" required placeholder="Confirm Password" 
                            class="block bg-gray outline-none w-full rounded-full py-3 px-4 mt-1 mb-3"/>
                    <?php if($incorrect_match): ?>
                        <p class="text-red" id="match_error">Passwords must match.</p>
                    <?php endif; ?>

                    
                    <button type="submit" class="bg-primary rounded-full w-full py-2">Submit</button>
            </form>


            

        </div>

        <!-- User's discussions -->
        <div class="mt-10 w-full">
            <h2 class="font-bold text-2xl">My Discussions</h2>

            <?php foreach($discussions as $discussion): ?>
                <div class="border-b border-gray py-7">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">

                                    <p class="text-sm md:text-base opacity-60 font-thin"><?php echo time_elapsed_since_now($discussion['created_at'], $full = false)?></p>

                                    <div class="md:px-1 md:py-1 opacity-60">|</div>

                                    <p>
                                        <span class="text-sm md:text-base opacity-60 font-thin">Topic: </span>
                                        <span class="bg-gray text-sm md:text-base text-primary px-2 py-1 md:px-4 rounded-full md:py-2 ml-2">
                                            <?php echo $discussion['topic_name'] ?>
                                        </span>
                                    </p>

                                </div>
                                <div class="flex items-center gap-2">
                                    <button class="bg-primary px-2 py-1 md:py-2 md:px-4 text-base md:text-lg rounded-full hover:bg-blue duration-100 ease-in">
                                        Enter
                                    </button>
                                    <?php if($isUsersProfile): ?>
                                        <button class="bg-bgColor text-red border border-red hover:border-primary hover:text-primary px-2 py-1 md:py-2 md:px-4 text-base md:text-lg rounded-full">
                                            Delete
                                        </button>
                                    <?php endif; ?>
                                </div>

                    
                                
                            </div>
                            
                            <h3 class="text-xl md:text-2xl mt-6"><?php echo $discussion['subject']; ?></h3>

                            <div class="flex items-center gap-4 mt-7">
                                        <div class="flex items-center gap-2 opacity-50">
                                            <img src="public/assets/icons/thumbs-up.svg"/>
                                            <p>0</p>
                                        </div>
                                        <div class="flex items-center gap-2 opacity-50">
                                            <img src="public/assets/icons/thumbs-down.svg"/>
                                            <p>0</p>
                                        </div>
                                        <div class="flex items-center gap-2 opacity-50">
                                            <img src="public/assets/icons/comment.svg"/>
                                            <p>0</p>
                                        </div>
                            </div>
                            
                        </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>


<?php require_once("inc/footer.php"); ?>

<script src="public/js/edit-password-show-form.js"></script>
