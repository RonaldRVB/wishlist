@component('mail::message')
# Bonjour !

Tu es invité à l’événement : **{{ $eventTitle }}**

@component('mail::button', ['url' => $acceptUrl, 'color' => 'success'])
Oui, je participe
@endcomponent

@component('mail::button', ['url' => $refuseUrl, 'color' => 'error'])
Non, je refuse
@endcomponent

Merci !
@endcomponent