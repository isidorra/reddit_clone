document.addEventListener('DOMContentLoaded', function () {
    
    var likeBtns = document.querySelectorAll(".like_btn");
    var likeIcon = document.querySelectorAll(".like_icon");
    

    var isLiked = false;

    likeBtns.forEach(function (button, index) {
        button.addEventListener("click", function () {
            isLiked = !isLiked;

            likeIcon[index].src = isLiked
            ? "http://localhost/reddit_clone/public/assets/icons/filled-like.svg"
            : "http://localhost/reddit_clone/public/assets/icons/empty-like.svg";
        });
    });
  
});