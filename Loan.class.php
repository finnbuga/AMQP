<?php
/**
 * Loan
 *
 * Stores loan data (sum, days) and calculates interest.
 */
class Loan {

  public function __construct($sum, $days) {
    $this->sum = $sum;
    $this->days = $days;
  }
  
  /**
   * Calculates the interest.
   *
   * @todo Optimise for a big numbers of days.
   * @todo Avoid calculating the daily interest rates each time. Store them in a table for the first 100 days.
   */
  public function calculate_interest() {
    $total_interest = 0;
    
    for($i=1; $i<=$this->days; $i++) {
      
      if (($i%3 == 0) && ($i%5 == 0)) {
        $daily_interest_rate = .03;
      }
      elseif ($i%5 == 0) {
        $daily_interest_rate = .02;
      }
      elseif ($i%3 == 0) {
        $daily_interest_rate = .01;
      }
      else {
        $daily_interest_rate = .04;
      }
      
      $daily_interest = round($this->sum * $daily_interest_rate, 2);
      $total_interest += $daily_interest;
    }
    
    $this->interest = $total_interest;
  }
}
