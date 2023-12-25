document.addEventListener('DOMContentLoaded', function () {
    var showButtons = document.querySelectorAll(".show-replies-btn");
    var repliesContainers = document.querySelectorAll(".replies");

    showButtons.forEach(function (button, index) {
        button.addEventListener("click", function () {
            repliesContainers[index].classList.remove("hidden");
        });
    });

    var hideButtons = document.querySelectorAll(".hide-replies-btn");
    hideButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            button.closest(".replies").classList.add("hidden");
        });
    });
});