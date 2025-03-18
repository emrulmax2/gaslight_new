const FULL_DASH_ARRAY = 283;
let TIME_LIMIT = 0;
let timePassed = 0;
let timeLeft = TIME_LIMIT;
let timerInterval = null;

const timerPath = document.getElementById("base-timer-path-remaining");
const resetBtn = document.getElementById("reset");
const startStopBtn = document.getElementById("start");
const calculateBtn = document.getElementById("calculate");
const gasRate = document.getElementById("gas_rate");
const gross = document.getElementById("gross");
const net = document.getElementById("net");
const typeSelect = document.getElementById("type");
const measurementSelect = document.getElementById("measurement");
const metricTimerSelect = document.getElementById("metric_timer");
const imperialTimerSpan = document.querySelector(".imperial_timer");
const timeLabel = document.querySelector(".timeLabel");
const readingInput = document.getElementById("reading_input");
const initialReading = document.getElementById("initial_reading");
const finalReading = document.getElementById("final_reading");

startStopBtn.addEventListener("click", startStop);
resetBtn.addEventListener("click", reset);
typeSelect.addEventListener("change", reset);
calculateBtn.addEventListener("click", calculateGasRate);
measurementSelect.addEventListener("change", handleMeasurementChange);
metricTimerSelect.addEventListener("change", handleTimerChange);
initialReading.addEventListener("input", updateStartButtonVisibility);
finalReading.addEventListener("input", updateStartButtonVisibility);

function initializeUI() {
    if (measurementSelect.value == "imperial") {
        timerPath.classList.add("stroke-cyan-300");
        timerPath.classList.remove("arc");
        timerPath.setAttribute("stroke-dasharray", `0 ${FULL_DASH_ARRAY}`);

        readingInput.classList.add("hidden");
    } else {
        metricTimerSelect.style.display = "block";
        imperialTimerSpan.style.display = "none";
        readingInput.classList.remove("hidden");

        timerPath.classList.remove("stroke-cyan-300");
        timerPath.classList.add("arc");
        setCircleDasharray();
    }
    updateStartButtonVisibility();
}


function updateStartButtonVisibility() {
    if (measurementSelect.value === "metric") {
        if (initialReading.value.trim() === "") {
            startStopBtn.classList.add("hidden");
        } else if (initialReading.value.trim() !== "" && finalReading.value.trim() !== "") {
            startStopBtn.classList.add("hidden");
        } else {
            startStopBtn.classList.remove("hidden");
        }
    }else{
        startStopBtn.classList.remove("hidden");
    }

}

initializeUI();

function handleMeasurementChange() {
    reset();
    if (measurementSelect.value == "metric") {
        metricTimerSelect.classList.remove("hidden");
        imperialTimerSpan.classList.add("hidden");
        timerPath.classList.remove("stroke-cyan-300");
        timerPath.classList.add("arc");
        TIME_LIMIT = parseInt(metricTimerSelect.value);
        timeLeft = TIME_LIMIT;
        timeLabel.innerHTML = formatTime(timeLeft);
        readingInput.classList.remove("hidden");
    } else {
        metricTimerSelect.classList.add("hidden");
        imperialTimerSpan.classList.remove("hidden");
        timerPath.classList.add("stroke-cyan-300");
        timerPath.classList.remove("arc");
        TIME_LIMIT = 1;
        timeLeft = TIME_LIMIT;
        timeLabel.innerHTML = formatTime(timeLeft);
        readingInput.classList.add("hidden");
    }
    updateStartButtonVisibility();
}

function handleTimerChange() {
    TIME_LIMIT = parseInt(metricTimerSelect.value);
    timeLeft = TIME_LIMIT;
}

function startStop() {
    if (timerInterval) {
        stop();
        startStopBtn.textContent = "Start";
        startStopBtn.classList.remove("bg-danger", "border-danger", "text-white");
        startStopBtn.classList.add("bg-success", "border-success", "text-white");
    } else {
        if (measurementSelect.value == "metric" && initialReading.value === "") {
            alert("Please enter initial reading.");
            return;
        }
        start();
        startStopBtn.textContent = "Stop";
        startStopBtn.classList.remove("bg-success", "border-success", "text-white");
        startStopBtn.classList.add("bg-danger", "border-danger", "text-white");
    }
    updateStartButtonVisibility();
}

function start() {
    metricTimerSelect.style.display = "none";
    timeLabel.style.display = "block";

    if (measurementSelect.value == "imperial") {
        imperialTimerSpan.style.display = "none";
    }

    if (!timerInterval) {
        if (measurementSelect.value == "metric") {
            TIME_LIMIT = parseInt(metricTimerSelect.value);
            timeLeft = TIME_LIMIT;
        } else if (measurementSelect.value == "imperial") {
            calculateImperialGasRate();
        }

        timerInterval = setInterval(() => {
            if (measurementSelect.value == "metric") {
                timePassed += 1;
                timeLeft = TIME_LIMIT - timePassed;
                timeLabel.innerHTML = formatTime(timeLeft);
                setCircleDasharray();

                if (timeLeft == 0) {
                    timeIsUp();
                }
            } else {
                timePassed += 1;
                timeLeft += 1;
                timeLabel.innerHTML = formatTime(timeLeft);
                calculateImperialGasRate();
            }
        }, 1000);
    }
}

function stop() {
    if (measurementSelect.value == "metric") {
        finalReading.classList.remove("hidden");
    }
    clearInterval(timerInterval);
    timerInterval = null;
    updateStartButtonVisibility();
}

function reset() {
    stop();
    timePassed = 0;
    if (measurementSelect.value == "metric") {
        TIME_LIMIT = parseInt(metricTimerSelect.value);
        timeLeft = TIME_LIMIT;
        metricTimerSelect.style.display = "block";
        imperialTimerSpan.style.display = "none";
    } else {
        TIME_LIMIT = 0;
        timeLeft = TIME_LIMIT;
        metricTimerSelect.style.display = "none";
        imperialTimerSpan.style.display = "block";
    }

    timeLabel.innerHTML = formatTime(timeLeft);

    timeLabel.style.display = "none";
    if (measurementSelect.value == "imperial") {
        imperialTimerSpan.style.display = "block";
        metricTimerSelect.style.display = "none";
    } else {
        metricTimerSelect.style.display = "block";
        imperialTimerSpan.style.display = "none";
    }

    setCircleDasharray();
    gasRate.innerHTML = "0.00";
    gross.innerHTML = "0.00";
    net.innerHTML = "0.00";
    startStopBtn.textContent = "Start";
    finalReading.classList.add("hidden");
    calculateBtn.classList.add("hidden");
    initialReading.value = "";
    finalReading.value = "";

    updateStartButtonVisibility();
}

function timeIsUp() {
    stop();
    startStopBtn.textContent = "Start";
    startStopBtn.classList.remove("bg-danger", "border-danger", "text-white");
    startStopBtn.classList.add("bg-success", "border-success", "text-white");


    timePassed = 0;
    if (measurementSelect.value == "metric") {
        TIME_LIMIT = parseInt(metricTimerSelect.value);
        timeLeft = TIME_LIMIT;
        metricTimerSelect.style.display = "block";
        imperialTimerSpan.style.display = "none";
    } else {
        TIME_LIMIT = 0;
        timeLeft = TIME_LIMIT;
        metricTimerSelect.style.display = "none";
        imperialTimerSpan.style.display = "block";
    }

    timeLabel.innerHTML = formatTime(timeLeft);

    timeLabel.style.display = "none";
    if (measurementSelect.value == "imperial") {
        imperialTimerSpan.style.display = "block";
        metricTimerSelect.style.display = "none";
    } else {
        metricTimerSelect.style.display = "block";
        imperialTimerSpan.style.display = "none";
    }

    setCircleDasharray();
}

function formatTime(time) {
    const minutes = Math.floor(time / 60);
    let seconds = time % 60;
    if (seconds < 10) {
        seconds = `0${seconds}`;
    }

    return `${minutes}:${seconds}`;
}

function setCircleDasharray() {
    if (measurementSelect.value == "metric") {
        const circleDasharray = `${(calculateTimeFraction() * FULL_DASH_ARRAY).toFixed(0)} ${FULL_DASH_ARRAY}`;
        timerPath.setAttribute("stroke-dasharray", circleDasharray);
    } else {
        timerPath.setAttribute("stroke-dasharray", `0 ${FULL_DASH_ARRAY}`);
    }
}

function calculateTimeFraction() {
    return timeLeft / TIME_LIMIT;
}


finalReading.addEventListener("input", () => {
    if (finalReading.value.trim() === "") {
        calculateBtn.classList.add("hidden");
    } else {
        calculateBtn.classList.remove("hidden");
    }
    updateStartButtonVisibility();
});

function calculateGasRate() {
    if (measurementSelect.value == "metric") {
        const initial = parseFloat(initialReading.value);
        const final = parseFloat(finalReading.value);

        if (!isNaN(initial) && !isNaN(final) && final >= initial) {
            const gasType = typeSelect.value;
            const volume = final - initial;
            const timeInSeconds = timePassed > 0 ? timePassed : TIME_LIMIT;

            if (gasType == "natural_gas") {
                const rate = (3600 * volume) / timeInSeconds;
                gasRate.innerHTML = rate.toFixed(2);
                
                const grossKW = (3600 * volume * 10.76) / timeInSeconds;
                gross.innerHTML = grossKW.toFixed(2);
                
                const netKW = grossKW / 1.11;
                net.innerHTML = netKW.toFixed(2);
            } else if (gasType == "lpg") {
                
                const rate = (3600 * volume) / timeInSeconds;
                gasRate.innerHTML = rate.toFixed(2);

                const grossKW = (3600 * volume * 2516) / (timeInSeconds * 3412);
                gross.innerHTML = grossKW.toFixed(2);

                const netKW = grossKW / 1.11;
                net.innerHTML = netKW.toFixed(2);
            }
        } else {
            alert("Invalid readings. Final reading must be greater than or equal to initial reading.");
            return;
        }
    }
}

function calculateImperialGasRate() {
    if (measurementSelect.value == "imperial") {
        const gasType = typeSelect.value;

        const calculateTimeLeft = timeLeft === 0 ? 1 : timeLeft;

        if (gasType == "natural_gas") {
            const rate = (3600 * 0.0283) / calculateTimeLeft;
            gasRate.innerHTML = rate.toFixed(2);

            const grossBTU = (3600 * 1040) / calculateTimeLeft;
            const grossKW = grossBTU / 3412;
            gross.innerHTML = grossKW.toFixed(2);

            const netKW = grossKW / 1.11;
            net.innerHTML = netKW.toFixed(2);
        } else if (gasType == "lpg") {
            const rate = (3600 * 0.0283) / calculateTimeLeft;
            gasRate.innerHTML = rate.toFixed(2);

            const grossBTU = (3600 * 2516) / calculateTimeLeft;
            const grossKW = grossBTU / 3412;
            gross.innerHTML = grossKW.toFixed(2);

            const netKW = grossKW / 1.11;
            net.innerHTML = netKW.toFixed(2);
        }
    }
}