<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col h-[650px] border border-gray-300 rounded-lg"> <!-- Increased height here -->
                        <div id="messages" class="flex-1 p-4 overflow-y-auto space-y-2">
                            <!-- Messages will be appended here -->
                        </div>
                        <div class="p-4 border-t border-gray-300 flex items-center space-x-2">
                            <input type="text" id="message" placeholder="Type your message here" class="flex-1 border border-gray-300 p-2 rounded" autofocus>
                            <button id="sendMessage" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Send</button>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            // Setup Echo to listen to the 'chat' channel
                            Echo.channel('chat')
                                .listen('MessageSent', (e) => {
                                    const messagesDiv = document.getElementById('messages');
                                    const messageElement = document.createElement('div');
                                    const isCurrentUser = e.user === '{{ auth()->user()->name }}'; // Adjust based on your authentication setup
                                    
                                    messageElement.classList.add('p-2', 'border-b', 'border-gray-200');
                                    messageElement.classList.add(isCurrentUser ? 'text-right' : 'text-left'); // Align based on user
                                    
                                    messageElement.innerHTML = `
                                        <div>
                                            <strong>${e.user}: </strong><em>${e.timestamp}</em><br>
                                            Message: ${e.message}
                                        </div>
                                    `;
                                    
                                    messagesDiv.appendChild(messageElement);
                                    messagesDiv.scrollTop = messagesDiv.scrollHeight; // Auto-scroll to the bottom
                                });

                            // Handle sending messages
                            function sendMessage() {
                                const messageInput = document.getElementById('message');
                                const message = messageInput.value;

                                // Prevent sending empty messages
                                if (message.trim() === '') {
                                    return;
                                }

                                fetch('/broadcast-message', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ message })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'Message broadcasted successfully!') {
                                        messageInput.value = ''; // Clear the input field
                                    }
                                });
                            }

                            // Add event listener for the Send button
                            document.getElementById('sendMessage').addEventListener('click', sendMessage);

                            // Add event listener for Enter key in the input field
                            document.getElementById('message').addEventListener('keypress', function(event) {
                                if (event.key === 'Enter') {
                                    event.preventDefault(); // Prevent default Enter key behavior (line break)
                                    sendMessage();
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
