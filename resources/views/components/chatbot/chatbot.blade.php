{{-- <button id="chat-button" class="chat-button">
    <i id="chat-icon" class="fa fa-comment"></i>
</button>
<div id="chat-container" class="chat-container hidden">
    <div id="chat-header" class="chat-header">
        <p class="chat-title">Helper Bot</p>
        <p class="chat-icon"><i class="fa fa-wechat"></i></p>
    </div>
    <iframe id="chat-frame" class="chat-frame hidden"
        src='https://webchat.botframework.com/embed/ChatBotJYP?s=LFxQB5IN5dQ.-HKd0sojcXTYTKLnB_Xyf-ZlFCtaumyaGbVXxt-1SW8'></iframe>
        //Reemplazar el iframe o key con el que se tenga en el bot	
</div> --}}

<style>
    .hidden {
        display: none;
    }

    .chat-button {
        position: fixed;
        bottom: 50px;
        right: 20px;
        height: 3rem;
        width: 3rem;
        background-color: #2222ff;
        color: white;
        font-size: 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        z-index: 9999;

    }

    .chat-button.rotate-45 {
        transform: rotate(45deg);
    }

    .chat-container {
        position: fixed;
        bottom: 7rem;
        right: 1.5rem;
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        padding: 0.75rem;
        transition: all 0.3s;
        z-index: 9999;

    }

    .chat-header {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        background-color: #2222ff;
        color: white;
        padding: 0.5rem;
        margin: -0.75rem -0.75rem 0;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        cursor: pointer;
    }

    .chat-title {
        font-size: 1.125rem;
        font-weight: 800;
    }

    .chat-icon {
        font-size: 1.25rem;
        color: #fff;
    }

    .chat-frame {
        width: 100%;
        height: 24rem;
        border: none;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const chatButton = document.getElementById('chat-button');
    const chatIcon = document.getElementById('chat-icon');
    const chatContainer = document.getElementById('chat-container');
    const chatFrame = document.getElementById('chat-frame');
    let showChat = false;
    let renderChatbot = false;

    chatButton.addEventListener('click', () => {
        showChat = !showChat;

        if (!renderChatbot) {
            renderChatbot = true;
            chatFrame.classList.remove('hidden');
        }

        chatContainer.classList.toggle('hidden', !showChat);
        chatButton.classList.toggle('rotate-45', showChat);
        chatIcon.className = `fa ${showChat ? 'fa-times' : 'fa-comment'}`;
    });

    document.getElementById('chat-header').addEventListener('click', () => {
        showChat = false;
        chatContainer.classList.add('hidden');
        chatButton.classList.remove('rotate-45');
        chatIcon.className = 'fa fa-comment';
    });
});

</script>