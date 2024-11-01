=== DPD SK for WooCommerce ===
Contributors: webikon
Tags: shipping, woocommerce, dpd
Donate link: https://platobnebrany.sk/
Requires at least: 5.3
Tested up to: 6.6.1
Requires PHP: 7.4
Stable tag: 7.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin spoločnosti Direct Parcel Distribution SK, s. r. o. poskytuje jednoduché a rýchle riešenie na prenos údajov o objednaných prepravných službách s doručením na adresu príjemcu a do odberných miest.

== Description ==
Plugin spoločnosti **Direct Parcel Distribution SK, s. r. o.** poskytuje jednoduché a rýchle riešenie na prenos údajov o objednaných prepravných službách s doručením na adresu príjemcu a do odberných miest.

Pomocou pluginu si môžete vo svojich **Woocommerce objednávkach** vyberať zo širokého portfólia produktov a služieb DPD, ako sú napríklad **DPD Home, DPD Classic, DPD 10:00, DPD 12:00, DPD 18:00 /DPD Guarantee** s doručením na adresu príjemcu a do odberných miest v rámci Slovenska a do 230 krajín sveta.

Plugin je prepojený cez API riešenie k [www.dpdshipper.sk](https://www.dpdshipper.sk). Všetky objednávky vytvorené týmto pluginom si budete môcť prezerať aj v prehľade objednávok v online aplikácii [DPD Shipper](https://www.dpdshipper.sk/login).

Plugin je **zadarmo v plnej verzii** pre zmluvných zákazníkov DPD SK.

= FUNKCIE PLUGINU =

* Jednoduché a rýchle vytváranie objednávok prepravy
* Nastavenie predvoleného produktu na každú objednávku, napr. DPD Home
* Tri možnosti exportovania objednávok do DPD
* Úprava objednávky pred exportovaním do DPD
* Tlač prepravných štítkov vo formáte A4 alebo A6
* Vytváranie objednávok doručenia do odberných miest Pickup a Pickup Station (samoobslužného boxu)
* Možnosť pridania ďalších ID bankových účtov a zvozových adries
* Použitie dvoch referencii na úrovni objednávky
* Možnosť nastavenia dopravy zdarma
* Možnosť nastavenia ceny dopravy podľa hmotností produktov v košíku.

== Installation ==
1. Stiahnite si plugin z WordPress.org
2. Nainštalujte a aktivujte si plugin

= Nastavenie pluginu =

1. Prejdite do nastavení **WooCommerce** -> **Doprava** -> **DPD nastavenia exportu**
2. Vyplňte **požadované údaje** pre pripojenie na API DPD Shipper
  * **ID delis**
  * **E-mail (Login) posielaný spolu s API kľúčom**
  * **API Kľúč**
3. Vyplňte **informácie pre doručenie** - štandardné nastavenia
  * **ID bankového účtu** - z [www.dpdshipper.sk](https://www.dpdshipper.sk) -> Nastavenia -> Bankové účty -> hodnota z **ID účtu**
  * **ID adresy pre zber** - z [www.dpdshipper.sk](https://www.dpdshipper.sk) ->  Nastavenia -> Zvozové adresy -> hodnota z **ID adresy**
  * **Doprava** - produkt, ktorý štandardne používate pre odosielanie balíkov
  * **Notifikácie** - Zapnutie/Vypnutie oznámení pri vybraných produktoch prepravy
4. Spôsob odosielania objednávok do portálu DPD Shipper
  * **Z detailu objednávky:** V detaile objednávky v bočnom paneli máte možnosť odoslať objednávku do API. Máte tam tiež možnosť zmeniť štandardné nastavenia pre aktuálnu objednávku (napr. zmena produktu)
  * **Hromadné odoslanie objednávok do DPD Shipper portálu:** V zozname objednávok si označíte objednávky na odoslanie a v hromadných akciách si vyberiete možnosť **DPD Hromadný Export**.
5. Funkčnosť prepojenia si môžete **overiť vytvorením testovacej objednávky** a následným exportom

== Screenshots ==
1. Nastavenia pluginu
2. Zobrazenie stĺpca pre export do DPD v zozname WooCommerce objednávok
3. Zobrazenie možnosti hromadného exportu do DPD v zozname WooCommerce objednávok
4. Zobrazenie možnosti exportu do DPD v detaile WooCommerce produktu

== Changelog ==

= 7.0.1 =
* Standardize weight unit to kg for parcelshop filtering

= 7.0.0 =
* Add ability to hide parcelshops from interactive map

= 6.0.1 =
* Handle dynamic map elements

= 6.0.0 =
* Added an option to enable a map widget that allows users to choose a parcelshop from an interactive map.
* Changed the `wc_dpd_cod_id` filter to accept an array of payment method IDs instead of a single method string.
* Added a new filter `wc_dpd_card_payment_ids` to specify an array of card payment method IDs.

= 5.0.3 =
* Fix pickup date that is not a Saturday, Sunday, or a holiday in Slovakia.
* Disable card payment for cash on delivery (COD) orders to countries other than Slovakia.
* Fixed deprecated warnings in the DpdExport class.

= 5.0.2 =
* Fixed tax for free shipping

= 5.0.1 =
* Added multisite support

= 5.0.0 =
* Added support for WooCommerce HPOS
* Added WooCommerce version check and updated the minimum required WC version
* Added filter for COD payment method ID wc_dpd_cod_id

= 4.0.4 =
* Replace package ID with international PUS ID

= 4.0.3 =
* Add reset button to order metabox

= 4.0.2 =
* Updated compatibility with PHP 7.4

= 4.0.1 =
* Add functionality to check for free shipping coupon

= 4.0.0 =
* Added parcelbox packages validation by weight or dimensions
* Added Alzabox packages validation by weight or dimensions
* Added Slovenska Posta packages validation by weight or dimensions
* Allow credit card payment for cod

= 3.0.2 =
* Added action wc_dpd_order_exported

= 3.0.1 =
* Added filter to modify export data
* Added filter to prevent export
* Fixed cod amount rounding that caused discrepancies in some transactions

= 3.0.0 =
* Added an option to set a value for free shipping
* Added an option to set shipping cost based on the weight of products in the cart
* Added an option to choose A4 or A6 labels format
* Fixed a decimal numbers error in shipping cost calculation
* Fixed a critical error in WordPress site health
* Pop-up can be now closed by pressing ESC or clicking outside
* Order number is included in variable symbol.
* Updated user manual

= 2.0.1 =
* Updated plugin description

= 2.0.0 =
* Added support for parcelshops, bulk download of labels, multiple addresses and bank accounts, adding reference texts to the order export and displaying the parcel number in the order list and order detail.

= 1.0.1 =
* Update readme.txt.

= 1.0.0 =
* First implementation.
