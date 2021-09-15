var components = {};


//Add selected to cheapest option
$(document).ready(function() {
    $(".card-group .card:first-child" ).addClass('selected');
    updateData();
});

//Remove selection from element
function unselect(category,id){
    for (const elementsByClassNameElement of document.getElementsByClassName('selected')) {
        if(elementsByClassNameElement.dataset.category === category){
            if(elementsByClassNameElement.dataset.id === id){
                elementsByClassNameElement.classList.remove('selected');
            }
        }
    }
}

//Add selection to element
function selectItem(category,id){
    components[category] = id;
    for (const elementsByClassNameElement of document.getElementsByClassName('card')) {
        if(elementsByClassNameElement.dataset.category === category){
            if(elementsByClassNameElement.dataset.id === id){
                elementsByClassNameElement.classList.add('selected');
            }
        }
    }
}

//Update data like the price
function updateData(){
    price = 0.00;
    for (const elementsByClassNameElement of document.getElementsByClassName('selected')) {
        price += parseFloat(elementsByClassNameElement.dataset.price);
        components[elementsByClassNameElement.dataset.category] = elementsByClassNameElement.dataset.id;
    }
    document.getElementById('finalPrice').innerText = price;
}


//When pressed the card it wil trigger the selections and updates
$(".card").click(function() {
    unselect($(this)[0].dataset.category,components[$(this)[0].dataset.category]);
    selectItem($(this)[0].dataset.category,$(this)[0].dataset.id);
    updateData();
    return false;
});

// function updateUrl(){
//     for (let componentsKey in components) {
//
//     }
// }