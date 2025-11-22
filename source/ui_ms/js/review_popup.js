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

    // Here you would typically send the review data to the server via AJAX
    // For this example, we'll just log it to the console and close the popup
    // Example AJAX call (uncomment and modify as needed):
    /*
    fetch('submit_review.php', {							
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            reservation_id: currentReservationId,
            rating: selectedRating,
            review_text: review
        })
    })*/
    
    closeReviewPopup();
    alert("Review submitted successfully!");
}