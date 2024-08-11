<!DOCTYPE html>
<html lang="en">

<?php
session_start();

include("./includes/adminHead.php");
include("./includes/adminHeader.php");

$username = $_SESSION['username'];
$role = $_SESSION['jenis_role'];
?>

<body>
    <p id="userOutput"></p>
    <p id="lastPartValue"></p> <!-- New element to display lastPart value -->

    <!-- Date input for date selection -->
    <label for="selectedDate">Select Date:</label>
    <input type="date" id="selectedDate">

    <a href="../dashboard.php">Back</a>
    <div class="chart-container">
        <div id="chart" style="width: 100%; min-height: 300px"></div>
        <div id="chart-line" style="width: 100%; height: 300px"></div>
        <div id="chart-line2" style="width: 100%; height: 300px"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include FullCalendar library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://moment.github.io/luxon/global/luxon.js"></script>

    <script>
        // Get the current URL
        var currentURL = window.location.href;
        // Split the URL by '/'
        var parts = currentURL.split('/');
        // Get the last part of the URL
        var lastPart = parts[parts.length - 1];
        // Remove any query parameters
        var macAddress = lastPart.split('?')[0];
        // Display lastPart value above the "Back" button
        document.getElementById('lastPartValue').innerText = 'MAC Address : ' + macAddress;
    </script>

    <script>
        // Store the original URL when the page loads
        const originalUrl = window.location.href;

        // Declare formattedDate in a higher scope
        let formattedDate;

        // Event handler for the selectedDate change
        $('#selectedDate').on('change', function() {
            const selectedDate = $(this).val();

            // Format the date as "MM-DD-YYYY"
            formattedDate = formatDate(selectedDate);
            // console.log("waktu1:", formattedDate);

            // Update the current URL with the formatted date
            const newUrl = originalUrl.split('?')[0] + '?tgl=' + formattedDate;

            // Update the browser URL without reloading the page
            history.pushState(null, null, newUrl);

            // Now you can use formattedDate here or call a function
            handleFormattedDate();
        });

        function handleFormattedDate() {
            // Use formattedDate in subsequent code
            // console.log("waktu2:", formattedDate);

            // Assuming macAddress and formattedDate may contain leading or trailing spaces
            const trimmedMacAddress = macAddress.trim();
            const trimmedFormattedDate = formattedDate.trim();

            // Replace the URL with the actual API endpoint you want to fetch data from
            const serverUrl = 'http://185.207.9.74/KP_2023/Activity_Log_' + trimmedMacAddress + '_' + trimmedFormattedDate + '.json';

            // Fetch data or perform other operations here
            // Using the fetch function to make a GET request
            fetch(serverUrl)
                .then(response => {
                    // Checking if the response status is OK (200)
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    // Parsing the JSON data from the response
                    return response.json();
                })
                .then(data => {
                    // // Handling the data after it's been successfully fetched
                    // console.log('Fetched data:', data);

                    // Send the fetched data to the server using AJAX
                    $.ajax({
                        type: 'POST',
                        url: '../store/saveData.php',
                        data: {
                            identifier: trimmedMacAddress, // menggunakan trimmedMacAddress sebagai identifier
                            log_date: trimmedFormattedDate, // menggunakan trimmedFormattedDate sebagai log_date
                            data: JSON.stringify(data) // mengonversi data ke format JSON string dan menggunakan sebagai data
                            // tambahkan data lainnya jika diperlukan
                        },
                        success: function(response) {
                            // Tanggapan dari server setelah operasi berhasil
                            console.log('Server response:', response);
                        },
                        error: function(error) {
                            // Menangani kesalahan ketika permintaan tidak berhasil
                            console.error('Error during AJAX request:', error);
                        }
                    });
                })
                .catch(error => {
                    // Handling errors that may occur during the fetch process
                    console.error('Error during fetch:', error);
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


            ajaxRequest = $.ajax({
                url: '../store/getData.php',
                dataType: 'json',
                success: function(data) {
                    console.log(data);

                    // Find the data row that matches macAddress and formattedDate
                    const matchingDataRow = data.find(item => item.identifier === macAddress && item.log_date === formattedDate);

                    if (matchingDataRow) {
                        // Extract the necessary data from the matching row
                        const jsonData = matchingDataRow.data;
                        const parsedData = JSON.parse(jsonData);
                        const windowData = parsedData["aw-watcher-window"];

                        // Check if windowData is an array and log its length
                        if (Array.isArray(windowData.window)) {
                            // Generate timeline data and log it
                            const timelineData = generateTimelineData(windowData.window);

                            // Check if timelineData is not empty before rendering the chart
                            if (timelineData.length > 0) {
                                createAndRenderChart(timelineData);
                            } else {
                                console.log("Timeline data is empty.");
                                // Handle the case where timelineData is empty
                                // Clear or hide the existing charts if necessary
                            }
                        } else {
                            console.log("windowData.window is not an array.");
                            // Handle the case where windowData.window is not an array
                            // Clear or hide the existing charts if necessary
                        }

                    } else {
                        console.log("No data found for the selected date and MAC Address.");
                        // Handle the case where no matching data is found
                        // Clear or hide the existing charts if necessary
                    }

                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                    // Handle the error (e.g., display an error message)
                    // Clear or hide the existing charts if necessary
                }
            });


            $.ajax({
                url: '../store/getData.php',
                dataType: 'json',
                success: function(data) {
                    // Find the first matching row based on macAddress and formattedDate
                    const matchingRow = data.find(item => item.identifier === macAddress && item.log_date === formattedDate);

                    if (matchingRow) {
                        // Extract the necessary data from the matching row
                        const jsonData = matchingRow.data;
                        const parsedData = JSON.parse(jsonData);
                        const cpmData = parsedData["aw-watcher-CPM"];
                        const ppmData = parsedData["aw-watcher-mouse"];

                        // Update the CPM chart
                        processCPMData(cpmData, chartCPM);

                        // Update the PPM chart
                        processPPMData(ppmData, chartPPM);
                    } else {
                        console.log("No data found for the selected date and MAC Address.");
                        // Handle the case where no matching data is found
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

        // Function to convert timestamp to milliseconds
        function convertTimestampToMillis(timestamp) {
            const timestampWithoutMilliseconds = timestamp.replace(/\.\d+/, "");
            return luxon.DateTime.fromFormat(timestampWithoutMilliseconds, "yyyy-MM-dd HH:mm:ss.ZZ", {
                zone: "Asia/Jakarta"
            }).toMillis();
        }

        // Function to calculate startTimestamp based on endTimestamp and durationInSeconds
        function calculateStartTimestamp(endTimestamp, duration) {
            return endTimestamp - (duration * 1000); // Convert duration to milliseconds
        }

        // Function to generate data for timeline
        function generateTimelineData(windowData) {
            const timelineData = [];

            windowData.forEach((item, index) => {
                const windowItem = item.window;
                const appTitle = windowItem.appTitle;
                const tabTitle = windowItem.tabTitle;
                const timestamp = windowItem.timestamp;
                const duration = windowItem.durationInSeconds;

                if (appTitle && tabTitle && timestamp && duration) {
                    const endTimestamp = convertTimestampToMillis(timestamp);
                    const startTimestamp = calculateStartTimestamp(endTimestamp, duration);

                    let color = matchedApp === appTitle ? "#00FF00" : "#FF0000"; // green for matchedApp, red for others

                    timelineData.push({
                        name: appTitle,
                        tab: tabTitle,
                        data: [{
                            x: "Windows",
                            y: [startTimestamp, endTimestamp],
                        }],
                        color: color,
                    });
                } else {
                    console.log("No data found for the selected date.");
                    // Display a message or handle the absence of data
                    // Clear or hide the existing charts if necessary
                }
            });

            return timelineData;
        }


        // Function to create and render the chart
        function createAndRenderChart(timelineData) {
            var options = {
                series: timelineData,
                chart: {
                    height: 400,
                    type: "rangeBar",
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: "50%",
                        barWidth: "50%",
                        rangeBarGroupRows: true,
                    },
                },
                colors: timelineData.map((data) => data.color),
                fill: {
                    type: "solid",
                },
                xaxis: {
                    type: "datetime",
                    labels: {
                        rotate: -90,
                        formatter: function(value) {
                            // Format time zone for labels
                            const formattedTime = luxon.DateTime.fromMillis(value, {
                                zone: "Asia/Jakarta"
                            }).toFormat("HH:mm:ss a");
                            return formattedTime;
                        },
                    },
                },
                yaxis: {
                    title: {
                        text: "Windows",
                        rotate: -90,
                        style: {
                            fontSize: "15px", // Adjust text size as needed
                        },
                    },
                },
                legend: {
                    show: false, // Hide the legend
                },
                fill: {
                    opacity: 1,
                    type: "solid",
                },
                tooltip: {
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex
                    }) {
                        const appTitle = timelineData[seriesIndex].name;
                        const tabTitle = timelineData[seriesIndex].tab;
                        const endTimestamp = series[seriesIndex][dataPointIndex];
                        const duration = timelineData[seriesIndex].data[0].y[1] - timelineData[seriesIndex].data[0].y[0];
                        const startTimestamp = endTimestamp - duration;
                        // Format time zone for tooltip
                        const formattedStartTime = luxon.DateTime.fromMillis(startTimestamp, {
                            zone: "Asia/Jakarta"
                        }).toFormat("yyyy-MM-dd HH:mm:ss");
                        const formattedEndTime = luxon.DateTime.fromMillis(endTimestamp, {
                            zone: "Asia/Jakarta"
                        }).toFormat("yyyy-MM-dd HH:mm:ss");
                        return `App: ${appTitle}<br>
                        Tab: ${tabTitle}<br>
                        Start: ${formattedStartTime}<br>
                        End: ${formattedEndTime}<br>
                        Duration: ${duration / 1000} Seconds `;
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }


        // Function to process CPM data
        function processCPMData(cpmData, chart) {
            const cpmValues = [];
            const categories = [];

            if (cpmData && Array.isArray(cpmData.cpm)) {
                cpmData.cpm.forEach((item) => { // Adjusted this line
                    const cpmItem = item.cpm;

                    if (cpmItem) {
                        const timestamp = cpmItem.timestamp;
                        const cpmValue = cpmItem.cpm;

                        cpmValues.push(cpmValue);
                        categories.push(timestamp);
                    }
                });

                updateChart(chart, "CPM Data", categories, cpmValues);
            }
        }

        // Function to process PPM data
        function processPPMData(ppmData, chart) {
            const ppmValues = [];
            const categories = []; // Define categories within the function

            if (ppmData && Array.isArray(ppmData.ppm)) {
                ppmData.ppm.forEach((item) => {
                    const ppmItem = item.ppm;

                    if (ppmItem) {
                        const timestamp = ppmItem.timestamp;
                        const ppmValue = ppmItem.ppm;

                        ppmValues.push(ppmValue);
                        categories.push(timestamp);
                    }
                });

                updateChart(chart, "PPM Data", categories, ppmValues);
            }
        }

        // Function to update the chart with new data
        function updateChart(chart, seriesName, categories, data) {
            chart.updateOptions({
                xaxis: {
                    categories: categories,
                    labels: {
                        style: {
                            fontSize: "9px",
                        },
                    },
                },
            });
            chart.updateSeries([{
                name: seriesName,
                data: data,
            }]);
        }

        // Create and render the CPM chart
        const optionsCPM = {
            series: [{
                name: "CPM Data",
                data: [],
            }],
            chart: {
                id: "chart-line",
                group: "social",
                type: "line",
                height: 300,
            },
            colors: ["#FB0008"],
            yaxis: {
                title: {
                    text: "CPM",
                    style: {
                        fontSize: "17px",
                    },
                },
            },
            stroke: {
                width: 1,
            },
        };
        const chartCPM = new ApexCharts(
            document.querySelector("#chart-line"),
            optionsCPM
        );
        chartCPM.render();

        // Create and render the PPM chart
        const optionsPPM = {
            series: [{
                name: "PPM Data",
                data: [],
            }],
            chart: {
                id: "chart-line2",
                group: "social",
                type: "line",
                height: 300,
            },
            colors: ["#02F53B"],
            yaxis: {
                title: {
                    text: "PPM",
                    style: {
                        fontSize: "17px",
                    },
                },
            },
            stroke: {
                width: 1,
            },
        };
        const chartPPM = new ApexCharts(
            document.querySelector("#chart-line2"),
            optionsPPM
        );
        chartPPM.render();

        // Assuming you have the data object, process and update the charts
        const cpmData = data["aw-watcher-CPM"]["cpm"];
        const ppmData = data["aw-watcher-mouse"]["ppm"];

        processCPMData(cpmData, chartCPM);
        processPPMData(ppmData, chartPPM);
    </script>

    <script>
        let matchedApp; // Declare the variable outside the AJAX blocks

        $.ajax({
            url: '../dashboard/getUser.php',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                let row = '';
                let matchedUser = ''; // Variable to store the matched user
                let i = 1;
                let job; // Declare job variable
                $.each(data, function(key, value) {
                    // Check if value.slug matches lastPart
                    if (value.slug === macAddress) {
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
                    // console.log(data);
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

</body>

</html>