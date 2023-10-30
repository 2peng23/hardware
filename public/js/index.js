const loginButton = document.getElementById("login-btn");
let timeLeft = 15;

function countdown() {
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        document.getElementById("timer").style.display = "none";
    } else {
        document.getElementById("timeLeft").textContent = timeLeft;
        timeLeft--;

        // Disable the button when countdown is not equal to 0
        if (timeLeft !== 0) {
            loginButton.classList.remove("bg-blue-500", "hover:bg-blue-800");
            loginButton.classList.add("bg-gray-500", "hover:bg-gray-800");
            loginButton.disabled = true;
        } else {
            loginButton.classList.remove("bg-gray-500", "hover:bg-gray-800");
            loginButton.classList.add("bg-blue-500", "hover:bg-blue-800");
            loginButton.disabled = false;
        }
    }
}

let timerInterval = setInterval(countdown, 1000); // 1-second countdown
