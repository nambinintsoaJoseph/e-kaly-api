<h2>Repas</h2>

<h4>Récupérer tout le repas (agent)</h4>

```raw
GET http://localhost/e-kaly/api/routes/repas/agent.php
``` 

Headers

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
```

Succès 

```json
{
    "success": true,
    "repas": [
        {
            "id_repas": "21",
            "nom": "La quiche lorraine",
            "description": "Originaire de la Lorraine, dans le nord-est de la France...",
            "photo": "/uploads/repas/683611198a0fe.jpeg",
            "prix": "32000"
        }, 
        {
            "id_repas": "22",
            "nom": "Le boeuf Bourguignon",
            "description": " Réputée pour ses fabuleux plats en sauce...",
            "photo": "/uploads/repas/68ersae.jpeg",
            "prix": "30000"
        }
    ]
}
```

<h4>Récupère tous les repas ajoutés par un gérant spécifique</h4>

```raw
GET http://localhost/e-kaly/api/routes/repas/gerant.php
```

Headers

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIs... 
```

Succès 

```json
{
    "success": true,
    "repas": [
        {
            "id_repas": "21",
            "nom": "Paint au chocolat",
            "description": "Le pain au chocolat...",
            "photo": "/uploads/repas/683611198a0fe.jpeg",
            "prix": "3000"
        },
        {
            "id_repas": "22",
            "nom": "Spaghetti à la carbonara",
            "description": "Plat italien....",
            "photo": "/uploads/repas/6836111sqezr12de.jpeg",
            "prix": "25000"
        }
    ]
}
```

<h4>Ajouter un repas (gérant)</h4> 

```raw
POST http://localhost/e-kaly/api/routes/repas/ajouter.php
```

Headers

```raw
Content-Type: multipart/form-data
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
```

Body 

```raw
multipart/form-data 

nom (text) : "string" 
description (text) : "string" 
photo (file) : Choose a file... 
prix (text) : "string" 
```

Succès 

```json
{
    "sucess": true,
    "message": "Repas créé avec succès"
}
``` 

Echec 

```json
{
    "success": false,
    "message": "Accès refusé, vous ne pouvez pas effectuer cette action."
}
```

<h4>Modifier un repas (gérant)</h4> 

```raw
PUT http://localhost/e-kaly/api/routes/repas/modifier.php?id_repas={number}
``` 

Headers 

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
```

Body 

```json  
{
    "nom": "string", 
    "description": "string", 
    "prix": number
}
``` 

Succès 

```json
{
    "success": true,
    "message": "Repas modifié avec succès."
}
``` 

Echec 

```json 
{
    "success": false, 
    "message": "Accès refusé, vous ne pouvez pas effectuer cette action."
}
``` 

<h4>Supprimer un repas (gérant)</h4> 

```raw
DELETE http://localhost/e-kaly/api/routes/repas/supprimer.php?id_repas={number}
```

Headers

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
```

Succès 

```json
{
    "success": true,
    "message": "Repas supprimé avec succès."
}
```

Echec 

```json
{
    "success": false, 
    "message": "Erreur de la suppression du repas."
}
``` 

<h2>Commande</h2> 

<h4>Récupère toutes les commandes d'un agent avec les repas associés</h4>

```raw
GET http://localhost/e-kaly/api/routes/commande/agent.php
```

Header 

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5...
```

Succès 

```json
{
    "success": true,
    "commandes": [
        {
            "id_commande": "3",
            "date_commande": "27-05-2025 - 18:23:49",
            "gerant": "Jean Pierre",
            "repas": [
                {
                    "id_repas": "21",
                    "nom": "Paint au chocolat",
                    "description": "Le pain au chocolat...",
                    "photo": "/uploads/repas/683611198a0fe.jpeg",
                    "prix_unitaire": "3000",
                    "quantite": "2",
                    "sous_total": 6000
                }, 
                {
                    "id_repas": "21",
                    "nom": "Macaroni au fromage",
                    "description": "Le macaroni au fromage, familièrement...",
                    "photo": "/uploads/repas/683611198asere.jpeg",
                    "prix_unitaire": "10000",
                    "quantite": "1",
                    "sous_total": 10000
                },
            ],
            "total": 16000
        }
    ]
}
```

<h4>Création d'une commande (agent)</h4> 

```raw
POST http://localhost/e-kaly/api/routes/commande/creer.php
``` 

Header

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
```


id_utilisateur récuperé depuis le token

Succès 

```json 
{
    "success": true,
    "message": "Commande créée avec succès."
}
``` 

Echec 

```json
{
    "success": false, 
    "message": "Erreur de la création du commande."
}
```

<h4>Ajouter un repas dans une commande (agent)</h4> 

```raw
POST http://localhost/e-kaly/api/routes/commanderepas/ajouter.php
```

Header 

```raw
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI...
``` 

Body 

```json 
{
    "id_repas": number, 
    "id_commande": number, 
    "quantite": number
}
``` 

Succès 

```json
{
    "success": true,
    "message": "Repas ajouté à la commande."
}
```

Echec 

```json  
{
    "success": false, 
    "message": "Erreur lors de l'ajout du repas dans la commande."
}
``` 

<h2>Utilisateur</h4> 

<h4>Créer un utilisateur</h4> 

```raw
POST http://localhost/e-kaly/api/routes/utilisateur/creer.php
``` 

Body

```json
{	
    "nom": "string", 
    "prenom": "string", 
    "email": "string",
    "mot_passe": "string", 
    "role": "agent|gerant"
}
```

```json
{
    "success": true,
    "message": "Utilisateur créé avec succès."
}
```

<h4>Authentifier un utilisateur</h4>

```raw
POST http://localhost/e-kaly/api/routes/utilisateur/login.php
```

Body 

```json
{	
    "email": "string",
    "mot_passe": "string"
}
```
Succès 

```json
{
    "success": true,
    "token": "eyJhbGciOi..."
}
```

Echec 

```json
{
    "success": false,
    "message": "Echec d'authentification."
}
```