document.addEventListener('DOMContentLoaded', function () {
    
    var open = document.getElementById("show-password-form");
    var close = document.getElementById("close-password-form");
    var form = document.getElementById("password-form");

    var old_password_error = document.getElementById("incorrect_error");
    var length_error = document.getElementById("length_error");
    var match_error = document.getElementById("match_error");
    
    if (old_password_error !== null || length_error !== null || match_error !== null) {
        form.classList.remove("hidden");
    }

    open.addEventListener("click",  () => {
        form.classList.remove("hidden");
    });

    close.addEventListener("click", () => {
        form.classList.add("hidden");
    })
    
  
});