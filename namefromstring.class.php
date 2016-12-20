<?php
/**
 * NAME FROM STRING
 * Extracts person's real name from an email string.
 * Replaces an earlier package <https://github.com/peterkahl/name-extractor>.
 *
 * @version    0.2 (2016-12-20)
 * @author     Peter Kahl <peter.kahl@colossalmind.com>
 * @since      2012
 * @copyright  2012-2016 Peter Kahl
 * @license    Apache License, Version 2.0
 *
 *
 * Copyright 2012-2016 Peter Kahl <peter.kahl@colossalmind.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      <http://www.apache.org/licenses/LICENSE-2.0>
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class namefromstring {

  public $longest_name = 14;

  private $dictArray;

  private $common = array(
    'abuse'          => 'Abuse',
    'noreply'        => 'No Reply',
    'donotreply'     => 'No Reply',
    'dontreply'      => 'No Reply',
    'nobody'         => 'Nobody',
    'test'           => 'Test',
    'orderupdate'    => 'Order Update',
    'orderstatus'    => 'Order Status',
    'status'         => 'Status',
    'sales'          => 'Sales',
    'billing'        => 'Billing',
    'receipt'        => 'Receipt',
    'invoice'        => 'Invoice',
    'confirm'        => 'Confirm',
    'confirmation'   => 'Confirmation',
    'verify'         => 'Verify',
    'verification'   => 'Verification',
    'bounce'         => 'Bounce',
    'support'        => 'Support',
    'wwwdata'        => 'www-data',
    'apache'         => 'Apache',
    'cron'           => 'Crontab',
    'mailerdaemon'   => 'Mailer Daemon',
    'info'           => 'Info',
    'enquiry'        => 'Enquiry',
    'inquiry'        => 'Inquiry',
    'admin'          => 'Administrator',
    'administrator'  => 'Administrator',
    'postmaster'     => 'Postmaster',
    'webmaster'      => 'Webmaster',
    'office'         => 'Office',
    'helpdesk'       => 'Help Desk',
    'careers'        => 'Careers',
    'jobs'           => 'Jobs',
    'cv'             => 'CV',
    'employment'     => 'Employment',
    'feedback'       => 'Feedback',
    'marketing'      => 'Marketing',
  );

  #-------------------------------------------------------------------

  public function get_name($str) {
    $str = $this->resetExplode('@', strtolower($str));
    #---------------------------------------
    $test = str_replace('-', '', $str);
    if (array_key_exists($test, $this->common)) {
      return $this->common[$test];
    }
    #---------------------------------------
    if (strpos($str, '.') !== false) { # john.brown
      $str = explode('.', $str);
      return $this->ucfirst_words($str);
    }
    #---------------------------------------
    if (strpos($str, '_') !== false) { # john_brown
      $str = explode('_', $str);
      return $this->ucfirst_words($str);
    }
    #---------------------------------------
    if (strpos($str, '-') !== false) { # no-reply
      $str = explode('-', $str);
      return $this->ucfirst_words($str);
    }
    #---------------------------------------
    $str = str_replace('+', ' ', $str);
    $str = preg_replace('/\ +/', ' ', $str);
    #---------------------------------------
    $new = preg_replace('/\d+/', ' ', $str);
    $new = trim($new);
    if (empty($new)) {
      $new = $str;
    }
    if (strlen($new) < 4) {
      return $this->ucfirst_words($new);
    }
    $nameArr = explode(' ', $new);
    $new = array();
    foreach ($nameArr as $frag) {
      if (strlen($frag) > 2) {
        $frag = $this->breakString($frag);
      }
      $new[] = $this->ucfirst_words($frag);
    }
    return implode($new);
  }

  #-------------------------------------------------------------------

  private function breakString($str) {
    $this->load_dictionary();
    $wc = 0; # counts segmented words
    #----
    $str_length = strlen($str);
    if ($str_length < $this->longest_name) {
      $maxlen = $str_length;
    }
    else {
      $maxlen = $this->longest_name;
    }
    # $n .... position (index)
    for ($n = 0; $n < $str_length; ) {
      # build a word with 1 character
      $word[$wc] = substr($str, $n, 1);
      $m = 1; # count chars in word
      $test = $word[$wc];
      $found = false;
      # keep incrementing
      while ($m <= $maxlen && ($n+$m) < $str_length) {
        $test .= substr($str, $n+$m, 1); # append 1 character
        # try to find the word in dictionary
        if (array_key_exists($test, $this->dictArray)) {
          $word[$wc] = $test; # because word test exists
          $k = $m;
          $found = true;
        }
        $m++; # number of chars in word
      }
      if ($found) {
        $n += $k+1;
      }
      else {
        $n++;
      }
      $wc++;
    }
    # glue together single characters
    $n = 0;
    $single = false;
    foreach ($word as $key => $val) {
      if (strlen($val) > 1) {
        if ($single == true) {
          $n++;
          $single = false;
        }
        $new[$n] = $val;
        $n++;
      }
      else {
        $single = true;
        if (!isset($new[$n])) {
          $new[$n] = '';
        }
        $new[$n] .= $val;
      }
    }
    return implode(' ', $new); # string
  }

  #-------------------------------------------------------------------

  private function load_dictionary() {
    if (!empty($this->dictArray)) {
      return;
    }
    require __DIR__.'/dictionary-names.php';
    $this->dictArray = array_flip($dict); # speed trick
    $dict = array();
  }

  #-------------------------------------------------------------------

  /**
   * Accepts @arg array or string
   *
   */
  private function ucfirst_words($str) {
    if (is_array($str)) {
      $str = implode(' ', $str);
    }
    return ucwords($str);
  }

  #-------------------------------------------------------------------

  private function resetExplode($glue, $str) {
    if (strpos($str, $glue) === false) {
      return $str;
    }
    $str = explode($glue, $str);
    $str = reset($str);
    return $str;
  }

  #-------------------------------------------------------------------

}

