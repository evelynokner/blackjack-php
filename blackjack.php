<?php
# 52 card deck
# randomly pick 2 cards from deck for player & dealer when round starts
# fill array with 52 1's to represent a card is currently in deck
# if card_deck[idx] = 0, then the card at idx has been drawn.

$card_deck = array_fill(0, 52, 1);

$ranks = ["Two", "Three", "Four", "Five", "Six",
            "Seven", "Eight", "Nine", "Ten",
            "Jack", "Queen", "King", "Ace"];
$rank_value = [2, 3, 4, 5, 6, 7, 8, 9, 10, 10, 10, 10, 11];            
$suits = ["diamonds", "hearts", "spades", "clubs"];

$blackjack = false;
# -1 = no winner, 0 = player wins, 1 = dealer wins
$winner = -1;
$player_hand = array();
$dealer_hand = array();

$player_points = 0;
$dealer_points = 0;

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
    initialize_round();
    deal_cards_first_time();
    if (gameover()) return;
    player_move();
    if (gameover()) return;
    dealer_move();
    # TODO: track points
    show_results();
    echo "playing\n";
}

function initialize_round(){}

function gameover(){
    global $winner;
    return $winner != -1;
}

function stop(){
    echo "Thanks for playing!";
}

function deal_cards_first_time(){
    echo "Dealing cards\n";

    global $player_hand, $dealer_hand, 
    $player_points, $dealer_points;
    array_push($player_hand, deal_card(0));
    array_push($player_hand, deal_card(0));
   
    array_push($dealer_hand, deal_card(1));
    array_push($dealer_hand, deal_card(1));

    echo "Player hand:\n", print_hand($player_hand), "\n";
    echo "Dealer hand:\n", print_hand($dealer_hand), "\n";
    
    echo "Player points: ", $player_points, "\n";
    echo "Dealer points: ", $dealer_points, "\n";
}

function deal_card($move){
    # move = 0 (player's move)
    # move = 1 (dealer's move)
    global $card_deck, $player_points, $dealer_points,
    $winner;
    $random_card = array_rand($card_deck, 1);
    if ($card_deck[$random_card] == 0){ // taken, try again
        deal_card($move);
    } else {
        $card_deck[$random_card] = 0; // mark the card as taken
        $value = get_card_points($random_card);
        if ($move == 0){
            $player_points += $value;
            if($player_points == 21){
                $winner = 0;
            }
        } else {
            $dealer_points += $value;
            if($dealer_points == 21){
                $winner = 1;
            }
        }
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
    # deal cards and check if game over
}

function stand(){
    echo "User stands";
}

function print_hand($hand){
    //echo implode(", ", $hand);
    global $card_deck;
    foreach ($hand as $card) {
        echo get_card_rank($card), " of ", get_card_suit($card),
        "\nPoints: ", get_card_points($card), "\n";
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

function get_card_points($card_position){
    global $rank_value;
    return $rank_value[floor($card_position / 4)];
}

?>
