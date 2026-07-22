document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.getElementById('chatbot-toggle');
  const panel = document.getElementById('chatbot-panel');
  const close = document.getElementById('chatbot-close');
  const form = document.getElementById('chatbot-form');
  const input = document.getElementById('chatbot-input');
  const messages = document.getElementById('chatbot-messages');

  function addMessage(text, type) {
    const message = document.createElement('div');
    message.className = 'chatbot-message ' + type;
    message.textContent = text;
    messages.appendChild(message);
    messages.scrollTop = messages.scrollHeight;
  }

  if (toggle && panel && close && form && input && messages) {
    toggle.addEventListener('click', function () {
      panel.classList.toggle('open');
    });

    close.addEventListener('click', function () {
      panel.classList.remove('open');
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const text = input.value.trim();
      if (!text) return;

      addMessage(text, 'user');
      input.value = '';
      addMessage('Je réfléchis à votre demande...', 'bot');

      fetch('../assets/chatbot_api.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message: text })
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          const lastBotMessage = messages.querySelector('.chatbot-message.bot:last-child');
          if (lastBotMessage) {
            lastBotMessage.remove();
          }
          addMessage(data.reply || 'Je n’ai pas pu générer une réponse.', 'bot');
        })
        .catch(function () {
          const lastBotMessage = messages.querySelector('.chatbot-message.bot:last-child');
          if (lastBotMessage) {
            lastBotMessage.remove();
          }
          addMessage('Le service IA est indisponible pour le moment.', 'bot');
        });
    });
  }
});
