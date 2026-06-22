document.querySelectorAll('.counter').forEach(counter => {
    let target = +counter.getAttribute('data-target');
    let count = 0;
    let speed = 30;

    let update = () => {
        count++;
        counter.textContent = count;

        if (count < target) {
            setTimeout(update, speed);
        }
    };

    update();
});

/* ================== HAMBURGER (JADI LAPAR EUY) ================== */
const toggle = document.getElementById('menu-toggle');
const menu = document.getElementById('menu');

toggle.addEventListener('click', (e) => {
    e.stopPropagation();
    menu.classList.toggle('show');
});

document.addEventListener('click', (e) => {
    if (!menu.contains(e.target) && !toggle.contains(e.target)) {
        menu.classList.remove('show');
    }
});
/* ================== END HAMBURGER (JADI LAPAR EUY) ================== */

/* ================== TOGGLE PW ================== */
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
/* ================== END TOGGLE PW ================== */

const dropdownToggle = document.querySelector('.dropdown-toggle');
const dropdownMenu = document.querySelector('.dropdown-menu');

dropdownToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle('show');
});

document.addEventListener('click', () => {
    dropdownMenu.classList.remove('show');
});
