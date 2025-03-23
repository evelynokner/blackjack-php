<?php
# 52 card deck
# randomly pick 2 cards from deck for player & dealer when round starts
$card_deck = array(2, 2, 2, 2,
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
                    11, 11, 11, 11); #Ace

$ranks = ["Two", "Three", "Four", "Five", "Six",
            "Seven", "Eight", "Nine", "Ten",
            "Jack", "Queen", "King", "Ace"];
$suits = ["diamonds", "hearts", "spades", "clubs"];

$blackjack = false;
$player_hand = array();
$dealer_hand = array();

# ask user if they wish to play game, if true, call play(), else quit
echo "Welcome to the casino! Would you like to play Blackjack?\n";
# boolean to play or quit

# if prompt returns true, play/continue game, else, end game
while(prompt()){
    play();
    # TODO: restore deck to original state (52 cards)
}
stop();

function prompt(){
    # returns true if user says Y, else ends game
    return ("Y" == readline("Y for yes or N for no: "));
}

function play(){
    deal_cards_first_time();
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

function deal_cards_first_time(){
    echo "Dealing cards\n";

    global $player_hand, $dealer_hand;
    array_push($player_hand, deal_card());
    array_push($player_hand, deal_card());
   
    array_push($dealer_hand, deal_card());
    array_push($dealer_hand, deal_card());

    echo "Player hand: ", print_hand($player_hand), "\n";
    echo "Dealer hand: ", print_hand($dealer_hand), "\n";

}

function deal_card(){
    global $card_deck;
    $random_card = array_rand($card_deck, 1);
    if ($card_deck[$random_card] == 0) { // taken, try again
        deal_card();
    } else
    {
        $card_deck[$random_card] = 0; // mark the card as taken
        return $random_card;
    }
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
    echo "Would you like to Hit or Stand?\n";
    if (readline("H for hit, S for stand: ") == "H"){
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

function print_hand($hand){
    //echo implode(", ", $hand);
    global $card_deck;
    foreach ($hand as $card) {
        
        echo get_card_rank($card), " of ", get_card_suit($card), "\n";
    }
}

function get_card_rank($card_position){
    global $ranks;
    return $ranks[floor($card_position / 4)];
}

function get_card_suit($card_position){
    global $suits;
    return $suits[$card_position % 4];
}

?>
