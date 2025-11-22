function writeStars(id, n)
{
    // Get the field
    field = document.getElementById(id);

    // Writing n start
    let stars = '';
    for (let i = 0; i < n; i++)
        stars += '★';
    field.innerText += " " + stars;
}