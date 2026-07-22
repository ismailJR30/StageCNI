<div class="chatbot-widget">
  <button class="chatbot-toggle" id="chatbot-toggle" type="button" aria-label="Ouvrir le chatbot">💬</button>

  <div class="chatbot-panel" id="chatbot-panel" aria-live="polite">
    <div class="chatbot-header">
      <strong>Assistant</strong>
      <button class="chatbot-close" id="chatbot-close" type="button" aria-label="Fermer le chatbot">✕</button>
    </div>

    <div class="chatbot-messages" id="chatbot-messages">
      <div class="chatbot-message bot">Bonjour ! Je peux vous aider pour les inscriptions, les cycles et la gestion du site.</div>
    </div>

    <form class="chatbot-form" id="chatbot-form">
      <input type="text" id="chatbot-input" placeholder="Écrivez votre message..." autocomplete="off" required>
      <button type="submit">Envoyer</button>
    </form>
  </div>
</div>

<script src="../assets/js/chatbot.js"></script>
