<div align="center">
  <img src="assets/images/apointoo-logo.png" alt="Appointo Logo" width="200">
  <h1>Floaty Button</h1>
  <p>
    <strong>A customizable floating CTA button for WordPress.</strong>
  </p>
  <p>
    <a href="#installation">Installation</a> •
    <a href="#configuration">Configuration</a> •
    <a href="#features">Features</a> •
    <a href="#licensing">Licensing</a>
  </p>
</div>

---

## Overview

The **Floaty Button** plugin adds a customizable floating CTA button to your WordPress site. It is designed to be lightweight, secure, and easy to configure. Whether you need a simple link, a booking modal, or a direct WhatsApp chat, Floaty Button handles it with style.

### ✨ Features

*   **🎨 Customizable Button:** Change the label, position (bottom right/left), and action.
*   **🔗 Multiple Actions:**
    *   Open a link (new/same tab).
    *   Display an iframe modal (perfect for booking widgets like NexHealth, Calendly).
    *   Open a WhatsApp chat.
*   **💬 WhatsApp Integration:** Dedicated WhatsApp template with native styling and prefilled messages.
*   **📅 Google Reserve Integration:** Add your Appointo Merchant ID to enable "Reserve with Google" features.
*   **📊 DataLayer Tracking:** Automatically pushes events to `dataLayer` for easy tracking with Google Tag Manager.
*   **💅 Custom CSS:** Add your own CSS directly from the settings page.

> **Security Goal:** This plugin aims to comply with WordPress.org’s plugin guidelines and the WordPress Plugin Security Handbook, prioritizing least privilege, full input validation/sanitization, and secure use of the WordPress APIs.

---

<div align="center">
  <h3>🇧🇷 Português (Portuguese)</h3>
</div>

O plugin **Floaty Button** adiciona um botão de CTA flutuante personalizável ao seu site WordPress. Ele foi projetado para ser leve, seguro e fácil de configurar.

**Funcionalidades:**
*   **Botão Personalizável:** Altere o rótulo, a posição (inferior direito/esquerdo) e a ação.
*   **Múltiplas Ações:** Abra um link (nova/mesma aba), exiba um modal iframe (ex: para widgets de agendamento) ou abra uma conversa no WhatsApp.
*   **Integração com WhatsApp:** Modelo dedicado do WhatsApp com estilo nativo e mensagens pré-preenchidas.
*   **Integração Google Reserve:** Adicione seu Merchant ID do Appointo para habilitar recursos do "Reserve com Google".
*   **Rastreamento DataLayer:** Envia automaticamente eventos para o `dataLayer` para fácil rastreamento com o Google Tag Manager.
*   **CSS Personalizado:** Adicione seu próprio CSS diretamente da página de configurações.

---

## 🚀 Installation

1.  Download the plugin folder `floaty-button`.
2.  Place it in your `wp-content/plugins/` directory.
3.  Activate **Floaty Button** from **Plugins** in the WordPress Admin Dashboard.

### 🇧🇷 Instalação
1.  Coloque a pasta `floaty-button` no diretório `wp-content/plugins/` do seu site.
2.  Ative o **Floaty Button** no menu **Plugins** do Painel Administrativo do WordPress.

---

## ⚙️ Configuration

Navigate to **Settings > Floaty Button** to configure the plugin.

### Main Settings
*   **Enable Plugin:** Toggle to show or hide the button globally.
*   **Button Template:** Choose between "Default Button" or "WhatsApp Floating Button".
*   **Button Label:** Text displayed on the button (e.g., "Book Now").
*   **Button Position:** Choose where the button appears (Bottom Right or Bottom Left).
*   **Action Type:**
    *   **Open Link:** Opens a URL (e.g., calendar, booking link) in the selected target.
    *   **Open Iframe Modal:** Displays a URL inside a modal popup (e.g., NexHealth, Calendly).
*   **Link URL:** URL to open when "Open Link" is selected.
*   **Link Target:** `_blank` (new tab) or `_self` (same tab).
*   **Iframe URL:** URL to embed when "Open Iframe Modal" is selected.
*   **DataLayer Event Name:** Event name pushed to `dataLayer` on click (default: `floaty_click`).
*   **Custom CSS:** Additional CSS injected on the front end for styling overrides.

### WhatsApp Settings
*   **WhatsApp Phone Number:** Enter your number in international format (digits only).
*   **Prefilled Message:** Optional message to start the conversation.

### Google Reserve Integration
*   **Enable Google Reserve:** Toggle to enable the integration.
*   **Merchant ID:** Enter the Merchant ID provided by Appointo.

---

### 🇧🇷 Configuração

Navegue até **Configurações > Floaty Button** para configurar o plugin.

**Configurações Principais**
*   **Habilitar Plugin:** Ative ou desative o botão globalmente.
*   **Modelo do Botão:** Escolha entre "Botão Padrão" ou "Botão Flutuante WhatsApp".
*   **Rótulo do Botão:** Texto exibido no botão (ex: "Agendar Agora").
*   **Posição do Botão:** Escolha onde o botão aparece (Inferior Direito ou Inferior Esquerdo).
*   **Tipo de Ação:**
    *   **Abrir Link:** Abre uma URL (ex: calendário, link de agendamento) no destino selecionado.
    *   **Abrir Modal Iframe:** Exibe uma URL dentro de um popup modal (ex: NexHealth, Calendly).
*   **URL do Link:** URL para abrir quando "Abrir Link" for selecionado.
*   **Destino do Link:** `_blank` (nova aba) ou `_self` (mesma aba).
*   **URL do Iframe:** URL para incorporar quando "Abrir Modal Iframe" for selecionado.
*   **Nome do Evento DataLayer:** Nome do evento enviado ao `dataLayer` no clique (padrão: `floaty_click`).
*   **CSS Personalizado:** CSS adicional injetado no front-end para substituições de estilo.

**Configurações do WhatsApp**
*   **Número de Telefone WhatsApp:** Digite seu número no formato internacional (apenas dígitos).
*   **Mensagem Pré-preenchida:** Mensagem opcional para iniciar a conversa.

**Integração Google Reserve**
*   **Habilitar Google Reserve:** Ative para habilitar a integração.
*   **Merchant ID:** Insira o Merchant ID fornecido pelo Appointo.

---

## 📊 DataLayer Event

When the button is clicked, the plugin pushes an event with core metadata:

```js
{
  event: 'floaty_click', // or your configured event name
  floatyActionType: 'link' | 'iframe_modal' | 'whatsapp',
  floatyLabel: 'Book Now' // or 'WhatsApp'
}
```

---

## 🎨 Customizing Styles

Use the **Custom CSS** field to override colors, spacing, or positioning. Example:

```css
.floaty-button {
    background-color: #ff0000; /* Red button */
}

.floaty-position-bottom_left {
    left: 40px;
}
```

---

## 📋 Requirements

*   WordPress 6.4 or later (tested up to 6.6)
*   PHP 8.0 or later

---

## 📄 Licensing

Floaty Button is released under the **GPLv2 or later** license. See [GNU Licenses](https://www.gnu.org/licenses/gpl-2.0.html) for the full text.

**Contributors:** vizuh, hugoc, Atroci, andreluizsr90
