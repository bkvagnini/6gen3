// choose_from_list.js
// 10/7/2018
// Brian K. Vagnini
// This file defines howto open, read, and choose
// from different categories for feeeedme web app

function open_json (file){
  pass;
  //open a json file and load into memory

  //This currently doesn't work - 082618
  var main_dish = JSON.parse(main_dish);

  return //what?
}

function choose_from_json (file) {
  pass;
  //read length of array, then choose a random numbered item from the json file
  return //whatever the value was that random number
}


// from Treehouse

function random_dish(dish) {
        var url="main_dish.json";
        $.getJSON(url, function(response) {
        var arrayRandom = Math.floor(Math.random() * response.length);
        return dish
      }); //end getJSON
    }
    random_dish();
    $('#main_dish').on('click', random_dish);
