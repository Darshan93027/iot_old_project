<?php
// Save classroom data to a file for persistence
$file = 'classrooms.txt';

// Initialize strength values
$initialStrengthBtech = 30;
$initialStrengthDesign = 25;

// Check if the request is POST (form submission) or GET (ESP8266 or web browser)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $btechClass = $_POST['btechClass'];
    $designClass = $_POST['designClass'];

    // Save classroom numbers and initial strengths to a file
    $classroomData = "B.Tech Classroom: " . $btechClass . "\nStrength: " . $initialStrengthBtech . "\nDesign Classroom: " . $designClass . "\nStrength: " . $initialStrengthDesign . "\n";
    file_put_contents($file, $classroomData);

    // Echo back the updated values with line breaks for better formatting
    echo "B.Tech Classroom: " . $btechClass . "\nStrength: " . $initialStrengthBtech . "\nDesign Classroom: " . $designClass . "\nStrength: " . $initialStrengthDesign . "\n";

} else {
    // On GET request, read the classroom numbers from the file
    if (file_exists($file)) {
        $classroomData = file_get_contents($file);
        echo nl2br($classroomData); // Ensure line breaks are preserved
    } else {
        // Default values if no file is found
        echo "B.Tech Classroom: Not set\nStrength: Not set\nDesign Classroom: Not set\nStrength: Not set\n";
    }
}

// Function to update strength values based on sensor readings
function updateStrength($currentStrength, $sensorValue, $lastSensorValue) {
    if ($sensorValue > $lastSensorValue) {
        return $currentStrength + 1; // Increment strength
    } elseif ($sensorValue < $lastSensorValue) {
        return max(0, $currentStrength - 1); // Decrement strength but not below zero
    }
    return $currentStrength; // No change
}

// Example of using the updateStrength function
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sensorValue'])) {
    $sensorValue = (int)$_POST['sensorValue'];
    $lastSensorValue = isset($_POST['lastSensorValue']) ? (int)$_POST['lastSensorValue'] : 0; // Replace with the actual last sensor value

    // Update the strengths based on sensor values
    $updatedStrengthBtech = updateStrength($initialStrengthBtech, $sensorValue, $lastSensorValue);
    $updatedStrengthDesign = updateStrength($initialStrengthDesign, $sensorValue, $lastSensorValue);

    // Save updated strengths back to the file
    $classroomData = "B.Tech Classroom: " . $btechClass . "\nStrength: " . $updatedStrengthBtech . "\nDesign Classroom: " . $designClass . "\nStrength: " . $updatedStrengthDesign . "\n";
    file_put_contents($file, $classroomData);
}
?>
