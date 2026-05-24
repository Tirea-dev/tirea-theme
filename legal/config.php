<?php
// Configuration centrale des pages légales — modifie ICI les emails, titres, sections
if (!defined('ABSPATH')) exit;

// CTA standard "Une question ? Écrivez-nous." → page contact
$cta_contact = [
    'pill'    => 'Réponse sous 24h',
    'h3'      => 'Une question ?',
    'h3_em'   => 'Écrivez-nous.',
    'text'    => 'Notre équipe française vous répond personnellement, sous 24 heures, du lundi au dimanche.',
    'btn'     => 'Contacter le service client',
    'btn_url' => home_url('/contact/'),
];

return [

    // ==================== CGV ====================
    'cgv' => [
        'pill'    => 'Informations légales',
        'h1'      => 'Conditions',
        'h1_em'   => 'Générales de Vente.',
        'lede'    => 'Le cadre qui régit chacune de vos commandes sur tirea.fr. Lecture claire, sans jargon, pour acheter en toute sérénité.',
        'meta'    => [
            ['label' => 'En vigueur depuis le', 'value' => '[Date de publication]'],
            ['label' => 'Version', 'value' => '1.0'],
            ['label' => 'Lecture', 'value' => '≈ 8 min'],
        ],
        'sections' => [
            ['id' => 's01', 'label' => 'Objet & champ d\'application'],
            ['id' => 's02', 'label' => 'Identification du vendeur'],
            ['id' => 's03', 'label' => 'Produits & disponibilité'],
            ['id' => 's04', 'label' => 'Prix'],
            ['id' => 's05', 'label' => 'Commande'],
            ['id' => 's06', 'label' => 'Paiement'],
            ['id' => 's07', 'label' => 'Livraison'],
            ['id' => 's08', 'label' => 'Droit de rétractation'],
            ['id' => 's09', 'label' => 'Garanties légales'],
            ['id' => 's10', 'label' => 'Service client'],
            ['id' => 's11', 'label' => 'Responsabilité'],
            ['id' => 's12', 'label' => 'Données personnelles'],
            ['id' => 's13', 'label' => 'Propriété intellectuelle'],
            ['id' => 's14', 'label' => 'Litiges & médiation'],
        ],
        'cta'    => $cta_contact,
        'module' => null,
    ],

    // ==================== MENTIONS LÉGALES ====================
    'mentions-legales' => [
        'pill'    => 'Informations légales',
        'h1'      => 'Mentions',
        'h1_em'   => 'Légales.',
        'lede'    => 'Informations légales relatives à l\'édition du site tirea.fr, conformément à la loi pour la confiance dans l\'économie numérique (LCEN).',
        'meta'    => [
            ['label' => 'En vigueur depuis le', 'value' => '[Date de publication]'],
            ['label' => 'Version', 'value' => '1.0'],
            ['label' => 'Lecture', 'value' => '≈ 4 min'],
        ],
        'sections' => [
            ['id' => 's01', 'label' => 'Éditeur du site'],
            ['id' => 's02', 'label' => 'Hébergeur'],
            ['id' => 's03', 'label' => 'Propriété intellectuelle'],
            ['id' => 's04', 'label' => 'Responsabilité'],
            ['id' => 's05', 'label' => 'Liens hypertextes'],
            ['id' => 's06', 'label' => 'Cookies & traceurs'],
            ['id' => 's07', 'label' => 'Droit applicable'],
        ],
        'cta'    => $cta_contact,
        'module' => null,
    ],

    // ==================== CONFIDENTIALITÉ ====================
    'confidentialite' => [
        'pill'    => 'Informations légales',
        'h1'      => 'Politique de',
        'h1_em'   => 'Confidentialité.',
        'lede'    => 'Parce que la transparence est la clé d\'une relation de confiance. Découvrez comment TIREA collecte, utilise et protège vos données personnelles, dans le strict respect du RGPD et de la loi Informatique et Libertés.',
        'meta'    => [
            ['label' => 'En vigueur depuis le', 'value' => '[Date de publication]'],
            ['label' => 'Version', 'value' => '1.0'],
            ['label' => 'Lecture', 'value' => '≈ 6 min'],
        ],
        'sections' => [
            ['id' => 's01', 'label' => 'Responsable de traitement'],
            ['id' => 's02', 'label' => 'Données collectées'],
            ['id' => 's03', 'label' => 'Finalités & bases légales'],
            ['id' => 's04', 'label' => 'Destinataires des données'],
            ['id' => 's05', 'label' => 'Durées de conservation'],
            ['id' => 's06', 'label' => 'Vos droits'],
            ['id' => 's07', 'label' => 'Cookies et traceurs'],
            ['id' => 's08', 'label' => 'Sécurité de vos données'],
            ['id' => 's09', 'label' => 'Transferts hors UE'],
            ['id' => 's10', 'label' => 'Modification de la politique'],
        ],
        'cta'    => [
            'pill'    => 'Données personnelles',
            'h3'      => 'Une question ?',
            'h3_em'   => 'Écrivez-nous.',
            'text'    => 'Pour toute demande relative à vos données personnelles, notre équipe vous répond personnellement à rgpd@tirea.fr.',
            'btn'     => 'Contacter notre équipe',
            'btn_url' => 'mailto:rgpd@tirea.fr',
        ],
        'module' => null,
    ],

    // ==================== LIVRAISON ====================
    'livraison' => [
        'pill'    => 'Engagements TIREA',
        'h1'      => 'Politique de',
        'h1_em'   => 'Livraison.',
        'lede'    => 'Une expédition rapide, un suivi fiable, une livraison gratuite en France métropolitaine. Tout ce qu\'il faut savoir avant de commander.',
        'meta'    => [
            ['label' => 'En vigueur depuis le', 'value' => '[Date de publication]'],
            ['label' => 'Version', 'value' => '1.0'],
            ['label' => 'Lecture', 'value' => '≈ 3 min'],
        ],
        'sections' => [
            ['id' => 's01', 'label' => 'Délais de préparation'],
            ['id' => 's02', 'label' => 'Zones desservies & tarifs'],
            ['id' => 's03', 'label' => 'Délais de livraison'],
            ['id' => 's04', 'label' => 'Suivi de votre colis'],
            ['id' => 's05', 'label' => 'Réception & vérification'],
            ['id' => 's06', 'label' => 'Problème de livraison'],
        ],
        'cta'    => $cta_contact,
        'module' => null,
    ],

    // ==================== RETOURS ====================
    'retours' => [
        'pill'    => 'Engagements & Sérénité',
        'h1'      => 'Politique de',
        'h1_em'   => 'Retour.',
        'lede'    => 'Votre satisfaction est notre priorité absolue. Si une pièce ne répond pas pleinement à vos attentes, nous vous offrons la possibilité de nous la retourner en toute simplicité, conformément à la réglementation en vigueur.',
        'meta'    => [
            ['label' => 'Site', 'value' => 'tirea.fr'],
            ['label' => 'Version', 'value' => '1.0'],
            ['label' => 'Lecture', 'value' => '≈ 5 min'],
        ],
        'sections' => [
            ['id' => 's01', 'label' => 'Délai de rétractation'],
            ['id' => 's02', 'label' => 'Conditions d\'éligibilité'],
            ['id' => 's03', 'label' => 'Procédure de retour'],
            ['id' => 's04', 'label' => 'Frais & responsabilités'],
            ['id' => 's05', 'label' => 'Modalités de remboursement'],
            ['id' => 's06', 'label' => 'Politique d\'échange'],
            ['id' => 's07', 'label' => 'Articles défectueux'],
            ['id' => 's08', 'label' => 'Formulaire de rétractation'],
        ],
        'cta'    => [
            'pill'    => 'Réponse sous 24h',
            'h3'      => 'Une question ?',
            'h3_em'   => 'Écrivez-nous.',
            'text'    => 'Notre équipe française vous répond personnellement, sous 24 heures, du lundi au dimanche.',
            'btn'     => 'Contacter le SAV',
            'btn_url' => home_url('/contact/'),
        ],
        'module' => 'retour', // formulaire de rétractation AJAX inséré en section 08
    ],

    // ==================== CONTACT ====================
    // Page contact = type spécial, structure différente (pas de sections numérotées)
    // Géré en étape 2.2 via le module form-contact
    'contact' => [
        'pill'    => 'Contact TIREA',
        'h1'      => 'Écrivez-nous.',
        'h1_em'   => 'On vous répond.',
        'lede'    => 'Notre équipe française vous répond personnellement, sous 24 heures, du lundi au dimanche. Choisissez votre sujet, on s\'occupe du reste.',
        'meta'    => [],
        'sections' => [], // pas de TOC pour la page contact
        'cta'    => null, // pas de CTA, la page EST le contact
        'module' => 'contact',
        'type'   => 'contact', // flag spécial pour rendu adapté
    ],

    // ==================== NOTRE HISTOIRE ====================
    'notre-histoire' => [
        'eyebrow' => 'Notre Histoire',
        'h1'      => 'L\'élégance,',
        'h1_em'   => 'sans compromis.',
        'lede'    => 'De la frustration d\'une chemise qui s\'échappe, à la volonté de créer la référence française des accessoires professionnels premium.',
        'meta'    => [],
        'sections' => [],
        'cta'     => [
            'eyebrow'  => 'Tirea, votre allure, notre obsession',
            'h2'       => 'Prêt à ne plus',
            'h2_em'    => 'y penser ?',
            'text'     => 'Découvrez l\'Ajusteur TIREA et rejoignez ceux qui ont fait de l\'élégance une habitude naturelle.',
            'btn1'     => 'Découvrir l\'Ajusteur',
            'btn1_url' => home_url('/produit/lajusteur-tirea/'),
            'btn2'     => 'Nous écrire',
            'btn2_url' => home_url('/contact/'),
        ],
        'module' => null,
        'type'   => 'histoire',
    ],

];