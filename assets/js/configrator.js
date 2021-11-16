const components = {};


//Add selected to cheapest option
$(document).ready(function() {
    //Temp save the first url
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    //Select all the defaults
    $(".card-group .card:first-child" ).addClass('selected');
    updateData();

    //Change to defaults to the selected
    for (let componentsKey in components) {
        if(urlParams.has(componentsKey)){
            unselect(componentsKey,components[componentsKey])
            selectItem(componentsKey,urlParams.get(componentsKey))
            updateData()
        }
    }
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
    let price = 0.00;
    for (const elementsByClassNameElement of document.getElementsByClassName('selected')) {
        price += parseFloat(elementsByClassNameElement.dataset.price);
        components[elementsByClassNameElement.dataset.category] = elementsByClassNameElement.dataset.id;
    }
    document.getElementById('finalPrice').innerHTML = "€" + price.toFixed(2);
    updateUrl();
}


//When pressed the card it wil trigger the selections and updates
$(".card").click(function() {
    unselect($(this)[0].dataset.category,components[$(this)[0].dataset.category]);
    selectItem($(this)[0].dataset.category,$(this)[0].dataset.id);
    updateData();
    return false;
});

function updateUrl(){
    let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?';
    for (let componentsKey in components) {
        newUrl+= componentsKey + "=" + components[componentsKey] + "&";

    }
    if (history.pushState) {
        window.history.pushState({path:newUrl},'',newUrl);
    }
    document.getElementById("config").value=JSON.stringify(components);
}