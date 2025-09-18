const wheel = document.getElementById("wheel");
const spinBtn = document.getElementById("spinBtn");

spinBtn.addEventListener("click", () => {
    let randomDegree = Math.floor(Math.random() * 360) + 1800; // 5 putaran min
    wheel.style.transition = "transform 5s ease-out";
    wheel.style.transform = `rotate(${randomDegree}deg)`;
});
