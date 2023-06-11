<?php
/*
    https://ccnmtl.columbia.edu/projects/mmt/frontiers/web/chapter_5/6665.html
    1. All non-zero numbers ARE significant. The number 33.2 has THREE significant figures because all of the digits present are non-zero.
    2. Zeros between two non-zero digits ARE significant. 2051 has FOUR significant figures. The zero is between a 2 and a 5.
    3. Leading zeros are NOT significant. They're nothing more than "place holders." The number 0.54 has only TWO significant figures. 0.0032 also has TWO significant figures. All of the zeros are leading.
    4. Trailing zeros to the right of the decimal ARE significant. There are FOUR significant figures in 92.00.
    92.00 is different from 92: a scientist who measures 92.00 milliliters knows his value to the nearest 1/100th milliliter; meanwhile his colleague who measured 92 milliliters only knows his value to the nearest 1 milliliter. It's important to understand that "zero" does not mean "nothing." Zero denotes actual information, just like any other number. You cannot tag on zeros that aren't certain to belong there.
    5. Trailing zeros in a whole number with the decimal shown ARE significant. Placing a decimal at the end of a number is usually not done. By convention, however, this decimal indicates a significant zero. For example, "540." indicates that the trailing zero IS significant; there are THREE significant figures in this value.
    6. Trailing zeros in a whole number with no decimal shown are NOT significant. Writing just "540" indicates that the zero is NOT significant, and there are only TWO significant figures in this value.
    7. Exact numbers have an INFINITE number of significant figures. This rule applies to numbers that are definitions. For example, 1 meter = 1.00 meters = 1.0000 meters =
    1.0000000000000000000 meters, etc.
    So now back to the example posed in the Rounding Tutorial: Round 1000.3 to four significant figures. 1000.3 has five significant figures (the zeros are between non-zero digits 1 and 3, so by rule 2 above, they are significant.) We need to drop the final 3, and since 3 < 5, we leave the last zero alone. so 1000. is our four-significant-figure answer. (from rules 5 and 6, we see that in order for the trailing zeros to "count" as significant, they must be followed by a decimal. Writing just "1000" would give us only one significant figure.)
    8. For a number in scientific notation: N x 10x, all digits comprising N ARE significant by the first 6 rules; "10" and "x" are NOT significant. 5.02 x 104 has THREE significant figures: "5.02." "10 and "4" are not significant.

    https://germanna.edu/sites/default/files/2022-03/Significant%20Figure%20Rules.pdf
    Addition/subtraction: least precise decimal
    Multiplication/division: least precise significant figure
*/

require_once('./calculate_sig_figs.php');

class CalculateSigFigsUnitTest extends \PHPUnit\Framework\TestCase
{
    public function testPositiveInteger()
    {
        $input = 123;
        $expectedOutput = 3;

        $result = get_sig_figs($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testNegativeDecimalNumber()
    {
        $input = -0.0032;
        $expectedOutput = 2;

        $result = get_sig_figs($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testWholeNumberWithTrailingZeroAndDecimalPoint()
    {
        $input = 540.00;
        $expectedOutput = 2;

        $result = get_sig_figs($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testPositiveNumberInScientificNotation()
    {
        $input = 5.02e4;
        $expectedOutput = 3;

        $result = get_sig_figs($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testZero()
    {
        $input = 0;
        $expectedOutput = 0;

        $result = get_sig_figs($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testNonNumerical()
    {
        $input = "abc";
        $expectedOutput = null;

        $result = get_sig_figs($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testPositiveIntegerWithTrailingZeros()
    {
        $input = 2000;
        $expectedOutput = 1;

        $result = get_sig_figs($input);
        $this->assertEquals($expectedOutput, $result);
    }

    // constants
    public function testInfinity()
    {
        $input = M_PI;
        $expectedOutput = INF;

        $result = get_sig_figs($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testTwoNumbersHavingDifferentDecimalPlacesAdditionSubtraction()
    {
        $result = calculate_sig_figs_addition_and_subtraction(3.456, 2.1);
        $expectedOutput = 1;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testThreeNumbersHavingDifferentDecimalPlacesAdditionSubtraction()
    {
        $result = calculate_sig_figs_addition_and_subtraction(3.456, 2.1, 0.123);
        $expectedOutput = 1;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testTwoNumbersHavingSameDecimalPlacesAdditionSubtraction()
    {
        $result = calculate_sig_figs_addition_and_subtraction(1.23, 4.56);
        $expectedOutput = 2;
        
        $this->assertEquals($expectedOutput, $result);
    }

    public function testNegativeNumbersHavingDifferentDecimalPlacesAdditionSubtraction()
    {
        $result = calculate_sig_figs_addition_and_subtraction(-10.5, -0.12345);
        $expectedOutput = 1;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testNegativeAndPositiveNumbersHavingDifferentDecimalPlacesAdditionSubtraction()
    {
        $result = calculate_sig_figs_addition_and_subtraction(-10.5, 5.4321, -0.12345);
        $expectedOutput = 1;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testZeroDecimalsAdditionSubtraction()
    {
        $result = calculate_sig_figs_addition_and_subtraction(10, 100, 1000);
        $expectedOutput = 0;

        $result = get_sig_figs($result);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testNonNumericalAdditionSubtraction()
    {
        $result = calculate_sig_figs_addition_and_subtraction(10, "#");
        $expectedOutput = null;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPositiveNumberInScientificNotationAdditionSubtraction()
    {
        $result = calculate_sig_figs_addition_and_subtraction(3.4e5);
        $expectedOutput = 0;

        $this->assertEquals($expectedOutput, $result);
    }
    
    public function testPositiveNumberInScientificNotationMultiplicationDivision()
    {
        $result = calculate_sig_figs_multiplication_and_division(3.4e5);
        $expectedOutput = 2;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testTwoNumbersHavingDifferentSigFigsMultiplicationDivision()
    {
        $result = calculate_sig_figs_multiplication_and_division(3.456, 2.1);
        $expectedOutput = 2;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testThreeNumbersHavingDifferentSigFigsMultiplicationDivision()
    {
        $result = calculate_sig_figs_multiplication_and_division(3.456, 2.1, 0.123);
        $expectedOutput = 2;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testTwoNumbersHavingSameSigFigsMultiplicationDivision()
    {
        $result = calculate_sig_figs_multiplication_and_division(1.23, 4.56);
        $expectedOutput = 3;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testNegativeNumbersHavingDifferentSigFigsMultiplicationDivision()
    {
        $result = calculate_sig_figs_multiplication_and_division(-10.5, -0.12345);
        $expectedOutput = 3;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testNegativeAndPositiveNumbersHavingDifferentSigFigsMultiplicationDivision()
    {
        $result = calculate_sig_figs_multiplication_and_division(-10.5, 5.4321, -0.12345);
        $expectedOutput = 3;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testZeroSigFigsMultiplicationDivision()
    {
        $result =calculate_sig_figs_multiplication_and_division(0, 0.0000);
        $expectedOutput = 0;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testNonNumericalMultiplicationDivision()
    {
        $result = calculate_sig_figs_multiplication_and_division(10, "#");
        $expectedOutput = null;

        $this->assertEquals($expectedOutput, $result);
    }

    public function testPositiveNumberInScientificNotationSigFigsMultiplicationDivision()
    {
        $result = calculate_sig_figs_multiplication_and_division(3.4e5, 2.1);
        $expectedOutput = 2;

        $this->assertEquals($expectedOutput, $result);
    }
}
?>