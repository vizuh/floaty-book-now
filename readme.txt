=== Floaty Book Now Chat ===
Contributors: vizuh, hugoc, atroci, andreluizsr90
Tags: booking, appointments, whatsapp, chat, modal
Requires at least: 6.4
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight floating “Book Now” button + WhatsApp chat. Open a link, launch a modal, or collect a lead before redirecting.

== Description ==

Floaty adds a clean floating call-to-action button to your site so visitors can **book faster** or **start a WhatsApp chat**—without digging through menus.

Choose your mode:
* WhatsApp mode: click-to-chat with optional prefilled message
* Custom mode: open a link or launch an iframe modal (great for booking widgets)
* Lead Capture mode: show a simple form before redirecting to WhatsApp or a custom link

= Key Features =
* Floating CTA on every page (bottom-left / bottom-right)
* Modes: WhatsApp, Custom, or Lead Capture
* Custom mode actions: open link or iframe modal
* Lead capture form with configurable fields and redirect
* Custom CSS field for quick styling overrides
* Lean, WordPress-native settings UI

= Attribution and Analytics =
Floaty focuses on the CTA and booking button layer. If you also need attribution and analytics for CTA clicks or leads, pair it with ClickTrail:
https://wordpress.org/plugins/click-trail-handler/

= Apointoo Booking (Optional) =
If you use Apointoo, Floaty includes an optional integration tab for booking configuration used in Google Search/Maps booking flows where available via your provider setup.

Need a Merchant ID? Email support@vizuh.com.

Note: Booking visibility on Google Search/Maps depends on eligibility and provider setup.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory, or install via the WordPress Plugins screen.
2. Activate the plugin through the 'Plugins' screen.
3. Go to Settings → Floaty.
4. Enable Floaty and choose your mode (WhatsApp, Custom, or Lead Capture).

== Frequently Asked Questions ==

= Can I use Floaty without WhatsApp? =
Yes. Use Custom mode to open a link or iframe modal.

= How do I track attribution and analytics for Floaty clicks or leads? =
Floaty focuses on the CTA layer. For attribution and analytics, use it alongside ClickTrail.

= Can I use Floaty without Apointoo? =
Yes. The Apointoo tab is optional.

= Does Floaty guarantee booking visibility on Google Search/Maps? =
No. That depends on eligibility and provider setup. Floaty provides the on-site CTA and integration settings.

== Screenshots ==

1. General tab (enable, label, position, targeting, mode)
2. WhatsApp tab (phone + prefilled message)
3. Custom tab (link/modal + URL fields + custom CSS)
4. Lead Capture tab (fields + redirect settings)
5. Apointoo Booking tab (enable + Merchant ID)
6. Frontend example (floating button on a page)

== Changelog ==

= 1.0.1 =
* Prevented frontend assets from loading in XML, feed, preview, embed, and Elementor builder contexts to reduce theme and response-type conflicts.
* Improved frontend page targeting checks to avoid relying on fragile global post state.
* Fixed integration manager initialization so lead integrations are registered during normal runtime.
* Loaded the database class during plugin boot for lead capture and integration paths.
* Fixed the missing GTM tab registration and removed a duplicate Apointoo section registration in admin settings.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.1 =
Recommended maintenance release for sites using Elementor or pages that generate XML-style responses.

= 1.0.0 =
Initial release.
