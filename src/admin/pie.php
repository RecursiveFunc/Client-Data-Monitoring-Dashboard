<!DOCTYPE html>
<html lang="en">

<?php
session_start();

include("./includes/adminHead.php");
include("./includes/adminHeader.php");

$username = $_SESSION['username'];
$role = $_SESSION['jenis_role'];
?>

<style>
    /* Apply CSS styles for the charts */
    #workingTimeChart,
    #chart {
        width: 50%;
        min-height: 200px;
    }
</style>

<body>
    <p id="userOutput"></p>
    <p id="lastPartValue"></p> <!-- New element to display lastPart value -->

    <!-- Date input for date selection -->
    <label for="selectedDate">Select Date:</label>
    <input type="date" id="selectedDate">

    <a href="../summary.php">Back</a>

    <!-- The container for the first chart -->
    <h2>Chart 1: Working Time Percentages</h2>
    <div id="workingTimeChart"></div>

    <!-- The container for the second chart -->
    <h2>Chart 2: Application Time Percentages</h2>
    <div id="appTimeChart"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        // Get the current URL
        var currentURL = window.location.href;

        // Split the URL by '/'
        var parts = currentURL.split('/');

        // Get the last part of the URL
        var lastPart = parts[parts.length - 1];

        // Extract the part before the question mark
        var slug = lastPart.split('?')[0];

        // Get the date parameter from the URL
        var datePart = getUrlParameter('tgl');

        // Display lastPart value above the "Back" button
        document.getElementById('lastPartValue').innerText = 'MAC Address : ' + slug;

        // Function to get URL parameter by name
        function getUrlParameter(name) {
            var params = new URLSearchParams(window.location.search);
            return params.get(name);
        }
    </script>

    <script>
        let matchedApp; // Declare the variable outside the AJAX blocks

        $.ajax({
            url: '../dashboard/getUser.php',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                let row = '';
                let matchedUser = ''; // Variable to store the matched user
                let i = 1;
                let job; // Declare job variable
                $.each(data, function(key, value) {
                    // Check if value.slug matches lastPart
                    if (value.slug === slug) {
                        console.log('Match found! User: ' + value.user);
                        matchedUser = value.user; // Store the matched user
                        job = value.pekerjaan_description; // Store the job description
                    }
                });
                // Update the HTML content
                $('#userOutput').text('User PC : ' + matchedUser);

                // Call the getApp AJAX after the getUser AJAX is complete
                getApp(job);
            }
        });

        function getApp(jobDescription) {
            $.ajax({
                url: '../app/getApp.php',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    let row = '';
                    let i = 1;
                    let appNotFound = true;

                    $.each(data, function(key, value) {
                        if (value.pekerjaan_description === jobDescription) {
                            console.log('Match found! App: ' + value.app);
                            matchedApp = value.app; // Store the matched job description
                            appNotFound = false;
                        }
                    });

                    if (appNotFound) {
                        console.log('No matching app found.');
                        // Handle the case where no matching app is found
                    } else {
                        // Now you can use matchedApp here or in any subsequent code
                        console.log('Matched Job App: ' + matchedApp);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                    // Handle the error (e.g., display an error message)
                }
            });
        }
    </script>

    <script>
        // Declare previousSelectedDate to keep track of the previous selected date
        let previousSelectedDate;
        // Declare a variable to store the XMLHttpRequest object
        let ajaxRequest;

        // Event handler for the selectedDate change
        $('#selectedDate').on('change', function() {
            const selectedDate = $(this).val();

            // Format the date as "YYYY-MM-DD"
            formattedDate = formatDate(selectedDate);

            // Check if the selected date has changed
            if (formattedDate !== previousSelectedDate) {
                // Update the previousSelectedDate
                previousSelectedDate = formattedDate;

                // Abort the previous AJAX request if it exists
                if (ajaxRequest && ajaxRequest.readyState !== 4) {
                    ajaxRequest.abort();
                }

                // Call the function to handle AJAX with formattedDate
                if (formattedDate) {
                    handleAjaxCall();
                }
            }
        });

        // Function to format date as "YYYY-MM-DD"
        function formatDate(dateString) {
            const date = new Date(dateString);
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${year}-${month}-${day}`;
        }

        // Function to handle AJAX with formattedDate
        function handleAjaxCall() {
            // Abort the previous AJAX request if it exists
            if (ajaxRequest && ajaxRequest.readyState !== 4) {
                ajaxRequest.abort();
            }

            // Create a new AJAX request
            ajaxRequest = $.ajax({
                url: '../store/getData.php',
                dataType: 'json',
                success: function(data) {
                    // Find the first matching row based on macAddress and formattedDate
                    const matchingRow = data.find(item => item.identifier === slug && item.log_date === formattedDate);

                    if (matchingRow) {
                        // Extract the necessary data from the matching row
                        const jsonData = matchingRow.data;
                        const windowData = JSON.parse(jsonData)["aw-watcher-window"];

                        // Update the chart with the new data
                        updateCharts(windowData);
                    } else {
                        console.log("No data found for the selected date and MAC Address.");
                        // Display a message or handle the absence of data
                        // Clear or hide the existing charts if necessary
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                    // Handle the error (e.g., display an error message)
                    // Clear or hide the existing charts if necessary
                }
            });

        }

        // Function to update both charts with new data
        function updateCharts(windowData) {
            const workingTimeData = [];
            const codeExeTimeData = [];
            const appTitleData = [];

            // Loop through each data item
            windowData.window.forEach((item, index) => {
                const windowItem = item.window;
                const appTitle = windowItem.appTitle;
                const duration = windowItem.durationInSeconds;

                // Check if the data is not empty
                if (duration !== undefined) {
                    workingTimeData.push({
                        durationInSeconds: duration,
                    });

                    codeExeTimeData.push({
                        appTitle: appTitle,
                        durationInSeconds: duration,
                    });

                    appTitleData.push({
                        appTitle: appTitle,
                        durationInSeconds: duration,
                    });
                }
            });

            // Calculate the total duration for working time
            const totalWorkingDuration = workingTimeData.reduce(
                (total, item) => total + item.durationInSeconds,
                0
            );

            // Calculate the total duration for code.exe
            const totalCodeExeDuration = codeExeTimeData.reduce(
                (total, item) => total + item.durationInSeconds,
                0
            );

            // Calculate non-working time
            const nonWorkingDuration = 24 * 60 * 60 - totalWorkingDuration;

            // Calculate percentages
            const workingTimePercentage = (totalWorkingDuration / (24 * 60 * 60)) * 100;
            const nonWorkingPercentage = (nonWorkingDuration / (24 * 60 * 60)) * 100;

            // Update the working time chart
            updateWorkingTimeChart(workingTimePercentage, nonWorkingPercentage);

            // Group data by appTitle
            const groupedData = appTitleData.reduce((result, item) => {
                const key = item.appTitle || "Unknown";
                result[key] = (result[key] || 0) + item.durationInSeconds;
                return result;
            }, {});

            // Convert to an array of objects
            const dataArray = Object.keys(groupedData).map((key) => ({
                appTitle: key,
                durationInSeconds: groupedData[key],
            }));

            // Sort the array by durationInSeconds in descending order
            dataArray.sort((a, b) => b.durationInSeconds - a.durationInSeconds);

            // Extract labels, series, and colors
            const labels = dataArray.map((item) => item.appTitle);
            const series = dataArray.map(
                (item) => (item.durationInSeconds / totalWorkingDuration) * 100
            );
            const colors = labels.map((label) =>
                label.toLowerCase() === matchedApp ? "#00FF00" : "#FF0000"
            );

            // Update the app time chart
            updateAppTimeChart(labels, series, colors);
        }

        // Function to update the working time chart
        function updateWorkingTimeChart(workingTimePercentage, nonWorkingPercentage) {
            // Your ApexCharts options for working time
            var workingTimeOptions = {
                series: [workingTimePercentage, nonWorkingPercentage],
                chart: {
                    width: 500,
                    type: "pie",
                },
                labels: [
                    `Working Time (${workingTimePercentage.toFixed(2)}%)`,
                    `Non-Working Time (${nonWorkingPercentage.toFixed(2)}%)`,
                ],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200,
                        },
                        legend: {
                            position: "bottom",
                        },
                    },
                }, ],
            };

            // Create a new ApexCharts instance for working time
            var workingTimeChart = new ApexCharts(
                document.querySelector("#workingTimeChart"),
                workingTimeOptions
            );
            workingTimeChart.render();
        }

        // Function to update the app time chart
        function updateAppTimeChart(labels, series, colors) {
            // Your ApexCharts options for app time
            var appTimeOptions = {
                series: series,
                chart: {
                    width: 500,
                    type: "pie",
                },
                labels: labels.map(
                    (label, index) => `${label} (${series[index].toFixed(2)}%)`
                ),
                colors: colors,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200,
                        },
                        legend: {
                            position: "bottom",
                        },
                    },
                }, ],
            };

            // Create a new ApexCharts instance for app time
            var appTimeChart = new ApexCharts(
                document.querySelector("#appTimeChart"),
                appTimeOptions
            );
            appTimeChart.render();
        }
    </script>
</body>

</html>