document.querySelectorAll(".faq-question").forEach(button => {
    button.addEventListener("click", function() {
        const answer = this.nextElementSibling;
        const icon = this.querySelector(".icon");

        if (answer.style.display === "block") {
            answer.style.display = "none";
            icon.textContent = "+";
            this.classList.remove("active");
        } else {
            answer.style.display = "block";
            icon.textContent = "-";
            this.classList.add("active");
        }
    });
});