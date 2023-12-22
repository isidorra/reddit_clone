document.addEventListener('DOMContentLoaded', function () {
    
    var open = document.getElementById("show-edit-form");
    var close = document.getElementById("close-edit-form");
    var form = document.getElementById("edit-form");
    

    open.addEventListener("click",  () => {
        form.classList.remove("hidden");
    });

    close.addEventListener("click", () => {
        form.classList.add("hidden");
    })
    
  
});