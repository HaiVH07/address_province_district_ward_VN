<?php
function fetchAndStoreDistrictData($districtCodes, $outputFile) {
    $combinedData = [];

    foreach ($districtCodes as $districtCode) {
        $apiUrl = "https://provinces.open-api.vn/api/d/{$districtCode}?depth=2";
        $ch = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $response = curl_exec($ch);

        if ($response !== false) {
            // Decode the API response (assuming it's in JSON format)
            $districtData = json_decode($response, true);

            if ($districtData) {
                // Add the province data to the combined data array
                $combinedData[$districtCode] = $districtData;
            } else {
                echo "Failed to decode the API response for province $districtCode as JSON.\n";
            }
        } else {
            echo "cURL Error for province $districtCode: " . curl_error($ch) . "\n";
        }

        // Close the cURL session
        curl_close($ch);
    }

    // Save the combined data to the specified output file
    file_put_contents($outputFile, json_encode($combinedData, JSON_PRETTY_PRINT));

    echo "Data has been successfully saved to $outputFile\n";
}

// Example usage:
$jsonData = file_get_contents('district.json');

// Decode the JSON data into a PHP array
$data = json_decode($jsonData, true);

if ($data) {
    // Extract the 'code' values and store them in the $provinceCodes array
    $districtCodes = array_column($data, 'code');

    // Print the extracted codes
    print_r($districtCodes);
} else {
    echo "Failed to decode the JSON data from district.json.";
}

$outputFile = "ward.json";
fetchAndStoreDistrictData($districtCodes, $outputFile);
