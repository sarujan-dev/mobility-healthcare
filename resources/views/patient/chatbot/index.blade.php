<x-app-layout>
<div style="display:flex; flex-direction:column; height:calc(100vh - 73px);">

    <!-- Header -->
    <div style="background:#fff; border-bottom:1px solid #e5e7eb; padding:16px 28px; display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
        <div style="display:flex; align-items:center; gap:14px;">
            <div style="width:46px; height:46px; border-radius:12px; background:linear-gradient(135deg,#4f46e5,#6366f1); display:flex; align-items:center; justify-content:center; font-size:22px;">🤖</div>
            <div>
                <h1 style="font-size:17px; font-weight:700; margin:0; color:#111827;">Maternal Care Assistant</h1>
                <p style="font-size:12px; color:#9ca3af; margin:2px 0 0;">Ask about pregnancy, symptoms & general care</p>
            </div>
        </div>
        <form method="POST" action="{{ route('patient.chatbot.clear') }}" onsubmit="return confirm('Clear all chat history?');">
            @csrf
            <button type="submit" style="background:#fff; color:#6b7280; border:1px solid #e5e7eb; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; display:flex; align-items:center; gap:6px;">
                🗑 Clear Chat
            </button>
        </form>
    </div>

    <!-- Disclaimer -->
    <div style="background:#fffbeb; border-bottom:1px solid #fde68a; padding:10px 28px; font-size:12.5px; color:#92400e; flex-shrink:0;">
        ⚠️ This assistant gives general information only and is not a substitute for professional medical advice. For emergencies, use the <a href="{{ route('patient.emergency.index') }}" style="color:#92400e; font-weight:700;">Emergency VOG Finder</a>.
    </div>

    <!-- Chat Window -->
    <div id="chat-window" style="flex:1; overflow-y:auto; padding:28px; background:#f9fafb; display:flex; flex-direction:column; gap:18px;">
        @if($chatHistory->isEmpty())
            <div style="display:flex; gap:12px; max-width:720px;">
                <div style="width:36px; height:36px; border-radius:50%; background:#e0e7ff; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0;">🤖</div>
                <div style="background:#fff; padding:14px 18px; border-radius:14px; border-top-left-radius:4px; font-size:14.5px; line-height:1.6; box-shadow:0 1px 2px rgba(0,0,0,0.05); color:#374151;">
                    Hello! I'm here to help answer your pregnancy and general health questions. What would you like to know?
                </div>
            </div>
        @else
            @foreach($chatHistory as $msg)
                <div style="display:flex; gap:12px; max-width:720px; {{ $msg->role == 'user' ? 'flex-direction:row-reverse; align-self:flex-end;' : '' }}">
                    <div style="width:36px; height:36px; border-radius:50%; background:{{ $msg->role == 'user' ? '#4f46e5' : '#e0e7ff' }}; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; color:{{ $msg->role == 'user' ? '#fff' : '#000' }};">
                        {{ $msg->role == 'user' ? '🧑' : '🤖' }}
                    </div>
                    <div style="background:{{ $msg->role == 'user' ? 'linear-gradient(135deg,#4f46e5,#6366f1)' : '#fff' }}; color:{{ $msg->role == 'user' ? '#fff' : '#374151' }}; padding:14px 18px; border-radius:14px; {{ $msg->role == 'user' ? 'border-top-right-radius:4px;' : 'border-top-left-radius:4px;' }} font-size:14.5px; line-height:1.6; white-space:pre-wrap; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                        {{ $msg->message }}
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Input Bar -->
    <div style="background:#fff; border-top:1px solid #e5e7eb; padding:18px 28px; flex-shrink:0;">
        <div style="max-width:900px; margin:0 auto; display:flex; gap:12px; align-items:center;">
            <input type="text" id="chat-input" placeholder="Type your question..."
                style="flex:1; border:1.5px solid #e5e7eb; border-radius:24px; padding:13px 20px; font-size:14.5px; outline:none; transition:border-color 0.2s;"
                onkeypress="if(event.key==='Enter') sendMessage();"
                onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#e5e7eb'">
            <button onclick="sendMessage()" id="send-btn"
                style="background:linear-gradient(135deg,#4f46e5,#6366f1); color:#fff; padding:13px 28px; border-radius:24px; border:none; font-weight:600; font-size:14.5px; cursor:pointer; box-shadow:0 4px 12px rgba(79,70,229,0.3); white-space:nowrap;">
                Send ➤
            </button>
        </div>
    </div>

</div>

<script>
    const chatWindow = document.getElementById('chat-window');
    chatWindow.scrollTop = chatWindow.scrollHeight;

    function appendMessage(text, isUser) {
        const wrapper = document.createElement('div');
        wrapper.style.cssText = 'display:flex; gap:12px; max-width:720px;' + (isUser ? ' flex-direction:row-reverse; align-self:flex-end;' : '');

        wrapper.innerHTML = `
            <div style="width:36px; height:36px; border-radius:50%; background:${isUser ? '#4f46e5' : '#e0e7ff'}; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; color:${isUser ? '#fff' : '#000'};">
                ${isUser ? '🧑' : '🤖'}
            </div>
            <div style="background:${isUser ? 'linear-gradient(135deg,#4f46e5,#6366f1)' : '#fff'}; color:${isUser ? '#fff' : '#374151'}; padding:14px 18px; border-radius:14px; ${isUser ? 'border-top-right-radius:4px;' : 'border-top-left-radius:4px;'} font-size:14.5px; line-height:1.6; white-space:pre-wrap; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                ${text}
            </div>
        `;
        chatWindow.appendChild(wrapper);
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    function showTyping() {
        const wrapper = document.createElement('div');
        wrapper.id = 'typing-indicator';
        wrapper.style.cssText = 'display:flex; gap:12px; max-width:720px;';
        wrapper.innerHTML = `
            <div style="width:36px; height:36px; border-radius:50%; background:#e0e7ff; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0;">🤖</div>
            <div style="background:#fff; padding:14px 18px; border-radius:14px; border-top-left-radius:4px; box-shadow:0 1px 2px rgba(0,0,0,0.05); display:flex; gap:4px; align-items:center;">
                <span style="width:7px; height:7px; background:#9ca3af; border-radius:50%; animation:bounce 1.4s infinite;"></span>
                <span style="width:7px; height:7px; background:#9ca3af; border-radius:50%; animation:bounce 1.4s infinite 0.2s;"></span>
                <span style="width:7px; height:7px; background:#9ca3af; border-radius:50%; animation:bounce 1.4s infinite 0.4s;"></span>
            </div>
        `;
        chatWindow.appendChild(wrapper);
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    function removeTyping() {
        const el = document.getElementById('typing-indicator');
        if (el) el.remove();
    }

    function sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        if (!message) return;

        appendMessage(message, true);
        input.value = '';

        const sendBtn = document.getElementById('send-btn');
        sendBtn.disabled = true;
        showTyping();

        fetch("{{ route('patient.chatbot.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            removeTyping();
            appendMessage(data.reply, false);
        })
        .catch(() => {
            removeTyping();
            appendMessage('Sorry, something went wrong. Please try again.', false);
        })
        .finally(() => {
            sendBtn.disabled = false;
        });
    }
</script>

<style>
    @keyframes bounce {
        0%, 60%, 100% { transform: translateY(0); opacity:0.4; }
        30% { transform: translateY(-4px); opacity:1; }
    }
</style>
</x-app-layout>
