<?php
# 52 card deck
# randomly pick 2 cards from deck for player & dealer when round starts
$card_deck[52] = [2, 2, 2, 2, 
                    3, 3, 3, 3,
                    4, 4, 4, 4,
                    5, 5, 5, 5,
                    6, 6, 6, 6,
                    7, 7, 7, 7,
                    8, 8, 8, 8,
                    9, 9, 9, 9,
                    10, 10, 10, 10,
                    10, 10, 10, 10, #Jack
                    10, 10, 10, 10, #Queen
                    10, 10, 10, 10, #King
                    11, 11, 11, 11]; #Ace
                    
# ask user if they wish to play game, if true, call play(), else quit
echo "Welcome to the casino! Would you like to play a round of Blackjack?\n";
# boolean to play or quit
$play_game = false;
$play_or_quit = readline("Y for yes or N for no: ");
if($play_or_quit == "Y"){
    $play_game = true;
}
else { $play_game = false; }

function play(){}

function deal_cards(){}

?>
