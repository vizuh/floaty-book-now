# Floaty – Book Now & Chat Button

A lightweight floating CTA for WordPress: “Book Now” (link or modal) + WhatsApp chat. Includes optional Apointoo booking settings for Google Search/Maps booking flows where available.

**WordPress.org listing:** coming soon

---

## What it does
Floaty adds a persistent floating button to every page so visitors can **book faster** or **start a chat**—without hunting through menus.

Choose one primary mode:
- **WhatsApp:** open a chat with optional prefilled message
- **Custom:** open a link or launch an iframe modal (ideal for booking widgets)

---

## Key features
- Floating CTA on every page (bottom-left / bottom-right)
- Modes: **WhatsApp** or **Custom**
- Actions (Custom mode): **Open link** or **Iframe modal**
- Lightweight styles + **Custom CSS** overrides
- dataLayer click event for GTM/GA4 tracking (default: `floaty_click`)
- WordPress best practices: capability checks + sanitized settings

---

## Installation
1. Clone or download into: `wp-content/plugins/floaty-book-now-chat/`
2. Activate in **Plugins → Installed Plugins**
3. Open **Settings → Floaty**

---

## Configuration (Tabs)
### General
- Enable plugin
- Button label
- Position
- Mode (WhatsApp or Custom)
- dataLayer event name

### WhatsApp
- Phone number (international digits only)
- Prefilled message

### Custom
- Action type (Link or Iframe modal)
- Link URL + target (_blank/_self)
- Iframe URL
- Custom CSS

### Apointoo Booking
- Enable Apointoo integration
- Merchant ID  
  Need an ID? Email **support@vizuh.com**

> Note: Booking visibility on Google Search/Maps depends on eligibility and provider setup.

---

## dataLayer event
On click, Floaty pushes this to `window.dataLayer` (if available):

```js
{
  event: "floaty_click",
  floatyActionType: "link" | "iframe_modal" | "whatsapp",
  floatyLabel: "Book now"
}
```

## Development

No build step. PHP + assets are ready to run.

### PHPCS (WordPress Coding Standards)
- `phpcs --standard=WordPress --ignore=vendor --extensions=php .`
- `phpcbf --standard=WordPress --ignore=vendor --extensions=php .`

## License

GPLv2 or later.

## Credits

Maintained by Vizuh.
