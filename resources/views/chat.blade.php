<!DOCTYPE html>
<html>
<head>
    <title>AI Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>

        #chatBox {
            transition: all 0.3s ease;
            transform: translateY(10px);
            opacity: 0;
            visibility: hidden;
        }
        #chatBox.show {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body class="p-4">

    <button id="chatBtn" class="fixed bottom-4 right-4 bg-blue-500 text-white p-3 rounded-full shadow-lg hover:bg-blue-600 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
    </button>


    <div id="chatBox" class="fixed bottom-20 right-4 bg-white border border-gray-200 rounded-lg shadow-xl w-80 flex flex-col" style="height: 400px;">

        <div class="bg-blue-500 text-white p-3 rounded-t-lg flex justify-between items-center">
            <h3 class="font-bold">AI Assistant</h3>
            <button id="closeChat" class="text-white hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>


        <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-3"></div>


        <div class="p-3 border-t border-gray-200">
            <div class="flex gap-2">
                <input type="text" id="userInput" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Writting Here...">
                <button id="sendBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            function toggleChat() {
                $('#chatBox').toggleClass('show');


                if ($('#chatBox').hasClass('show')) {
                    $('#chatBtn').html(`
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    `);
                } else {
                    $('#chatBtn').html(`
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    `);
                }
            }


            $('#chatBtn').click(toggleChat);


            $('#closeChat').click(toggleChat);


            function sendMessage() {
                let message = $('#userInput').val().trim();
                if (message === '') return;


                $('#messages').append(`
                    <div class="flex justify-end mb-2">
                        <div class="bg-blue-100 text-blue-800 rounded-lg py-2 px-4 max-w-xs">
                            ${message}
                        </div>
                    </div>
                `);

                $('#userInput').val('');
                $('#messages').scrollTop($('#messages')[0].scrollHeight);


                $.ajax({
                    url: '/chat',
                    method: 'POST',
                    data: {
                        message: message,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#messages').append(`
                            <div class="flex justify-start mb-2">
                                <div class="bg-gray-100 text-gray-800 rounded-lg py-2 px-4 max-w-xs">
                                    ${response.reply}
                                </div>
                            </div>
                        `);
                        $('#messages').scrollTop($('#messages')[0].scrollHeight);
                    },
                    error: function() {
                        $('#messages').append(`
                            <div class="flex justify-start mb-2">
                                <div class="bg-red-100 text-red-800 rounded-lg py-2 px-4 max-w-xs">
                                    Problem: Server Prbblem
                                </div>
                            </div>
                        `);
                    }
                });
            }


            $('#sendBtn').click(sendMessage);


            $('#userInput').keypress(function(e) {
                if (e.which == 13) {
                    sendMessage();
                }
            });
        });
    </script>
</body>
</html>
