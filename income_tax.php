<?php

define(
  'TAX_RATES',
  array(
    'Single' => array(
      'Rates' => array(10, 12, 22, 24, 32, 35, 37),
      'Ranges' => array(0, 10275, 41775, 89075, 170050, 215950, 539900),
      'MinTax' => array(0, 1027.5, 4807.5, 15213.5, 34647.5, 49335.5, 162718)
    ),
    'Married_Jointly' => array(
      'Rates' => array(10, 12, 22, 24, 32, 35, 37),
      'Ranges' => array(0, 20550, 83550, 178150, 340100, 431900, 647850),
      'MinTax' => array(0, 2055, 9615, 30427, 69295, 98671, 174235.5)
    ),
    'Married_Separately' => array(
      'Rates' => array(10, 12, 22, 24, 32, 35, 37),
      'Ranges' => array(0, 10275, 41775, 89075, 17050, 215950, 323925),
      'MinTax' => array(0, 1027.5, 4807.50, 15213.50, 34647.50, 49335.50, 87126.75)
    ),
    'Head_Household' => array(
      'Rates' => array(10, 12, 22, 24, 32, 35, 37),
      'Ranges' => array(0, 14650, 55900, 89050, 170050, 215950, 539900),
      'MinTax' => array(0, 1465, 6415, 13708, 33148, 47836, 161218.50)
    )
  )
);

// IncomeTax function calculates the taxable income based on the tax bracket 

function incomeTax($taxableIncome, $status)
{

  if ($taxableIncome < 0)
    return 0;

  // define the tax rates constant to extract the arrays for the
  // tax rates, tax ranges, and minimum taxes.
  $tax_brackets = TAX_RATES[$status]['Rates'];
  $ranges = TAX_RATES[$status]['Ranges'];
  $minTax = TAX_RATES[$status]['MinTax'];

  // initialize the tax bracket to zero
  $taxBracket = 0;
  $incTax = 0.0;

  // define the range of the array in the ranges
  $index = count($ranges);

  for ($i = count($ranges) - 1; $i >= 0; $i--) {
    if ($taxableIncome > $ranges[$i]) {
      $index = $i;
      break;
    }
  }

  $incTax = $minTax[$index] + $tax_brackets[$index] * ($taxableIncome - $ranges[$index]) / 100;

  /*
    While loop is to find the tax bracket for the taxable income.
    the increment of tax bracket until it is greater the upper limit
    of the current bracket.
  */
  // while ($taxableIncome > $ranges[$taxBracket + 1]) {
  //   $taxBracket++;
  // }

  // calculate the income tax by adding the minimum tax for the bracket that
  // exceed the lower limit of the bracket and multiply by the tax rate.
  // $incTax = $minTax[$taxBracket] + ($tax_brackets[$taxBracket] / 100) *
  //   ($taxableIncome - $ranges[$taxBracket]);

  return $incTax;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>HW4 Part2 - Nguyen</title>

  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</head>

<body>

  <div class="container">

    <h3>Income Tax Calculator</h3>

    <form class="form-horizontal" method="post">

      <div class="form-group">
        <label class="control-label col-sm-2">Enter Net Income:</label>
        <div class="col-sm-10">
          <input type="number" step="any" name="netIncome" placeholder="Taxable  Income" required autofocus>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>

    </form>

    <?php


    // Fill in the rest of the PHP code for form submission results
    if (isset($_POST['netIncome'])) {

      // get data from the form and store the data in the income variable
      $income = $_POST['netIncome'];

      // retrieve the status of the taxble income bracket data.
      $single = incomeTax($income, 'Single');
      $marriedJoint = incomeTax($income, 'Married_Jointly');
      $marriedSep = incomeTax($income, 'Married_Separately');
      $household = incomeTax($income, 'Head_Household');

    ?>

      <!-- display the net income in a format USD. -->
      <p>With a net taxable income of $
        <?php echo number_format($_POST['netIncome'], 2) ?>

        <!-- Display the income tax bracket based on the status in a table -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th width="20%">Status</th>
            <th>Tax</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Single</td>
            <td>$<?php echo number_format($single, 2) ?></td>
          </tr>
          <tr>
            <td>Married Filing Jointly</td>
            <td>$<?php echo number_format($marriedJoint, 2) ?></td>
          </tr>
          <tr>
            <td>Married Filing Separately</td>
            <td>$<?php echo number_format($marriedSep, 2) ?></td>
          </tr>
          <tr>
            <td>Head of Household</td>
            <td>$<?php echo number_format($household, 2) ?></td>
          </tr>
        </tbody>
      </table>

    <?php } ?>

    <h3>2022 Tax Brackets and Tax Rates (for filing in 2023)</h3>

    <?php

    /*
      The foreach loop through the bracket array and display the
      taxable income of the taxable range.
    */
    foreach (TAX_RATES as $status => $bracketArray) {
      
      $ranges = $bracketArray['Ranges'];
      $min = $bracketArray['MinTax'];
      $tax_brackets = $bracketArray['Rates'];
      $len = count($ranges);
      ?>

      <h4><?php echo $status?></h4>

      <!-- display the table head of the tax rate -->
      <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="30%">Taxable Income</th>
                <th >Tax Rate</th>
            </tr>
        </thead>

        <tbody>
        <!-- The first bracket -->
          <tr>
            <td> 
              $<?php echo number_format($ranges[0])?> - $<?php echo number_format($ranges[1]) ?>
            </td>
            <td>
              <?php echo $tax_brackets[0]?>%
            </td>
          </tr>

      <!-- loop through the innerloop length, intialize index one
      to the second of the last index array -->
      <?php for($i = 1; $i < ($len - 1); $i++){
        ?>
        <tr>
          <td>$<?php echo number_format($ranges[$i]+1) ?> - $<?php echo number_format($ranges[$i+1])?>
          </td>
          <td>
            $<?php echo number_format($min[$i], 2) ?> plus <?php echo  $tax_brackets[$i]?> 
            % of the amount over $<?php echo number_format($ranges[$i])?>
          </td>         
        </tr>    
      <?php } ?>

      <!-- // display the last index data in a table -->
      <tr>
        <td> 
          $<?php echo number_format($ranges[$len - 1] + 1)?> or more
        </td>
        <td> 
          $<?php echo number_format($min[$len - 1], 2)?> plus <?php $tax_brackets[$i]?> 
          % of the amount over $<?php echo number_format($ranges[$i])?>
        </td>
      </tr>
      </tbody>
    </table>
    <?php } ?>
  </div>

</body>

</html>
