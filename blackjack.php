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
$suits = ["Diamonds", "Hearts", "Spades", "Clubs"];

$player_hand = array();
$dealer_hand = array();

# -1 = no winner, 0 = player wins, 1 = dealer wins, 2 = tie
$winner = -1;
$player_points = 0;
$dealer_points = 0;

echo "Welcome to the casino! ";

# if prompt returns true, play/continue game, else, end game
while(prompt()){
    play();
} stop();

function prompt(){
    # returns true if user says Y, else ends game
    return ("Y" == readline("Would you like to play Blackjack?\nY for yes or N for no: "));
}

function play(){
    initialize_round();
    deal_cards_first_time();
    # if a player immediately gets a blackjack after first cards are dealt, end game
    if (gameover()) return;
    player_move();
    if (gameover()) return;
    dealer_move();
    show_results();
}

function initialize_round(){
    global $card_deck, $winner, 
    $player_hand, $dealer_hand,
    $player_points, $dealer_points;
    # reset card deck
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
    global $player_hand, $dealer_hand, 
    $player_points, $dealer_points;
    array_push($player_hand, deal_card(0));
    array_push($player_hand, deal_card(0));
   
    array_push($dealer_hand, deal_card(1));
    array_push($dealer_hand, deal_card(1));

    echo "\nPlayer hand:\n", print_hand($player_hand), "\n";
    echo "Dealer hand:\n", print_hand($dealer_hand), "\n";
    
    echo "Player points: ", $player_points, "\n";
    echo "Dealer points: ", $dealer_points, "\n";
}

function deal_card($move){
    # move = 0 (player's move)
    # move = 1 (dealer's move)
    global $card_deck, $winner,
    $player_points, $dealer_points;
    $random_card = array_rand($card_deck, 1);
    # card taken, try again
    if ($card_deck[$random_card] == 0){
        deal_card($move);
    } else {
        $card_deck[$random_card] = 0; # mark the card as taken
        $value = get_card_points($random_card);
        if ($move == 0){
            $player_points += $value;
            update_result(0); # update result for player
        } else {
            $dealer_points += $value;
            update_result(1); # update result for dealer
        } return $random_card;
    }
}

function update_result($side) {
    $points;
    global $player_points, $dealer_points, $winner;
    
    if ($side == 0) $points = $player_points;
    else $points = $dealer_points;
    # tie if player & dealer both have 21 pts
    if($player_points == 21 && $dealer_points == 21){
        $winner = 2; # tie
        return;
    }
    if($points == 21){
        $winner = $side; # player wins
    }
    else if($points > 21){
        # if points on current side is over 21, other side wins
        if ($side == 0) $winner = 1;
        else $winner = 0; 
    }
}

function player_move(){
    echo "\nPlayer's turn:\n";
    hit_or_stand();
}

function dealer_move(){
    # dealer draws cards until:
    // blackjack OR bust OR points > player's points
    echo "Dealer's turn\n";
    global $dealer_hand, $dealer_points, 
    $player_points, $winner;
    while(true){
        # deal cards to dealer and update hand
        array_push($dealer_hand, deal_card(1));
        if($dealer_points > 21){
            # bust, player wins
            $winner = 0;
            return;
        }
        # dealer wins if:
        // dealer has more points than player
        // or dealer has 21 points (blackjack)
        if($dealer_points > $player_points || $dealer_points == 21){
            $winner = 1;
            return;
        } 
        # continue dealing cards if the above conditions have not been met yet
        else array_push($dealer_hand, deal_card(1));
    }
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
    # print player and dealer points again
    show_results();
}

function stand(){
    echo "User stands\n";
}

function print_hand($hand){
    global $card_deck;
    foreach ($hand as $card){
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
    global $winner, $player_points, $dealer_points, 
    $player_hand, $dealer_hand;
    if($winner == 0) {
        echo "Player wins!\n";
    } else {
        if($winner == 1) echo "Dealer wins!\n";
        else echo "No winner (error)\n";
    }
    # display new hands
    echo "Player hand:\n", print_hand($player_hand), "\n";
    echo "Dealer hand:\n", print_hand($dealer_hand), "\n";
    # display player and dealer points
    echo "Player points: ", $player_points, "\n";
    echo "Dealer points: ", $dealer_points, "\n";
}

?>
