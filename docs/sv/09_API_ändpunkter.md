# API-ändpunkter

## Models
HTTP Metod | URI | Handling
--- | --- | ---
GET | sections | Lista avdelningar
POST | sections | Skapa avdelning
PUT | sections/:id | Uppdatera avdelning
DELETE | sections/:id | Ta bort avdelning
GET | articles | Lista artiklar
POST | articles | Skapa artikel
PUT | articles/:id | Uppdatera artikel
DELETE | articles/:id | Ta bort artikel
GET | categories | Lista kategorier
POST | categories | Skapa kategori
PUT | categories/:id | Uppdatera kategori
DELETE | categories/:id | Ta bort kategori
GET | users | Lista användare
POST | users | Skapa användare
PUT | users/:id | Uppdatera användare
DELETE | users/:id | Ta bort användare


## Inloggad användare & hjälpfunktioner

HTTP Method | URI | Handling
--- | --- | ---
GET | overview | Hämta översikt (sections > contacts > articles)
GET | overview/my | Hämta artiklar som den inloggade användaren skapat
GET | user | Hämta inloggad användare
GET | contacts | Hämta tillgängliga kontakter i sektionen
GET | roles | Hämta tillgängliga behörighetsnivåer (för admin)
GET | token | Hämta XSRF-TOKEN
