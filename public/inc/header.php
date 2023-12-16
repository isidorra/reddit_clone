
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
        theme: {
            extend: {
                colors: {
                    bgColor: '#0d1416',
                    primary: '#d93a00',
                    gray: '#1a282d',
                    white: '#ffffff',
                    blue: '#57899c'
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

                    <a href="#" class="bg-primary py-2 px-4 rounded-full text-w hover:bg-blue duration-100 ease-in">Log In</a>
                </div>
      
        </div>
    </div>