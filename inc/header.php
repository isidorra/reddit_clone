<?php 
    require_once("app/config/config.php");
    require_once("app/models/User.php");

    $user = new User();
    if($user->is_logged()) {
        $logged_user = $user->get_by_id($_SESSION['user_id']);
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="public/js/dropdown-header.js"></script>
    <script>
        tailwind.config = {
        theme: {
            extend: {
                colors: {
                    bgColor: '#0d1416',
                    primary: '#d93a00',
                    gray: '#1a282d',
                    white: '#ffffff',
                    blue: '#57899c',
                    red: '#fa3e3e'
                },
                fontFamily: {
                    ubuntu: ['Ubuntu', 'sans-serif']
                }
            }
        }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .content-auto {
                content-visibility: auto;
            }

            .max-container {
                max-width: 1400px;
                margin: auto;
            }

            .form-container {
                max-width: 500px;
                margin: auto;
                padding: 10px;
            }
        }
    </style>
    <title>Reddit Clone</title>
</head>
<body class="font-ubuntu bg-bgColor text-white">
    <div class="max-container">
        <div class="flex items-center justify-between py-4 px-4 border-b border-gray">
                <a href="index.php" class="flex items-center gap-2">
                    <img src="public/assets/icons/logo.svg" alt="Logo" class="bg-white rounded-full px-1 py-1 md:px-3 md:py-2"/>
                    <p class="text-xl md:text-3xl font-bold">discussify</p>
                </a>

                <div class="flex items-center gap-3">
                    <form class="w-60 flex items-center gap-2 bg-gray rounded-full py-2 px-4 invisible md:visible">
                        <img src="public/assets/icons/magnifying_glass.svg" alt="Search"/>
                        <input placeholder="Search Discussify" class="bg-gray block w-full outline-none"/>
                    </form>

                    <div>
                        <?php if($user->is_logged()): ?>
                            <button class="flex items-center gap-2" id="dropdown-btn">
                                <img src="public/assets/profile-photos/<?php echo $logged_user['profile_photo']?>" class="w-6 md:w-8" alt="Profile Photo"/>
                                <?php echo $logged_user['username'] ?>
                                <img src="public/assets/icons/chevron-down.svg" alt="Arrow" id="arrow-icon"/>
                            </button>
                            <ul class="absolute bg-gray w-auto py-5 px-5 rounded-lg mt-2 drop-shadow-2xl hidden" id="dropdown-menu">
                                <li class="mb-3"><a href="profile.php?user_id=<?php echo $_SESSION['user_id'] ?>">My Profile</a></li>
                                <li class="text-blue mb-3"><hr/></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
            
                        <?php else: ?>
                            <a href="login.php" class="bg-primary py-2 px-4 rounded-full text-w hover:bg-blue duration-100 ease-in">
                                Log In
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
      
        </div>
    </div>