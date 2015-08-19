<?php
class Loan {

  public function __construct($sum, $days) {
    $this->sum = $sum;
    $this->days = $days;
  }
  
  /**
  * Calculates the interest.
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
