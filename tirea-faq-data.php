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
 *
 * MISE EN AVANT DANS UNE RÉPONSE :
 *   utilisez <b>...</b> (gras). N'utilisez pas <em> : il s'affiche en bleu italique.
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
    //  a        : la réponse (HTML autorisé : <p>, <b>, <ul>/<li>)
    //  tags     : étiquettes affichées sous la réponse
    //  keywords : mots-clés en plus pour la recherche (non affichés)
    'items' => [


        [
            'q' => "L'Ajusteur TIREA™, c'est quoi exactement ?",
            'a' => "<p>L'Ajusteur TIREA™ est un accessoire invisible qui garde votre chemise (ou votre haut) parfaitement rentrée. Il se fixe discrètement au dernier bouton et plaque le tissu pour une silhouette nette, sans le moindre faux pli.</p>
                    <p>Contrairement aux bretelles, il reste <b>totalement invisible</b> une fois le pantalon enfilé : on ne le remarque que le jour où on l'oublie.</p>",
            'tags' => ['Produit', '30s à comprendre'],
            'keywords' => "c'est quoi, à quoi ça sert",
        ],
        [
            'q' => "Est-il visible une fois porté ?",
            'a' => "<p>Non. Porté correctement, il reste sous le pantalon et demeure <b>parfaitement invisible</b>, même sous une chemise blanche fine, un pull ou un t-shirt.</p>",
            'tags' => ['Confort', 'Discrétion'],
            'keywords' => "visible, invisible sous chemise",
        ],
        [
            'q' => "Tient-il vraiment, et comment éviter qu'il glisse ?",
            'a' => "<p>L'Ajusteur TIREA™ est en <b>taille unique</b> : son système de réglage intégré s'adapte à votre tour de taille en quelques secondes, quelle que soit votre morphologie.</p>
                    <p>Sa <b>zone de maintien de 3 cm</b> offre une large surface de contact : combinée à la tension de l'élastique et à l'ancrage sur le dernier bouton, elle empêche votre chemise de remonter ou de glisser, même en plein mouvement.</p>",
            'tags' => ['Taille', 'Maintien'],
            'keywords' => "ça glisse, taille unique",
        ],
        [
            'q' => "Peut-il abîmer mes vêtements ?",
            'a' => "<p>Non. La suédine adhère au tissu <b>sans en accrocher les fibres</b> : il se fixe sur le dernier bouton sans le forcer ni marquer le vêtement.</p>
                    <p>Il convient à tous les textiles, du plus fin au plus épais, sans déchirure ni marque permanente.</p>",
            'tags' => ['Compatibilité', 'Avantages'],
            'keywords' => "abîme les vêtements, marque le tissu",
        ],
        [
            'q' => "En quelles matières est-il fabriqué ?",
            'a' => "<p><b>Trois</b> matériaux choisis pour durer :</p>
                    <ul>
                      <li><b>Élastique en nylon</b> : souple et résistant, il conserve sa tension dans le temps.</li>
                      <li><b>Patins en suédine</b> : un toucher doux doté de propriétés antidérapantes, qui adhère sans abîmer le tissu.</li>
                      <li><b>Boucle en acier inoxydable</b> : pour l'attache, garantie sans rouille.</li>
                    </ul>",
            'tags' => ['Matières', 'Durabilité'],
            'keywords' => "matières, de quoi c'est fait",
        ],
        [
            'q' => "Quels sont les délais et frais de livraison ?",
            'a' => "<p><b>Livraison offerte</b>, sans minimum d'achat, avec <b>suivi de colis en temps réel</b> inclus.</p>
                    <ul>
                      <li><b>France métropolitaine</b> : expédition garantie sous 24h depuis notre stock en France, livraison en 24 à 72h.</li>
                      <li><b>Europe</b> : livraison disponible, délais variables selon le pays de destination.</li>
                    </ul>",
            'tags' => ['Livraison', 'France & Europe'],
            'keywords' => "délai livraison, frais de port",
        ],
        [
            'q' => "Satisfait ou remboursé : puis-je le retourner ?",
            'a' => "<p>Oui. Au-delà des 14 jours prévus par la loi, nous prolongeons votre délai à <b>30 jours</b> : c'est notre garantie <b>Satisfait ou Remboursé</b>, sans avoir à vous justifier.</p>
                    <p>Un simple e-mail à notre service client, ou le formulaire de retour présent sur la page du même nom, suffit pour lancer la procédure (réponse sous 24h). Vos remarques sont toujours les bienvenues pour faire évoluer notre offre.</p>",
            'tags' => ['Retour', 'SAV'],
            'keywords' => "retour, satisfait remboursé",
        ],
        [
            'q' => "Comment s'utilise-t-il ?",
            'a' => "<p>En <b>3 étapes et 30 secondes</b> :</p>
                    <ul>
                      <li>Insérez l'accroche dans le dernier bouton de la chemise (étape à sauter sur les hauts sans bouton : le maintien reste efficace grâce aux patins en suédine).</li>
                      <li>Insérez la boucle en acier dans l'accroche.</li>
                      <li>Ajustez la tension à votre tour de taille.</li>
                    </ul>
                    <p>Un mode d'emploi visuel est disponible sur la fiche produit et sur la page d'accueil.</p>",
            'tags' => ['Utilisation', '30 secondes'],
            'keywords' => "comment l'utiliser, mode d'emploi",
        ],
        [
            'q' => "Fonctionne-t-il avec tous les types de chemise ?",
            'a' => "<p>Oui : lin, coton, popeline, velours, mais aussi polos et t-shirts fins. L'accroche au dernier bouton optimise la tenue sur une chemise classique.</p>
                    <p><b>Pas de dernier bouton ?</b> Aucun problème : il suffit de refermer l'accessoire sans passer par le bouton. La suédine assure le maintien sur la plupart des textiles, avec une tenue légèrement moins ferme.</p>",
            'tags' => ['Compatibilité', 'Sans bouton'],
            'keywords' => "type de chemise, sans bouton",
        ],
        [
            'q' => "Est-il gênant quand je suis assis ?",
            'a' => "<p>Non. L'élastique s'adapte naturellement à votre position : au bureau, en voiture ou au restaurant, vous l'oubliez complètement.</p>",
            'tags' => ['Confort', 'Mouvements'],
            'keywords' => "gêne assis, confort assis",
        ],
        [
            'q' => "C'est pour les hommes ou pour les femmes ?",
            'a' => "<p>Les deux. L'Ajusteur TIREA™ est <b>universel</b> et convient à toutes les morphologies : chemises cintrées, chemisiers, hauts féminins. La même efficacité pour tout le monde.</p>",
            'tags' => ['Pour qui', 'Unisexe'],
            'keywords' => "homme ou femme, mixte",
        ],
        [
            'q' => "Quelle différence avec une jarretière de chemise ?",
            'a' => "<p>La jarretière relie le bas de la chemise aux chaussettes et tire le tissu vers le bas. L'Ajusteur TIREA™, lui, ceinture le bas de la chemise sur 3 cm : il affine la silhouette tout en restant <b>totalement invisible</b>, sans rien attacher à vos jambes.</p>
                    <p>De ce fait, il n'est pas perceptible au niveau du pantalon, même lorsque vous êtes assis.</p>",
            'tags' => ['Comparaison', 'Avantages'],
            'keywords' => "vs jarretière, jarretière chemise",
        ],
        [
            'q' => "Qu'a-t-il de mieux qu'une simple ceinture ?",
            'a' => "<p>Une ceinture serre la taille mais laisse la chemise remonter et se déformer au fil de la journée. L'Ajusteur combine <b>adhérence</b> (suédine), <b>élasticité</b> (tension calibrée) et <b>invisibilité</b> : il est pensé pour maintenir le tissu en place sans bouger.</p>",
            'tags' => ['Comparaison', 'Avantages'],
            'keywords' => "vs ceinture, mieux qu'une ceinture",
        ],
        [
            'q' => "Pourquoi pas de simples bretelles ?",
            'a' => "<p>Les bretelles bougent dans les mouvements amples et créent des plis. L'Ajusteur plaque le tissu au corps pour une silhouette nette, cintrée et moderne.</p>",
            'tags' => ['Comparaison', 'Avantages'],
            'keywords' => "vs bretelles, bretelles",
        ],
        [
            'q' => "Pourquoi le choisir ?",
            'a' => "<p>Une zone de maintien généreuse de <b>3 cm</b>, une marque française qui expédie depuis la France (livraison avec suivi offerte) et une exigence de qualité sur chaque détail, du nylon à l'acier inoxydable.</p>
                    <p>Un produit simple, pensé pour bien faire une seule chose : <b>tenir</b>.</p>",
            'tags' => ['Pourquoi nous', 'Choix'],
            'keywords' => "pourquoi le choisir, vs concurrents",
        ],
        [
            'q' => "Combien de temps dure-t-il ?",
            'a' => "<p>Ses matériaux ont été sélectionnés pour durer : utilisé au quotidien, il peut vous accompagner plusieurs années. Conçu pour rester aussi efficace au fil du temps qu'au premier jour.</p>",
            'tags' => ['Durabilité', 'Conception'],
            'keywords' => "durée de vie, combien de temps",
        ],
        [
            'q' => "Pourquoi en prendre deux ou trois, et quel pack choisir ?",
            'a' => "<p>Pour ne jamais être pris au dépourvu : un à la maison, un dans la valise ou au bureau, un d'avance en cas d'oubli ou de perte. Le pack est aussi une excellente idée à offrir.</p>
                    <p>Notre recommandation : le <b>pack de 2 ou 3</b>, pour toujours en avoir un sous la main.</p>",
            'tags' => ['Packs', 'Idée cadeau'],
            'keywords' => "pack 2 ou 3, lequel choisir",
        ],
        [
            'q' => "Et s'il est reçu défectueux ?",
            'a' => "<p>Nous vous renvoyons immédiatement un nouvel exemplaire, entièrement à nos frais. Un e-mail au service client suffit.</p>",
            'tags' => ['SAV', 'Garantie'],
            'keywords' => "défectueux, produit cassé",
        ],
        [
            'q' => "D'où vient TIREA ?",
            'a' => "<p>TIREA est née de l'expérience de son fondateur, un entrepreneur français confronté au port quotidien de la chemise durant ses études, puis dans l'hôtellerie. Constatant qu'aucune solution réelle ne répondait vraiment à ce besoin, il a créé l'Ajusteur.</p>",
            'tags' => ['La marque', 'Histoire'],
            'keywords' => "origine tirea, histoire marque",
        ],
        [
            'q' => "Pourquoi « TIREA » ?",
            'a' => "<p>C'est un clin d'œil à l'expression « tiré à quatre épingles ». Simple, mémorable et bien français.</p>",
            'tags' => ['La marque', 'Histoire'],
            'keywords' => "signification du nom, pourquoi tirea",
        ],
        [
            'q' => "Êtes-vous une grande entreprise ?",
            'a' => "<p>Non : un entrepreneur français indépendant, pour l'instant. Tout le focus est mis sur un produit irréprochable, une communauté et une vision.</p>",
            'tags' => ['La marque', 'Histoire'],
            'keywords' => "qui est tirea, entreprise",
        ],
        [
            'q' => "Fabriquez-vous en France ?",
            'a' => "<p>C'est notre ambition : des modèles brevetés, fabriqués en France, pour soutenir l'économie locale et garantir une qualité maximale.</p>",
            'tags' => ['La marque', 'Made in France'],
            'keywords' => "fabriqué en france, made in france",
        ],
        [
            'q' => "Êtes-vous présents sur les réseaux ?",
            'a' => "<p>Oui, retrouvez-nous sur <b>@tirea.fr</b> : conseils style, astuces autour de la chemise et coulisses de la marque. Rejoignez la communauté.</p>",
            'tags' => ['Communauté', 'Réseaux sociaux'],
            'keywords' => "réseaux sociaux, instagram tiktok",
        ],
        [
            'q' => "Puis-je poster avec l'Ajusteur ?",
            'a' => "<p>Avec plaisir ! Taguez <b>@tirea.fr</b>, nous repartageons vos contenus. De quoi devenir une référence style.</p>",
            'tags' => ['Communauté', 'Réseaux sociaux'],
            'keywords' => "poster photo, partager",
        ],
        [
            'q' => "Est-ce adapté aux uniformes ?",
            'a' => "<p>Oui, c'est même l'un de ses usages idéaux : il garde une tenue impeccable, parfait pour les uniformes et les environnements exigeants.</p>",
            'tags' => ['Compatibilité', 'Uniformes'],
            'keywords' => "uniforme, uniformes professionnels",
        ],

    ],
];