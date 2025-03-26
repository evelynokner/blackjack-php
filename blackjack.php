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

# -1 = no winner, 0 = player wins, 1 = dealer wins, 2 = tie
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
    // if a player immediately gets a blackjack after first cards are dealt, end game
    if (gameover()) return;
    player_move();
    if (gameover()) return;
    dealer_move();
    show_results();
    echo "playing\n";
}

function initialize_round(){
    global $card_deck, $winner, $player_hand, $dealer_hand,
    $player_points, $dealer_points;
    // reset card deck
    $card_deck = array_fill(0, 52, 1);
# -1 = no winner, 0 = player wins, 1 = dealer wins, 2 = tie
$winner = -1;
$player_hand = array();
$dealer_hand = array();

$player_points = 0;
$dealer_points = 0;
}

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
            update_result(0); // update result for player
                
        }
         else {
            $dealer_points += $value;
            update_result(1); // update result for dealer
        }
        return $random_card;
    }
}

function update_result($side) {
    global $player_points, $dealer_points,
    $winner;
    $points;
    if ($side == 0) $points = $player_points;
    else $points = $dealer_points;
    
    if($player_points == 21 && $dealer_points == 21){
                $winner = 2;
                return; // tie   
            }
    if($points == 21){
        $winner = $side; // player wins
            }
    else if($points > 21){
        // if points on current side is over 21, other side wins
                if ($side == 0) $winner = 1;
                else $winner = 0; 
            }
}

function player_move(){
    echo "Player's turn\n";
    hit_or_stand();
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

function hit(){
    global $player_hand, $player_points, $dealer_points;
    echo "User hits\n";
    # deal cards and check if game over
    array_push($player_hand, deal_card(0));
    echo "Player hand:\n", print_hand($player_hand), "\n";
    // print player and dealer points again
    echo "Player points: ", $player_points, "\n";
    echo "Dealer points: ", $dealer_points, "\n";
    show_results();
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

function show_results(){
    # determine if draw
    global $winner;
    if($winner == 0) {
        echo "Player wins!";
    } else {
        if($winner == 1) echo "Dealer wins!";
        else echo "No winner (error)";
    }
}

?>
