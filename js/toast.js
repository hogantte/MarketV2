function toast(message, type = "basari") {
    const container = document.getElementById("toast-container");
    const div = document.createElement("div");

    div.className = `toast ${type}`;

    div.innerHTML = `<span>${message}</span>`;

    container.appendChild(div);

    setTimeout(() => {
        div.classList.add("aktif");
    }, 10);

    setTimeout(() => {
        div.classList.remove("aktif");

        setTimeout(() => {
            div.remove();
        }, 300);

    }, 3000);
}