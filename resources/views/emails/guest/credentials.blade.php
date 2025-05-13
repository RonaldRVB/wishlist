@component('mail::message')
# Bonjour {{ $name }} 🎉

Ton accès invité à la wishlist est bien activé.

Voici tes identifiants temporaires :

- ✉️ **Email** : {{ $email }}
- 🔑 **Mot de passe** : {{ $password }}

@component('mail::button', ['url' => route('login'), 'color' => 'success'])
Se connecter
@endcomponent

⚠️ Ton accès est temporaire et sera supprimé après quelques jours.  
Pense à noter ces informations si tu souhaites revenir plus tard.

Merci et à bientôt 👋  
@endcomponent
