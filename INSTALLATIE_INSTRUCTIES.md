# Multi-Language Installatie Instructies

## ⚠️ BELANGRIJK: BACKUP EERST!

Maak altijd een volledige backup van je database en bestanden voordat je begint:

```bash
# Database backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Bestanden backup
tar -czf backup_files_$(date +%Y%m%d).tar.gz .
```

## Stap-voor-Stap Installatie

### 1. Bestanden Uploaden
Upload alle nieuwe bestanden naar je server:
- `update_multilanguage.php` (in root directory)
- `README_MULTILANGUAGE.md`
- `admin/functions/` directory met nieuwe bestanden
- `admin/modules/content/forms/` nieuwe bestanden

### 2. Update Script Uitvoeren

**Via Browser:**
1. Ga naar `http://jouwdomein.com/update_multilanguage.php`
2. Het script voert automatisch alle benodigde updates uit
3. Controleer of alle stappen succesvol zijn uitgevoerd

**Via Command Line:**
```bash
php update_multilanguage.php
```

### 3. Permissies Controleren
Zorg ervoor dat de webserver schrijfrechten heeft op:
- `config.php` (voor backup en updates)
- `src/site.class.php` (voor backup en updates)
- `lang/` directory (voor backup en updates)
- `admin/functions/` (nieuwe bestanden)

### 4. Database Verificatie
Controleer of de database wijzigingen correct zijn uitgevoerd:

```sql
-- Controleer nieuwe kolommen
DESCRIBE group_content;
DESCRIBE group_menu;

-- Controleer of data correct is gemigreerd
SELECT COUNT(*) FROM group_content WHERE lang_code = 'nl';
SELECT COUNT(*) FROM group_menu WHERE lang_code = 'nl';

-- Controleer languages tabel
SELECT * FROM languages;
```

### 5. Admin Configuratie

1. **Log in op admin interface**
2. **Ga naar Config → Languages**
3. **Selecteer gewenste talen:**
   - Nederlands (nl) - meestal al actief
   - Engels (en)
   - Andere gewenste talen

4. **Test de configuratie:**
   - Ga naar Content beheer
   - Controleer of taal selector zichtbaar is
   - Maak test content aan in verschillende talen

### 6. Frontend Testen

1. **Test language detection:**
   ```
   http://jouwdomein.com/?lang=en
   http://jouwdomein.com/?lang=nl
   ```

2. **Controleer cookies:**
   - Taal keuze moet worden onthouden
   - Browser refresh moet taal behouden

3. **Test browser detectie:**
   - Verwijder cookies
   - Verander browser taal instellingen
   - Herlaad pagina

### 7. Language Switcher Toevoegen

Voeg de language switcher toe aan je templates:

**In header.php of navbar.php:**
```php
<?php include $_SERVER['DOCUMENT_ROOT'] . '/themes/components/language_switcher.php'; ?>
```

**Custom styling toevoegen:**
```css
/* Voeg toe aan je CSS */
.language-switcher {
    /* Pas aan naar jouw design */
}
```

### 8. Content Migratie (Optioneel)

Als je bestaande content wilt dupliceren voor andere talen:

1. **Ga naar admin directory:**
   ```bash
   cd admin/
   ```

2. **Bewerk migrate_content.php:**
   ```php
   // Uncomment en pas aan:
   $result = migrateContentToLanguages(['en', 'de']);
   ```

3. **Voer migratie uit:**
   ```bash
   php migrate_content.php
   ```

### 9. Templates Updaten

Update je theme templates om taalstrings te gebruiken:

**Voor:**
```php
<h1>Welkom</h1>
<p>Contact ons</p>
```

**Na:**
```php
<h1><?php echo lang('WELCOME'); ?></h1>
<p><?php echo lang('CONTACT_US'); ?></p>
```

### 10. SEO URLs (Optioneel)

Voor SEO-vriendelijke URLs, voeg toe aan `.htaccess`:

```apache
# Multi-language URL rewriting
RewriteEngine On
RewriteRule ^([a-z]{2})/(.*)$ $2?lang=$1 [QSA,L]
RewriteRule ^([a-z]{2})/?$ index.php?lang=$1 [QSA,L]
```

## Verificatie Checklist

- [ ] Database updates succesvol uitgevoerd
- [ ] Backup bestanden aangemaakt
- [ ] Config.php werkt zonder errors
- [ ] Site.class.php werkt zonder errors
- [ ] Admin language selector zichtbaar
- [ ] Language switcher werkt op frontend
- [ ] Taal detectie werkt (URL, cookie, browser)
- [ ] Content kan worden aangemaakt in verschillende talen
- [ ] Bestaande content blijft werken

## Troubleshooting

### "Class 'database' not found"
- Controleer of `system/database.php` correct wordt geladen
- Controleer bestandspaden in config

### "Call to undefined method"
- Controleer of site.class.php correct is geüpdatet
- Herstel van backup indien nodig: `cp src/site.class.php.backup src/site.class.php`

### Language switcher toont geen talen
- Controleer of talen zijn geconfigureerd in admin
- Controleer of `$site`, `$shop_id` variabelen beschikbaar zijn
- Controleer database verbinding

### Content verschijnt niet in andere taal
- Controleer `lang_code` in database
- Controleer of content status op 'y' staat
- Controleer of juiste taal wordt doorgegeven aan methods

### Database errors
- Controleer database permissies
- Controleer of alle tabellen bestaan
- Controleer of kolommen correct zijn toegevoegd

## Rollback Procedure

Als er problemen zijn, kun je terugkeren naar de originele staat:

```bash
# Herstel database
mysql -u username -p database_name < backup_YYYYMMDD.sql

# Herstel bestanden
cp config.php.backup config.php
cp src/site.class.php.backup src/site.class.php
cp lang/nl.php.backup lang/nl.php
cp lang/en.php.backup lang/en.php

# Verwijder nieuwe bestanden
rm -f update_multilanguage.php
rm -rf admin/functions/get_translations.php
rm -rf admin/functions/duplicate_content.php
```

## Support

Voor vragen of problemen:
1. Controleer eerst deze documentatie
2. Controleer de error logs van je webserver
3. Test met debug mode aan (voeg `?debug_lang=1` toe aan URL)

## Na Installatie

1. **Test grondig** op een staging omgeving
2. **Train je content beheerders** in het nieuwe systeem
3. **Plan je vertaal strategie**
4. **Monitor performance** na go-live
5. **Maak regelmatige backups**

---

*Laatste update: December 2024*