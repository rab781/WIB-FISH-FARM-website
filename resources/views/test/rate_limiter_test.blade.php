<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Limiter Test</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        #results {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 4px;
            max-height: 400px;
            overflow-y: auto;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Rate Limiter Test</h1>
    
    <div>
        <button id="testSingle">Test Single Request</button>
        <button id="testMultiple">Test 10 Requests</button>
        <button id="clearResults">Clear Results</button>
    </div>

    <div id="results">
        <p>Results will appear here...</p>
    </div>

    <script>
        $(document).ready(function() {
            $('#testSingle').click(function() {
                callApi();
            });

            $('#testMultiple').click(function() {
                for (let i = 0; i < 10; i++) {
                    setTimeout(() => {
                        callApi();
                    }, i * 300);
                }
            });

            $('#clearResults').click(function() {
                $('#results').html('<p>Results will appear here...</p>');
            });

            function callApi() {
                const timestamp = new Date().toLocaleTimeString();
                
                $.ajax({
                    url: '/api/debug/ratelimiter',
                    method: 'GET',
                    success: function(response) {
                        $('#results').prepend(`
                            <div class="success">
                                <strong>${timestamp}</strong>: Success - ${response.message}
                            </div>
                        `);
                    },
                    error: function(xhr) {
                        let errorMessage = 'Unknown error';
                        try {
                            errorMessage = xhr.responseJSON.message || xhr.statusText;
                        } catch(e) {
                            errorMessage = xhr.statusText || 'Error';
                        }
                        
                        $('#results').prepend(`
                            <div class="error">
                                <strong>${timestamp}</strong>: Error - ${errorMessage}
                                <br>Status: ${xhr.status}
                            </div>
                        `);
                    }
                });
            }
        });
    </script>
</body>
</html>
