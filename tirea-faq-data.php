<?php
/**
 * TIREA — Données de la FAQ (source unique)
 *
 * C'est LE seul fichier à modifier pour gérer les questions.
 * Toute question ajoutée ici apparaît automatiquement :
 *   - en entier sur la page /faq (centre d'aide complet) ;
 *   - dans la recherche de la page d'accueil, même si elle n'est pas
 *     affichée par défaut (au-delà des premières questions).
 *
 * POUR AJOUTER UNE QUESTION :
 *   copiez un bloc complet de [ ... ] dans la liste 'items', collez-le où
 *   vous voulez dans la liste, et complétez les 4 champs (q, a, tags, keywords).
 *   La numérotation se met à jour toute seule.
 */

if (!defined('ABSPATH')) exit;

return [

    // --- Réglage de la version allégée (page d'accueil) -------------------
    // Nombre de questions affichées par défaut sur la HOME.
    // Les suivantes restent masquées mais cherchables. Mettez 5, 6 ou 7.
    'home_limit' => 7,

    // --- Textes d'en-tête (communs home + page /faq) ----------------------
    'badge'    => "Centre d'aide TIREA",
    'title'    => "Vos questions,",
    'title_em' => "nos réponses",
    'intro'    => "Tout ce qu'il faut savoir sur l'Ajusteur TIREA™ : concept, matières, livraison et retours. Si vous ne trouvez pas la réponse à votre question, écrivez-nous : notre équipe vous répond sous 24h.",

    // --- Bloc « Une autre question ? » ------------------------------------
    'contact_badge' => "Réponse sous 24h",
    'contact_title' => "Une autre question ?",
    'contact_em'    => "Posez-la nous.",
    'contact_lead'  => "Notre équipe vous répond personnellement par e-mail, tous les jours, sous 24 heures.",

    // --- Les questions -----------------------------------------------------
    //  q        : la question (texte)
    //  a        : la réponse (HTML autorisé : <p>, <b>, <em>, <ul>/<li>)
    //  tags     : étiquettes affichées sous la réponse
    //  keywords : mots-clés en plus pour la recherche (non affichés)
    'items' => [

        [
            'q' => "L'Ajusteur TIREA™, c'est quoi exactement ?",
            'a' => '<p>L\'Ajusteur TIREA™ est un accessoire pensé comme une <em>ceinture invisible</em> qui se fixe entre votre chemise et votre pantalon. Il maintient votre chemise parfaitement rentrée, toute la journée, sans avoir à la rajuster.</p>
                    <p>Il s\'ancre délicatement au dernier bouton de votre chemise et reste totalement invisible une fois le pantalon enfilé.</p>',
            'tags' => ['Produit', '30s à comprendre'],
            'keywords' => "ajusteur tirea c'est quoi accessoire chemise ceinture invisible",
        ],
        [
            'q' => "En quelles matières est-il fabriqué ?",
            'a' => '<p>Trois matériaux choisis pour leur durabilité :</p>
                    <ul>
                      <li>Élastique gros grain : souple, résistant et conçu pour conserver sa tension dans le temps.</li>
                      <li>Acier inoxydable : pour les éléments d\'attache, garanti sans rouille.</li>
                      <li>Cuir synthétique Skyvertex : au toucher cuir, doté de propriétés antidérapantes pour un ancrage parfait.</li>
                    </ul>',
            'tags' => ['Matières', 'Durabilité'],
            'keywords' => "matière fabrication élastique acier inoxydable cuir skyvertex antidérapant",
        ],
        [
            'q' => "Est-il visible une fois porté ?",
            'a' => '<p>Porté correctement, l\'Ajusteur reste sous votre pantalon, il est <em>parfaitement invisible</em>, même avec une chemise blanche fine.</p>
                    <p>Un mode d\'emploi clair en <b>3 étapes et 30 secondes</b> est fourni avec chaque commande.</p>',
            'tags' => ['Confort', 'Discrétion'],
            'keywords' => "visible porté discret invisible sous pantalon mode emploi",
        ],
        [
            'q' => "Comment choisir la bonne taille et éviter qu'il glisse ?",
            'a' => '<p>L\'Ajusteur TIREA™ est en <b>taille unique</b>. Le système de réglage intégré permet de l\'ajuster à votre tour de taille en quelques secondes.</p>
                    <p>Une fois réglé, la pression élastique combinée à l\'ancrage sur le dernier bouton empêche votre chemise de remonter ou de glisser, même en mouvement.</p>',
            'tags' => ['Taille', 'Maintien'],
            'keywords' => "taille unique ajusteur intégré morphologie glisser maintien",
        ],
        [
            'q' => "Peut-il abîmer mes chemises ?",
            'a' => '<p>L\'ancrage est volontairement <em>doux</em> : il s\'attache sur le dernier bouton sans le forcer ni marquer le tissu.</p>
                    <p>Il fonctionne aussi sur des chemises sans bouton (T-shirt, polo), le maintien reste correct mais légèrement moins ferme.</p>',
            'tags' => ['Compatibilité'],
            'keywords' => "abîmer chemise compatible bouton t-shirt polo préserver",
        ],
        [
            'q' => "Quels sont les délais et frais de livraison ?",
            'a' => '<p><b>Livraison offerte</b>, sans minimum d\'achat. Délai total : <b>72h</b>.</p>
                    <ul>
                      <li>Expédition sous <b>24h</b> depuis notre stock en France.</li>
                      <li>Livraison sous <b>48h</b> en France métropolitaine.</li>
                    </ul>',
            'tags' => ['Livraison', 'France'],
            'keywords' => "livraison 72h gratuite france expédition 24h 48h stock",
        ],
        [
            'q' => "Et si l'Ajusteur ne me convient pas, je peux le retourner ?",
            'a' => '<p>Notre politique <b>Satisfait ou Remboursé</b> vous permet de retourner votre commande dans les <b>14 jours</b> suivant la réception, sans avoir à vous justifier.</p>
                    <p>Un email à notre service client suffit pour lancer la procédure, avec une réponse sous 24h.</p>',
            'tags' => ['Retour', 'SAV'],
            'keywords' => "retour remboursement satisfait politique sav 14 jours",
        ],
        [
            'q' => "Et si l'Ajusteur ne me convient pas, je peux le retourner ?",
            'a' => '<p>Notre politique <b>Satisfait ou Remboursé</b> vous permet de retourner votre commande dans les <b>14 jours</b> suivant la réception, sans avoir à vous justifier.</p>
                    <p>Un email à notre service client suffit pour lancer la procédure, avec une réponse sous 24h.</p>',
            'tags' => ['Retour', 'SAV'],
            'keywords' => "retour remboursement satisfait politique sav 14 jours",
        ],

    ],
];