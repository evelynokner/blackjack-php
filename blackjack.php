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

$blackjack = false;

# ask user if they wish to play game, if true, call play(), else quit
echo "Welcome to the casino! Would you like to play Blackjack?\n";
# boolean to play or quit

# if prompt returns true, play/continue game, else, end game
while(prompt()){
    play();    
}
stop();

function prompt(){
    # returns true if user says Y, else ends game
    return ("Y" == readline("Y for yes or N for no: "));
}

function play(){
    deal_cards();
    player_move();
    # if player does not automatically get a blackjack, dealer moves
    if (!$blackjack){
        dealer_move();
    }
    # TODO: track points
    show_results();
    echo "playing\n";
}

function stop(){
    echo "Thanks for playing!";
}

function deal_cards(){
    echo "Dealing cards\n";
}

function player_move(){
    echo "Player's turn\n";
    if (blackjack()){
        return;
    } else hit_or_stand();
}

function dealer_move(){
    echo "Dealer's turn\n";
    
}

function hit_or_stand(){
    # gives user option to hit (draw more cards) or stand (leave hand as it is)
    echo "Would you like to Hit or Stand?";
    if (readline("H for hit, S for stand") == "H"){
        hit();
    } else stand();
}

function blackjack(){
    $blackjack = false;
    return false;
}

function hit(){
    echo "User hits";
}

function stand(){
    echo "User stands";
}

?>
