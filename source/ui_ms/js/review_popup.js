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