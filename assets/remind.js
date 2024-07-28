function checkTime() {
    const now = new Date();
    const currentTime = now.getUTCHours() * 60 + now.getUTCMinutes();
    const targetStart = 0;
    const targetEnd = 9 * 60 + 30;
    const beijingOffset = 8 * 60;

    const beijingTime = (currentTime + beijingOffset) % (24 * 60);

    const overlay = document.getElementById('overlay');

    if (beijingTime >= targetStart && beijingTime <= targetEnd) {
        overlay.style.display = 'none';
    } else {
        overlay.style.display = 'flex';
    }
}

document.addEventListener('DOMContentLoaded', checkTime);