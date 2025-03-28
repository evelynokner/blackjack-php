<?php
/* 52 card deck
randomly pick 2 cards from deck for player & dealer when round starts
fill array with 52 1's to represent a card is currently in deck
if card_deck[idx] = 0, then the card at idx has been drawn.*/
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

/* set fixed amount of money that player has in bank initially 
and sets fixed amount of money player can bet per round */
$bank = 100;
$bet = 10;

echo "Welcome to the casino!";

# if prompt returns true, play/continue game, else, end game
while(prompt() && $bank > 0){
    play();
} stop();

function prompt(){
    $user_input = readline("\nWould you like to play Blackjack?\nY for yes, N for no: ");
    # returns true if player says Y, else ends game
    if($user_input == "Y"){
        return true;
    } else return false;
    }
}

function play(){
    initialize_round();
    deal_cards_first_time();
    # if a player immediately gets a blackjack after first cards are dealt, end game
    if (gameover()) return;
    player_move();
    if (gameover()) return;
    dealer_move();
    update_bank();
    show_results();
}

function update_bank(){
    global $winner, $bank, $bet;
    if($winner == 0){
        # if player wins, add betted money to their bank
        $bank += $bet;
        echo "\nBank updated: $$bank\n";
        return;
    }
    if($winner == 1){
        # if dealer wins, subtract betted money from player's bank
        $bank -= $bet;
        echo "\nBank updated: $$bank\n";
        return;
    } # otherwise, bank is not updated
}

function initialize_round(){
    global $card_deck, $winner, 
    $player_hand, $dealer_hand,
    $player_points, $dealer_points;
    # reset card deck
    $card_deck = array_fill(0, 52, 1);
    # -1 = no winner, 0 = player wins, 1 = dealer wins, 2 = tie
    # reset winner
    $winner = -1;
    # reset hands
    $player_hand = array();
    $dealer_hand = array();
    # reset points
    $player_points = 0;
    $dealer_points = 0;
}

function deal_cards_first_time(){
    global $player_hand, $dealer_hand, 
    $player_points, $dealer_points;
    array_push($player_hand, deal_card(0));
    array_push($player_hand, deal_card(0));
   
    array_push($dealer_hand, deal_card(1));
    array_push($dealer_hand, deal_card(1));

    echo "\nPlayer's hand:\n", print_hand($player_hand), "\n";
    echo "Dealer's hand:\n", print_hand($dealer_hand), "\n";
    
    echo "Player's points: ", $player_points, "\n";
    echo "Dealer's points: ", $dealer_points, "\n";
}

function deal_card($move){
    # move = 0 (player's move)
    # move = 1 (dealer's move)
    global $card_deck, $winner,
    $player_points, $dealer_points;
    $random_card = array_rand($card_deck, 1);
    # card already drawn, try to pick another card
    if ($card_deck[$random_card] == 0){
        deal_card($move);
    } else {
        $card_deck[$random_card] = 0; # mark the card as taken
        $value = get_card_points($random_card);
        if ($move == 0){
            $player_points += $value;
            update_round_result(0); # update result for player
        } else {
            $dealer_points += $value;
            update_round_result(1); # update result for dealer
        } return $random_card;
    }
}

function update_round_result($side){ # side = who's turn it currently is
    $points;
    global $player_points, $dealer_points, $winner;
    
    # if it's the player's turn, set $points to player's points
    if ($side == 0) $points = $player_points;
    # otherwise, set $points to dealer's points
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
    echo "\nPLAYER'S TURN:\n";
    hit_or_stand();
}

function dealer_move(){
    /* dealer draws cards until:
    - blackjack
    - OR bust
    - OR points > player's points */
    
    global $dealer_hand, $dealer_points, 
    $player_points, $winner;
    
    echo "\nDEALER'S TURN:\n";
    
    while(true){
        # deal cards to dealer and update hand
        array_push($dealer_hand, deal_card(1));
        if($dealer_points > 21){
            # bust, player wins
            $winner = 0;
            return;
        }
        /* dealer wins if:
        - dealer has more points than player
        - OR dealer has 21 points (blackjack) */
        if($dealer_points > $player_points || $dealer_points == 21){
            $winner = 1;
            return;
        } 
        # continue dealing cards if the above conditions have not been met yet
        else array_push($dealer_hand, deal_card(1));
    }
}

function hit_or_stand(){
    # gives player option to hit (draw more cards) or stand (leave hand as it is)
    echo "Would you like to Hit or Stand?\n";
    if (readline("H for hit, S for stand: ") == "H"){
        hit();
    } else stand();
}

function hit(){
    global $player_hand, $player_points, $dealer_points;
    echo "Player hits\n";
    # deal cards and check if game over
    array_push($player_hand, deal_card(0));
    # print player and dealer points again
    show_results();
}

function stand(){
    echo "Player stands\n";
}

function print_hand($hand){
    global $card_deck;
    foreach ($hand as $card){
        echo get_card_rank($card), " of ", get_card_suit($card),
        " - ", get_card_points($card), " points\n";
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
    $player_hand, $dealer_hand,
    $bank;
    if($winner == 0) {
        echo "\nPLAYER WINS!\n";
    } else {
        if($winner == 1) echo "\nDEALER WINS!\n";
        # check for errors
        else echo "\nNo winner (error)\n";
    }
    # display new hands
    echo "\nPlayer's hand:\n", print_hand($player_hand), "\n";
    echo "Dealer's hand:\n", print_hand($dealer_hand), "\n";
    
    # display player and dealer points
    echo "Player's points: ", $player_points, "\n";
    echo "Dealer's points: ", $dealer_points, "\n";
    
    # if there's no more money in bank, end game
    if($bank <= 0){
        echo "Player is out of money!\n";
    }
}

function gameover(){
    global $winner;
    update_bank();
    return $winner != -1;
}

function stop(){
    echo "Thanks for playing!";
}

?>
