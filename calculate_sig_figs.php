<?php
  const MATHEMATICAL_CONSTANTS = [
    M_PI,
    M_E,
    M_LOG2E,
    M_LOG10E,
    M_LN2,
    M_LN10,
    M_PI_2,
    M_PI_4,
    M_1_PI,
    M_2_PI,
    M_SQRTPI,
    M_2_SQRTPI,
    M_SQRT2,
    M_SQRT3,
    M_SQRT1_2,
    M_LNPI,
    M_EULER
  ];
  
    function calculate_sig_figs_addition_and_subtraction(...$numbers) {
        $arr_decimals = array();

        // Grab numbers from right of decimal by regex
        // https://stackoverflow.com/questions/2430084/php-get-number-of-decimal-digits
        foreach ($numbers as $n) {
          if (!is_numeric($n) || $n == NAN) {
            return;
          }
          
          // printf("Number: %f\n", $n);
          // This is for English, haven't taken into account the culture that is using MOM.
          $arr_decimals[] = (int) strpos(strrev($n), ".");
        }

        return min($arr_decimals);
    }

    // THANKS TO NICK CURRA: https://www.myopenmath.com/forums/posts.php?cid=1&forum=374413&thread=1305520&page=1
    function get_sig_figs($number) {
      if (!is_numeric($number)  || $number == NAN) {
          return;
      }

      if ($number == 0) {
        return 0;
      }

      // Exact numbers are infinite
      // PATTERN MUST NOT REPEAT
      // E and PI are exact in symbolic form, fractions are exact
      // Irrational numbers are not exact
      // Terminating decimals are exact
      // Unless it's a perfect root it's not going to go in perfectly and is irrational number
      // Keep in mind exact numbers are based on definition so like measurement

      foreach (MATHEMATICAL_CONSTANTS as $M) {
        if ($number == $M) {
          return INF;
        }
      }

      // Strip out beginning + or - signs
      if (strpos("-", $number) == 0 || strpos("+", $number) == 0) {
        preg_match('/^[\+\-]+(.*)$/', $number, $matches);

        if (isset($matches[1])) {
          $number = $matches[1];
        }
      }

      // printf("Stripped out number: %f\n", $number);

      // Does it look like 4.5E7
      if (substr_count($number, "E") == 1 || substr_count($number, "e") == 1) {
        preg_match('/^([^Ee]+)[Ee].*$/', $number, $matches);
        
        if (isset($matches[1])) {
          $number = $matches[1];
        }
      }

      // Strip out leading zeros
      // 3. Leading zeros are NOT significant. They're nothing more than "place holders." The number 0.54 has only TWO significant figures. 0.0032 also has TWO significant figures. All of the zeros are leading.
      preg_match('/^0*(.+)$/', $number, $matches);
      if (isset($matches[1])) {
        $number = $matches[1];
      }

      $sigFigs = 0;

      if (substr_count($number,".") == 1) {
        // Does it look like 0.00045
        preg_match('/^0?\.0*(.+)$/', $number, $matches);
        
        $sigFigs = (isset($matches[1])) ? strlen($matches[1]) : strlen($number) - 1;

        printf("Matches: %d, Sig figs does not contain decimal: %d\n", $matches[1], $sigFigs);

        // Does it not contain a decimal point
      } elseif (substr_count($number,".") == 0) {
        // Does it have trailing zeros
        preg_match('/^(\d*[^0])0+$/', $number, $matches);

        if (isset($matches[1])) {
          $number = $matches[1];
        }

        $sigFigs = strlen($number);
        // printf("Matches: %d, Sig figs does not contain decimal: %d\n", $matches[1], $sigFigs);
      }

    return $sigFigs;
    }

    function calculate_sig_figs_multiplication_and_division(...$numbers) {
        $arr_sig_figs = array();

        foreach ($numbers as $n) {
            if (!is_numeric($n) || $n == NAN) {
              return;
            }

            // printf("Number: %f\n", $n);
            $arr_sig_figs[] = get_sig_figs($n);
        }

        return min($arr_sig_figs);
    }
?>