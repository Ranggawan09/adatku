{{-- Chatbot UI --}}
<div id="chatbot-container" class="fixed bottom-5 right-5 z-50">
    <!-- Chat Bubble -->
    <div id="chat-bubble" class="w-16 h-16 bg-pr-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-pr-700 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
    </div>

    <!-- Chat Window -->
    <div id="chat-window" class="hidden absolute bottom-20 right-0 w-80 sm:w-96 bg-white rounded-lg shadow-2xl flex flex-col" style="height: 60vh;">
        <!-- Header -->
        <div class="bg-pr-500 text-white p-4 flex justify-between items-center rounded-t-lg">
            <h3 class="font-semibold text-lg">Asisten Adatku</h3>
            <button id="close-chat" class="text-white hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50">
            {{-- Initial bot message --}}
            <div class="flex mb-3">
                <div class="bg-jawa-200 text-jawa-800 p-3 rounded-lg max-w-xs">
                    <p>Halo! Ada yang bisa saya bantu? Anda bisa bertanya tentang seputar sewa pakaian adat atau status sewa.</p>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="p-4 border-t bg-white rounded-b-lg">
            <form id="chat-form" class="flex items-center">
                <input type="text" id="chat-input" placeholder="Ketik pesan Anda..." autocomplete="off" class="flex-1 px-4 py-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-pr-400">
                <button type="submit" id="chat-submit" class="ml-3 bg-pr-600 text-white p-2 rounded-full hover:bg-pr-700 focus:outline-none focus:ring-2 focus:ring-pr-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatBubble = document.getElementById('chat-bubble');
    const chatWindow = document.getElementById('chat-window');
    const closeChat = document.getElementById('close-chat');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');

    // === Tampilkan / sembunyikan jendela chat ===
    chatBubble.addEventListener('click', () => {
        chatWindow.classList.remove('hidden');
        chatBubble.classList.add('hidden');
    });

    closeChat.addEventListener('click', () => {
        chatWindow.classList.add('hidden');
        chatBubble.classList.remove('hidden');
    });

    // === Saat user kirim pesan ===
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        appendMessage(message, 'user');
        chatInput.value = '';
        showTypingIndicator();

        fetch("{{ route('chatbot.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.error || `Server error: ${response.status}`);
                }).catch(() => {
                    throw new Error(`Server error: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            removeTypingIndicator();
            console.log("Respons dari Laravel:", data);

            // ✅ FIX: Rasa kirim array of messages, bukan data.reply
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    if (item.text) {
                        appendMessage(item.text, 'bot');
                    } else if (item.image) {
                        appendMessage(`<img src="${item.image}" class="rounded-lg max-w-xs" />`, 'bot');
                    } else {
                        appendMessage('Maaf, saya tidak mengerti. Bisa coba lagi?', 'bot');
                    }
                });
            } else if (data.error) {
                appendMessage("⚠️ " + data.error, 'bot');
            } else {
                appendMessage('Maaf, saya tidak mengerti. Bisa coba lagi?', 'bot');
            }
        })
        .catch(error => {
            removeTypingIndicator();
            console.error('Error:', error);
            appendMessage(error.message || 'Maaf, terjadi masalah koneksi. Silakan coba lagi nanti.', 'bot');
        });
    });

    // === Fungsi tampilkan pesan ===
    function appendMessage(text, sender) {
        const messageWrapper = document.createElement('div');
        const messageBubble = document.createElement('div');
        
        messageWrapper.classList.add('flex', 'mb-3');
        messageBubble.classList.add('p-3', 'rounded-lg', 'max-w-xs', 'text-left');

        // Ganti • dengan <br> untuk balasan bot multi-baris
        if (sender === 'bot') {
            text = text.replace(/•/g, '<br>•');
        }

        messageBubble.innerHTML = `<p>${text}</p>`;

        if (sender === 'user') {
            messageWrapper.classList.add('justify-end');
            messageBubble.classList.add('bg-pr-500', 'text-white');
        } else {
            messageBubble.classList.add('bg-jawa-200', 'text-jawa-800');
        }

        messageWrapper.appendChild(messageBubble);
        chatMessages.appendChild(messageWrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // === Indikator bot mengetik ===
    function showTypingIndicator() {
        const typingIndicator = document.createElement('div');
        typingIndicator.id = 'typing-indicator';
        typingIndicator.classList.add('flex', 'mb-3');
        typingIndicator.innerHTML = `
            <div class="bg-jawa-200 text-jawa-800 p-3 rounded-lg max-w-xs">
                <p class="animate-pulse">...</p>
            </div>
        `;
        chatMessages.appendChild(typingIndicator);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) typingIndicator.remove();
    }
});
</script>
