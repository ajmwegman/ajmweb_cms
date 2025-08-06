# Multi-Language Implementation voor AJMWEB CMS

## Overzicht
Dit document beschrijft de implementatie van multi-language ondersteuning voor het AJMWEB CMS systeem.

## Installatie

### Stap 1: Update Script Uitvoeren
```bash
# Via browser
http://jouwdomain.com/update_multilanguage.php

# Of via command line
php update_multilanguage.php
```

### Stap 2: Database Configuratie
Na het uitvoeren van het update script zijn de volgende database wijzigingen doorgevoerd:

- `group_content.lang_code` - Taalcode voor content items
- `group_menu.lang_code` - Taalcode voor menu items  
- `languages` tabel gevuld met basis talen
- Indexen toegevoegd voor betere performance

### Stap 3: Admin Configuratie
1. Log in op de admin interface
2. Ga naar Config → Languages
3. Selecteer welke talen actief moeten zijn voor jouw site
4. Klik op opslaan

## Gebruik

### Language Detection
Het systeem detecteert de taal in de volgende volgorde:
1. URL parameter: `?lang=en`
2. Cookie: `site_lang`
3. Browser taal (Accept-Language header)
4. Fallback naar Nederlands

### Language Switcher Toevoegen
Voeg de language switcher toe aan je templates:

```php
<?php include $_SERVER['DOCUMENT_ROOT'] . '/themes/components/language_switcher.php'; ?>
```

### Content Beheer
1. **Nieuwe Content**: Selecteer de taal bij het aanmaken
2. **Bestaande Content Dupliceren**: 
   - Gebruik `admin/migrate_content.php` 
   - Of dupliceer handmatig via admin interface

### Template Updates
Update je templates om taalstrings te gebruiken:

```php
<!-- Voor -->
<h1>Welkom</h1>

<!-- Na -->
<h1><?php echo lang('WELCOME'); ?></h1>
```

## API Wijzigingen

### Site Class Methods
De volgende methods zijn uitgebreid met language support:

```php
// Oude manier
$menu = $site->getActiveMenuItems($group_id);

// Nieuwe manier  
$menu = $site->getActiveMenuItems($group_id, $current_lang);
```

### Nieuwe Methods
- `getAvailableLanguages($site_id)` - Beschikbare talen voor site
- `getActiveMenuItems($groupid, $lang_code)` - Menu items per taal
- `getActiveContent($groupid, $lang_code)` - Content per taal
- `MenuItemsByLocation($groupid, $location, $lang_code)` - Content per locatie en taal
- `getCategoryContent($group_id, $location, $limit, $lang_code)` - Categorie content per taal

## Bestandsstructuur

```
/
├── update_multilanguage.php          # Hoofdupdate script
├── config.php                        # Uitgebreid met language detection
├── lang/
│   ├── nl.php                        # Nederlandse vertalingen (uitgebreid)
│   ├── en.php                        # Engelse vertalingen (uitgebreid)
│   ├── nl.php.backup                 # Backup van origineel
│   └── en.php.backup                 # Backup van origineel
├── src/
│   ├── site.class.php                # Uitgebreid met ML methods
│   └── site.class.php.backup         # Backup van origineel
├── admin/
│   ├── functions/
│   │   └── language_management.php   # Admin helper functies
│   └── migrate_content.php           # Content migratie script
└── themes/
    └── components/
        └── language_switcher.php     # Language switcher component
```

## Database Schema

### languages
```sql
CREATE TABLE languages (
    lang_id int PRIMARY KEY,
    locale varchar(5),
    label varchar(50)
);
```

### language_link  
```sql
CREATE TABLE language_link (
    site_id int,
    locale varchar(5)
);
```

### Uitgebreide Tabellen
- `group_content.lang_code VARCHAR(5) DEFAULT 'nl'`
- `group_menu.lang_code VARCHAR(5) DEFAULT 'nl'`

## Troubleshooting

### Veelvoorkomende Problemen

**1. Language switcher werkt niet**
- Controleer of `$site`, `$shop_id` en `$current_lang` variabelen beschikbaar zijn
- Controleer of er talen geconfigureerd zijn in de admin

**2. Content verschijnt niet in andere taal**
- Controleer of content `lang_code` correct is ingesteld
- Controleer of content status op 'y' staat

**3. Database errors**
- Controleer of alle database updates succesvol zijn uitgevoerd
- Controleer database permissies

**4. Config.php errors**
- Herstel van backup: `cp config.php.backup config.php`
- Voer update script opnieuw uit

### Debug Mode
Voeg toe aan config.php voor debugging:
```php
// Debug language detection
if(isset($_GET['debug_lang'])) {
    echo "Current lang: " . $current_lang . "<br>";
    echo "Available langs: " . implode(', ', $available_languages) . "<br>";
    echo "Cookie: " . ($_COOKIE['site_lang'] ?? 'none') . "<br>";
}
```

## Performance Tips

1. **Database Indexen**: Automatisch toegevoegd door update script
2. **Caching**: Overweeg language-aware caching
3. **CDN**: Configureer CDN voor verschillende taal-URLs

## Uitbreidingen

### Meer Talen Toevoegen
1. Voeg toe aan `languages` tabel
2. Maak nieuw taalbestand: `lang/xx.php`
3. Configureer in admin interface

### URL Structure
Voor SEO-vriendelijke URLs, voeg toe aan .htaccess:
```apache
RewriteRule ^([a-z]{2})/(.*)$ $2?lang=$1 [QSA,L]
RewriteRule ^([a-z]{2})/?$ index.php?lang=$1 [QSA,L]
```

### Automatische Vertaling
Integreer met vertaal-APIs zoals Google Translate voor automatische vertalingen.

## Support
Voor vragen of problemen, raadpleeg de documentatie of neem contact op met de ontwikkelaar.

---
*Laatste update: 2024*