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
- Modes: **WhatsApp**, **Custom**, or **Lead Capture**
- Actions (Custom mode): **Open link** or **Iframe modal**
- Lead capture form with redirect to WhatsApp or a custom link
- Lightweight styles + **Custom CSS** overrides
- WordPress best practices: capability checks + sanitized settings
- Works well alongside ClickTrail when you want attribution and analytics

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
- Mode (WhatsApp, Custom, or Lead Capture)
- Device and page targeting rules

### WhatsApp
- Phone number (international digits only)
- Prefilled message

### Custom
- Action type (Link or Iframe modal)
- Link URL + target (_blank/_self)
- Iframe URL
- Custom CSS

### Lead Capture
- Name, email, and phone field toggles
- Redirect to WhatsApp or a custom link after submit

### Apointoo Booking
- Enable Apointoo integration
- Merchant ID  
  Need an ID? Email **support@vizuh.com**

> Note: Booking visibility on Google Search/Maps depends on eligibility and provider setup.

---

## ClickTrail Compatibility
Floaty focuses on the CTA/button layer. If you also need attribution and analytics for CTA clicks or leads, pair it with [ClickTrail](https://wordpress.org/plugins/click-trail-handler/).

## Development

No build step. PHP + assets are ready to run.

## Changelog

### 1.0.1
- Prevented frontend assets from loading in feed, XML-style, preview, embed, and Elementor builder contexts.
- Hardened frontend targeting and plugin bootstrapping for lead capture and integrations.
- Fixed the GTM admin tab registration and removed duplicate Apointoo section registration.

### PHPCS (WordPress Coding Standards)
- `phpcs --standard=WordPress --ignore=vendor --extensions=php .`
- `phpcbf --standard=WordPress --ignore=vendor --extensions=php .`

## License

GPLv2 or later.

## Credits

Maintained by Vizuh.
