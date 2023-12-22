


document.addEventListener('DOMContentLoaded', function () {
    
    var btn = document.getElementById("dropdown-btn");
    var arrowIcon = document.getElementById("arrow-icon");
    var dropdown = document.getElementById("dropdown-menu");

    var isDropdownOpen = false;

    btn.addEventListener("click",  () => {
        isDropdownOpen = !isDropdownOpen;

        arrowIcon.src = isDropdownOpen
            ? "http://localhost/reddit_clone/public/assets/icons/chevron-up.svg"
            : "http://localhost/reddit_clone/public/assets/icons/chevron-down.svg";
            

        if (isDropdownOpen) {
            dropdown.classList.remove("hidden");
        } else {
            dropdown.classList.add("hidden");
        }
    })
    
  
});

