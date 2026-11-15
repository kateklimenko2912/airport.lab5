// Знаходимо потрібні елементи сторінки.
const plane = document.getElementById("plane");
const scene = document.getElementById("scene");

const startButton = document.getElementById("startButton");
const pauseButton = document.getElementById("pauseButton");
const resetButton = document.getElementById("resetButton");

const speedSelect = document.getElementById("speed");
const statusText = document.getElementById("status");
const progressText = document.getElementById("progress");
const progressFill = document.getElementById("progressFill");
const progressBar = document.getElementById("progressBar");

// Поточний стан анімації.
let progress = 0;
let speed = 1;
let isRunning = false;
let lastTime = null;
let animationId = null;

// Тривалість польоту на швидкості ×1: 10 секунд.
const duration = 10000;

// Оновлення положення літака і прогресу.
function render() {
    const availableWidth = Math.max(
        0,
        scene.clientWidth - plane.clientWidth
    );

    const x = progress * availableWidth;

    // Після 25% шляху починається набір висоти.
    const takeoffProgress = Math.max(
        0,
        (progress - 0.25) / 0.75
    );

    const y = takeoffProgress * (scene.clientHeight - 150);

    plane.style.transform =
        `translate(${x}px, ${-y}px)`;

    const percent = Math.round(progress * 100);

    progressText.textContent = `${percent}%`;
    progressFill.style.width = `${percent}%`;
    progressBar.setAttribute("aria-valuenow", percent);
}

// Один кадр анімації.
function animate(currentTime) {
    if (!isRunning) {
        return;
    }

    if (lastTime === null) {
        lastTime = currentTime;
    }

    const deltaTime = currentTime - lastTime;
    lastTime = currentTime;

    progress = Math.min(
        1,
        progress + (deltaTime / duration) * speed
    );

    render();

    if (progress < 1) {
        if (progress < 0.25) {
            statusText.textContent = "Розгін на смузі";
        } else {
            statusText.textContent = "Літак набирає висоту";
        }

        animationId = requestAnimationFrame(animate);
    } else {
        isRunning = false;
        animationId = null;

        statusText.textContent = "Політ завершено";
        startButton.textContent = "▶ Повторити";

        startButton.disabled = false;
        pauseButton.disabled = true;
    }
}

// Почати або продовжити політ.
function startFlight() {
    if (isRunning) {
        return;
    }

    if (progress >= 1) {
        progress = 0;
    }

    isRunning = true;
    lastTime = null;

    startButton.disabled = true;
    pauseButton.disabled = false;

    statusText.textContent = "Починаємо політ";

    animationId = requestAnimationFrame(animate);
}

// Призупинити політ.
function pauseFlight() {
    if (!isRunning) {
        return;
    }

    isRunning = false;

    cancelAnimationFrame(animationId);

    animationId = null;
    lastTime = null;

    statusText.textContent = "Політ призупинено";
    startButton.textContent = "▶ Продовжити";

    startButton.disabled = false;
    pauseButton.disabled = true;
}

// Повернути літак на початкову позицію.
function resetFlight() {
    cancelAnimationFrame(animationId);

    progress = 0;
    isRunning = false;
    lastTime = null;
    animationId = null;

    statusText.textContent = "Готовий до зльоту";
    startButton.textContent = "▶ Почати";

    startButton.disabled = false;
    pauseButton.disabled = true;

    render();
}

// Реакція на натискання кнопок.
startButton.addEventListener("click", startFlight);
pauseButton.addEventListener("click", pauseFlight);
resetButton.addEventListener("click", resetFlight);

// Зміна швидкості.
speedSelect.addEventListener("change", function () {
    speed = Number(speedSelect.value);
});

// Перерахунок координат після зміни розміру вікна.
window.addEventListener("resize", render);

// Не враховуємо час, проведений на іншій вкладці.
document.addEventListener("visibilitychange", function () {
    lastTime = null;
});

// Початкове відображення.
render();