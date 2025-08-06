<div class="container mt-3">
    <h3>Body Instellingen</h3>
    <div class="row">
        <!-- Body Tekstgrootte (em) -->
        <div class="col-md-3">
            <label for="bodySize">Tekstgrootte (em)</label>
            <?php echo input('number', 'bodySize', $data['bodySize'] ?? '', 'bodySize', 'class="form-control autosave_config_site" step="0.1" min="0.5" max="5.0" data-field="bodySize" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Body Kleur -->
        <div class="col-md-3">
            <label for="bodyColor">Tekstkleur</label>
            <?php echo input('color', 'bodyColor', $data['bodyColor'] ?? '', 'bodyColor', 'class="form-control autosave_config_site" data-field="bodyColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Body Font -->
        <div class="col-md-3">
            <?php echo selectboxFont('Lettertype', 'bodyFont', $data['bodyFont'] ?? '', 'class="form-select autosave_config_site" data-field="bodyFont" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Body Achtergrondkleur -->
        <div class="col-md-3">
            <label for="bodyBgColor">Achtergrondkleur</label>
            <?php echo input('color', 'bodyBgColor', $data['bodyBgColor'] ?? '', 'bodyBgColor', 'class="form-control autosave_config_site" data-field="bodyBgColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>
    <hr>
    <h3>Headers</h3>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-3">
                    <h4>Tekstgrootte (em)</h4>
                </div>
                <div class="col-md-3">
                    <h4>Tekstkleur</h4>
                </div>
                <div class="col-md-3">
                    <h4>Lettertype</h4>
                </div>
            </div>
        </div>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <div class="col-md-12 mt-1">
                <div class="row">
                    <!-- Header Tekstgrootte (em) -->
                    <div class="col-md-1">
                        <h4>H<?php echo $i; ?></h4>
                    </div>
                    <div class="col-md-3">
                        <?php echo input('number', 'h' . $i . 'Size', $data['h' . $i . 'Size'] ?? '', 'h' . $i . 'Size', 'class="form-control autosave_config_site" step="0.1" min="0.5" max="5.0" data-field="h' . $i . 'Size" data-set="'.$data['id'].'"'); ?>
                    </div>

                    <!-- Header Kleur -->
                    <div class="col-md-3">
                        <?php echo input('color', 'h' . $i . 'Color', $data['h' . $i . 'Color'] ?? '', 'h' . $i . 'Color', 'class="form-control autosave_config_site" data-field="h' . $i . 'Color" data-set="'.$data['id'].'"'); ?>
                    </div>

                    <!-- Header Font -->
                    <div class="col-md-3">
                        <?php echo selectboxFont('H' . $i . ' Lettertype', 'h' . $i . 'Font', $data['h' . $i . 'Font'] ?? '', 'class="form-select autosave_config_site" data-field="h' . $i . 'Font" data-set="'.$data['id'].'"'); ?>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <hr>
    <div class="row mt-4">
    <h3>HR Instellingen</h3>
    <div class="row">
        <!-- HR Kleur -->
        <div class="col-md-3">
            <label for="hrColor">HR Kleur</label>
            <?php echo input('color', 'hrColor', $data['hrColor'] ?? '', 'hrColor', 'class="form-control autosave_config_site" data-field="hrColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- HR Hoogte -->
        <div class="col-md-3">
            <label for="hrHeight">HR Hoogte (px)</label>
            <?php echo input('number', 'hrHeight', $data['hrHeight'] ?? '', 'hrHeight', 'class="form-control autosave_config_site" step="1" min="1" max="20" data-field="hrHeight" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- HR Opacity -->
        <div class="col-md-3">
            <label for="hrOpacity">HR Opacity (0-1)</label>
            <?php echo input('number', 'hrOpacity', $data['hrOpacity'] ?? '', 'hrOpacity', 'class="form-control autosave_config_site" step="0.1" min="0" max="1" data-field="hrOpacity" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>
</div>
<hr>
    <div class="container mt-3">
    <h3>Back to Top Button Instellingen</h3>
    <div class="row">
        <!-- Button Achtergrondkleur -->
        <div class="col-md-3">
            <label for="backToTopBg">Achtergrondkleur</label>
            <?php echo input('color', 'backToTopBg', $data['backToTopBg'] ?? '', 'backToTopBg', 'class="form-control autosave_config_site" data-field="backToTopBg" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Button Hover Achtergrondkleur -->
        <div class="col-md-3">
            <label for="backToTopHoverBg">Hover Achtergrondkleur</label>
            <?php echo input('color', 'backToTopHoverBg', $data['backToTopHoverBg'] ?? '', 'backToTopHoverBg', 'class="form-control autosave_config_site" data-field="backToTopHoverBg" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Button Icon Kleur -->
        <div class="col-md-3">
            <label for="backToTopIconColor">Icon Kleur</label>
            <?php echo input('color', 'backToTopIconColor', $data['backToTopIconColor'] ?? '', 'backToTopIconColor', 'class="form-control autosave_config_site" data-field="backToTopIconColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>
</div>

    <hr>
<div class="container mt-3">
    <h3>Header Instellingen</h3>
    <!-- Header algemene instellingen -->
    <div class="row mb-4 border-bottom pb-3">
        <!-- Achtergrondkleur -->
        <div class="col-md-3">
            <label for="headerBgColor">Achtergrondkleur</label>
            <?php echo input('color', 'headerBgColor', $data['headerBgColor'] ?? '', 'headerBgColor', 'class="form-control autosave_config_site" data-field="headerBgColor" data-set="'.$data['id'].'"'); ?>
        </div>
        
        <!-- Borderkleur -->
        <div class="col-md-3">
            <label for="headerBorderColor">Border Kleur</label>
            <?php echo input('color', 'headerBorderColor', $data['headerBorderColor'] ?? '', 'headerBorderColor', 'class="form-control autosave_config_site" data-field="headerBorderColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

<!-- Header logo instellingen -->
<div class="row mb-4 border-bottom pb-3">
    <h4 class="col-12">Logo Instellingen</h4>
    <!-- Logo kleur -->
    <div class="col-md-3">
        <label for="headerLogoColor">Logo Kleur</label>
        <?php echo input('color', 'headerLogoColor', $data['headerLogoColor'] ?? '', 'headerLogoColor', 'class="form-control autosave_config_site" data-field="headerLogoColor" data-set="'.$data['id'].'"'); ?>
    </div>
    
    <!-- Logo font grootte -->
    <div class="col-md-3">
        <label for="headerFontSize">Logo Font Grootte (em)</label>
        <?php echo input('number', 'headerFontSize', $data['headerFontSize'] ?? '', 'headerFontSize', 'class="form-control autosave_config_site" step="0.1" min="0.5" max="5.0" data-field="headerFontSize" data-set="'.$data['id'].'"'); ?>
    </div>
    
    <!-- Logo font familie -->
    <div class="col-md-3">
        <?php echo selectboxFont('Logo Font Familie', 'headerFontFamily', $data['headerFontFamily'] ?? '', 'class="form-select autosave_config_site" data-field="headerFontFamily" data-set="'.$data['id'].'"'); ?>
    </div>
</div>

<!-- Slogan instellingen -->
<div class="row">
    <h4 class="col-12">Slogan Instellingen</h4>
    <!-- Slogan kleur -->
    <div class="col-md-3">
        <label for="sloganColor">Slogan Kleur</label>
        <?php echo input('color', 'sloganColor', $data['sloganColor'] ?? '', 'sloganColor', 'class="form-control autosave_config_site" data-field="sloganColor" data-set="'.$data['id'].'"'); ?>
    </div>

    <!-- Slogan font grootte -->
    <div class="col-md-3">
        <label for="sloganFontSize">Slogan Font Grootte (em)</label>
        <?php echo input('number', 'sloganFontSize', $data['sloganFontSize'] ?? '', 'sloganFontSize', 'class="form-control autosave_config_site" step="0.1" min="0.5" max="3.0" data-field="sloganFontSize" data-set="'.$data['id'].'"'); ?>
    </div>

    <!-- Slogan font familie -->
    <div class="col-md-3">
        <?php echo selectboxFont('Slogan Font Familie', 'sloganFontFamily', $data['sloganFontFamily'] ?? '', 'class="form-select autosave_config_site" data-field="sloganFontFamily" data-set="'.$data['id'].'"'); ?>
    </div>
</div>
<hr>
<div class="container mt-3">
    <h3>Navigation Menu Instellingen</h3>
    <div class="row">
        <!-- Navigatie Achtergrondkleur -->
        <div class="col-md-4">
            <label for="navbarBgColor">Achtergrondkleur</label>
            <?php echo input('color', 'navbarBgColor', $data['navbarBgColor'] ?? '', 'navbarBgColor', 'class="form-control autosave_config_site" data-field="navbarBgColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Navigatie Achtergrond Opaciteit -->
        <div class="col-md-4">
            <label for="navbarBgOpacity">Achtergrond Opaciteit</label>
            <?php echo input('number', 'navbarBgOpacity', $data['navbarBgOpacity'] ?? '', 'navbarBgOpacity', 'class="form-control autosave_config_site" step="0.1" min="0" max="1" data-field="navbarBgOpacity" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Lettertype Grootte -->
        <div class="col-md-4">
            <label for="navbarFontSize">Lettertype Grootte (em)</label>
            <?php echo input('number', 'navbarFontSize', $data['navbarFontSize'] ?? '', 'navbarFontSize', 'class="form-control autosave_config_site" step="0.1" min="0.5" max="5.0" data-field="navbarFontSize" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Lettertype Dikte -->
        <div class="col-md-4">
            <?php echo selectbox('Lettertype Dikte', 'navbarFontWeight', $data['navbarFontWeight'] ?? '', ['Normal' => 'normal', 'Bold' => 'bold'], 'class="form-select autosave_config_site" data-field="navbarFontWeight" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Letterspacing -->
        <div class="col-md-4">
            <label for="navbarLetterSpacing">Letterspacing (px)</label>
            <?php echo input('number', 'navbarLetterSpacing', $data['navbarLetterSpacing'] ?? '', 'navbarLetterSpacing', 'class="form-control autosave_config_site" step="0.1" min="0" max="10" data-field="navbarLetterSpacing" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Regelafstand -->
        <div class="col-md-4">
            <label for="navbarLineHeight">Regelhoogte (em)</label>
            <?php echo input('number', 'navbarLineHeight', $data['navbarLineHeight'] ?? '', 'navbarLineHeight', 'class="form-control autosave_config_site" step="0.1" min="1" max="3" data-field="navbarLineHeight" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Tekstkleur -->
        <div class="col-md-4">
            <label for="navbarTextColor">Tekstkleur</label>
            <?php echo input('color', 'navbarTextColor', $data['navbarTextColor'] ?? '', 'navbarTextColor', 'class="form-control autosave_config_site" data-field="navbarTextColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Hover Tekstkleur -->
        <div class="col-md-4">
            <label for="navbarHoverColor">Hover Tekstkleur</label>
            <?php echo input('color', 'navbarHoverColor', $data['navbarHoverColor'] ?? '', 'navbarHoverColor', 'class="form-control autosave_config_site" data-field="navbarHoverColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Hover Achtergrondkleur -->
        <div class="col-md-4">
            <label for="navbarHoverBgColor">Hover Achtergrondkleur</label>
            <?php echo input('color', 'navbarHoverBgColor', $data['navbarHoverBgColor'] ?? '', 'navbarHoverBgColor', 'class="form-control autosave_config_site" data-field="navbarHoverBgColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Tekstdecoratie -->
        <div class="col-md-4">
            <?php echo selectbox('Tekstdecoratie', 'navbarTextDecoration', $data['navbarTextDecoration'] ?? '', ['None' => 'none', 'Underline' => 'underline'], 'class="form-select autosave_config_site" data-field="navbarTextDecoration" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>
</div>
    <hr>
<div class="container mt-3">
    <h3>Footer Instellingen</h3>
    <div class="row">
        <!-- Achtergrondkleur -->
        <div class="col-md-3">
            <label for="footerBgColor">Achtergrondkleur</label>
            <?php echo input('color', 'footerBgColor', $data['footerBgColor'] ?? '', 'footerBgColor', 'class="form-control autosave_config_site" data-field="footerBgColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Tekstkleur -->
        <div class="col-md-3">
            <label for="footerTextColor">Tekstkleur</label>
            <?php echo input('color', 'footerTextColor', $data['footerTextColor'] ?? '', 'footerTextColor', 'class="form-control autosave_config_site" data-field="footerTextColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Font Grootte -->
        <div class="col-md-3">
            <label for="footerFontSize">Font Grootte (em)</label>
            <?php echo input('number', 'footerFontSize', $data['footerFontSize'] ?? '', 'footerFontSize', 'class="form-control autosave_config_site" step="0.1" min="0.5" max="5.0" data-field="footerFontSize" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- H4 Font Dikte -->
        <div class="col-md-3">
            <?php echo selectbox('H4 Font Dikte', 'footerH4FontWeight', $data['footerH4FontWeight'] ?? '', ['Normal' => 'normal', 'Bold' => 'bold'], 'class="form-select autosave_config_site" data-field="footerH4FontWeight" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Copyright Tekstkleur -->
        <div class="col-md-3">
            <label for="footerCopyrightColor">Copyright Tekstkleur</label>
            <?php echo input('color', 'footerCopyrightColor', $data['footerCopyrightColor'] ?? '', 'footerCopyrightColor', 'class="form-control autosave_config_site" data-field="footerCopyrightColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Link Tekstkleur -->
        <div class="col-md-3">
            <label for="footerLinkColor">Link Tekstkleur</label>
            <?php echo input('color', 'footerLinkColor', $data['footerLinkColor'] ?? '', 'footerLinkColor', 'class="form-control autosave_config_site" data-field="footerLinkColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Link Hover Kleur -->
        <div class="col-md-3">
            <label for="footerHoverLinkColor">Link Hover Kleur</label>
            <?php echo input('color', 'footerHoverLinkColor', $data['footerHoverLinkColor'] ?? '', 'footerHoverLinkColor', 'class="form-control autosave_config_site" data-field="footerHoverLinkColor" data-set="'.$data['id'].'"'); ?>
        </div>

        <!-- Social Icon Kleur -->
        <div class="col-md-3">
            <label for="footerSocialIconColor">Social Icon Kleur</label>
            <?php echo input('color', 'footerSocialIconColor', $data['footerSocialIconColor'] ?? '', 'footerSocialIconColor', 'class="form-control autosave_config_site" data-field="footerSocialIconColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>
</div>
<hr>
    <div class="container mt-3">
    <h3>Button Instellingen</h3>

    <!-- Primary Button -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="btnPrimaryColor">Primary Button Kleur</label>
            <?php echo input('color', 'btnPrimaryColor', $data['btnPrimaryColor'] ?? '', 'btnPrimaryColor', 'class="form-control autosave_config_site" data-field="btnPrimaryColor" data-set="'.$data['id'].'"'); ?>
        </div>
        <div class="col-md-3">
            <label for="btnPrimaryTextColor">Primary Text Kleur</label>
            <?php echo input('color', 'btnPrimaryTextColor', $data['btnPrimaryTextColor'] ?? '', 'btnPrimaryTextColor', 'class="form-control autosave_config_site" data-field="btnPrimaryTextColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <!-- Secondary Button -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="btnSecondaryColor">Secondary Button Kleur</label>
            <?php echo input('color', 'btnSecondaryColor', $data['btnSecondaryColor'] ?? '', 'btnSecondaryColor', 'class="form-control autosave_config_site" data-field="btnSecondaryColor" data-set="'.$data['id'].'"'); ?>
        </div>
        <div class="col-md-3">
            <label for="btnSecondaryTextColor">Secondary Text Kleur</label>
            <?php echo input('color', 'btnSecondaryTextColor', $data['btnSecondaryTextColor'] ?? '', 'btnSecondaryTextColor', 'class="form-control autosave_config_site" data-field="btnSecondaryTextColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <!-- Success Button -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="btnSuccessColor">Success Button Kleur</label>
            <?php echo input('color', 'btnSuccessColor', $data['btnSuccessColor'] ?? '', 'btnSuccessColor', 'class="form-control autosave_config_site" data-field="btnSuccessColor" data-set="'.$data['id'].'"'); ?>
        </div>
        <div class="col-md-3">
            <label for="btnSuccessTextColor">Success Text Kleur</label>
            <?php echo input('color', 'btnSuccessTextColor', $data['btnSuccessTextColor'] ?? '', 'btnSuccessTextColor', 'class="form-control autosave_config_site" data-field="btnSuccessTextColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <!-- Danger Button -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="btnDangerColor">Danger Button Kleur</label>
            <?php echo input('color', 'btnDangerColor', $data['btnDangerColor'] ?? '', 'btnDangerColor', 'class="form-control autosave_config_site" data-field="btnDangerColor" data-set="'.$data['id'].'"'); ?>
        </div>
        <div class="col-md-3">
            <label for="btnDangerTextColor">Danger Text Kleur</label>
            <?php echo input('color', 'btnDangerTextColor', $data['btnDangerTextColor'] ?? '', 'btnDangerTextColor', 'class="form-control autosave_config_site" data-field="btnDangerTextColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <!-- Warning Button -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="btnWarningColor">Warning Button Kleur</label>
            <?php echo input('color', 'btnWarningColor', $data['btnWarningColor'] ?? '', 'btnWarningColor', 'class="form-control autosave_config_site" data-field="btnWarningColor" data-set="'.$data['id'].'"'); ?>
        </div>
        <div class="col-md-3">
            <label for="btnWarningTextColor">Warning Text Kleur</label>
            <?php echo input('color', 'btnWarningTextColor', $data['btnWarningTextColor'] ?? '', 'btnWarningTextColor', 'class="form-control autosave_config_site" data-field="btnWarningTextColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <!-- Info Button -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="btnInfoColor">Info Button Kleur</label>
            <?php echo input('color', 'btnInfoColor', $data['btnInfoColor'] ?? '', 'btnInfoColor', 'class="form-control autosave_config_site" data-field="btnInfoColor" data-set="'.$data['id'].'"'); ?>
        </div>
        <div class="col-md-3">
            <label for="btnInfoTextColor">Info Text Kleur</label>
            <?php echo input('color', 'btnInfoTextColor', $data['btnInfoTextColor'] ?? '', 'btnInfoTextColor', 'class="form-control autosave_config_site" data-field="btnInfoTextColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <!-- Light Button -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="btnLightColor">Light Button Kleur</label>
            <?php echo input('color', 'btnLightColor', $data['btnLightColor'] ?? '', 'btnLightColor', 'class="form-control autosave_config_site" data-field="btnLightColor" data-set="'.$data['id'].'"'); ?>
        </div>
        <div class="col-md-3">
            <label for="btnLightTextColor">Light Text Kleur</label>
            <?php echo input('color', 'btnLightTextColor', $data['btnLightTextColor'] ?? '', 'btnLightTextColor', 'class="form-control autosave_config_site" data-field="btnLightTextColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>

    <!-- Dark Button -->
    <div class="row mt-3">
        <div class="col-md-3">
            <label for="btnDarkColor">Dark Button Kleur</label>
            <?php echo input('color', 'btnDarkColor', $data['btnDarkColor'] ?? '', 'btnDarkColor', 'class="form-control autosave_config_site" data-field="btnDarkColor" data-set="'.$data['id'].'"'); ?>
        </div>
        <div class="col-md-3">
            <label for="btnDarkTextColor">Dark Text Kleur</label>
            <?php echo input('color', 'btnDarkTextColor', $data['btnDarkTextColor'] ?? '', 'btnDarkTextColor', 'class="form-control autosave_config_site" data-field="btnDarkTextColor" data-set="'.$data['id'].'"'); ?>
        </div>
    </div>
</div>

<hr>
    
<div class="container mt-3">
    <h3>Outline Button Instellingen</h3>

    <!-- Primary Outline Button -->
<div class="row mt-3">
    <div class="col-md-3">
        <label for="btnOutlinePrimaryColor">Primary Outline Kleur</label>
        <?php echo input('color', 'btnOutlinePrimaryColor', $data['btnOutlinePrimaryColor'] ?? '', 'btnOutlinePrimaryColor', 'class="form-control autosave_config_site" data-field="btnOutlinePrimaryColor" data-set="'.$data['id'].'"'); ?>
    </div>
    <div class="col-md-3">
        <label for="btnOutlinePrimaryTextColor">Primary Outline Text Kleur</label>
        <?php echo input('color', 'btnOutlinePrimaryTextColor', $data['btnOutlinePrimaryTextColor'] ?? '', 'btnOutlinePrimaryTextColor', 'class="form-control autosave_config_site" data-field="btnOutlinePrimaryTextColor" data-set="'.$data['id'].'"'); ?>
    </div>
</div>

<!-- Secondary Outline Button -->
<div class="row mt-3">
    <div class="col-md-3">
        <label for="btnOutlineSecondaryColor">Secondary Outline Kleur</label>
        <?php echo input('color', 'btnOutlineSecondaryColor', $data['btnOutlineSecondaryColor'] ?? '', 'btnOutlineSecondaryColor', 'class="form-control autosave_config_site" data-field="btnOutlineSecondaryColor" data-set="'.$data['id'].'"'); ?>
    </div>
    <div class="col-md-3">
        <label for="btnOutlineSecondaryTextColor">Secondary Outline Text Kleur</label>
        <?php echo input('color', 'btnOutlineSecondaryTextColor', $data['btnOutlineSecondaryTextColor'] ?? '', 'btnOutlineSecondaryTextColor', 'class="form-control autosave_config_site" data-field="btnOutlineSecondaryTextColor" data-set="'.$data['id'].'"'); ?>
    </div>
</div>

<!-- Success Outline Button -->
<div class="row mt-3">
    <div class="col-md-3">
        <label for="btnOutlineSuccessColor">Success Outline Kleur</label>
        <?php echo input('color', 'btnOutlineSuccessColor', $data['btnOutlineSuccessColor'] ?? '', 'btnOutlineSuccessColor', 'class="form-control autosave_config_site" data-field="btnOutlineSuccessColor" data-set="'.$data['id'].'"'); ?>
    </div>
    <div class="col-md-3">
        <label for="btnOutlineSuccessTextColor">Success Outline Text Kleur</label>
        <?php echo input('color', 'btnOutlineSuccessTextColor', $data['btnOutlineSuccessTextColor'] ?? '', 'btnOutlineSuccessTextColor', 'class="form-control autosave_config_site" data-field="btnOutlineSuccessTextColor" data-set="'.$data['id'].'"'); ?>
    </div>
</div>

<!-- Danger Outline Button -->
<div class="row mt-3">
    <div class="col-md-3">
        <label for="btnOutlineDangerColor">Danger Outline Kleur</label>
        <?php echo input('color', 'btnOutlineDangerColor', $data['btnOutlineDangerColor'] ?? '', 'btnOutlineDangerColor', 'class="form-control autosave_config_site" data-field="btnOutlineDangerColor" data-set="'.$data['id'].'"'); ?>
    </div>
    <div class="col-md-3">
        <label for="btnOutlineDangerTextColor">Danger Outline Text Kleur</label>
        <?php echo input('color', 'btnOutlineDangerTextColor', $data['btnOutlineDangerTextColor'] ?? '', 'btnOutlineDangerTextColor', 'class="form-control autosave_config_site" data-field="btnOutlineDangerTextColor" data-set="'.$data['id'].'"'); ?>
    </div>
</div>

<!-- Warning Outline Button -->
<div class="row mt-3">
    <div class="col-md-3">
        <label for="btnOutlineWarningColor">Warning Outline Kleur</label>
        <?php echo input('color', 'btnOutlineWarningColor', $data['btnOutlineWarningColor'] ?? '', 'btnOutlineWarningColor', 'class="form-control autosave_config_site" data-field="btnOutlineWarningColor" data-set="'.$data['id'].'"'); ?>
    </div>
    <div class="col-md-3">
        <label for="btnOutlineWarningTextColor">Warning Outline Text Kleur</label>
        <?php echo input('color', 'btnOutlineWarningTextColor', $data['btnOutlineWarningTextColor'] ?? '', 'btnOutlineWarningTextColor', 'class="form-control autosave_config_site" data-field="btnOutlineWarningTextColor" data-set="'.$data['id'].'"'); ?>
    </div>
</div>

<!-- Info Outline Button -->
<div class="row mt-3">
    <div class="col-md-3">
        <label for="btnOutlineInfoColor">Info Outline Kleur</label>
        <?php echo input('color', 'btnOutlineInfoColor', $data['btnOutlineInfoColor'] ?? '', 'btnOutlineInfoColor', 'class="form-control autosave_config_site" data-field="btnOutlineInfoColor" data-set="'.$data['id'].'"'); ?>
    </div>
    <div class="col-md-3">
        <label for="btnOutlineInfoTextColor">Info Outline Text Kleur</label>
        <?php echo input('color', 'btnOutlineInfoTextColor', $data['btnOutlineInfoTextColor'] ?? '', 'btnOutlineInfoTextColor', 'class="form-control autosave_config_site" data-field="btnOutlineInfoTextColor" data-set="'.$data['id'].'"'); ?>
    </div>
</div>

<!-- Light Outline Button -->
<div class="row mt-3">
    <div class="col-md-3">
        <label for="btnOutlineLightColor">Light Outline Kleur</label>
        <?php echo input('color', 'btnOutlineLightColor', $data['btnOutlineLightColor'] ?? '', 'btnOutlineLightColor', 'class="form-control autosave_config_site" data-field="btnOutlineLightColor" data-set="'.$data['id'].'"'); ?>
    </div>
    <div class="col-md-3">
        <label for="btnOutlineLightTextColor">Light Outline Text Kleur</label>
        <?php echo input('color', 'btnOutlineLightTextColor', $data['btnOutlineLightTextColor'] ?? '', 'btnOutlineLightTextColor', 'class="form-control autosave_config_site" data-field="btnOutlineLightTextColor" data-set="'.$data['id'].'"'); ?>
    </div>
</div>

<!-- Dark Outline Button -->
<div class="row mt-3">
    <div class="col-md-3">
        <label for="btnOutlineDarkColor">Dark Outline Kleur</label>
        <?php echo input('color', 'btnOutlineDarkColor', $data['btnOutlineDarkColor'] ?? '', 'btnOutlineDarkColor', 'class="form-control autosave_config_site" data-field="btnOutlineDarkColor" data-set="'.$data['id'].'"'); ?>
    </div>
    <div class="col-md-3">
        <label for="btnOutlineDarkTextColor">Dark Outline Text Kleur</label>
        <?php echo input('color', 'btnOutlineDarkTextColor', $data['btnOutlineDarkTextColor'] ?? '', 'btnOutlineDarkTextColor', 'class="form-control autosave_config_site" data-field="btnOutlineDarkTextColor" data-set="'.$data['id'].'"'); ?>
    </div>
</div>

</div>

<!--- EINDE --->    
</div>
