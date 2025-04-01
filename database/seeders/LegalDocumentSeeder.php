<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegalDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('legal_documents')->insert([
            'title' => 'Mentions légales',
            'version' => 'v1.0',
            'is_active' => true,
            'content' => <<<TEXT

## Identité et coordonnées de l’éditeur responsable

Marie-Céline Squelin  
rue Deffalque, 18  
1490 Court-Saint-Étienne  
<contact@wishlist.roracq.be>

---

## Propriété intellectuelle

Le site [https://www.wishlist.roracq.be](https://www.wishlist.roracq.be) est une création protégée par le droit d’auteur.

Les textes, photos et autres éléments de ce site sont protégés par le droit d’auteur, que ce soient des productions originales créées par moi, ou des œuvres pour lesquelles j’ai obtenu les droits nécessaires.

Toute copie, adaptation, traduction, arrangement, communication au public, location et autre exploitation, modification de tout ou partie de ce site est strictement interdite sans consentement préalable.

Toute infraction peut entraîner des poursuites civiles ou pénales.

Toutes les dénominations, logos et autres sigles utilisés sont des marques ou noms commerciaux protégés. Toute utilisation sans autorisation est interdite.

Si vous pensez que votre propriété intellectuelle a été utilisée abusivement, merci de me contacter.

---

## Responsabilité quant au contenu

Ce site a été créé dans le cadre de mes études de Webdeveloper à l’IFOSUP de Wavre.

Je m’efforce d’en assurer l’exactitude et la mise à jour, mais je ne peux garantir l’absence d’erreurs ou d’omissions.

Le site peut être modifié sans préavis. Je ne suis pas responsable des conséquences d’erreurs, d'inaccessibilité ou d’omissions.

L’utilisation du site est soumise au droit belge.  
Tout litige relève de la compétence exclusive des Tribunaux de Nivelles.

---

## Politique de confidentialité

La politique de confidentialité concerne le traitement des données personnelles des visiteurs et utilisateurs du site.

Les données personnelles ne sont récoltées que dans le cadre de la gestion du site et pour répondre aux demandes des utilisateurs. Elles ne sont jamais transmises à des tiers.

### Données collectées

- Votre nom de domaine (détecté automatiquement)  
- Votre e-mail si vous me l’avez communiqué  
- Les pages consultées sur le site  
- Les informations saisies dans des formulaires

### Cookies

Un cookie est un petit fichier texte stocké sur votre appareil. Il enregistre vos préférences de navigation.

Les cookies permettent un accès plus rapide à certains contenus ou affichent des publicités ciblées.

Ils ne contiennent pas de données personnelles.  
Vous pouvez les refuser ou les supprimer via les paramètres de votre navigateur.

---

## Droits et contacts

Conformément à la loi, vous pouvez, sans frais et sur justification de votre identité :

1. Obtenir une copie des données personnelles enregistrées  
2. Demander la correction de données inexactes  
3. Demander la suppression de vos données  
4. Demander la limitation du traitement  
5. Obtenir vos données dans un format structuré  
6. Vous opposer au traitement de vos données  
7. Déposer une plainte auprès de l’autorité de protection des données :

Autorité de protection des données (APD)  
Rue de la Presse 35  
1000 Bruxelles  
+32 (0)2 274 48 00  
contact@apd-gba.be  
https://www.autoriteprotectiondonnees.be/


TEXT
        ]);
    }
}
