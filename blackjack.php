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
    if (deal_cards()){
        # blackjack
    } else
    {
    
    }
    # if player does not have 21 pts, dealer moves
    if (!player_move()){
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
    # if current hand's points = 21, blackjack
    if(blackjack()){
       return true; 
    } else return false;
}

function player_move(){
    echo "Player's turn\n";

}

function dealer_move(){
    echo "Dealer's turn\n";
    
}

?>
