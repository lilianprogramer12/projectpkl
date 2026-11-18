document.addEventListener("DOMContentLoaded", () => {
    // Efek Spotlight & 3D Tilt pada Kartu Kostum Customer
    const cards = document.querySelectorAll(".costume-card");

    cards.forEach(card => {
        card.addEventListener("mousemove", (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            card.style.setProperty("--mouse-x", `${x}px`);
            card.style.setProperty("--mouse-y", `${y}px`);

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = ((y - centerY) / centerY) * -5;
            const rotateY = ((x - centerX) / centerX) * 5;

            card.style.transform = `perspective(900px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "perspective(900px) rotateX(0deg) rotateY(0deg) translateY(0px)";
        });
    });

    // Live Search pada Halaman Data Pemesan Admin
    const searchInput = document.getElementById("searchPenyewa");
    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            const keyword = this.value.toLowerCase();
            const items = document.querySelectorAll(".pesanan-item");
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(keyword) ? "block" : "none";
            });
        });
    }
});

// Preview Foto Kostum di Halaman Tambah Kostum Admin
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById('imgPreview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}