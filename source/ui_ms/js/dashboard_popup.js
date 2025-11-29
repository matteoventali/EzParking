let selectedRating = 0;
let currentReservationId = null;

function openReviewPopup(id, name) {
    currentReservationId = id;
    document.getElementById("reviewTitle").innerText = "Review for " + name;
    document.getElementById("reviewModal").classList.add("active");
    document.querySelector(".page-content")?.classList.add("blurred");
}

function closeReviewPopup() {
    document.getElementById("reviewModal").classList.remove("active");
    document.querySelector(".page-content")?.classList.remove("blurred");
    clearReviewPopup();
}

function clearReviewPopup() {
    selectedRating = 0;
    highlightStars();
    document.getElementById("reviewText").value = "";
}

function setRating(value) {
    selectedRating = value;
    highlightStars();
}

function highlightStars() {
    const stars = document.querySelectorAll(".rating-stars span");
    stars.forEach((star, index) => {
        star.style.color = (index < selectedRating) ? "#fbbf24" : "#d1d5db";
    });
}

function submitReview() {
    const review = document.getElementById("reviewText").value;

    if (selectedRating === 0) {
        alert("Please select a rating.");
        return;
    }

    if (review.trim() === "") {
        alert("Please write a review.");
        return;
    }

    // Perform the request to the server
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/submit_review.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 201 && response.body.code === "0") {
                alert("Review submitted successfully!");
                closeReviewPopup();
                location.reload();
            } else {
                alert("Error submitting review: " + response.message);
            }
        }
    };
    const params = `reservation_id=${encodeURIComponent(currentReservationId)}&rating=${encodeURIComponent(selectedRating)}&review=${encodeURIComponent(review)}`;
    xhr.send(params);
}

function openTransactionsPopup(transactions) {
    const container = document.getElementById("transactionsContainer");
    container.innerHTML = ""; // reset

    if (!transactions || transactions.length === 0) {
        container.innerHTML = "<p>No transactions available.</p>";
        document.getElementById("transactionsModal").style.display = "flex";
        return;
    }

    transactions.forEach(t => {
        const div = document.createElement("div");
        div.classList.add("transaction-item");

        div.style.padding = "14px 12px";
        div.style.borderBottom = "1px solid #ddd";
        div.style.background = "#fafafa";
        div.style.borderRadius = "8px";
        div.style.marginBottom = "10px";

        // Parse amount
        const amount = parseFloat(t.amount);
        const color = amount >= 0 ? "green" : "red";
        const prefix = amount >= 0 ? "+" : "";

        console.log(t);

        // Extract resident name
        const residentName = t.resident;

        // Date + time
        const dt = new Date(t.payment_ts);
        const dateString = dt.toLocaleDateString("it-IT");
        const timeString = dt.toLocaleTimeString("it-IT", { hour: "2-digit", minute: "2-digit" });

        div.innerHTML = `
            <p style="margin: 4px 0; font-weight:bold; font-size:16px;">
                Payment for ${residentName}
            </p>

            <p style="margin:4px 0; font-size:15px;">
                Amount:
                <span style="color:${color}; font-weight:bold;">
                    ${prefix}${amount}€
                </span>
            </p>

            <p style="margin:4px 0 0 0; font-size:15px;">
                On:${dateString} at ${timeString} UTC
            </p>

            <p style="margin:4px 0 0 0; font-size:15px;">
                Method: ${t.method}
            </p>
        `;

        container.appendChild(div);
    });

    document.getElementById("transactionsModal").style.display = "flex";
}

function closeTransactionsPopup() {
    document.getElementById("transactionsModal").style.display = "none";
}
