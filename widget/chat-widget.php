<div class="chatbot-widget" id="chatbot-widget">
  <div class="chatbot-panel" id="chatbot-panel" aria-live="polite">
    <div class="chatbot-header">
      <strong>Assistant formations</strong>
      <button class="chatbot-close" id="chatbot-close" type="button" aria-label="Fermer le chatbot">✕</button>
    </div>
    <div class="chatbot-messages" id="chatbot-messages">
      <div class="chatbot-message bot">Bonjour ! Je peux vous aider à trouver des formations ou des formateurs.</div>
    </div>
    <form class="chatbot-form" id="chatbot-form">
      <input type="text" id="chatbot-input" placeholder="Posez votre question..." autocomplete="off" required>
      <button type="submit">Envoyer</button>
    </form>
  </div>

  <button class="chatbot-toggle" id="chatbot-toggle" type="button" aria-label="Ouvrir le chatbot">💬</button>
</div>

<style>
.chatbot-widget {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 9999;
  font-family: Arial, sans-serif;
}
.chatbot-toggle {
  border: none;
  border-radius: 50%;
  width: 56px;
  height: 56px;
  background: #2f6fed;
  color: white;
  font-size: 24px;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.chatbot-panel {
  display: none;
  width: 320px;
  max-height: 430px;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  margin-bottom: 10px;
}
.chatbot-panel.open { display: block; }
.chatbot-header {
  background: #2f6fed;
  color: white;
  padding: 10px 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.chatbot-close {
  background: transparent;
  border: none;
  color: white;
  font-size: 18px;
  cursor: pointer;
}
.chatbot-messages {
  padding: 10px;
  height: 260px;
  overflow-y: auto;
  background: #f8faff;
}
.chatbot-message {
  margin-bottom: 8px;
  padding: 8px 10px;
  border-radius: 8px;
  max-width: 85%;
  line-height: 1.4;
}
.chatbot-message.bot { background: #e7f0ff; color: #183a60; }
.chatbot-message.user { background: #dceeff; color: #123; margin-left: auto; }
.chatbot-form { display: flex; border-top: 1px solid #ddd; padding: 8px; }
.chatbot-form input { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-right: 6px; }
.chatbot-form button { background: #2f6fed; color: white; border: none; border-radius: 4px; padding: 8px 10px; cursor: pointer; }
</style>

<script>
(function () {
  const toggle = document.getElementById('chatbot-toggle');
  const panel = document.getElementById('chatbot-panel');
  const close = document.getElementById('chatbot-close');
  const form = document.getElementById('chatbot-form');
  const input = document.getElementById('chatbot-input');
  const messages = document.getElementById('chatbot-messages');
  const history = [];

  function addMessage(text, type) {
    const div = document.createElement('div');
    div.className = 'chatbot-message ' + type;
    div.textContent = text;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  toggle.addEventListener('click', function () {
    panel.classList.toggle('open');
  });

  close.addEventListener('click', function () {
    panel.classList.remove('open');
  });

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    const userMessage = input.value.trim();
    if (!userMessage) {
      return;
    }

    addMessage(userMessage, 'user');
    input.value = '';
    addMessage('Je réfléchis…', 'bot');

    fetch('../api/chat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ message: userMessage, history: history })
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        const lastBotMessage = messages.querySelector('.chatbot-message.bot:last-child');
        if (lastBotMessage) {
          lastBotMessage.remove();
        }
        addMessage(data.reply || 'Désolé, je n’ai pas pu répondre.', 'bot');
        history.push({ role: 'user', content: userMessage });
        history.push({ role: 'assistant', content: data.reply || '' });
      })
      .catch(function () {
        const lastBotMessage = messages.querySelector('.chatbot-message.bot:last-child');
        if (lastBotMessage) {
          lastBotMessage.remove();
        }
        addMessage('Une erreur est survenue. Vérifiez la configuration d’Ollama.', 'bot');
      });
  });
})();
</script>
