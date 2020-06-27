// kijk of er geklikt wordt op de blog delete knop
// pak een id op en zet daar een bepaalde button
document.getElementById("delete").innerHTML = "<button class='btn btn-outline-warning delete'>Delete</a>";
// kijk of er op delete wordt gedrukt
$(".delete").click(function () {
    // stuur een msg om te vragen of ze het zeker weten
    alert("Are you sure you want to delete this blog?");
    // verandere de button bij de opgegeven id
    document.getElementById("delete").innerHTML = "<a class='btn btn-outline-danger deleting' href='blog_delete.php?id=<?php echo $blogID ?>'>Delete</a>";
});

// maak een variable aan voor de bioteller
let text_max = $("#comment").attr("maxlength");
// geef in een html element weer hoeveel characters ze overhebben
$("#count_message").html(text_max + " Characters left");
// kijk of er een toets is ingedrukt bij de comment add
$("#comment").keyup(function () {
    // maak een variable aan voor de lengte van de comment
    let comment_lenght = $("#comment").val().length;
    // maak een variable aan voor hoeveel er nog over zijn
    let letters_remaining = text_max - comment_lenght;

    $("#count_message").html(letters_remaining + " Characters left");
});

function laad() {
    $.getJSON("stockInfo.php")
        .done(function (data) {
            let output = "";
            for(let i in data){
                output += '<div class="card text-white bg-dark m-1"><div class="card-body"><h3 class="card-title">Stock</h3><p class="card-text  text-left">';
                output += '<span class="float-left">Item:<span style=\'color:<?php echo $session_kleur ?>\'><?php echo $stockItem[\'ItemName\'] ?> </span></span><br>';
            }
        })
}