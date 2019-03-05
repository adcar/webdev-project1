
<?php
class MyCase {
    private $isOpened = false;
    private $value = 0;
    private $isSelected = false;
    function __construct($value) {
        $this->value = $value;
    }
    function getValue() {
        return $this->value;
    }
    function isOpened() {
        return $this->isOpened;
    }
    function openCase() {
        $this->isOpened = true;
    }
    function selectCase() {
        $this->isSelected = true;
    }
    function isSelected() {
        return $this->isSelected;
    }
}

function showCases($arrayOfCases, $state) {
    if (gettype($arrayOfCases) !== "array" || sizeof($arrayOfCases) != 25) {
        throw new Error("Invalid array");
    }

    if ($state == 0) {
        echo "<h2>Pick you prize case!</h2>";
    } elseif ($state == 1) {
        echo "<h2>Pick 6 cases to open</h2>";
    } elseif ($state == 2) {
        echo "<h2>You selected 6 cases:</h2>";
    }
    $casesTable = "<table>";
    for ($i = 0; $i < 5; $i++) {
        $casesTable = $casesTable . "<tr>";
        for ($j = 0; $j < 5; $j++) {
            $currentCase = $arrayOfCases[$i * 5 + $j];
            $currentCaseNum = $i * 5 + $j;

            if ($state == 0) {
                $casesTable =
                    $casesTable .
                    "<td><input required type='radio' name='prizeCase' value=" .
                    $currentCaseNum .
                    "/> Case #" .
                    $currentCaseNum .
                    "</td>";
            } elseif ($state == 1) {
                if ($currentCase->isSelected()) {
                    // Custom colors if it's selected
                    $casesTable =
                        $casesTable .
                        "<td style='background-color: teal; color: white'> Case #" .
                        $currentCaseNum .
                        "</td>";
                } else {
                    $casesTable =
                        $casesTable .
                        "<td><input type='checkbox' name='openedCases[]' value=$currentCaseNum /> Case #" .
                        $currentCaseNum .
                        "</td>";
                }
            } elseif ($state == 2) {
                if ($currentCase->isOpened()) {
                    $casesTable =
                        $casesTable .
                        "<td style='background-color: lightgrey'>$" .
                        $arrayOfCases[$i * 5 + $j]->getValue() .
                        "</td>";
                } elseif ($currentCase->isSelected()) {
                    $casesTable =
                        $casesTable .
                        "<td style='background-color: teal; color: white'> Case #" .
                        $currentCaseNum .
                        "</td>";
                } else {
                    $casesTable =
                        $casesTable . "<td> Case #" . $currentCaseNum . "</td>";
                }
            }
        }
        $casesTable = $casesTable . "</tr>";
    }

    $casesTable = $casesTable . "</table>";

    return $casesTable;
}

function updateArrayOfCases($arrayOfCases) {
    for ($i = 0; $i < 25; $i++) {
        // Open the correct cases
        if (isset($_POST["openedCases"])) {
            if (in_array(strval($i), $_POST['openedCases'])) {
                $arrayOfCases[$i]->openCase();
            }
        }
        if (isset($_POST["prizeCase"])) {
            if (intVal($_POST['prizeCase']) == $i) {
                $arrayOfCases[$i]->selectCase();
                $_SESSION['prizeCaseIndex'] = $i;
            }
        }
    }
    $_SESSION['arrayOfCases'] = $arrayOfCases;
}

function cmp($a, $b) {
    return $a->getValue() - $b->getValue();
}

function getCashValues($arrayOfCases) {
    echo "<h2>Remaining Cash Values</h2>";
    usort($arrayOfCases, "cmp");
    echo "<table>";
    for ($i = 0; $i < 5; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 5; $j++) {
            if ($arrayOfCases[$i * 5 + $j]->isOpened()) {
                echo "<td style='background-color: lightgrey'>$" .
                    number_format($arrayOfCases[$i * 5 + $j]->getValue()) .
                    "</td>";
            } else {
                echo "<td>$" .
                    number_format($arrayOfCases[$i * 5 + $j]->getValue()) .
                    "</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";
}


?>
