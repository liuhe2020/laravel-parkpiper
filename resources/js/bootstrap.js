import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Theme toggle functionality
const themeToggle = document.getElementById("themeToggle");
const html = document.documentElement;

themeToggle.addEventListener("click", () => {
    html.classList.toggle("dark");

    // Save preference
    const theme = html.classList.contains("dark") ? "dark" : "light";
    localStorage.setItem("theme", theme);
});

// Check mode selector
document.addEventListener("DOMContentLoaded", () => {
    const radios = document.querySelectorAll('input[name="check_mode"]');
    const specific = document.getElementById("specificFields");
    const duration = document.getElementById("durationFields");
    const checkDate = document.querySelector('input[name="check_date"]');
    const stayStart = document.querySelector('input[name="stay_start"]');
    const stayEnd = document.querySelector('input[name="stay_end"]');

    const toggleFields = (mode) => {
        specific.classList.toggle("hidden", mode !== "specific");
        duration.classList.toggle("hidden", mode !== "duration");

        checkDate.disabled = mode !== "specific";
        stayStart.disabled = stayEnd.disabled = mode !== "duration";
    };

    radios.forEach((radio) => {
        radio.addEventListener("change", () => toggleFields(radio.value));
    });

    const initial = document.querySelector('input[name="check_mode"]:checked');
    toggleFields(initial ? initial.value : "current");
});
